<?php session_start();
include_once('../conexion/conexion.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/recibo_caja.class.php');
include_once('../clases/transacciones.class.php');

$ano = $_SESSION['elaniocontable'];
$centro = new centro_de_costos();
$factura = new factura();
$rec_caja = new rec_caja();
$inst_transaccion = new transacciones();
$estado=0;

$con_dat_recibos=$rec_caja->buscar_recibos($_POST['val']);
$num_filas=mssql_num_rows($con_dat_recibos);

if($num_filas==0)
{
	$valor = $_POST['total'];
	$cons_conse = $rec_caja->obt_consecutivo(15);

	$sigla = "REC-CAJ_".$cons_conse;
	$gua_recibo = $rec_caja->guardar_recibos_provisional($_POST['val'],date('d-m-Y'),$valor,'PROVISIONAL',$cons_conse);
	$ult_rec_caja = $rec_caja->sel_max_rec_caja();
	$resultado = mssql_fetch_array($ult_rec_caja);

	$obt_num_transaccion = $inst_transaccion->num_tran($_POST['fac_conse']);
	$nueTran = $inst_transaccion->guaPagTransaccion(strtoupper($sigla),date('d-m-Y'),$_POST['nit'],$_POST['centro'],$valor,0,date('d-m-Y'),$resultado['rec_caj_id'],$_SESSION['k_nit_id'],date('d-m-Y'),102,$obt_num_transaccion,1,$ano);

	$act_rec_caja = $rec_caja->act_consecutivo(15);
	if($gua_recibo)
		$rec_caja->act_recProvisional($cons_conse,0);


	$sql = "UPDATE factura SET fac_estado=3 WHERE fac_id=".$_POST['val'];
	$query = mssql_query($sql);
	
	$estado=1;
	echo $estado;
}
else
{
	$estado=2;
	echo $estado;
}
?>