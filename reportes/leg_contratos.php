<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Legalizaci&oacute;n contratos</title>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function Mostrar()
{
	form=document.leg_contratos;
	if($("#rep_leg_contrato").val()==0)
	{
		$("#PorCiudad").css("display","none");
		$("#PorHospital").css("display","none");
	}
	if($("#rep_leg_contrato").val()==1)
	{
		$("#PorCiudad").css("display","none");
		$("#PorHospital").css("display","none");
		form.action='../reportes_EXCEL/leg_contratos.php?eltipo=1';
		form.submit();
	}
	if($("#rep_leg_contrato").val()==2)
	{
		$("#PorCiudad").css("display","block");
		$("#PorHospital").css("display","none");
	}
	if($("#rep_leg_contrato").val()==3)
	{
		$("#PorCiudad").css("display","none");
		$("#PorHospital").css("display","block");
	}
}
function Enviar(elvalor)
{
	form=document.leg_contratos;
	if($("#rep_leg_contrato").val()==2)
	{
		form.action='../reportes_EXCEL/leg_contratos.php?eltipo=2&elval='+elvalor;
		form.submit();
	}
	if($("#rep_leg_contrato").val()==3)
	{
		form.action='../reportes_EXCEL/leg_contratos.php?eltipo=3&elval='+elvalor;
		form.submit();
	}
		
}
</script> 
</head>
<?php
include_once('../clases/centro_de_costos.class.php');
$ins_cen_costo=new centro_de_costos();
$con_ciudades=$ins_cen_costo->cons_centro_costos_ciudad();
$con_centros=$ins_cen_costo->cons_centro_costos();
?>
<body>
<form name="leg_contratos" id="leg_contratos" method="post">
<center>
<table border="1">
	<tr>
    	<th colspan="2">LEGALIZACI&Oacute;N CONTRATOS</th>
    </tr>
    <tr>
    	<th>Tipo reporte</th>
        <td>
        	<select name="rep_leg_contrato" id="rep_leg_contrato" onChange="Mostrar(this.value);">
            	<option value="0">--</option>
            	<option value="1">GENERAL</option>
                <option value="2">POR CIUDAD</option>
                <option value="3">POR HOSPITAL</option>
            </select>
        </td>
    </tr>
    <tr id="PorCiudad" style="display:none;">
    	<th>Ciudad</th>
        <td>
        <select name="ciudad" id="ciudad" onChange="Enviar(this.value);">
        	<option value="0">--</option>
            <?php
            while($res_ciudades=mssql_fetch_array($con_ciudades))
			{
			?>
            	<option value="<?php echo $res_ciudades['cen_cos_id']; ?>"><?php echo $res_ciudades['cen_cos_nombre']; ?></option>
			<?php
			}
			?>
        </select>
        </td>
    </tr>
    <tr id="PorHospital" style="display:none;">
    	<th>C. Costo</th>
        <td>
        <select name="centro" id="centro" onChange="Enviar(this.value);">
        	<option value="0">--</option>
            <?php
            while($res_centros=mssql_fetch_array($con_centros))
			{
			?>
            	<option value="<?php echo $res_centros['cen_cos_id']; ?>"><?php echo $res_centros['cen_cos_nombre']; ?></option>
			<?php
			}
			?>
        </select>
        </td>
    </tr>
</table>
</center>
</form>
</body>
</html>