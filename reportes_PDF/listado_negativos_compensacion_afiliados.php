<?php
session_start();

include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/varios.class.php');

$ins_nit = new nits();
$ins_varios=new varios();
$html='';


$recibe_nits_array=$ins_varios->recibe_array_url($_GET['nits']);
$recibe_valores_array=$ins_varios->recibe_array_url($_GET['valores']);

$html.='<table border="1">';

$html.='<tr><th colspan="2"><img src="../imagenes/logo_sedar_dentro.png" width="700" height="190" alt="Logo Sedar" /></th>';
$html.='<tr>';
$html.='<th colspan="2">LISTADO DE AFILIADOS CON VALORES NEGATIVOS</th>';
$html.='</tr>';
$html.='<tr>';
$html.='<th>AFILIADO</th>';
$html.='<th>VALOR A PAGAR</th>';
$html.='</tr>';

$contador=0;
while($contador<$_GET['cantidad'])
{
	$con_nombres=$ins_nit->consultar($recibe_nits_array[$contador]);
	$res_nombres=mssql_fetch_array($con_nombres);
	$html.='<tr>';
	$html.='<td>'.$res_nombres['nits_num_documento'].' - '.$res_nombres['nits_nombres'].' '.$res_nombres['nits_apellidos'].'</td>';
	$html.='<td>'.number_format($recibe_valores_array[$contador]).'</td>';
	$html.='</tr>';
	$contador++;
}
$html.='</table>';
//$_SESSION['informacion_retiro']=$html;
//echo $html;

require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR
//echo '<script>location.href="../reportes_PDF/estado_cuenta_fondo_retiro_sindical.php";</script>';
?>
