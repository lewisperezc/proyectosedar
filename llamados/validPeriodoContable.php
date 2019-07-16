<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
error_reporting(E_ALL);
$mes = new mes_contable();
$valida=$mes->validarPeriodo($_POST['mes'],$_POST['ano']);
echo $valida;
?>