<?php session_start();
include_once('../conexion/conexion.php');
include_once('../clases/moviminetos_contables.class.php');
$movimiento = new movimientos_contables();
$ano = $_SESSION['elaniocontable'];
$tipo=$_POST['tipo'];
$realizar_cierre=$movimiento->cierre_ano($tipo,$ano);

if($realizar_cierre)
	echo 1;
else
	echo 0;

/*if($movimiento->cierre_ano($tipo,$ano))
	echo 1;
else
	echo 0;
*/



?>