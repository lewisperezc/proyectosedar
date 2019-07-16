<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
$dia=date('d');
$mes = $_POST['mes'];
$ano = $_POST['anio'];
$fecha1="01-01-2011";
$fecha2=$dia."-".$mes."-".$ano;
$may_desde=$_POST['may_desde'];
$may_hasta=$_POST['may_hasta'];

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/may_balance.jpg",0,0,200,'C');
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
$pdf->AddPage();
$pdf->SetFont('Arial','',7);

$sql = "EXECUTE mayor_y_balance $mes,'$may_desde','$may_hasta'";
$query = mssql_query($sql);
if($query)
{
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY(50);$pdf->SetX(115);
	$pdf->Cell(21,8,"MES: ".$mes);
	
	$deb_inicial=0;$cre_inicial=0;$dif_inicial=0;
	//$debito
	
	$balance = "SELECT * FROM reportes";
	$que_balance = mssql_query($balance);
	$i=1;
	$pdf->SetFont('Arial','',7);
	while($row = mssql_fetch_array($que_balance))
	{
	  if($row['tres']!=0 || $row['cinco']!=0)
	  {
		  //AK DEBO TENER EL DEBITO INICIAL, COMO NO LO TENGO AUN LO PONGO EN 0.
		  $deb_inicial=$deb_inicial+0;
		  $cre_inicial=$cre_inicial+0;
		  if(strlen($row['uno'])<=2)
		  {
			  $pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$row['uno']);
			  $pdf->SetY(65+($i*3));$pdf->SetX(25);$pdf->Cell(21,8,substr($row['dos'],0,25));
			  $pdf->SetY(65+($i*3));$pdf->SetX(105);$pdf->Cell(21,8,number_format($row['tres'],2));//DEBITO
			  $pdf->SetY(65+($i*3));$pdf->SetX(145);$pdf->Cell(21,8,number_format($row['cinco'],2));//CREDITO
			  if(substr($row['uno'],0,1) == 1 || substr($row['uno'],0,1) == 5 || substr($row['uno'],0,1) == 6)
				{$pdf->SetY(65+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format($row['tres']-$row['cinco'],2));}
			  else
				{$pdf->SetY(65+($i*3));$pdf->SetX(175);$pdf->Cell(21,8,number_format($row['cinco']-$row['tres'],2));}
			  $i++;
			  if($i%67==0)
				{
					$pdf->AddPage();
					$pdf->SetFont('Arial','',7);
					$i=1;
				}
		  }
	 	}
		$_SESSION['posicion']=$i;
	}
	$dif_inicial=$deb_inicial-$cre_inicial;
	
	$pdf->SetY(65+($_SESSION['posicion']*3));
	$pdf->SetX(70);
	$pdf->Cell(180,10,"_____________________________________________________________________________________________");
	
	$pdf->SetY(72+($_SESSION['posicion']*3));
	$pdf->SetX(48);
	$pdf->Cell(150,10,"DEBITOS");
	
	$pdf->SetY(72+($_SESSION['posicion']*3));
	$pdf->SetX(77);
	$pdf->Cell(160,10,number_format($deb_inicial,2));
	
	$pdf->SetY(76+($_SESSION['posicion']*3));
	$pdf->SetX(48);
	$pdf->Cell(150,10,"CREDITOS");
	
	$pdf->SetY(76+($_SESSION['posicion']*3));
	$pdf->SetX(77);
	$pdf->Cell(160,10,number_format($cre_inicial,2));
	
	$pdf->SetY(80+($_SESSION['posicion']*3));
	$pdf->SetX(48);
	$pdf->Cell(150,10,"DIFERENCIA");
	
	$pdf->SetY(80+($_SESSION['posicion']*3));
	$pdf->SetX(77);
	$pdf->Cell(160,10,number_format($dif_inicial,2));
	
	$pdf->SetY(85+($_SESSION['posicion']*3));
	$pdf->SetX(70);
	$pdf->Cell(180,10,"_____________________________________________________________________________________________");
	
}
$pdf->Output();
?>