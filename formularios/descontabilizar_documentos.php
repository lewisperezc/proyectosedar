<?php session_start();

ini_set('memory_limit', '-1');

  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
  include_once('conexion/conexion.php');
  include_once('clases/mes_contable.class.php');
  include_once('clases/moviminetos_contables.class.php');
  include_once('clases/cuenta.class.php');
  $mes = new mes_contable();
  $cuenta = new cuenta();
  $meses = $mes->DatosMesesAniosContables($ano);
?>

<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/separador.js"></script>
<script type="text/javascript">
  function abreFactura(URL,num)
    {
     day = new Date();
	 id = day.getTime();
	 eval("page" + (id+num) + " = window.open(URL, '" + (id+num) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
    }
 
 function tabla(val,mes)
 {

   $('#encabezado').html('<div><img src="imagenes/loading.gif"/></div>');
   quitarComas();
   var dat_mes = mes.split('-');
   var cuantos = $("#doc_completo > tbody > tr").length;
	for(i=0;i<cuantos;i++)
	 {eliminarCon(i);eliminarCon(i);}
   $.ajax({
   type: "POST",url: "llamados/comprobante.php",data: "compro="+val+"&mes="+dat_mes[1],
   success: function(msg)
   {
	 dat_tablas = msg.split('/-/');
	 $('#encabezado').fadeIn(1000).html(dat_tablas[0]);
	 $("#doc_comple").append(dat_tablas[1]);
   }
   });
 }
 function eliminarCon(oId){
     $("#tr"+oId).remove();
     return true;
  }
 function mes(mes)
 {
	quitarComas();
	var dat_mes = mes.split('-');
	$.ajax({
	   type: "POST",
	   url: "llamados/des_documento.php",
	   data: "compro="+dat_mes[1],
	   success: function(msg){$("#doc_des").html(msg);}
   });
 }
 function validar()
 {
	 quitarComas();
	 var dat_mes = $("#mes_sele").val();
	 var ano = $("#estAno").val();
	 var datos = dat_mes.split('-');
	 if(datos[0]==1)
	 {
		alert("Mes de solo lectura.");
		return false;
	 }
	 else
	 	document.descontabilizar.submit();
 }
 function mod_documento()
 {
	quitarComas();
	for(i=0;i<$("#can_registros").val();i++) 
	  {
	   $("#debito"+i).removeAttr("readonly");
	   $("#credito"+i).removeAttr("readonly");
	   $("#credito"+i).removeAttr("readonly");
	   $("#fil"+i).removeAttr("disabled"); 
	  }
	 $("#guardar").removeAttr("disabled"); 
 }
 function imp_causacion(valor)
 {
 	var mes=($("#mes_sele").val()).split('-');
	abreFactura('reportes_PDF/causacion_pago.php?sigla='+valor+'&mes='+mes[1],1);
 }
 
function modificar()
{
    quitarComas();quitarPuntos();
    var cadena = $('#mes_sele').val();
    var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
	{
		alert("Mes de solo lectura.");
		return false;
	}
	else
	{
		var str_1=$("#doc_des").val();
		var res_1=str_1.split("_");
		
		//alert(res_1[0]);
		
		if(res_1[0]=='CAU-FABS')
		{
			alert("Las causaciones de FABS deben ser modificadas por el modulo.");
			return false;
		}
		else
		{
			var mensaje=confirm("Desea guardar la modificacion del documento?");
			if(mensaje)
			{
				var form=document.descontabilizar;
				form.action='control/modificar_comprobante.php';
				form.submit();
			}
		}
	}
	
}
function Preguntar()
{
	var cadena = $('#mes_sele').val();
	var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
	{
		alert("Mes de solo lectura.");
		return false;
	}
	else
		{ 
			var mensaje=confirm("Esta seguro que desea descontabilizar el documento?");
			if(mensaje)
			{
				var form=document.descontabilizar;
				form.action='control/elim_comprobante.php';
				form.submit();
			}
		}
}

function generar_archivo_excel(sigla)
{
	var mes=($("#mes_sele").val()).split('-');
	var ano_contable=$("#ano_contable").val();
	abreFactura('reportes_EXCEL/movimiento_por_documento.php?sigla='+sigla+'&mes='+mes[1]+'&ano_contable='+ano_contable,1);
}

function adi_fila()
{
	var cuantos = $("#doc_completo > tbody > tr").length-1;
	html = '<tr id="tr'+cuantos+'"><td><input onchange="TraeNombreCuenta(this.value,'+cuantos+');" type="text" name="cuenta'+cuantos+'" id="cuenta'+cuantos+'" size="10" list="cuen'+cuantos+'" required/><datalist id="cuen'+cuantos+'">';
	<?php
	$cuen_cau=$cuenta->busqueda('no');
	$tipos="3,11,2,1,14,9,7,5,11,13,8";
	$beneficiarios = $nit->ConProFondo($tipos);
    while($dat_cuentas = mssql_fetch_array($cuen_cau)) {?>
      	html+='<option value="<?php echo $dat_cuentas[cue_id]; ?>" label="<?php echo $dat_cuentas[cue_id]."  ".$dat_cuentas[cue_nombre]; ?>">';
    <?php } ?>
	html += '</datalist><input type="hidden" name="conce_gua" id="conce_gua" value="" /></td><td><input type="text" name="nom_cuenta'+cuantos+'" id="nom_cuenta'+cuantos+'" size="10" readonly/></td><td><input onchange="TraeNombreNit(this.value,'+cuantos+');" type="text" name="docu'+cuantos+'" id="docu'+cuantos+'" size="10" list="nit_id'+cuantos+'" onchange="cambiar_valor(this.value,'+cuantos+')" required/><datalist id="nit_id'+cuantos+'">';
	<?php
	  while($dat_aso = mssql_fetch_array($beneficiarios)) { ?>
	    html+='<option value="<?php echo $dat_aso[nit_id]; ?>" label="<?php echo $dat_aso[nits_num_documento]." ".$dat_aso[nits_nombres]." ".$dat_aso[nits_apellidos]; ?>" >';
	<?php } ?>
	html+='</datalist></td><td><input type="text" name="nombre'+cuantos+'" id="nombre'+cuantos+'" size="15" readonly /><input type="hidden" name="nom_gua'+cuantos+'" id="nom_gua'+cuantos+'" /></td><td><input type="text" name="centro'+cuantos+'" id="centro'+cuantos+'" size="10" readonly /><input type="hidden" name="cen_gua'+cuantos+'" id="cen_gua'+cuantos+'" value="'+$("#cen_gua0").val()+'" /></td><td><input type="text" id="debito'+cuantos+'" name="debito'+cuantos+'" size="10" value="0" required/></td><td><input type="text" name="credito'+cuantos+'" id="credito'+cuantos+'" size="10" value="0" required/></td><td><input type="radio" name="fil'+cuantos+'" id="fil'+cuantos+'" value="'+cuantos+'" onchange="eliminarCon('+cuantos+');" disabled="disabled" /></td></tr>';
	$("#doc_completo").append(html);
	$("#can_registros").val(cuantos+1);
}



function TraeNombreNit(id,pos)
{
	$.ajax({
	type: "POST",
    url: "llamados/trae_nombres_nit.php",
    data: "id="+id,
    success: function(msg)
    {
    	$("#nombre"+pos).val(msg);
    	$("#nom_gua"+pos).val(id);
    }
    });
}

function TraeNombreCuenta(id,pos)
{
	$.ajax({
	type: "POST",
    url: "llamados/trae_nombre_cuenta.php",
    data: "id="+id,
    success: function(msg)
    {
    	$("#nom_cuenta"+pos).val(msg);
    }
    });
}
</script>

<form name="descontabilizar" id="descontabilizar" action="control/elim_comprobante.php" method="post">
<center>
 <table>
  <tr>
   <td>Mes Contable</td>
   <td><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
    <select name="mes_sele" id="mes_sele" onchange="mes(this.value);">
     <?php
	  echo "<option value='0' selected='selected'>Seleccione...</option>";
	  while($dat_meses = mssql_fetch_array($meses))
	    echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	 ?>  
    </select>
   </td>
  </tr>
  <tr>
   <td>Documentos para descontabilizar</td>
   <td><select name="doc_des" id="doc_des" onchange="tabla(this.value,mes_sele.value)" ></select></td>
  </tr>
 </table><br />
 
 <table id="encabezado" border="1">
  <tr><td>Comprobante</td><td>Concepto</td><td>Fecha de Elaboracion</td></tr>
 </table><br />
 
 <div id="doc_comple">
 </div>
 <table id='botones'>
  <tr>
   <td><input name="adicionar" id="adicionar" type="button" class="art-button" value="Adicionar Fila" onclick="adi_fila();"/></td>
   <td><input name="confirmar" id="confirmar" type="button" class="art-button" value="Confirmar Borrado" onclick="Preguntar();"/></td>
   <td><input name="mod" id="mod" type="button" class="art-button" value="Modificar Documento" onclick="mod_documento();" /></td>
   <td><input name="guardar" id="guardar" type="button" class="art-button" value="Guardar Modificacion" onclick="modificar();" disabled="disabled"/></td>
   <td><input name="imprimir" id="imprimir" type="button" class="art-button" value="Imprimir Documento" onclick="imp_causacion(compro.value)"/></td>
   <td><input name="exportar_excel" id="exportar_excel" type="button" class="art-button" value="Generar archivo excel" onclick="generar_archivo_excel(compro.value)"/><input type="hidden" name="ano_contable" id="ano_contable" value="<?php echo $ano; ?>"/></td>
  </tr>
 </table>
</center>
</form>