<?php
session_start();

require_once("../librerias/dompdf/dompdf_config.inc.php");
include_once('../clases/saldos_cuentas.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/mes_contable.class.php');

$dompdf = new DOMPDF();
$ins_cuentas=new cuenta();
$ins_sal_cuentas= new insercion();
$ins_mes = new mes_contable();
$html = "<table cellpadding='0' cellspacing='0' border='2'><tr><td><b>Mes</b></td><td><b>Debito</b></td><td><b>Credito</b></td><td><b>Saldo</b></tr>";

$eltipo=$_POST['tipo'];
$elvalor=$_POST['cuenta'];
$anio=$_SESSION['elaniocontable'];
$meses = $ins_mes->mes();
$i=1;
 while($row=mssql_fetch_array($meses))
	{
		$html.="<tr><td>".$row['mes_nombre']."</td>";
		echo $_POST['debito1'];
		$html.="<td>".$_POST['debito'.$i]."</td>";
		$html.="<td>".$_POST['credito'.$i]."</td>";
		$html.="<td>".$_POST['saldo'.$i]."</td></tr>";
		$i++;
	}

$html.= "</table>";



$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("formulario.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR
?>