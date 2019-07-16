<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../clases/reporte.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$ins_reporte = new reporte();
$res_tod_cen_costo=$ins_reporte->cons_centro_costos();

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/tabla_de_centros_de_costos.jpg",0,0,280,'C');
//Arial bold 15 
$this->SetFont('Arial','B',15); 
$this->SetFont('Arial','B',15);
//Movernos a la derecha 
//$this->Cell(10); 
$this->Ln(10); 
}

//Pie de página 
function Footer() 
{ 
//Posición: a 1,5 cm del final 
$this->SetY(-15); 
//Arial italic 8 
$this->SetFont('Arial','I',7); 
//Número de página 
$this->Cell(0,10,'Page '.$this->PageNo().'     Fecha de impresión '.date('d-m-Y'),0,0,'C'); 
} 

}// fin de la clase 


$pdf=new PDF(); 
//Tipo y tamaño de lertra 
$pdf->AddPage('l','letter');
$pdf->SetFont('Arial','',7);
$i=0;
while($row = mssql_fetch_array($res_tod_cen_costo))
{
	if($i!=0)
	  $j=$i*3;
	else
	  $j=$i;
	$pdf->SetY(100+$j);
	$pdf->SetX(13);
	$pdf->Cell(15+$j,8,$row['cen_cos_id']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(70);
	$pdf->Cell(15+$j,8,$row['cen_cos_nombre']);
	
	$i++;
	if($j>=70)
	{
		$i=0;
		$pdf->AddPage('l','letter');
		$pdf->SetFont('Arial','',7);
	}
}
$pdf->Output();
?>