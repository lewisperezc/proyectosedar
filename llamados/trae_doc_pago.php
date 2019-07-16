<?php
include_once('../clases/transacciones.class.php');
$transac = new transacciones();
$sigla = $_POST['sigla'];
$dat_facturas = $transac->doc_pago($sigla);
$i=0;
while($row = mssql_fetch_array($dat_facturas))
{
	$res[$i]["tran"] = $row['trans_id'];
	$res[$i]["sigla"] = $row['trans_sigla'];
	$res[$i]["fec_docu"] = $row['trans_fec_doc'];
	$res[$i]["fec_vencimiento"] = $row['trans_fec_vencimiento'];
	$res[$i]["num_factura"] = $row['trans_fac_num'];
	$res[$i]["causacion"] = $row['tran_tran_id'];
	$res[$i]["nit"] = $row['trans_nit'];
	$dat_cuenta = $transac->cuenta_pagar($row['tran_tran_id'],$row['trans_nit']);
	$datos_cuenta = mssql_fetch_array($dat_cuenta);
	$res[$i]["cue_pagar"] = $datos_cuenta['mov_cuent'];
	$res[$i]["valor"] = $row['trans_val_total'];
	$res[$i]["centro"] = $row['trans_centro'];
	$i++;
}
echo json_encode($res);
?>