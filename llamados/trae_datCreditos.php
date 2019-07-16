<?php 
@include_once('clases/credito.class.php');
@include_once('../clases/credito.class.php'); 
$ins_credito=new credito();
 
$con_ciu_por_dep=$ins_credito->con_dat_credito($_POST['cre_id']);
$linea_credito=mssql_fetch_array($con_ciu_por_dep);

$con_sal_credito=$ins_credito->ConsultarSaldoCreditoRecaudo($_POST['cre_id']);

error_reporting(E_ALL);
 
$html="";


$html.=$linea_credito['con_nombre']."#".number_format($con_sal_credito);
echo $html;
?>