<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); } ?>
<link rel="stylesheet" type="text/css" href="../estilos/limpiador.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
<?php
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/nits.class.php');
@include_once('../clases/centro_de_costo.class.php');
@include_once('../clases/reporte_jornadas.class.php');
@include_once('../clases/factura.class.php');
@include_once('clases/nits.class.php');
@include_once('clases/centro_de_costo.class.php');
@include_once('clases/reporte_jornadas.class.php');
@include_once('clases/factura.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('../clases/mes_contable.class.php');

$_SESSION["hospital"]="";
$_SESSION["tipo"]="";
$_SESSION["num_aso"] = "";
$_SESSION["i"] = "";
$fac_elegida;
$nit = new centro_de_costos();
$hospital = $nit->con_cen_por_con_estado(1);
$rep = new reporte_jornadas();
$factura = new factura();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);

?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/separador.js"></script>
<script language="javascript">
 function prueba(hosp,tipo){
   $.ajax({
   type: "POST",
   url: "./llamados/prueba.php",
   data: "id="+hosp,
   success: function(msg){$("#prue"+tipo).val(msg);}
 });
}

function habilitar(val)
{
	if(val=='NULL')
	{
		$("#facturas").css("display", "none");
		$("#repor_jornadas").css("display", "none");
	}
	if(val==1)
	{
		$("#facturas").css("display", "block");
		$("#repor_jornadas").css("display", "none");
	}
	if(val==2){
	  $("#facturas").css("display", "none");
	  $("#repor_jornadas").css("display", "block");
	}
}

function eliminarCon(oId){
     $("#aso"+oId).remove();
     return true;
  }

function aso_centro(valor)
{
	var cuantos = $("#dat_asociados > tbody > tr").length;
	for(i=0;i<cuantos;i++)
	    eliminarCon(i);
	var html = '';
	var cuantos = 0;
	var otro='';
	var nit = '';
	var cen_fac = valor.split("-");
	var mes = '';
	prueba(valor,1);
	$.ajax({
		type: "POST",
		url: "./llamados/asociados_reporte.php",
		data: "hospital="+cen_fac[0]+"&factura="+cen_fac[1],	
		success: function(msg){
			var myObject = eval('(' + msg + ')');
			for (var x = 0 ; x < myObject.length ; x++) 
			{
				mes = myObject[x].mes_contable;
				html+='<tr id="aso'+x+'"><td>'+myObject[x].nit_id+'</td><td>'+myObject[x].nom_aso+'</td><td>'+myObject[x].estado+'<input type="hidden" name="num_aso'+x+'" id="num_aso'+x+'" value="'+myObject[x].aso_num+'"/></td><td><input type="text" name="num_jornadas'+x+'" id="num_jornadas'+x+'" onblur="suma_jornadas('+myObject.length+');" required="required" onkeypress="mascara(this,cpf);"/></td><td><input type="text" name="jor_por_afiliado'+x+'" id="jor_por_afiliado'+x+'" onblur="suma_jornadas_individuales('+myObject.length+');" required="required" onkeypress="mascara(this,cpf);" value="0"/></td></tr>';
				cuantos = myObject.length;
			}
			html+='<tr><td colspan="2"><input type="hidden" name="mes_sele" id="mes_sele" value="'+mes+'" >Suma Jornadas</td><td colspan="2"><input type="text" name="sum_jorn" id="sum_jorn" readonly/></td><td><input type="text" name="sum_jor_por_afiliado" id="sum_jor_por_afiliado" size="10" value="0" readonly/></td></tr>';
			$("#dat_asociados").append(html);
			$("#cantidad").val(cuantos);
			$("#centro").val(valor);
			
		}
	});
}

