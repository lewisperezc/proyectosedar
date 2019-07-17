<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/recibo_caja.class.php');
include_once('../clases/transacciones.class.php');
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript">
 function abreFactura(URL)
 {
    day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
 }
</script>
<?php
$mov_contable = new movimientos_contables();
$cen_costo = new centro_de_costos();
$rec_caja = new rec_caja();
$transaccion = new transacciones();
$conse_pagSeg = $rec_caja->obt_consecutivo(34); 
$act_pagSeg = $rec_caja->act_consecutivo(34);
$sigla = "DIFER-".$conse_pagSeg;$i=0;
$cant_cont = $_POST['cant_contratos'];
$cant_cau = $_POST['cant_causaci'];
$fecha = date('d-m-Y');
$mes = date('m');
$ano = date('Y');
$doc_temp="";

for($i=0;$i<$cant_cont;$i++)
{
	$mov=$_POST['mov_id'.$i];
	$comprobante=$_POST['compro'.$i];
	$cue_dife=$_POST['cue_dife'.$i];
	$cue_gasto=$_POST['cue_gasto'.$i]; 
	$val_diferido=$_POST['val_dife'.$i];
	$val_diferir=$_POST['val_diferir'.$i];
	$coutas=$_POST['cant'.$i];
	$fec_ini=$_POST['fec_ini'.$i];
	$fec_fin=$_POST['fec_fin'.$i];
	$centro=$_POST['centro'.$i];
	$tercero=$_POST['tercero'.$i];
	$ins_movimiento=$mov_contable->guar_diferidos($comprobante,$cue_dife,$cue_gasto,$val_diferido,$val_diferir,$coutas,$fec_ini,$fec_fin,$mov,$centro,$tercero);
}

for($i=0;$i<$cant_cau;$i++)
{
	$mov=$_POST['mov_cau'.$i];
	$comprobante=$_POST['compro_cau'.$i];
	$cue_dife=$_POST['cue_dife_cau'.$i];
	$cue_gasto=$_POST['cue_gastoCau'.$i]; 
	$val_diferido=$_POST['valor_cau'.$i];
	$val_diferir=$_POST['val_dif_cau'.$i];
	$coutas=$_POST['meses_cau'.$i];
	$fec_ini=$_POST['fec_ini_cau'.$i];
	$fec_fin=$_POST['fec_fin_cau'.$i];
	$centro=$_POST['centro_cau'.$i];
	$tercero=$_POST['tercero_cau'.$i];
	$ins_movimiento=$mov_contable->guar_diferidos($comprobante,$cue_dife,$cue_gasto,$val_diferido,$val_diferir,$coutas,$fec_ini,$fec_fin,$mov,$centro,$tercero,$mes,$ano);
}
$diferir = $mov_contable->eje_diferidos($mes,$ano,$sigla,$conse_pagSeg);

echo "<script language='javascript'>abreFactura('../reportes_PDF/causacion_pago.php?sigla=".strtoupper($sigla)."');
      history.back(-1);</script>";
?>