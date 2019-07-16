<?php
session_start();
unset($html);
require_once("../librerias/dompdf/dompdf_config.inc.php");
include_once('../clases/nits.class.php');
$ins_nits=new nits();
$fec_inicial=$_POST['fec_inicio'];
$fec_final=$_POST['fec_final'];
$con_solicitudes=$ins_nits->SolicitudesRetiroSindical($fec_inicial,$fec_final);
//echo "la fecha es: ".$fec_inicial;
$html='<center>';
$html.='<table border="1">';
$html.='<tr>';
$html.='<th colspan="7"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar" /></th>';
$html.='</tr>';
$html.='<tr>';
$html.='<th colspan="7">SOLICITUDES FONDO DE RETIRO SINDICAL DESDE '.$fec_inicial.' HASTA '.$fec_final.'</th>';
$html.='</tr>';
$html.='<tr>';
$html.='<th>FECHA SOLICITUD</th>';
$html.='<th>HORA SOLICITUD</th>';
$html.='<th>DOCUMENTO</th>';
$html.='<th>NOMBRES</th>';
$html.='<th>RETIRO PARCIAL</th>';
$html.='<th>VALOR TOTAL FONDO DE RETIRO</th>';
$html.='<th>VALOR SOLICITADO</th>';
$html.='</tr>';

$sum_val_solicitado=0;
while($res_solicitudes=mssql_fetch_array($con_solicitudes))
{
	$html.='<tr>';
	$html.='<td>'.$res_solicitudes['reg_sol_ret_sin_fecha'].'</td>';
	$html.='<td>'.$res_solicitudes['reg_sol_ret_sin_hora'].'</td>';
	$html.='<td style="text-align:right">'.$res_solicitudes['nits_num_documento'].'</td>';
	$html.='<td>'.$res_solicitudes['nits_nombres']." ".$res_solicitudes['nits_apellidos'].'</td>';
	$html.='<td>'.$res_solicitudes['reg_sol_ret_sin_sol_parcial'].'</td>';
	$html.='<td style="text-align:right">'.number_format($res_solicitudes['reg_sol_ret_sin_val_disponible']).'</td>';
	$html.='<td style="text-align:right">'.number_format($res_solicitudes['reg_sol_ret_sin_val_solicitado']).'</td>';
	$sum_tot_val_solicitado+=$res_solicitudes['reg_sol_ret_sin_val_solicitado'];
	$html.='</tr>';
}

$html.='<tr>';
$html.='<th style="text-align:right;" colspan="6">TOTAL SOLICITADO: </th>';
$html.='<th style="text-align:right;">'.number_format($sum_tot_val_solicitado).'</th>';
$html.='</tr>';

$hora=localtime(time(),true); 

$html.='<tr>';
$html.='<th style="text-align:right;font-size:8;" colspan="7">Reporte generado por Sedasoft: '.date('d-m-Y')." a las ".$hora[tm_hour].":".$hora[tm_min].":".$hora[tm_sec].'</th>';
$html.='</tr>';
$html.='</table>';
$html.='</center>';

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper ('a4','landscape'); 
$dompdf->render();
$dompdf->stream("formulario.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR
?>