function suma_jornadas(valor)
 {
	var suma=0;
	var sum_sin=0;
	for(i=0;i<valor;i++)
	{
	  if($('#num_jornadas'+i).val()!="")
	  {

		sum_sin=$('#num_jornadas'+i).val();
	    sum_sin=sum_sin.replace(/,/g , "");sum_sin=sum_sin.replace('.',',');
	    sum_sin=elvalor(sum_sin.replace('$',''),1);
	    sum_sin=sum_sin.replace(',','.');
	    suma += parseFloat(sum_sin);
	  }
	}
	$('#sum_jorn').val(suma);
 }
 
 function suma_jornadas_individuales(valor)
 {
    var suma=0;
    var sum_sin=0;
    for(i=0;i<valor;i++)
    {
      if($('#jor_por_afiliado'+i).val()!="")
      {

        sum_sin=$('#jor_por_afiliado'+i).val();
        sum_sin=sum_sin.replace(/,/g , "");sum_sin=sum_sin.replace('.',',');
        sum_sin=elvalor(sum_sin.replace('$',''),1);
        sum_sin=sum_sin.replace(',','.');
        suma += parseFloat(sum_sin);
      }
    }
    $('#sum_jor_por_afiliado').val(suma);
 }
 
 
function enviar(hosp)
 {
    if(document.f1.sel_hos.selectedIndex==0)
      alert("Debe Seleccionar un hospital");
    else
      document.f1.submit();
 }

function val_cam(campos,hospital,entra)
{ 
	$("#f1").submit(function(){return false;});
	
	  quitarPuntos();
	  var suma_jornadas = 0;
	  var suma = 0
	  var bandera = 0;
	  var tip_entrada = $("#exi_fac").val();
	  /*mes=document.f1.mes_contable.value.split('-');*/
	  mes=$("#mes_sele").val().split('-');
	  ano=document.f1.estAno.value
	  //alert(mes[0]);
	  //alert(ano);
	  		if(tip_entrada==1)
	  		{
	  			var opcion = document.f1.tip_rep1.value;
				var maxi = document.f1.prue1.value;
				var dat_factura = $("#fac_sin").val();
				var datos_factura = dat_factura.split("-");
				if(datos_factura[2]!=$("#sum_jorn").val())
				{
					alert("El valor de las jornadas debe ser igual al valor de la Factura, revise sus jornadas");
					return false;
				}
				else
				{
					if(opcion == 0)
					{
						alert("Debe seleccionar el tipo de reporte");
						return false;
					}
					else
					{
						
						if(entra==0)
						{
							if(opcion==1)
							{
								if(suma != datos_factura[2])
								{
									alert("el reporte de jornadas no es igual al valor de la factura, revise sus jornadas");
								  	return false;
								}
							 }
							 else
							 {
							 	for(i=0;i<campos;i++)
							 	{
							 		if(document.getElementById("num_jornadas"+i).value<0 || document.getElementById("num_jornadas"+i).value>120)
									{
										alert("Debe escribir un valor entre 0 y 120 de las jornadas en la posicion "+ (i+1));
										bandera = 1;
										f1.num_jornadas+i.focus();
										return false;
								 	}
							  	}
							 }
								document.f1.action = 'control/guardar_reporte_jornadas.php';
						}
						else
						document.f1.action = 'control/guardar_reporte_jornadas.php?factura=1';
					}
				}
			  }//CIERRA EL IF QUE PREGUNTA SI YA EXISTE FACTURA
			else//LA FACTURA NO EXISTE
	  		{
	  			if(mes[0]==1)
	  			{
	  				alert("Mes de solo lectura");
	  				return false;
	  			}
	  			else
	  			{
		    		var opcion = document.f1.tip_rep2.value;
					var maxi = document.f1.prue2.value;
					if(opcion == 0)
				   	{
					   alert("Debe seleccionar el tipo de reporte");
					   return false;
				   	}
			  		else
			  		{
				  		if(opcion==1)
				  		{
				  			if(bandera==0)
				  			{		  
								if(entra==0)
		                        	document.f1.action = 'control/guardar_reporte_jornadas.php';
		                        else
		                        	document.f1.action = 'control/guardar_reporte_jornadas.php?factura=1';
		                    }
		                }
				  		else
				  		{
					 		for(i=0;i<campos;i++)
			         		{
					   			if(document.getElementById("num_jornadas"+i).value == '' || document.getElementById("num_jornadas"+i).value >120)
						 		{
						  			alert("Debe escribir un numero >= 0 y < que 120 en el numero de jornadas "+ (i+1));
						  			bandera = 1;
						  			f1.num_jornadas+i.focus();
						  			return false;
						 		}
				       			suma_jornadas += parseInt(document.getElementById("num_jornadas"+i).value);
				     		}
				     		if(bandera==0)
							{		  
						 		if(suma_jornadas > (120*campos))
						  		{
							 		alert("la suma de los reportes de jornadas no puede ser mayor a 120");
							 		return false;
						  		}
						 		else  
						 		{ 
						   			if(entra==0)
										document.f1.action = 'control/guardar_reporte_jornadas.php';
							 		else
										document.f1.action = 'control/guardar_reporte_jornadas.php?factura=1';
						 		} 
				        	}
			      		}
		        	}
		      }//CIERRA EL ELSE QUE EJECUTA TODO
	  		}  
	  		document.f1.submit();
  }
