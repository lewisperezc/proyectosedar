<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/telefonia.class.php');
$ins_telefonia = new telefonia();
$valores = split("-",$_POST['cam_estado']);

$reg_tel_id = $valores[0];
$lin_tel_por_pla_id = $valores[1];

if($reg_tel_id==1)
$reg_tel_id = 2;
elseif($reg_tel_id==2)
$reg_tel_id = 1;

$modificar_estado_registro = $ins_telefonia->cam_est_reg_telefonia($reg_tel_id,$lin_tel_por_pla_id);
?>