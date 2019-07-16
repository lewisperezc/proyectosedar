<?php
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/credito.class.php');
$credito = new credito();

$con_des_credito=$credito->dat_descuento($_POST['credito']);
$num_filas=mssql_num_rows($con_des_credito);
if($num_filas>0)
	echo 2;
else
{
	$elim=$credito->borrarCredito($_POST['credito']);
	if($elim)
		echo 0;
	else
		echo 1;
}
?>