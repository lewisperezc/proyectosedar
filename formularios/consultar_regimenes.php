<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('clases/regimenes.class.php');
@include_once('../clases/cuenta.class.php');
$regimen = new regimenes();
$cuenta = new cuenta();
$regimenes = $regimen->cons_regimen();
?>

<form name="datos_aso_1" method="post" action="#" target="frame2">
<center>
	<table>
  <tr><th colspan="3"><h4>Datos Regimenes</h4></th></tr>
  <tr><th><h4>Tipo Regimen</h4></th><th><h4>Se cobra impuestos a: </h4></th><th><h4>ReteIva</h4></th></tr>
  <?php
    $html="<td><select name='cuenta' id='cuenta' >";
	$cue_iva = $cuenta->cue_Pagar(2367);
	while($row_cuenta = mssql_fetch_array($cue_iva))
	 {
		if($row['reg_cueIva']==$row_cuenta['cue_id'])
	 	  $html.="<option value='".$row_cuenta['cue_id']."' selected='selected'>".$row_cuenta['cue_nombre']."</option>";
		else
		  $html.="<option value='".$row_cuenta['cue_id']."'>".$row_cuenta['cue_nombre']."</option>";
	 }
	$html.="</select></td>";
		
    while($row = mssql_fetch_array($regimenes))
	{
		echo "<tr><td>".$row['reg_nombre']."</td><td>".$row['impuestos']."</td>";
		echo $html."</tr>";
	}
  ?>
</table>
</center>
</form>