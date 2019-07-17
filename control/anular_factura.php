<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/factura.class.php');
$factura = new factura();
$_SESSION["consecu"];
$num_factura = $_POST['num_fac'];
?>
<?php
$anular = $factura->anularFactura($_SESSION["consecu"],$num_factura);

if($anular==0)
{
	echo "<script>alert('El mes actual se encuentra cerrado, para anular la factura es necesario abrir el mes.')</script>";
	echo "<script>document.location.href='../index.php?c=52';</script>";
}
else
	echo "<script>alert('Factura anulada correctamente.');document.location.href='../index.php?c=52';</script>"
?>