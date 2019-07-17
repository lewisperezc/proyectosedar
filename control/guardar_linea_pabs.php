<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/linea_pabs.class.php');
$ins_lin_pabs = new linea_pabs();
$aso_lin_pab_nombre = $_POST['aso_lin_pab_nombre'];
$aso_lin_pab_porcentaje = $_POST['aso_lin_pab_porcentaje'];
$i = 0;
while($i < sizeof($aso_lin_pab_nombre)){
$gua_lin_pabs = $ins_lin_pabs->gua_lin_pabs($aso_lin_pab_nombre,$aso_lin_pab_porcentaje);
$i++;
}
unset($_SESSION['aso_lin_pab_nombre']);
unset($_SESSION['aso_lin_pab_porcentaje']);
?>