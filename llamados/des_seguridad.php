<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);
$nit=$_POST['nit'];
$descu=$_POST['descu'];
$novedad=$_POST['novedad'];
$fact=$_POST['fac'];
$recibo=$_POST['recibo'];
$mes = $_POST['mes'];
$ano = $_POST['ano'];
$factu = $_POST['factu'];
$sql="SELECT * FROM rep_jor_con_recibo where rec_caj_consecutivo=$recibo";
$query=mssql_query($sql);
if(mssql_num_rows($query)>0)
{
	$sql = "SELECT DISTINCT mov_valor FROM movimientos_contables WHERE mov_compro='CAU-NOM-$fact' AND mov_nit_tercero = '".$nit."_380'";
	//echo $sql;
	$query = mssql_query($sql);
	if($query)
	 {
		$dat_facturado = mssql_fetch_array($query);
		$val=$dat_facturado['mov_valor']*100;
		if($val!=$novedad&&$val>0)
		   echo round(($descu*$novedad)/$val);
		else
		   echo round($descu);
	 }
	else
  		echo 0;
}
else
{
		$pago_mes = "SELECT SUM(mov_valor) valor FROM movimientos_contables
		WHERE mov_nit_tercero LIKE ('$nit') AND mov_compro LIKE ('CAU-SEG_%') AND mov_mes_contable = $mes
		AND mov_cuent=13250594 AND mov_ano_contable=$ano";
		
		$total_fac_mes = "SELECT SUM(rep_jor_num_jornadas) facturado FROM dbo.reporte_jornadas
		WHERE rep_jor_num_factura IN (SELECT fac_id FROM factura WHERE fac_cen_cos
		IN(SELECT cen_cos_id from dbo.nits_por_cen_costo WHERE nit_id=$nit) AND fac_mes_servicio=$mes
		AND fac_ano_servicio=$ano AND fac_estado!=5) AND id_nit_por_cen
		IN (select id_nit_por_cen from dbo.nits_por_cen_costo where nit_id = $nit)";
		$query_pago = mssql_query($pago_mes);
		$query_tot_fac_mes = mssql_query($total_fac_mes);
		if($query_pago)
		{
			$dat_pago = mssql_fetch_array($query_pago);
			$dat_fac_mes = mssql_fetch_array($query_tot_fac_mes);
			if($dat_fac_mes['facturado']==0)
			   echo 0;
			else
			   echo round($factu*($dat_pago['valor']/$dat_fac_mes['facturado']));
		}

}
?>