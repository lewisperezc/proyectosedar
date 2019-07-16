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
	$documento=$_POST['documento'];
	$con_inf_sal_por_nit=$ins_reporte->con_inf_sal_por_nit(2,$documento);
}
elseif($opcion==2)
	$con_inf_sal_por_nit=$ins_reporte->con_inf_sal_nits(2);

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/informacion_salarial_por_empleado.jpg",0,0,280,'C');
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
while($row = mssql_fetch_array($con_inf_sal_por_nit))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;
	$pdf->SetY(100+$j);
	$pdf->SetX(7);
	$pdf->Cell(15+$j,8,$row['nits_num_documento']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(57);
	$pdf->Cell(15+$j,8,$row['nombres']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(111);
	$pdf->Cell(15+$j,8,$row['cen_cos_nombre']);
	
	$pdf->SetY(100+$j);
	$pdf->SetX(181);
	$pdf->Cell(15+$j,8,number_format($row['nits_salario'],2));
	
	$pdf->SetY(100+$j);
	$pdf->SetX(220);
	$pdf->Cell(15+$j,8,number_format($row['nit_aux_transporte'],2));
	
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