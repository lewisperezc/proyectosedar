<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 include_once('../clases/cuenta.class.php');
 $cue_nominas = new cuenta();
 echo "Buscar las cuentas por pagar de nomina.";
?>