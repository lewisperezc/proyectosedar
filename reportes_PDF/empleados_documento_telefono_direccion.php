<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../clases/reporte.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$desde=$_POST['desde'];
$hasta=$_POST['hasta'];

$ins_reporte = new reporte();
$res_tod_empleados=$ins_reporte->con_doc_tel_dir_nit(2);

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/empleados_documento_telefono_direccion.jpg",0,0,280,'C');
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
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresión'.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 


$pdf=new PDF(); 
//Tipo y tamaño de lertra 
$pdf->AddPage('l','letter');
$pdf->SetFont('Arial','',7);
$i=0;
while($row = mssql_fetch_array($res_tod_empleados))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;
	$pdf->SetY(100+$j);
	$pdf->SetX(7);
	$pdf->Cell(15+$j,8,$row['nits_num_documento']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(73);
	$pdf->Cell(15+$j,8,$row['nombres']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(150);
	$pdf->Cell(15+$j,8,$row['nits_tel_residencia']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(203);
	$pdf->Cell(15+$j,8,$row['nits_dir_residencia']);
	
	$i++;
	if($j>=60)
	{
		$i=0;
		$pdf->AddPage('l','letter');
		$pdf->SetFont('Arial','',7);
	}
}
$pdf->Output();
?>