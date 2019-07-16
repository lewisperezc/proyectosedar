<?php
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/mes_contable.class.php');

$ins_credito=new credito();
$ins_nits=new nits();
$ins_mes_contable=new mes_contable();

$documento=$ins_nits->BuscarDocumentoPorId($_GET["per_id"]);
$cre_id=$_GET['cre_id'];

$res_enc_ext_credito=$ins_credito->ConsultarEncabezadoTablaAmortizacionCredito($documento,$cre_id);

$con_ext_credito=$ins_credito->con_dat_tab_amo_credito($cre_id);

$html="";

$html.='<center>';
$html.='<table border="0" style="font-size:12px;width:100%">';
	$html.='<tr><th colspan="6"><img src="../imagenes/logo_sedar_dentro.png" width="700" height="190" alt="Logo Sedar" /></th>';
	$html.='<tr><th colspan="2">NOMBRES: '.$res_enc_ext_credito["nits_num_documento"]." - ".$res_enc_ext_credito["nits_nombres"]." ".$res_enc_ext_credito["nits_apellidos"].'</th>';
	$html.='<th>FECHA: '.date('d-m-Y').'</th></tr>';
	$html.='<tr>';
	$html.='<th>PRESTAMO</th>';
	$html.='<td>'.$res_enc_ext_credito['cre_id'].'</td>';
	$html.='<th>TIPO</th>';
	$html.='<td>'.$res_enc_ext_credito['con_nombre'].'</td>';
	$html.='<th>VALOR CREDITO</th>';
	$html.='<td>'.number_format($res_enc_ext_credito['cre_valor']).'</td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th>TASA NOMINAL</th>';
	$html.='<td>'.$res_enc_ext_credito['cre_dtf'].'</td>';
	$html.='<th>TASA MENSUAL</th>';
	$html.='<td>'.round($res_enc_ext_credito['tasa_mensual'],2).'</td>';
	$html.='<th>PLAZO</th>';
	$html.='<td>'.$res_enc_ext_credito['cre_num_cuotas'].'</td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th colspan="6"><hr></th>';
	$html.='</tr>';

	$html.='</table>';
	
	$html.='<table style="font-size:12px;width:80%">';
	$html.='<tr>';
	$html.='<th style="text-align:right;">NRO.</th>';
	$html.='<th style="text-align:right;">FECHA</th>';
	$html.='<th style="text-align:right;">VALOR CUOTA</th>';
	$html.='<th style="text-align:right;">CAPITAL</th>';
	$html.='<th style="text-align:right;">INTERES</th>';
	$html.='<th style="text-align:right;">SALDO</th>';
	$html.='</tr>';
	$saldo_1=0;
	$tot_capital=0;
	$tot_interes=0;
	$tot_total=0;
	
	
	$i=0;
	while($res_ext_credito=mssql_fetch_array($con_ext_credito))
	{
		$html.='<tr>';
		$html.='<td style="text-align:right;">'.$res_ext_credito['tab_amo_num_cuota'].'</td>';
		$html.='<td style="text-align:right;">'.$res_ext_credito['tab_amo_fecha'].'</td>';
		$html.='<td style="text-align:right;">'.number_format($res_ext_credito['tab_amo_cuota']).'</td>';
		$html.='<td style="text-align:right;">'.number_format($res_ext_credito['tab_amo_cap_abonado']).'</td>';
		$html.='<td style="text-align:right;">'.number_format($res_ext_credito['tab_amo_intereses']).'</td>';
		$html.='<td style="text-align:right;">'.number_format($res_ext_credito['tab_amo_saldo']).'</td>';

		$tot_capital+=$res_ext_credito['tab_amo_cap_abonado'];
		$tot_interes+=$res_ext_credito['tab_amo_intereses'];
		$tot_total+=$res_ext_credito['tab_amo_cuota'];
		$html.='</tr>';
		$i++;
	}
	$html.='<tr>';
	$html.='<th colspan="6"><hr></th>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<th colspan="2">TOTALES:</th>';
	
	$html.='<th style="text-align:right;">'.number_format($tot_total).'</th>';
	$html.='<th style="text-align:right;">'.number_format($tot_capital).'</th>';
	$html.='<th style="text-align:right;">'.number_format($tot_interes).'</th>';
	$html.='</tr>';

	$html.='</table>';
$html.='</center>';

require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR
?>