<?php
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../conexion/conexion.php');
include_once('../clases/reporte.class.php');
include_once('../clases/varios.class.php');
$reporte = new reporte();
$varios = new varios();
$contratos=$reporte->contratos(1);
$_SESSION['datos_contratos_vencidos']="";
$html="";
$html.='<center>';
$html.='<table style="font-size:10px;text-align:center;">';
$html.='<tr>';
$html.='<th colspan="6"><img src="../imagenes/logo_sedar_dentro.png" width="744" height="210" alt="Logo Sedar" /></th>';
$html.='</tr>';
	
$html.='<tr>';
$html.='<th colspan="6">ESTADO CONTRATOS</th>';
$html.='</tr>';

$html.='<tr>';
$html.='<th>NUMERO	 CONTRATO</th>';
$html.='<th>TIPO</th>';
$html.='<th>HOSPITAL/CLINICA</th>';
$html.='<th>FEHCA INICIO</th>';
$html.='<th>FECHA FIN</th>';
$html.='<th>DIAS PARA VENCER</th>';
$html.='</tr>';

$html.='<tr><td>&nbsp;</td></tr>';

while($row=mssql_fetch_array($contratos))
{
	if($row['adi_otr_fec_fin']!="" && $row['tip_adi_nombre']!="")
		$resta = $row['adi_otr_fec_fin'];
	else
		$resta = $row['con_fec_fin'];
		
	$diferencia=$varios->restaFechas(date('d-m-Y'),$resta);
	if($diferencia<=30)
	{
		$temp=1;
		$html.='<tr>';
		$html.='<td>'.$row['con_id'].'</td>';
		if($row['adi_otr_fec_fin']!="")
			$html.='<td>ADICION/OTROSI - '.$row['tip_adi_nombre'].'</td>';
		else
			$html.='<td>CONTRATO</td>';
		$html.='<td>'.$row['nits_nombres'].'</td>';
		$html.='<td>'.$row['con_fec_inicio'].'</td>';
		$html.='<td>'.$resta.'</td>';
		$html.='<td>'.$diferencia.'</td>';
		$html.='</tr>';
	}
}
$html.='</table>';
$html.='</center>';

if($temp==0)
{
	echo '<script>alert(No hay contratos por vencer en menos de 30 dias.);</script>';
}

$_SESSION['datos_contratos_vencidos']=$html;
//echo $_SESSION['datos_contratos_vencidos'];
echo '<script>location.href="../reportes_PDF/contratos.php";</script>';
?>
