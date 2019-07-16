<?php
include_once('../clases/recibo_caja.class.php');
$ins_rec_caja = new rec_caja();
$elvalor=$_POST['elvalor'];
$criterio=$_POST['criterio'];
if($criterio==1)
	$trae_los_recibos=$ins_rec_caja->ConRecCajPorIdCentro($elvalor);
elseif($criterio==2)
	$trae_los_recibos=$ins_rec_caja->buscar_recibos($elvalor);
$numero_filas=mssql_num_rows($trae_los_recibos);
$res="";
echo $numero_filas;
if($numero_filas>0)
{
    while($ResDatRecibo=mssql_fetch_array($trae_los_recibos))
		$res.="<option value='".$ResDatRecibo['rec_caj_id']."' label='".$ResDatRecibo['rec_caj_consecutivo']."'>";
}
echo $res;
?>