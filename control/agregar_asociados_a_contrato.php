<?php session_start();
	  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
	  $ano = $_SESSION['elaniocontable'];
	  
include_once('../clases/contrato.class.php');
$ins_contrato = new contrato();

$hos_id = $_SESSION['hos'];
$nit_id = $_POST['aso_cen_costos'];

$res_cen_cos= $ins_contrato->con_nit_cen_costo($hos_id);

$gua_nit_por_contraro = $ins_contrato->agr_nit_contrato($res_cen_cos,$nit_id);
?>