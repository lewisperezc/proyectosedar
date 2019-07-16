<?php session_start();
include_once('../clases/transacciones.class.php');
$transac = new transacciones();
$proveedor = $_POST['prove'];
$mes = $_POST['mes'];
$ano = $_SESSION['elaniocontable'];
//echo "los datos son: ".$_SESSION['k_nit_id'];
$dat_facturas = $transac->tran_proveedores($proveedor,$ano,$mes,$_SESSION['k_nit_id']);
$con_dat_consulta = $transac->tran_proveedores2();
$i=0;

while($row=mssql_fetch_array($con_dat_consulta))
{
	$res[$i]["tran"] = $row['trans_id'];
	$res[$i]["sigla"] = $row['trans_sigla'];
	$res[$i]["fec_docu"] = $row['trans_fec_doc'];
	$res[$i]["fec_vencimiento"] = $row['trans_fec_vencimiento'];
	$res[$i]["num_factura"] = $row['trans_fac_num'];
	$res[$i]["mes_nombre"] = $row['mes_nombre'];
	$res[$i]["val_factura"] = $row['mov_valor'];
	$res[$i]["nombre"] = $row['nits_nombres'];
	$res[$i]["cuenta"] = $row['mov_cuent'];
	$res[$i]["centro"] = $row['mov_cent_costo'];
	$i++;
}
echo json_encode($res);
?>