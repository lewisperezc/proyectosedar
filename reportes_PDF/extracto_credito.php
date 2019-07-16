<?php 
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/factura.class.php');

$ins_credito=new credito();
$ins_nits=new nits();
$ins_mes_contable=new mes_contable();
$ins_factura=new factura();



$documento=$_POST["nit_num_documento"];
$cre_id=$_POST['nit_credito'];

$res_enc_ext_credito=$ins_credito->ConsultarEncabezadoExtractoDeCredito($documento,$cre_id);

$con_ext_credito=$ins_credito->ConsultarExtractoDeCredito($cre_id,$res_enc_ext_credito['nit_id']);

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
	$html.='<th>VALOR CUOTA</th>';
	$html.='<td>'.number_format($res_enc_ext_credito['valor_cuota']).'</td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th colspan="6"><hr></th>';
	$html.='</tr>';

	$html.='</table>';
	
	$html.='<table style="font-size:12px;width:80%">';
	$html.='<tr>';
	$html.='<th style="text-align:right;">FECHA</th>';
	$html.='<th style="text-align:right;">FACTURA</th>';
	$html.='<th style="text-align:right;">DOCUMENTO</th>';
	$html.='<th style="text-align:right;">CAPITAL</th>';
	$html.='<th style="text-align:right;">INTERES</th>';
	$html.='<th style="text-align:right;">TOTAL</th>';
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
		$html.='<td style="text-align:right;">'.$res_ext_credito['fec_sin_formato'].'</td>';
		if (strpos($res_ext_credito['des_cre_pagCompensacion'], 'PAG-COM-') !== false)
		{
			$con_dat_fac_por_id=$ins_factura->ConDatFacPorId($res_ext_credito['des_cre_factura']);
			$res_dat_fac_por_id=mssql_fetch_array($con_dat_fac_por_id);
    		$html.='<td style="text-align:right;">'.$res_dat_fac_por_id['fac_consecutivo'].'</td>';
		}
		else
		{
			$html.='<td style="text-align:right;">N/A</td>';
		}
		
		
		
		$html.='<td style="text-align:right;">'.$res_ext_credito['des_cre_pagCompensacion'].'</td>';
		$html.='<td style="text-align:right;">'.number_format($res_ext_credito['des_cre_capital']).'</td>';
		$html.='<td style="text-align:right;">'.number_format($res_ext_credito['des_cre_interes']).'</td>';
		$html.='<td style="text-align:right;">'.number_format($res_ext_credito['des_cre_total']).'</td>';
		if($i==0)
		{
			$saldo_1=$res_enc_ext_credito['cre_valor']-$res_ext_credito['des_cre_capital'];
			$html.='<td style="text-align:right;">'.number_format($saldo_1).'</td>';			
		}
		else
		{
			$saldo_1=$saldo_1-$res_ext_credito['des_cre_capital'];
			$html.='<td style="text-align:right;">'.number_format($saldo_1).'</td>';	
		}
		$tot_capital+=$res_ext_credito['des_cre_capital'];
		$tot_interes+=$res_ext_credito['des_cre_interes'];
		$tot_total+=$res_ext_credito['des_cre_total'];
		$html.='</tr>';
		$i++;
	}
	$html.='<tr>';
	$html.='<th colspan="6"><hr></th>';
	$html.='</tr>';
	
	$html.='<tr>';
	$html.='<th colspan="2"></th>';
	$html.='<th style="text-align:right;">'.number_format($tot_capital).'</th>';
	$html.='<th style="text-align:right;">'.number_format($tot_interes).'</th>';
	$html.='<th style="text-align:right;">'.number_format($tot_total).'</th>';
	$html.='</tr>';

	$html.='</table>';
$html.='</center>';

require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR

?>