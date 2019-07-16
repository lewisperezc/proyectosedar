<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();

$ano = $_POST['ano_sele'];
$certificado = $_POST['certi'];
$doc_ini = $_POST['doc_ini'];
$doc_fin = $_POST['doc_fin'];
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/certificados.jpg",0,0,200,'C');
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
$pdf->SetFont('Arial','',10);


$sql = "EXECUTE certificados '$doc_ini','$doc_fin',$certificado";
$query = mssql_query($sql);
if($query)
{
	$i=1;
	$p=0;
	$tot_cuenta=0;$total=0;
	$sql_reporte = "SELECT * FROM reportes ORDER BY cinco";
	$query_reporte = mssql_query($sql_reporte);
	while($row = mssql_fetch_array($query_reporte))
	{
	  if($p==0)
	  {
		$pdf->SetY(80);$pdf->SetX(160);$pdf->Cell(21,8,date('d-m-Y'));  
		$pdf->SetFont('Arial','B',7);
	  	$temp = $row['cinco'];
		$pdf->SetY(128+($i*3));$pdf->SetX(25);$pdf->Cell(21,8,$temp);
		$pdf->SetY(119+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$row['seis'],0,20);
		$i+=2;
		$p++;
	  }
	  if($temp == $row['cinco'])
	  {
		$pdf->SetFont('Arial','',7);
		$pdf->SetY(135+($i*3));$pdf->SetX(30);$pdf->Cell(21,8,$row['uno']);
		$pdf->SetY(135+($i*3));$pdf->SetX(80);$pdf->Cell(21,8,number_format($row['tres'],2),0,20);
		$pdf->SetY(135+($i*3));$pdf->SetX(120);$pdf->Cell(21,8,number_format($row['tres']/$row['cuatro'],2),0,20);
		$tot_cuenta+=$row['mov_valor'];$total += $row['mov_valor'];
		$i++;
	  }
	  else
	  {
		$pdf->AddPage();
		$temp = $row['cinco'];
		$i=1;
		$pdf->SetY(128+($i*3));$pdf->SetX(25);$pdf->Cell(21,8,$temp);
		$pdf->SetY(119+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$row['seis'],0,20);
		$pdf->SetY(80);$pdf->SetX(160);$pdf->Cell(21,8,date('d-m-Y'));
		if($certificado==1)
			$pdf->SetY(100);$pdf->SetX(100);$pdf->Cell(21,8,"ICA");
		if($certificado==2)
			$pdf->SetY(62);$pdf->SetX(100);$pdf->Cell(21,8,"FUENTE");
		$i+=2;
	  }
	}
  }

$pdf->Output();
?>