<?php session_start();
require_once("../librerias/dompdf/dompdf_config.inc.php");
include_once('../clases/credito.class.php');

$dompdf= new DOMPDF();

$ins_credito=new credito();
$consulta_datos=$ins_credito->BalanceDetalladoCreditoPorNit($_POST['nit_inicio'],$_POST['nit_fin']);

$html="";   

$html.='<center>';

$html.='<table style="font-size:12px;">';
$html.='<tr>';
$html.='<th colspan="2"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar" /></th>';
$html.='</tr>';
	
$html.='<tr>';
$html.='<th colspan="">BALANCE DETALLADO DE CREDITOS</th>';
$html.='<th colspan="">FECHA: '.date('d-m-Y').'</th>';
$html.='</tr>';

$html.='</table>';

$html.='<table style="font-size:12px;">';
$html.='<tr>';
$html.='<th>CEDULA</th>';
$html.='<th>APELLIDOS</th>';
$html.='<th>NOMBRES</th>';
$html.='<th>TIPO DE PRESTAMO</th>';
$html.='<th>PRESTAMO</th>';
$html.='<th>VALOR CREDITO</th>';
$html.='<th>VALOR DESCONTADO</th>';
$html.='<th>DIFERENCIA</th>';
$html.='</tr>';

while($res_creditos=mssql_fetch_array($consulta_datos))
{
	if($res_creditos['capital']>$res_creditos['cre_valor'])
	{
		$html.='<tr>';
		$html.='<td>'.$res_creditos['nits_num_documento'].'</td>';
		$html.='<td>'.$res_creditos['nits_apellidos'].'</td>';
		$html.='<td>'.$res_creditos['nits_nombres'].'</td>';
		$html.='<td>'.$res_creditos['con_nombre'].'</td>';
		$html.='<td style="text-align:center">'.$res_creditos['cre_id'].'</td>';
		$html.='<td style="text-align:right">'.number_format($res_creditos['cre_valor']).'</td>';
		$html.='<td style="text-align:right">'.number_format($res_creditos['capital']).'</td>';
		$resta=$res_creditos['cre_valor']-$res_creditos['capital'];
		$html.='<td style="text-align:right">'.number_format($resta).'</td>';
		$html.='</tr>';
	}
}

$html.='</table>';
$html.='</center>';

$dompdf->set_paper("A4","portrait");  //tiene que ser horizontal y lo deja en vertical (landscape)
$dompdf->load_html($html);  
$dompdf->render(); 
$dompdf->stream("Reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR*/

/*
$dompdf = new DOMPDF();
$dompdf->load_html($_SESSION['informacion']);
$dompdf->render();
$dompdf->set_paper("legal","landscape");  //tiene que ser horizontal y lo deja en vertical 
$dompdf->stream("Reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR*/
?>