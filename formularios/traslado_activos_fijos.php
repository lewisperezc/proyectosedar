<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php
@include_once('../clases/activo_fijo.class.php');
@include_once('clases/activo_fijo.class.php');
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$ins_act_fijo=new ActivoFijo();
$ins_nits=new nits();
$con_tod_ser_act_fijo=$ins_act_fijo->ConPlaActFijo();
$tipos="1,2,13";
$con_responsable_1=$ins_nits->ConProFondo($tipos);
$con_responsable_2=$ins_nits->ConProFondo($tipos);
$con_tod_tip_traslado=$ins_act_fijo->ConTodTipTraslado();
//LA FECHA//
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$lafecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
////////////
?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
function eliminarFila(oId)
{
	$("#tr"+oId).remove();   
	return true;
}
function DatosPersona(lacedula,laopcion,pos)
{
	TraePlacas(lacedula,pos);
	if(laopcion==1)
	{
		for(var i=0;i<=$("#canitem").val();i++)
		{
			eliminarFila(i);
		}
	}
	$.ajax({
   	  type: "POST",
   	  url: "llamados/trae_nombres_nit_por_cedula.php",
   	  data: "lacedula="+lacedula,
   	  success: function(msg)
	  {
	  	if(msg==""||msg==" "||msg=="# ")
		{
			alert('No se encontraron personas con el numero de documento ingresado!!!');
			if(laopcion==1)
			{
				$("#res_act_nombres").val('');
				$("#act_fij_res_actual").val('');
				$("#act_fij_res_actual").focus();
			}
			else
			{
				$("#res_nue_nombres").val('');
				$("#act_fij_res_nuevo").val('');
				$("#act_fij_res_nuevo").focus();
			}
		}
	  	else
		{
			var losdatos=msg.split("#");
			if(laopcion==1)
			{
				$("#res_act_nombres").val(losdatos[1]);
				$("#elidpersona1").val(losdatos[0]);
			}
			else
			{
				
				$("#res_nue_nombres").val(losdatos[1]);
				$("#elidpersona2").val(losdatos[0]);
			}
		}
	  }
	  });
}
function TraePlacas(cedula,pos)
{
	$.ajax({
   	  type: "POST",
   	  url: "llamados/trae_placa_activo_fijo_por_persona.php",
   	  data: "lacedula="+cedula,
   	  success: function(msg)
	  {
		  $("#placa"+pos).html(msg);
	  }
	});
}
function ObtenerDatosActivo(laplaca,elcontador)
{
	$.ajax({
   	  type: "POST",
   	  url: "llamados/trae_datos_activos_fijos.php",
   	  data: "laplaca="+laplaca,
   	  success: function(msg)
	  {
		var losdatos=msg.split("#");
	  	if(losdatos[0]==""||losdatos[0]==" ")
		{
			alert('No se encontraron activos fijos con la placa ingresada!!!');
			$("#act_fij_placa"+elcontador).val('');
			$("#act_fij_placa"+elcontador).focus();
		}
	  	else
		{
			$("#act_fij_id"+elcontador).val(losdatos[0]);
			$("#act_fij_tipo"+elcontador).val(losdatos[2]);
			$("#act_fij_serial"+elcontador).val(losdatos[6]);
			$("#act_fij_modelo"+elcontador).val(losdatos[5]);
		}
	  }
	  });
}
function NuevoItem()
{
	var cuantos=$("#lositems > tbody > tr").length-1;
	TraePlacas($("#act_fij_res_actual").val(),cuantos);
	var elhtml='<tr id="tr'+cuantos+'"><td>'+(cuantos+1)+'</td>';
	elhtml+='<td><input type="text" name="act_fij_placa'+cuantos+'" id="act_fij_placa'+cuantos+'" list="placa'+cuantos+'" onchange="ObtenerDatosActivo(this.value,'+cuantos+');" size="8" required/><datalist id="placa'+cuantos+'"></datalist></td>';
	elhtml+='<td><input type="hidden" name="act_fij_id'+cuantos+'" id="act_fij_id'+cuantos+'"/><input type="text" name="act_fij_tipo'+cuantos+'" id="act_fij_tipo'+cuantos+'" size="15" readonly/></td>';
	elhtml+='<td><input type="text" name="act_fij_serial'+cuantos+'" id="act_fij_serial'+cuantos+'" size="15" readonly/></td>';
	elhtml+='<td><input type="text" name="act_fij_modelo'+cuantos+'" id="act_fij_modelo'+cuantos+'" size="15" readonly/></td>';
	elhtml+='</tr>';
	$("#lositems").append(elhtml);
	$("#canitem").val(cuantos);
}
</script>
</head>
<body>
<form name="losactivosfijos" id="losactivosfijos" method="post" action="control/guardar_traslado_activo_fijo.php">
<center>
	<table>
    	<tr>
        	<th colspan="12">AUTORIZACION DE TRASLADO DE ACTIVOS PROPIEDAD DE ANESTECOOP - SEDAR</th>
        </tr>
        <tr>
        	<th colspan="6"><?php echo $lafecha; ?></th>
        </tr>
        <tr>
        	<td>RESPONSABLE ACTUAL DEL ACTIVO QUE SE TRASLADA:</td>
        	<td>C.C: <input type="text" name="act_fij_res_actual" id="act_fij_res_actual" list="res_actual" size="15" onChange="DatosPersona(this.value,1,0);" pattern="[0-9]+" title="El campo debe ser numerico" required/>
            <datalist id="res_actual">
 			<?php
	 	  	while($res_responsable_1=mssql_fetch_array($con_responsable_1))
		  	{
	   	  	echo "<option value='".$res_responsable_1['nits_num_documento']."' label='".$res_responsable_1['nits_num_documento']."'>";
		  	}
		  	?>        
            </datalist>
            </td>
            <td>
            <input type="hidden" name="elidpersona1" id="elidpersona1"/>
            <input type="text" name="res_act_nombres" id="res_act_nombres" size="30" required/>
            </td>
        </tr>
        <tr>
        	<td>RESPONSABLE  DEL FUNCIONARIO QUE RECIBE EL ACTIVO TRASLADADO:</td>
        	<td>C.C: <input type="text" name="act_fij_res_nuevo" id="act_fij_res_nuevo" list="res_nuevo" size="15" onChange="DatosPersona(this.value,2,0);" pattern="[0-9]+" title="El campo debe ser numerico" required/>
            <datalist id="res_nuevo">
 			<?php
	 	  	while($res_responsable_2=mssql_fetch_array($con_responsable_2))
		  	{
	   	  	echo "<option value='".$res_responsable_2['nits_num_documento']."' label='".$res_responsable_2['nits_num_documento']."'>";
		  	}
		  	?>        
            </datalist>
            </td>
            <td>
            <input type="hidden" name="elidpersona2" id="elidpersona2"/>
            <input type="text" name="res_nue_nombres" id="res_nue_nombres" size="30" required/></td>
        </tr>
	</table>
    <table id="lositems">
    <tr>
    	<th>ITEM</th>
        <th>PLACA</th>
        <th>TIP ACT FIJO</th>
        <th>SERIAL</th>
        <th>MODELO</th>
    </tr>
    </table>
    <table>
    	<tr><td><input type="button" class="art-button" class="art-button"  
 onClick="NuevoItem();" value="Nueva Fila"/>
		<input type="hidden" name="canitem" id="canitem"/>
		</td></tr>
    </table>
    <table>
    	<tr>
        	<th>TIPO DE TRASLADO</th>
            <td>
            <select name="tip_traslado" id="tip_traslado" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione</option>
            <?php
            while($res_tod_tip_traslado=mssql_fetch_array($con_tod_tip_traslado))
			{
			?>
            <option value="<?php echo $res_tod_tip_traslado['tip_tra_act_fij_id']; ?>"><?php echo $res_tod_tip_traslado['tip_tra_act_fij_nombre']; ?></option>
            <?php
			}
			?>
            </select>
            </td>
        </tr>
        <tr>
        	<th colspan="2">MOTIVO DEL TRASLADO</th>
        </tr>
        <tr>
        	<td colspan="2"><textarea name="mot_traslado" id="mot_traslado" cols="115" placeholder="Digite el motivo del traslado aqui..." required></textarea></td>
        </tr>
        <tr>
        	<td colspan="2"><input type="submit" class="art-button" class="art-button" value="Asignar"/></td>
        </tr>
    </table>
</center>
</form>
</body>
</html>