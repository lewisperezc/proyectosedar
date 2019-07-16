<?php
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/credito.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/comprobante.class.php');
$credito = new credito();
$factura=new factura();
$comprobante= new comprobante();

if($_POST['tipo']==1)//PROVISION DE CREDITOS
{
	$conce = $comprobante->cons_comprobante($_SESSION['elaniocontable'],$_POST['mes'],53);
	$sig = $comprobante->sig_comprobante(53);
	$comprobante->act_comprobante($_SESSION['elaniocontable'],$_POST['mes'],53);
	$sigla = $sig.$conce;
	$prov_cre = $credito->provCreditos($sigla,$conce,$_POST['mes'],$_SESSION['elaniocontable']);
	echo $sigla;
}
elseif($_POST['tipo']==2)//PROVISION DE CARTERA
{
	$conce = $comprobante->cons_comprobante($_SESSION['elaniocontable'],$_POST['mes'],43);
	$sig = $comprobante->sig_comprobante(43);
	$comprobante->act_comprobante($_SESSION['elaniocontable'],$_POST['mes'],43);
	$sigla = $sig.$conce;
	$prov_cre = $factura->proServicios($sigla,$conce,$_POST['mes'],$_SESSION['elaniocontable']);
	echo $sigla;
}

?>