</script>

<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<form name="f1" id="f1" method="post" action="#">
  <center>
   <table>
     <tr>
         <td>Ya existe factura para este reporte de jornadas?</td>
         <td>
             <select name="exi_fac" id="exi_fac" onchange="habilitar(this.value);" >
         	  <option value="NULL">Seleccione...</option>
              <option value="1">Si</option>
              <option value="2">No</option>
         	 </select>
         </td>
     </tr>
   </table>
   
<div id="facturas" style="display:none">
 <table>
  <tr>
      <td>Seleccione Factura: </td>
      <td>
        <select name="fac_sin" id="fac_sin" onchange="aso_centro(this.value);">
          <option value="NULL">Seleccione...</option>
          <?php
		   $fac_sin=$factura->fac_sin_reporte();
		   while($row=mssql_fetch_array($fac_sin))
		   {
				$val_notas=$factura->ConsultarValorNotasPorFactura($row['fac_id']);
		   		echo "<option value='".$row['fac_cen_cos']."-".$row['fac_id']."-".($row['fac_val_total']+$val_notas)."'>".$row['fac_consecutivo']."</option>";
		   }
		  ?>
      	</select>
      </td>
  </tr>
 </table>
<!--INICIO LAS JORNADAS YA TIENEN FACTURA-->
 <table id="dat_asociados" border="1">
  <tr>
    <td colspan="2">Tipo reporte</td>
    <td colspan="2">
      <select name="tip_rep1" id="tip_rep1">
       <option value="0">Seleccione...</option>
       <option value="1">Reporte en dinero</option>
       <option value="2">Reporte en jornadas</option>
      </select>
    </td>
   </tr>
   <tr>
	<th>Identificacion</th>
	<th>Nombre</th>
	<th>Estado</th>
	<th>Novedad</th>
	<th>Jornadas por afiliado</th>
    </tr>
</table>
  <input type="hidden" name="cantidad" id="cantidad" />
  <input type="hidden" name="centro" id="centro" />
  <input type="hidden" name="prue1" id="prue1" value=""/>
  <input type="hidden" name="val_fac" id="val_fac" value="" />
  <input type="submit" class="art-button" value="Guardar" onclick="val_cam(cantidad.value,centro.value,1);" />
</div>


