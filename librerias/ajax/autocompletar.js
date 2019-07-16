	var IE = navigator.appName.toLowerCase().indexOf("microsoft") > -1;
	var Mozilla = navigator.appName.toLowerCase().indexOf("netscape") > -1;

	var textoAnt = "";
	var posicionListaFilling = 0;

	var datos = new Array();
	

	function ajaxobj() {
		try {
			_ajaxobj = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				_ajaxobj = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (E) {
				_ajaxobj = false;
			}
		}
	   
		if (!_ajaxobj && typeof XMLHttpRequest!='undefined') {
			_ajaxobj = new XMLHttpRequest();
		}
		return _ajaxobj;
	}
	
	function cargaLista(evt, obj, txt) {
		ajax = ajaxobj();
		ajax.open("GET","formularios/autonits.php?texto="+txt, true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var datos = ajax.responseXML;
				var paises = datos.getElementsByTagName("tercero");
				
				var listaPaises = new Array();
				if (paises) {
					for (var i=0; i<paises.length; i++) {
						listaPaises[listaPaises.length] = paises[i].firstChild.data;
					}
				}
				escribeLista(obj, listaPaises);
			}
		}
		ajax.send(null);
	}
	
	function escribeLista(obj, lista) {
		var html = "";
		var fill = document.getElementById('lista');
		
		if (lista.length == 0) {
			// Si la lista es vacia no la mostramos
			fill.style.display = "none";
		} else {
			// Creamos una tabla con 
			// todos los elementos encontrados
			fill.style.display = "block";
			var html='<table cellspacing="0" '+
				'cellpadding="0" border="0" width="100%">';
			for (var i=0; i<lista.length; i++) {
				html += '<tr id="tr'+obj.id+i+
					'" '+(posicionListaFilling == i? 
						' class="fill" ': '')+
					' onmouseover="seleccionaFilling(\'tr'+
					obj.id+'\', '+i+
					')" onmousedown="seleccionaTextoFilling(\'tr'+
					obj.id+'\', '+i+')">';
				html += '<td>'+lista[i]+'</td></tr>';
			}
			html += '</table>';
		}

		// Escribimos la lista
		fill.innerHTML = html;
	}

	// Muestra las coincidencias en la lista
	function inputFilling(evt, obj) 
	{
		var fill = document.getElementById('lista');
		var elems = datos;
		var tecla = "";
		var lista = new Array();
		var res = obj.value;
		var borrar = false;
		
		// Almaceno la tecla pulsada
		if (!IE) {
		  tecla = evt.which;
		} else {
		  tecla = evt.keyCode;
		}
		
		var texto;
		// Si la tecla que pulso es una
		// letra o un espacio, o el intro
		// o la tecla borrar, almaceno lo 
		// que debo buscar
		if (!String.fromCharCode(tecla).match(/(\w|\s)/) && 
				tecla != 8 && 
				tecla != 13) {
			texto = textoAnt;
		} else {
			texto = obj.value;
		}
		
		textoAnt = texto;
		
		// Si el texto es distinto de vacio
		// o se pulsa ARRIBA o ABAJO
		// hago llamada AJAX para que 
		// me devuelva la lista de palabras
		// que coinciden con lo que hay
		// escrito en la caja
		if ((texto != null && texto != "") 
			|| (tecla == 40 || tecla == 38)) {
			cargaLista(evt, obj, texto);
		}
		
		
		// Según la letra que se pulse
		if (tecla == 37) { // Izquierda
			// No hago nada
		} else if (tecla == 38) { // Arriba
			// Subo la posicion en la
			// lista desplegable una posición
			if (posicionListaFilling > 0) {
				posicionListaFilling--;
			}
			// Corrijo la posición del scroll
			fill.scrollTop = posicionListaFilling*14;
		} else if (tecla == 39) { // Derecha
			// No hago nada
		} else if (tecla == 40) { // Abajo
			if (obj.value != "") {
				// Si no es la última palabra
				// de la lista
				if (posicionListaFilling < lista.length-1) { 
					// Corrijo el scroll
					fill.scrollTop = posicionListaFilling*14;
					// Bajo la posición de la lista
					posicionListaFilling++;
				} 
			}
		} else if (tecla == 8) { // Borrar <-
			// Se sube la lista del todo
			posicionListaFilling = 0;
			// Se permite borrar
			borrar = true;
		} else if (tecla == 13) { // Intro
			// Deseleccionamos el texto
			if (obj.createTextRange) {
				var r = obj.createTextRange();
				r.moveStart("character", 
					obj.value.length+1);
				r.moveEnd("character", 
					obj.value.length+1);
				r.select();
			} else if (obj.setSelectionRange) {
				obj.setSelectionRange(
					obj.value.length+1, 
					obj.value.length+1);
			}
			// Ocultamos la lista
			fill.style.display = "none";
			// Ponemos el puntero de 
			// la lista arriba del todo
			posicionListaFilling = 0;
			// Controlamos el scroll
			fill.scrollTop = 0;
			return true;
		} else {
			// En otro caso que siga
			// escribiendo
			posicionListaFilling = 0;
			fill.scrollTop = 0;
		}	
		
		// Si no se ha borrado
		if (!borrar) {
			if (lista.length != 0) {
				// Seleccionamos la parte del texto
				// que corresponde a lo que aparece
				// en la primera posición de la lista
				// menos el texto que realmente hemos
				// escrito
				obj.value = lista[posicionListaFilling];
				if (obj.createTextRange) {
					var r = obj.createTextRange();
					r.moveStart("character", 
						texto.length);
					r.moveEnd("character", 
						lista[posicionListaFilling].length);
					r.select();
				} else if (obj.setSelectionRange) {
					obj.setSelectionRange(
						texto.length, 
						lista[posicionListaFilling].length);
				}
			}
		}
		return true;
	}
  
  
	// Introduce el texto seleccionado
	function setInput(obj, fill) 
	{
		obj.value = textoAnt;
		fill.style.display = "none";
		posicionListaFilling = 0;
	}

	function seleccionaFilling(id, n) {
		document.getElementById(id + 
			n).className = "fill";
		document.getElementById(id + 
			posicionListaFilling).className = "";  	
		posicionListaFilling = n;
	}
  
	function seleccionaTextoFilling (id, n) {
		textoAnt = document.getElementById(id + 
			n).firstChild.innerHTML;
		posicionListaFilling = 0;
	}
  	
	function cambiarImagen(obj, ok) {
		var marcada = obj.src.indexOf("/_") > 0;
		
		if (ok) {
			if (!marcada) {
			  var ruta = obj.src.substring(
				0, 
				obj.src.lastIndexOf("/")+1)+
				"_"+obj.src.substring(
					obj.src.lastIndexOf("/")+1);
			  obj.src = ruta;
			}
		} else {
			if (marcada) {
				var ruta = ""+obj.src.substring(
					0, obj.src.lastIndexOf("_"))+
					obj.src.substring(
						obj.src.lastIndexOf("/")+2);
				obj.src = ruta;
			}
		}
	
	}