<?php
include_once('../clases/cuenta.class.php');
$ins_cuenta = new cuenta();
$valor="SI";
$con_cuentas=$ins_cuenta->con_cue_nomina($valor);
$i=0;
while($res_cuentas=mssql_fetch_array($con_cuentas))
{
	$res[$i]["id"]=$res_cuentas['tip_pro_id'];
	$res[$i]["nombre"]=$res_cuentas['tip_pro_nombre'];
	$i++;
}
echo json_encode($res);
?>