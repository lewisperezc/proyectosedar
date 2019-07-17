<?php 
  session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $new_pass = $_POST['nuevo'];
  @include_once('clases/usuario.class.php');
  @include_once('../clases/usuario.class.php');
  @include_once('clases/nits.class.php');
  @include_once('../clases/nits.class.php');
  $nit = new nits();
  $usuarios = new usuario();
  $modificar = $usuarios->modificar_usuario($nit,$new_pass);
  if($modificar)
  {
	  echo "<script>alert('Modifico correctamente su password.');</script>";
	  $_SESSION['k_password'] = $new_pass;
	  echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=107'>";
  }
  else
  {
	  echo "<script>alert('No se pudo modificar el password, intentelo nuevamente.');</script>";
	  echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=107'>"; 
  }  
  
?>
