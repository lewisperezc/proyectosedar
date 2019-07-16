<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../clases/reporte.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$ins_reporte = new reporte();
$con_tod_nits=$ins_reporte->con_tod_nits();
$filas=mssql_num_rows($con_tod_nits);
if($filas>0)
{
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/listado_completo_de_nits.jpg",0,0,280,'C');
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
while($row = mssql_fetch_array($con_tod_nits))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;
	$pdf->SetY(95+$j);
	$pdf->SetX(7);
	$pdf->Cell(15+$j,8,$row['nits_num_documento']);
	
	$pdf->SetY(95+$j);
	$pdf->SetX(80);
	$pdf->Cell(15+$j,8,$row['nits_nombres']." ".$row['nits_apellidos']);
	
	$i++;
	if($j>=70)
	{
		$i=0;
		$pdf->AddPage('l','letter');
		$pdf->SetFont('Arial','',7);
	}
}
$pdf->Output();
}
else
{
	echo "<script>
				alert('No Se Encontraron Registros!!!');
				history.back(-1);
		  </script>";
}
?>