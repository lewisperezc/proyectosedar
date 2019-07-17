<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

@include_once('../clases/tipo_comprobante.class.php');
@include_once('clases/tipo_comprobante.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
$ins_tip_comprobante=new tipo_comprobantes();
$notrae="2,4,5,7,13,19,22";
$con_tip_comprobante=$ins_tip_comprobante->ConTipComprobante($notrae);
$ins_mes_contable=new mes_contable();
$meses=$ins_mes_contable->DatosMesesAniosContables($ano);
?>
<!DOCTYPE HTML>
<html>
<head>
<script src="librerias/js/datetimepicker.js"></script>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
function ValidaMesContable()
{
	var cadena = document.sub_arc_plano.mes_sele.value;
	var ano = $("#estAno").val();
	cadena = cadena.split("-");
	if(cadena[0]==1)
	{
		alert("Mes de solo lectura.");
		return false;
	}
	else
		document.sub_arc_plano.submit();
	
}
</script>
</head>
<body>
<form name="sub_arc_plano" id="sub_arc_plano" action="control/subir_archivo_plano.php" method="post" enctype="multipart/form-data">
	<center>
		<table>
		<tr>
			<th colspan="4">SUBIR ARCHIVO PLANO</th>
		</tr>
<tr>
<td><input name="archivo" type="file" size="35" required/></td>
<td>Tipo Comprobante
<select name="tip_comprobante" id="tip_comprobante" required x-moz-errormessage="Seleccione Una Opcion Valida">
<option value="">--</option>
<?php
while($res_tip_comprobante=mssql_fetch_array($con_tip_comprobante))
{
?>
	<option value="<?php echo $res_tip_comprobante['tip_com_id']; ?>"><?php echo $res_tip_comprobante['tip_com_nombre']; ?></option>
<?php
}
?>
</select></td>
<td>Mes Contable
<select name="mes_sele" id="mes_sele">
<?php
while($dat_meses=mssql_fetch_array($meses))
{
	if($dat_meses['mes_estado']==2)
		echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."' selected='selected'>".$dat_meses['mes_nombre']."</option>";
	else
		echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";   
}
?>  
</select><input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes_contable->conAno($ano); ?>'/>
</td>
<td>Fecha
<input type="text" name="arc_pla_fecha" id="arc_pla_fecha" required/>
<a href="javascript:NewCal('arc_pla_fecha','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
</td>
</tr>
<tr><td colspan="4"><input name="enviar" onClick="ValidaMesContable();" type="button" class="art-button" value="Subir archivo"/></td></tr>
</table>
	</center>
</form>
</body>
</html>