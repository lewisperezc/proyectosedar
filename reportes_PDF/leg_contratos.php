<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/varios.class.php');
include_once('../clases/mes_contable.class.php');

require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$varios=new varios();
$mes_contable = new mes_contable();

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/leg_contratos.jpg",0,0,300,'C');
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
$pdf->AddPage('L');
$i=1;$p=0;
$sql = "EXECUTE leg_contratos";
$query = mssql_query($sql);
$pdf->SetFont('Arial','B',8);
$fec_dia = date('d-m-Y');

if($query)
{
	$sql_rep = "SELECT * FROM reportes";
	$query_rep = mssql_query($sql_rep);
	$num_rows = mssql_num_rows($query_rep);
	while($row=mssql_fetch_array($query_rep))
	{
		if($row['cuatro']>0)
		  $porcentaje = ($row['nueve']+$row['ocho'])/$row['cuatro'];
		else
		  $porcentaje = 0;
		$pdf->SetY(35+($i*4));$pdf->SetX(5);$pdf->Cell(21,8,$row['diez']);
		$pdf->SetFont('Arial','B',6);
		$pdf->SetY(35+($i*4));$pdf->SetX(23);$pdf->Cell(21,8,$row['once']);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetY(35+($i*4));$pdf->SetX(86);$pdf->Cell(21,8,$row['tres']);
		$pdf->SetY(35+($i*4));$pdf->SetX(120);$pdf->Cell(21,8,number_format((float)$row['cuatro'],0));
		$pdf->SetY(35+($i*4));$pdf->SetX(153);$pdf->Cell(21,8,$row['seis']);
		$pdf->SetY(35+($i*4));$pdf->SetX(178);$pdf->Cell(21,8,$row['siete']);
		$pdf->SetY(35+($i*4));$pdf->SetX(205);$pdf->Cell(21,8,number_format((float)$row['ocho'],0));
		$pdf->SetY(35+($i*4));$pdf->SetX(240);$pdf->Cell(21,8,number_format((float)$row['nueve'],0));
		$pdf->SetY(35+($i*4));$pdf->SetX(275);$pdf->Cell(21,8,number_format((float)$porcentaje,4));
		$i++;
		if($i==36)
		{
			$i=1;
			$pdf->AddPage('L');
		}
	}
	$i++;
}
$pdf->Output();
?>