<div id="repor_jornadas" style="display:none">
     <table id="hospital" width="30%">
      <tr>
       <td>Seleccione el Hospital</td>
       <td>
        <select name="sel_hos" id="sel_hos">
         <option value="0" onclick="enviar(this.value);">Seleccione</option>
         <?php 
           while($row = mssql_fetch_array($hospital))
                echo "<option value='".$row['cen_cos_id']."' onclick='enviar(this.value);'>".$row['cen_cos_nombre']."</option>";
         ?>
         </select>
        </td>
       </tr>
      </table>
    <br />  
  <!--</div>-->
  <?php
  	@include_once('clases/mes_contable.class.php');
	@include_once('../clases/mes_contable.class.php');
	$ins_mes = new mes_contable();
  	$sel_hosp = $_POST['sel_hos'];
  	echo "<input type='hidden' name='mes_contable' id='mes_contable' value='".$_POST['mes_sele']."' />";
  	echo "<input type='hidden' name='estAno' id='estAno' value='".$ins_mes->conAno($ano)."'/>";
	$mes = $_POST['mes_sele'];
	$ano = $_SESSION['elaniocontable'];
	$_SESSION["hospital"] = $sel_hosp;
	$_SESSION["tipo"]=1;
	if($sel_hos)
	{
		   echo '<script>$("#hospital").css("display", "none");</script>';
		   $nom_centro=$nit->datos_centro_por_id($sel_hos);
		   $asociados = $nit->buscar_asociados($sel_hosp);
	       ?>
	        <table id="reporte" border="1">
	       	<tr><th colspan="5"><?php echo $nom_centro['cen_cos_nombre']; ?></th></tr>
	       	<tr><td>Mes Contable</td>
	       	<td colspan="5"><select name="mes_sele" id="mes_sele">
          	<?php
				while($dat_meses = mssql_fetch_array($meses))
			    	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  	?>  
      		</select>    
	  		</td></tr>
            <?php echo "<script>prueba($sel_hosp,2)</script>"; ?>
	         <tr>
              <td>Tipo reporte</td>
              <td colspan="5">
               <select name="tip_rep2" id="tip_rep2">
                <option value="0">Seleccione...</option>
                <option value="1">Reporte en dinero</option>
                <option value="2">Reporte en jornadas</option>
               </select>
              </td>
             </tr>
             <tr>
		      <th>Identificacion</th>
		      <th>Nombre</th>
		      <th>Estado</th>
		      <th>Novedad</th>
		      <th>Jornadas por afiliado</th>
		     </tr>
	       <?php
		   	 $canti_aso = mssql_num_rows($asociados);
			 if($canti_aso>0)
			 {
	         $i=0;
	         while($row = mssql_fetch_array($asociados))
		      {
		          echo "<tr>";
			       echo "<td>".$row['doc']."</td>";
			       echo "<td>".$row['nombres']." ".$row['apellidos']."</td>";
			       echo "<td>".$row['estado']."</td>"; 
			       $num_aso[$i] = $row['cen_nit_id']; ?>
			    <td>
<input type="text" name="num_jornadas<?php echo $i; ?>" id="num_jornadas<?php echo $i; ?>" onblur="suma_jornadas(<?php echo $canti_aso; ?>);" 
onkeypress="mascara(this,cpf);" required="required"/></td>
<!--onpaste="return false"-->
                <td>
<input type="text" required="required" name="jor_por_afiliado<?php echo $i; ?>" id="jor_por_afiliado<?php echo $i; ?>" onblur="suma_jornadas_individuales(<?php echo $canti_aso; ?>);" 
onkeypress="mascara(this,cpf);" value="0"/>
<!--onpaste="return false"--> 
</td> 
                <?php
			     echo "</tr>";
			     $i++;
		      } 
		     $_SESSION["num_aso"] = $num_aso;
			 $_SESSION['i'] = $i;
	        ?>
         <tr>
          <th colspan="3">Suma Jornadas</th><td><input type="text" name="sum_jorn" id="sum_jorn" size="10" readonly/></td>
          <td><input type="text" name="sum_jor_por_afiliado" id="sum_jor_por_afiliado" size="10" value="0" readonly/></td>
	     <tr>
	        <td colspan="5" align="center">
	          <input type="submit" class="art-button" value="Guardar" onclick="val_cam(<?php echo $i; ?>,<?php echo $sel_hos;?>,0);" />
              <input type="hidden" name="prue2" id="prue2" value=""/>
	        </td>
	       </tr>
	     <?php
			 }
			 else
				echo "<tr><th colspan='4'>No se encontraron afiliados activos en este centro de costo.</th></tr>";
	    }
	?>
  </table>
</div>  
  </center>
</form>