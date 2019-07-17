<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include('../clases/tipo_producto.class.php');
$producto = $_POST['tipoproducto'];

$tip = $_POST['tipo'];
$concepto= $_POST['con'];
$cuenta = $_POST['cuenta'];
$tipo= new tipo_producto();
	$descrip=$tipo->crear_tipo_producto($tip,$concepto,$cuenta);
	if($descrip)
	{
		echo "<script>
				alert('Tipo producto creado correctamente.');
				location.href='../index.php?c=11';
			  </script>";
	}
?>