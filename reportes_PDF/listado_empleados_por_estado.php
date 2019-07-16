<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../clases/reporte.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$ins_reporte = new reporte();

$opcion=$_GET['opc'];
if($opcion==1)
{
	$estados=$_POST['estados'];
	$con_nit_por_est_tipo=$ins_reporte->con_nit_por_est_tipo(2,$estados);
}
elseif($opcion==2)
	$con_nit_por_est_tipo=$ins_reporte->con_nit_por_tipo(2);

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/listado_empleados_por_estado.jpg",0,0,280,'C');
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
while($row = mssql_fetch_array($con_nit_por_est_tipo))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;
	$pdf->SetY(100+$j);
	$pdf->SetX(7);
	$pdf->Cell(15+$j,8,$row['nits_num_documento']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(74);
	$pdf->Cell(15+$j,8,$row['nombres']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(153);
	$pdf->Cell(15+$j,8,$row['nit_est_nombre']);
	
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