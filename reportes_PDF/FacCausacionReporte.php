<?php
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/factura.class.php');
include_once('../clases/reporte_jornadas.class.php');
require_once("../librerias/dompdf/dompdf_config.inc.php");
$ano = $_POST["ano"];
$mes = $_POST["mes"];

$ins_factura=new factura();
$ins_reporte=new reporte_jornadas();
$sqlFactura = $ins_factura->FacMes(5,2015);

$html='<center>';
while($queryFactura=mssql_fetch_array($sqlFactura))
{
	$html.="<table border='1'>";
	$html.="<tr><th>Factura:</th><td colspan='4'>".$queryFactura['fac_consecutivo']."</td></tr>";
	
	$html.="<tr><th>Causacion: </th><td>".$ins_factura->busCausacion($queryFactura['fac_id'])."</td><th>Valor: </th><td>".$queryFactura['fac_val_total']."</td></tr></table><br><br>";
	$html.="<table border='1'>";
	$html.="<tr><th colspan='4'>Reporte de Jornadas </th></tr>";
	$html.="<tr><th>Cedula</th><th>Nombres</th><th>Apellidos</th><th>Valor de jornadas</th></tr>";

	$sqlJornadas=$ins_reporte->buscarReporteJornadas_Factura($queryFactura['fac_id']);

	while($queryJornadas=mssql_fetch_array($sqlJornadas))
	{
		$html.="<tr><td>".$queryJornadas['nits_num_documento']."</td><td>".$queryJornadas['nits_nombres']."</td><td>".$queryJornadas['nits_apellidos']."</td><td>".number_format($queryJornadas['rep_jor_num_jornadas'])."</td></tr>";		
	}

	$html.="<table>";
}
$html.='</center>';
echo $html;

/*$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("formulario.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR*/
?>