<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/telefonia.class.php');
$ins_telefonia = new telefonia();

$cam_est_lin_telefonica = $_POST['cam_est_lin_telefonica'];//ID DE LA LINEA
$est_lin_id = $_POST['est_lin_id']; //NUEVO ESTADO
if($est_lin_id == 1)
$est_lin_id = 2;
elseif($est_lin_id == 2)
$est_lin_id = 1;

$modificar_estado_linea_telefonica = $ins_telefonia->cam_est_lin_telefonica($est_lin_id,$cam_est_lin_telefonica);
?>