<?php
@include_once('clases/moviminetos_contables.class.php');
@include_once('moviminetos_contables.class.php');

@include_once('clases/factura.class.php');
@include_once('factura.class.php');

/*
$ins_mov_contable=new movimientos_contables();
$con_ret=$ins_mov_contable->cal_ret_fuente(5332275);
echo $con_ret."<br><br><br>";

 * */
$nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$ip_host=$_SERVER["REMOTE_ADDR"];

//    _SERVER["REMOTE_ADDR"] o $REMOTE_ADDR


//echo $nombre_host."___".$ip_host."___".php_uname()."___".$REMOTE_ADDR;

/*
$a=1;
$mes_actual=date('m');
$ano_actual=date('Y');
$bas_retencion=2000000;
$anu_fac ="EXECUTE insMovimiento 'ANU-".$a."','".$a."','".$a."','".$a."', '".$a."','".$a."','".$a."','2','".$a."','3','0','$a','la fecha','$mes_actual',".$ano_actual.",'$bas_retencion'";
echo $anu_fac;
*/
/*
$factura=4321;$mes=10;$ano=2018;$recibo_id=3976;$sig_causacion='CAU-NOM-4321';$sig_pago='PAG-COM-4880';
$con_dat=$ins_mov_contable->ajuste_causacion($factura, $mes, $ano, $recibo_id, $sig_causacion, $sig_pago,0,'30-10-2018','1169');
*/


$ins_factura=new factura();
$con_dat_factura=$ins_factura->TotalSaldoCreditosPorNit(1019);
echo "datos: ".$con_dat_factura."<br><br><br>";

?>