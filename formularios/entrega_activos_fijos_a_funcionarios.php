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
$con_responsable=$ins_nits->ConProFondo($tipos);
?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
function NuevoItem()
{
	var cuantos=$("#lositems > tbody > tr").length-2;
	<?php
	$con_tod_ser_act_fijo_2=$ins_act_fijo->ConPlaActFijo();
	$con_responsable_2=$ins_nits->ConProFondo($tipos);
	?>
	var elhtml='<tr id="tr'+cuantos+'"><td>'+(cuantos+1)+'</td>';
	elhtml+='<td><input type="text" name="act_fij_placa'+cuantos+'" id="act_fij_placa'+cuantos+'" list="placa'+cuantos+'" onchange="ObtenerDatos(this.value,'+cuantos+');" size="8" required="required"/><datalist id="placa'+cuantos+'">';
    <?php
	while($res_tod_ser_act_fijo_2=mssql_fetch_array($con_tod_ser_act_fijo_2)) { ?>
	elhtml+='<option value="<?php echo $res_tod_ser_act_fijo_2['act_fij_pla_actual']; ?>" label="<?php echo $res_tod_ser_act_fijo_2['act_fij_pla_actual']; ?>">;';
	<?php } ?>
	elhtml+='</datalist></td>';
	elhtml+='<td><input type="hidden" name="act_fij_id'+cuantos+'" id="act_fij_id'+cuantos+'"/><input type="text" name="act_fij_tipo'+cuantos+'" id="act_fij_tipo'+cuantos+'" size="12"/></td>';
	elhtml+='<td><input type="text" name="act_fij_descripcion'+cuantos+'" id="act_fij_descripcion'+cuantos+'"/></td>';
	elhtml+='<td><input type="text" name="act_fij_marca'+cuantos+'" id="act_fij_marca'+cuantos+'" size="12"/></td>';
	elhtml+='<td><input type="text" name="act_fij_modelo'+cuantos+'" id="act_fij_modelo'+cuantos+'" size="12"/></td>';
	elhtml+='<td><input type="text" name="act_fij_serial'+cuantos+'" id="act_fij_serial'+cuantos+'" size="12"/></td>';
	elhtml+='<td><input type="text" name="act_fij_color'+cuantos+'" id="act_fij_color'+cuantos+'" size="12"/></td>';
	elhtml+='<td><input type="text" name="act_fij_caracteristicas'+cuantos+'" id="act_fij_caracteristicas'+cuantos+'"/></td>';
	elhtml+='<td><input type="text" name="act_fij_propietario'+cuantos+'" id="act_fij_propietario'+cuantos+'" size="12"/></td>';
	elhtml+='<td><input type="text" name="act_fij_estado'+cuantos+'" id="act_fij_estado'+cuantos+'" size="8"/></td>';
	
	elhtml+='<td><input type="text" name="act_fij_responsable'+cuantos+'" id="act_fij_responsable'+cuantos+'" list="responsable'+cuantos+'" size="25" required="required"/><datalist id="responsable'+cuantos+'">';
    <?php
	while($res_responsable_2=mssql_fetch_array($con_responsable_2)) { ?>
	elhtml+='<option value="<?php echo $res_responsable_2['nit_id']."-".$res_responsable_2['nits_nombres']." ".$res_responsable_2['nits_apellidos']; ?>" label="<?php echo $res_responsable_2['nits_num_documento']; ?>">;';
	<?php } ?>
	elhtml+='</datalist></td>';
	elhtml+='</tr>';
	$("#lositems").append(elhtml);
	$("#canitem").val(cuantos);
}


