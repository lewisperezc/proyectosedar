<?php 
session_start();
include_once('../clases/compensacion_nomina.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/cuenta.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$causacion = new compensacion_nomina();
$nit = new nits();
$cuenta = new cuenta();
$dat_causacion = $causacion->causacion($_SESSION['consecutivo']);

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
$this->Image("../imagenes/reportes/rep_cauCompensacion.jpg",0,0,280,'C');
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
while($row = mssql_fetch_array($dat_causacion))
{
	if($i!=0)
	  $j=$i*3;
	else
	  $j=$i;  
	$pdf->SetY(55+$j);$pdf->SetX(13);
	$pdf->Cell(15+$j,8,$row['mov_compro']);
	$datos = split("_",$row['mov_nit_tercero'],2);
	if($datos[0])
	{
		$pdf->SetFont('Arial','',7);
		$dat_nit = $nit->consul_nits($datos[0]);
		$datos_nit = mssql_fetch_array($dat_nit);
		$pdf->SetY(55+$j);$pdf->SetX(44);
		$pdf->Cell(15+$j,8,$datos_nit['nits_num_documento']);
		$pdf->SetY(55+$j);$pdf->SetX(66);
		$pdf->Cell(15,8,$datos_nit['nits_nombres']." ".$datos_nit['nits_apellidos']);
	}
	else
	{
		$pdf->SetFont('Arial','',7);
		$dat_nit = $nit->consul_nits($row['mov_nit_tercero']);
		$datos_nit = mssql_fetch_array($dat_nit);
		$pdf->SetY(55+$j);$pdf->SetX(44);
		$pdf->Cell(15,8,$datos_nit['nits_num_documento']);
		$pdf->SetY(55+$j);$pdf->SetX(66);
		$pdf->Cell(15,8,$datos_nit['nits_nombres']." ".$datos_nit['nits_apellidos']);
	}
	if($datos[1])
	{
		$pdf->SetFont('Arial','',7);
		$dat_nit = $nit->consul_nits($datos[1]);
		$datos_nit = mssql_fetch_array($dat_nit);
		$pdf->SetY(55+$j);$pdf->SetX(170);
		$pdf->Cell(15,8,$datos_nit['nits_nombres']." ".$datos_nit['nits_apellidos']);
	}
	
	$pdf->SetFont('Arial','',6);
	$pdf->SetY(55+$j);$pdf->SetX(124);
	$cue = $cuenta->verificar_existe($row['mov_cuent']);
	$dat_cue = mssql_fetch_array($cue);
	$pdf->Cell(15,8,$dat_cue['cue_nombre']);
	
	$pdf->SetFont('Arial','',7);
	$pdf->SetY(55+$j);$pdf->SetX(210);
	$pdf->Cell(15,8,number_format($row['mov_valor'],1));
	
	$pdf->SetFont('Arial','',7);
	$pdf->SetY(55+$j);$pdf->SetX(250);
	$pdf->Cell(15,8,$row['mov_fec_elabo']);
	$i++;
	if($j>=132)
	{
		$i=0;
		$pdf->AddPage('l','letter');
	}
}
//LIMPIAR SESSIONES//
unset($_SESSION['consecutivo']);
/////////////////////
$pdf->Output();
?>