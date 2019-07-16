<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/concepto.class.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
$concepto = new concepto();
$mes = $_POST['mes'];
$ano = $_POST['ano'];
$dia = $_POST['dia'];
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/com_diario.jpg",0,0,200,'C');
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
$pdf->SetFont('Arial','',7);
$fecha = $dia."-".$mes."-".$ano;
$sql = "EXECUTE comprobante_diario '$fecha'";
//echo $sql;

$query = mssql_query($sql);
if($query)
{
	$balance = "select * from reportes where LEN(uno) = 4 ORDER BY siete";
	$que_balance = mssql_query($balance);
	$i=1;
	$p=0;$num=0;
	$debito=0;$credito=0;$tot_debito=0;$tot_credito=0;
	$num_rows = mssql_num_rows($que_balance);
	while($row = mssql_fetch_array($que_balance))
	{
	  if($p==0)
	  {
		$pdf->SetFont('Arial','B',7);
	  	$temp = $row['siete'];
		$nom_conce = $concepto->getcon_nombre($temp);
		$pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$temp);
		$pdf->SetY(65+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$nom_conce,0,20);
		$i++;
		$p++;
	  }
	  if($temp == $row['siete'])
	  {
		$pdf->SetFont('Arial','',7);
		$pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['uno']);
		$pdf->SetY(65+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['dos'],0,30));
		$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($row['tres'],2));
		$debito += $row['tres'];$credito += $row['cinco'];
		$tot_debito+=$row['tres']; $tot_credito += $row['cinco'];
		$pdf->SetY(65+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($row['cinco'],2));
		$i++;
	  }
	  else
	  {
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,"________________________________________________");
		$i++;
		$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($debito,2));
		$pdf->SetY(65+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($credito,2));
		$temp = $row['siete'];
		$i++;
		$nom_conce = $concepto->getcon_nombre($temp);
		$pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$temp);
		$pdf->SetY(65+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$nom_conce);
		$i++;
		$pdf->SetFont('Arial','',7);
		$pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['uno']);
		$pdf->SetY(65+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,substr($row['dos'],0,30));
		$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($row['tres'],2));
		$pdf->SetY(65+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($row['cinco'],2));
		$debito=0;$credito=0;
		$debito += $row['tres'];$credito += $row['cinco'];
		$tot_debito+=$row['tres']; $tot_credito += $row['cinco'];
		$i++;
		if($num==($num_rows-1))
		{
			$pdf->SetFont('Arial','B',7);
			$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,"________________________________________________");
			$i++;
			$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($debito,2));
			$pdf->SetY(65+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($credito,2));
		}
	  }
	  $num++;
	}
  $pdf->SetFont('Arial','B',7);  $i+=2;
  $pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,"Totales ________________________________________________");
  $i++;
  $pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($tot_debito,2));
  $pdf->SetY(65+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($tot_credito,2));	
}

$pdf->Output();
?>