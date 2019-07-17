<?php session_start();
$ano = $_SESSION['elaniocontable'];

@include_once('../clases/contrato.class.php');
@include_once('clases/contrato.class.php');
$ins_contrato=new contrato();
$con_con_act_con_adi_otrosi=$ins_contrato->ConContratoConAdiAtrosi(2,2,1);
?>
<!DOCTYPE html PUBLIC "#">
<html xmlns="#">
<head>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
function MosAdiOtrosi(elvalor){
	$("#otrosi_adicion_seleccionado").val(elvalor);
  	//alert("entra");
	$("#elresultado").css("display","none");
	$("#agr_nue_fila").css("display","none");
    $("#todoslosdatos").css("display","block");
	$.ajax({
		type: "POST",
		url: "llamados/trae_adiciones_otrosi_por_contrato.php",
		data: "elvalor="+elvalor,
		success: function(msg){
		$("#todoslosdatos").html(msg);
   		}
 	});
}

function TraeDatos(elvalor){
	$("#otrosi_adicion_seleccionado").val(elvalor);
   $("#elresultado").css("display","block");
   $("#agr_nue_fila").css("display","block");
   $.ajax({
   type: "POST",
   url: "llamados/trae_datos_adiciones_otrosi.php",
   data: "elvalor="+elvalor,
   success: function(msg){
     $("#elresultado").html(msg);
   }
 });
}


function AgregarFila()
{
	var cuantos=$("#agr_nue_fila > tbody > tr").length;
	//alert(cuantos);
	<?php
	$tipos="9,10";
    $con_tip_nit=$ins_contrato->con_tip_nit($tipos);
	$con_tip_concepto=$ins_contrato->con_tip_concepto(122);
	?>
	var html='<tr><th>Aseguradora</th><td><select name="con_nom_pol_aseguradora'+cuantos+'" id="con_nom_pol_aseguradora'+cuantos+'" required>';
    html+='<option value="">--Seleccione--</option>';
    <?php
    while($row = mssql_fetch_array($con_tip_nit))
    {
    ?>
    	html+='<option value="<?php echo $row['nit_id']; ?>"><?php echo substr($row['nits_nombres'],0,30); ?></option>';
    <?php
    }
    ?>                    
    html+='</select></td>';
	html+='<th>Poliza o Impuesto</th>';
    html+='<td><select name="con_pol_nombre'+cuantos+'" id="con_pol_nombre'+cuantos+'">';
    html+='<option value="">--Seleccione--</option>';
    <?php
    while($row = mssql_fetch_array($con_tip_concepto))
    {
    ?>
    	html+='<option value="<?php echo $row['con_id']; ?>"><?php echo substr($row['con_nombre'],0,30); ?></option>';
    <?php
    }
    ?>
    html+='</select></td>';
    html+='<th>Valor</th>';
    html+='<td><input type="text" name="con_pol_porcentaje'+cuantos+'" id="con_pol_porcentaje'+cuantos+'"/></td>';
    html+='<th>Tipo</th>';
    html+='<td><select name="tip_pol_impuesto'+cuantos+'" id="tip_pol_impuesto'+cuantos+'">';
    html+='<option value="">--</option>';
    html+='<option value="1">DESCONTABLE</option>';
    html+='<option value="2">INFORMATIVO</option>';
    html+='</select>';
    html+='</td>';
    html+='<th>Observaci&oacute;n</th><td><input type="text" name="obs_pol_impuesto'+cuantos+'" id="obs_pol_impuesto'+cuantos+'"/></td>';
    html+='</tr>';
	$("#agr_nue_fila").append(html);
	$("#can_fil_nue_poliza").val(cuantos);
	if(cuantos<=1)
	{
		html_btn='<tr><th><input type="button" name="btn_gua_nue_poliza" id="btn_gua_nue_poliza" value="Guardar" onclick="EnviarGuardar();"/></th></tr>';
		$("#tbl_guardar").append(html_btn);
	}
}

</script>
</head>
<body>

  <center>
	<?php
  	if(!empty($con_sele))
    	echo "<script>MosAdiOtrosi(".$con_sele.")</script>";
  	else
  	{
    	echo "<table><tr><th>Contrato:<select name='contrato' id='contrato' onchange='MosAdiOtrosi(this.value);'><option value=''>Seleccione</option>";
    	while($res_con_act_con_adi_otrosi=mssql_fetch_array($con_con_act_con_adi_otrosi))
        	echo "<option value=".$res_con_act_con_adi_otrosi['con_id'].">".$res_con_act_con_adi_otrosi['nombres']."</option>";
    	echo "</select></th><th id='todoslosdatos' style='display:none'></th>";
    	echo "</tr></table>";
  	}
  	?>
	<table id="elresultado" style="display:none;border:1px solid;">
    </table>

	<table name='agr_nue_fila' id='agr_nue_fila' style="display:none;border:1px solid;">
    	<tr><td colspan='8'><input type='button' name='btn_agr_fila' id='btn_agr_fila' value='Agregar poliza o impuesto' onclick='AgregarFila()'/>
    		<input type='hidden' name='can_fil_nue_poliza' id='can_fil_nue_poliza' value='0'/>
    	</td></tr>
    </table>
    <table id="tbl_guardar"></table>
    
   </center>
</body>
</html>