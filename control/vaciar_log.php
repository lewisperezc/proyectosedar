<?php session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
  
	@include_once('../clases/mantenimiento_base_datos.class.php');
	@include_once('clases/mantenimiento_base_datos.class.php');
	$ins_man_bas_datos=new mantenimiento_base_datos();
	$nombre_base_datos="sedasoftrediseno";
	$nombre_log="bd_sedasoft_puch_log";
	
	
	$vaciar_log=$ins_man_bas_datos->VaciarLog($nombre_base_datos,$nombre_log);
	
	if($vaciar_log)
		echo "<script>alert('Proceso realizado correctamente.');location.href='../index.php?c=165';</script>";
	else
		echo "<script>alert('Error al realizar el proceso, intentelo de nuevo.');location.href='../index.php?c=165';</script>";
?>
