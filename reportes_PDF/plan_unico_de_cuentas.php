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
$res_tod_cuentas=$ins_reporte->con_cue_por_rangos($desde,$hasta);

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/plan_unico_de_cuentas.jpg",0,0,280,'C');
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
$pdf->AddPage('l','letter');
$pdf->SetFont('Arial','',7);
$i=0;
while($row = mssql_fetch_array($res_tod_cuentas))
{
	if($i!=0)
	  $j=$i*3;
	else
	  $j=$i;
	$pdf->SetY(100+$j);
	$pdf->SetX(13);
	$pdf->Cell(15+$j,8,$row['cue_id']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(70);
	$pdf->Cell(15+$j,8,$row['cue_nombre']);
	
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