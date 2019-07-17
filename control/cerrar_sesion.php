<?php session_start();
include_once('../clases/usuario.class.php');
$ins_usuario = new usuario();
//$act_est_sesion = $ins_usuario->cam_est_sesion(1,$_SESSION['k_nit_id']);
$cerrar_sesion = $ins_usuario->cerrar_sesion();
echo "<script>location.href='../ingreso/index.php'</script>";
?>