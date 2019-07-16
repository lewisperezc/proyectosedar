<?php
include_once('../clases/pabs.class.php');
include_once('../clases/cuenta.class.php');
$fabs = new pabs();
$inst_cuenta = new cuenta();

$tipo_produ="";
$linea_pabs="<option value=''>Seleccione</option>";

$cons_banco = $inst_cuenta->cue_Pagar(1110);
$cons_cuentas = $inst_cuenta->cue_Pagar(2335);
//error_reporting(E_ALL);
$i=0;

while($cuentas = mssql_fetch_array($cons_banco))
	$linea_pabs.="<option value='".$unarray['cue_id']."'>".$cuentas['cue_id']." ".$cuentas['cue_nombre']."</option>";

while($cuentas = mssql_fetch_array($cons_cuentas))
	$linea_pabs.="<option value='".$unarray['cue_id']."'>".$cuentas['cue_id']." ".$cuentas['cue_nombre']."</option>";

echo $linea_pabs;
?>