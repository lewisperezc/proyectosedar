<?php
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/factura.class.php');
$factura=new factura();
echo $factura->cant_facDigita($_POST['tercer'],$_POST['documento']);
?>