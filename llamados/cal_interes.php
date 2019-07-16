<?php
session_start();
include_once('../conexion/conexion.php');
@include_once('../clases/credito.class.php');
@include_once('clases/credito.class.php');
@include_once('../librerias/php/funciones.php');
@include_once('librerias/php/funciones.php');
$ano = $_SESSION['elaniocontable'];
error_reporting(E_ALL);
$credito = new credito();

$posicion = $_POST['pos'];
$cre_numero=$_POST['credito'];
$cap = $_POST['capital'];

if(!empty($_POST['fecha']))
{
	$fecha = str_replace('-', '/', $_POST['fecha']);
	$dias_fecha = restaFechas($fecha,date('d/m/Y'));
	$num_dias= $credito->ult_pago($cre_numero)-$dias_fecha;
}
else
{
    $num_dias= $credito->ult_pago($cre_numero);
}	
$capital = $credito->saldo_credito($cre_numero);

if($num_dias<0)
	$num_dias=0;


$gen_credito = $credito->dat_creditos($cre_numero);
$datos_credito = mssql_fetch_array($gen_credito);
$cre_taza_nom = $datos_credito['cre_dtf'];

$interes_diario = (($cre_taza_nom/360)/100);
$interes = ($interes_diario*$num_dias)*$capital;

if(!empty($_POST['fecha']))
	echo $num_dias."-".round($interes,0);
else
	echo round($interes,0);
?>