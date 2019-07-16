<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$mes = $_GET['mes_sele'];
$ano = $_GET['ano_sele'];
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/bal_terceros.jpg",0,0,200,'C');
//Arial bold 15
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
$pdf->SetFont('Arial','',6);

$sql = "EXECUTE bal_tercero $mes,$ano";
$query = mssql_query($sql);
if($query)
{
	$balance = "SELECT * FROM reportes";
	$que_balance = mssql_query($balance);
	$i=1;
	while($row = mssql_fetch_array($que_balance))
	{
	  if($row['tres']!=0 || $row['cinco']!=0)
	  {
		  $pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['uno']);
		  $pdf->SetY(65+($i*3));$pdf->SetX(25);$pdf->Cell(21,8,substr($row['dos'],0,25));
		  $pdf->SetY(65+($i*3));$pdf->SetX(140);$pdf->Cell(21,8,number_format($row['tres'],0));
		  $pdf->SetY(65+($i*3));$pdf->SetX(160);$pdf->Cell(21,8,number_format($row['cinco'],0));
		  if($row['siete']!=0)
		  {
			  $dat_nits = $nit->consul_nits($row['siete']);
			  $datos = mssql_fetch_array($dat_nits);
			  $pdf->SetY(65+($i*3));$pdf->SetX(65);$pdf->Cell(21,8,$datos['nits_num_documento']);
		      $pdf->SetY(65+($i*3));$pdf->SetX(80);$pdf->Cell(21,8,substr($datos['nits_nombres']." ".$datos['nits_apellidos'],0,35));
		  }
		  if(substr($row['uno'],0,1) == 1 || substr($row['uno'],0,1) == 5 || substr($row['uno'],0,1) == 6)
			{$pdf->SetY(65+($i*3));$pdf->SetX(180);$pdf->Cell(21,8,number_format($row['tres']-$row['cinco'],2),0,0,"R");}
		  else
			{$pdf->SetY(65+($i*3));$pdf->SetX(180);$pdf->Cell(21,8,number_format($row['cinco']-$row['tres'],2),0,0,"R");}
		  $i++;
		  if($i%67==0)
			{
				$pdf->AddPage();
				$pdf->SetFont('Arial','',6);
				$i=1;
			}
	  }
	}
}

$pdf->Output();
?>