<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/factura.class.php');
include_once('../clases/reporte_jornadas.class.php');
require_once("../librerias/dompdf/dompdf_config.inc.php");
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$ano = $_POST["ano"];
$mes = $_POST["mes"];

$ins_factura=new factura();
$ins_reporte=new reporte_jornadas();
$sqlFactura = $ins_factura->FacMes($mes,$ano);

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tama�o,ubicacion)
$this->Image("../imagenes/reportes/factura_causacion.jpg",0,0,210,'C');
//Arial bold 15 
$this->SetFont('Arial','B',15); 
$this->SetFont('Arial','B',15);
//Movernos a la derecha 
//$this->Cell(10); 
$this->Ln(10); 
}

//Pie de p�gina 
function Footer() 
{ 
//Posici�n: a 1,5 cm del final 
$this->SetY(-15); 
//Arial italic 8 
$this->SetFont('Arial','I',7); 
//N�mero de p�gina 
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresion'.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 

$pdf=new PDF(); 
//Tipo y tama�o de lertra 
$pdf->AddPage();
$pdf->SetFont('Arial','',20);
$i=0;

while($queryFactura=mssql_fetch_array($sqlFactura))
{
	$pdf->SetFont('Arial','',20);
	$pdf->SetY(60+$i);$pdf->SetX(93);$pdf->Cell(15+$i,8,$queryFactura['fac_consecutivo']);$i++;
	$pdf->SetFont('Arial','',12);
	$pdf->SetY(77+$i);$pdf->SetX(70);$pdf->Cell(15+$i,8,$ins_factura->busCausacion($queryFactura['fac_id']));$i++;
	$pdf->SetY(77+$i);$pdf->SetX(153);$pdf->Cell(15+$i,8,number_format($queryFactura['fac_val_total']));$i++;

	$sqlJornadas=$ins_reporte->buscarReporteJornadas_Factura($queryFactura['fac_id']);

	while($queryJornadas=mssql_fetch_array($sqlJornadas))
	{
		if($queryJornadas['rep_jor_num_jornadas']>0)
		{
			$pdf->SetFont('Arial','',10);
			$pdf->SetY(97+$i);$pdf->SetX(11);$pdf->Cell(15+$i,8,$queryJornadas['nits_num_documento']);
			$pdf->SetY(97+$i);$pdf->SetX(55);$pdf->Cell(15+$i,8,$queryJornadas['nits_nombres']);
			$pdf->SetY(97+$i);$pdf->SetX(105);$pdf->Cell(15+$i,8,$queryJornadas['nits_apellidos']);
			$pdf->SetY(97+$i);$pdf->SetX(173);$pdf->Cell(15+$i,8,number_format($queryJornadas['rep_jor_num_jornadas']));
			$i+=3;
		}
	}
	$i=0;
	$pdf->AddPage();
}
$pdf->Output();
?>