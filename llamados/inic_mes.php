<?php 
session_start();
include_once('../clases/comprobante.class.php');
$comprobante= new comprobante();
$mes=$_POST['mes'];
$tipo=$_POST['tipo'];
$conce = $comprobante->cons_comprobante($_SESSION['elaniocontable'],$mes,$tipo);
$sig = $comprobante->sig_comprobante($tipo);
echo $sig.$conce;
?>