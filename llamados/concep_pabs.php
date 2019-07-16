<?php
include_once('../clases/cuenta.class.php');
$ins_cuenta = new cuenta();
$nit_id = $_POST['id'];
$cuentas = $ins_cuenta->con_cue_pabs(61201020);
error_reporting(E_ALL);
if($cuentas!=false)
  {
	$i=0;
    while($unarray = mssql_fetch_array($cuentas))       
	   {
		$res[$i]["id"] = $unarray['cue_id'];
 		$res[$i]["nombre"] =$unarray["cue_nombre"];
 		$res[$i]["costo"] ="000";
		$i++;
	   }  
  }
echo json_encode($res);
?>