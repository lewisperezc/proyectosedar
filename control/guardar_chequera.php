<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/chequera.class.php');
$inst_chequera = new chequera();
$cuenta = $_POST['cuenta'];
$con_inicio = $_POST['ini'];
$con_fin = $_POST['fin'];
$estado = 1;
$guardar = $inst_chequera->ins_chequera($cuenta,$con_inicio,$con_fin,$estado);
if($guardar)
	echo "<script>alert('Chequera creada correctamente.');location.href = '../index.php?c=77';</script>";
else
	echo "<script>alert('Error al crear la chequera, Intentelo de nuevo.');location.href = '../index.php?c=77';</script>";
?>