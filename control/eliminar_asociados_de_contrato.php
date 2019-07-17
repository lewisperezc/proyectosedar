<?php session_start();
	  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
	  $ano = $_SESSION['elaniocontable'];
	  
include_once('../clases/contrato.class.php');
$ins_contrato = new contrato();
?>