<?php session_start();
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/cuenta.class.php');
$cuenta = new cuenta();
$mes = $_POST['mes']+1;
$ano = $_SESSION['elaniocontable'];
$columna = "a".$ano."a".$mes;
$existe = $cuenta->exis_columna($columna,'tipo_comprobante');
echo 1;
?>