function ObtenerDatos(laplaca,elcontador)
{
	$.ajax({
   	  type: "POST",
   	  url: "llamados/trae_datos_activos_fijos.php",
   	  data: "laplaca="+laplaca,
   	  success: function(msg)
	  {
		var losdatos=msg.split("#");
	  	if(losdatos[0]==""&&losdatos[1]=="")
		{
			alert('No se encontraron activos fijos con la placa ingresada!!!');
		}
	  	else
		{
			$("#act_fij_id"+elcontador).val(losdatos[0]);
			$("#act_fij_tipo"+elcontador).val(losdatos[2]);
			$("#act_fij_descripcion"+elcontador).val(losdatos[3]);
			$("#act_fij_marca"+elcontador).val(losdatos[4]);
			$("#act_fij_modelo"+elcontador).val(losdatos[5]);
			$("#act_fij_serial"+elcontador).val(losdatos[6]);
			$("#act_fij_color"+elcontador).val(losdatos[7]);
			$("#act_fij_caracteristicas"+elcontador).val(losdatos[8]);
			$("#act_fij_propietario"+elcontador).val(losdatos[9]);
			$("#act_fij_estado"+elcontador).val(losdatos[10]);
			$("#act_fij_responsable"+elcontador).val(losdatos[11]+"-"+losdatos[12]+" "+losdatos[13]+" "+losdatos[14]);
		}
	  }
	  });
}
</script>
</head>
<body>
<form name="losactivosfijos" id="losactivosfijos" method="post" action="control/asignar_activo_fijo.php">
<center>
	<table id="lositems">
    	<tr>
        	<th colspan="12">INVENTARIO  DE  ACTIVOS  FIJOS  PROPIEDAD  DE  ANESTECOOP  - SEDAR <br>
            ENTREGADOS PARA EL DESEMPEÑO DE SUS FUNCIONES  Y CUSTODIA  FUNCIONARIOS DE LA ENTIDAD.</th>
        </tr>
    	<tr>
        	<th>ITEM</th>
            <th>PLACA</th>
            <th>TIP ACT FIJO</th>
            <th>DESCRIPCIÓN</th>
            <th>MARCA</th>
            <th>MODELO</th>
            <th>SERIAL</th>
            <th>COLOR</th>
            <th>CARACTERISTICAS</th>
            <th>PROPIEDAD</th>
            <th>ESTADO</th>
            <th>RESPONSABLE</th>
        </tr>
        <tr>
        	<td>1</td>
            <td>
            <input type="text" name="act_fij_placa0" id="act_fij_placa0" list="placa0" onchange='ObtenerDatos(this.value,0);' size="8" required/>
            <datalist id="placa0">
 			<?php
	 	  	while($res_tod_ser_act_fijo = mssql_fetch_array($con_tod_ser_act_fijo))
		  	{
	   	  	echo "<option value='".$res_tod_ser_act_fijo['act_fij_pla_actual']."' label='".$res_tod_ser_act_fijo['act_fij_pla_actual']."'>";
		  	}
		  	?>        
            </datalist>
            </td>
            <td>
            <input type="hidden" name="act_fij_id0" id="act_fij_id0"/>
            <input type="text" name="act_fij_tipo0" id="act_fij_tipo0" size="12" readonly/></td>
            <td><input type="text" name="act_fij_descripcion0" id="act_fij_descripcion0" readonly/></td>
            <td><input type="text" name="act_fij_marca0" id="act_fij_marca0" size="12" readonly/></td>
            <td><input type="text" name="act_fij_modelo0" id="act_fij_modelo0" size="12" readonly/></td>
            <td><input type="text" name="act_fij_serial0" id="act_fij_serial0" size="12" readonly/></td>
            <td><input type="text" name="act_fij_color0" id="act_fij_color0" size="12" readonly/></td>
            <td><input type="text" name="act_fij_caracteristicas0" id="act_fij_caracteristicas0" readonly/></td>
            <td><input type="text" name="act_fij_propietario0" id="act_fij_propietario0" size="12" readonly/></td>
            <td><input type="text" name="act_fij_estado0" id="act_fij_estado0" size="8" readonly/></td>
            <td><input type="text" name="act_fij_responsable0" id="act_fij_responsable0" list="responsable0" size="35" required/>
            <datalist id="responsable0">
 			<?php
	 	  	while($res_responsable = mssql_fetch_array($con_responsable))
		  	{
	   	  	echo "<option value='".$res_responsable['nit_id']."-".$res_responsable['nits_nombres']." ".$res_responsable['nits_apellidos']."' label='".$res_responsable['nits_num_documento']."'>";
		  	}
		  	?>        
            </datalist>
            </td>
        </tr>
    </table>
    <table>
    <tr>
		<td colspan="12">
        MEDIANTE EL PRESENTE INVENTARIO Y DOCUMENTO, LAS PARTES QUE EN EL  INTERVIENEN, ADMINISTRADOR DE INVENTARIOS DE ACTIVOS FIJOS PROPIEDAD DE ANESTECOOP - SEDAR Y EL FUNCIONARIO QUE FIRMA EL RECIBIDO, DECLARAN QUE  LOS BIENES (ACTIVOS) SON ENTREGADOS Y RECIBIDOS EN PERFECTO ESTADO DE FUNCIONAMIENTO Y CONSERVACIÒN  SALVO CON LAS OBSERVACIONES ANOTADAS EN EL MOMENTO DE LA ENTREGA Y POR EL DESGASTE Y DETERIORO NORMAL DE SU USO; QUE POR LO TANTO, EL FUNCIONARIO QUE RECIBE LOS ACTIVOS FIJOS PARA EL DESEMPEÑO DE SUS LABORES , SE COMPROMETE Y OBLIGA CON ANESTECOOP - SEDAR A VELAR POR SU CONSERVACIÒN Y CUSTODIA, POR LO QUE EN CASO DE PÈRDIDA, HURTO O DETERIORO DE LOS MISMOS OCURRIDOS POR NEGLIGENCIA DEMOSTRADA, ASUME LA RESPONSABILIDAD QUE LE CORRESPONDE Y POR CONSIGUIENTE SE SOMETE A LAS ACTUACIONES ADMINISTRATIVAS A QUE HAYA LUGAR DE CONFORMIDAD CON EL PRESENTE  MANUAL DE POLÌTICAS Y PROCEDIMIENTOS PARA LA ADMINISTRACIÒN DE LOS ACTIVOS FIJOS DE LA EMPRESA  ANESTECOOP - SEDAR.
        </td>
    </tr>
    </table>
    <table>
    	<tr><td><input type="button" class="art-button" onClick="NuevoItem();" value="Nueva Fila"/>
        <td><input type="submit" class="art-button" value="Asignar"/></td>
		<input type="hidden" name="canitem" id="canitem"/>
		</td></tr>
    </table>
    </center>
</form>
</body>
</html>