<?php
include_once('../conexion/conexion.php');
include_once('../clases/credito.class.php');
$credito = new credito();
$dat_credito=split('---',$_POST['credito']);
$dias=$credito->ult_pago($dat_credito[0]);
echo $credito->saldo_credito($dat_credito[0])."-".$dias;
?>