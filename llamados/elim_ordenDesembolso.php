<?php
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/transacciones.class.php');
$transacciones = new transacciones();
$elim=$transacciones->elim_ordenDesembolso($_POST['orden']);
echo $elim;
?>