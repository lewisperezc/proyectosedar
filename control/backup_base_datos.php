<?php session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
  
	@include_once('../clases/mantenimiento_base_datos.class.php');
	@include_once('clases/mantenimiento_base_datos.class.php');
	$ins_man_bas_datos=new mantenimiento_base_datos();
	$base_datos='sedasoftrediseno';
	$backup_base_datos=$ins_man_bas_datos->BackupBaseDatos($base_datos);
	
	if($backup_base_datos)
		echo "<script>alert('Proceso realizado correctamente.');location.href='../index.php?c=166';</script>";
	else
		echo "<script>alert('Error al realizar el proceso, intentelo de nuevo.');location.href='../index.php?c=166';</script>";
?>
