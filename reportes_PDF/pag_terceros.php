<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
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
$this->Image("../imagenes/reportes/pago_tercero.jpg",0,0,200,'C');
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
$pdf->SetFont('Arial','',6);

$sql = "SELECT mov.mov_cuent,cue.cue_nombre,nit.nits_num_documento,nit.nits_nombres,nit.nits_apellidos,mov.mov_valor 
		FROM movimientos_contables mov
		INNER JOIN nits nit on nit.nit_id=mov.mov_nit_tercero
		INNER JOIN cuentas cue ON cue.cue_id=mov.mov_cuent
		WHERE mov.mov_cuent like('111005%') AND mov_tipo=2 
		AND mov_mes_contable = $mes ORDER BY mov_cuent,nits_num_documento";
$query = mssql_query($sql);
if($query)
{
	$i=1;
	$p=0;$num=0;
	$tot_cuenta=0;$total=0;
	while($row = mssql_fetch_array($query))
	{
	   if($p==0)
	  {
		$pdf->SetFont('Arial','B',7);
	  	$temp = $row['mov_cuent'];
		$pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$temp);
		$pdf->SetY(65+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$row['cue_nombre'],0,20);
		$i+=2;
		$p++;
	  }
	  if($temp == $row['mov_cuent'])
	  {
		$pdf->SetFont('Arial','',7);
		$pdf->SetY(65+($i*3));$pdf->SetX(50);$pdf->Cell(21,8,$row['nits_num_documento']);
		$pdf->SetY(65+($i*3));$pdf->SetX(90);$pdf->Cell(21,8,$row['nits_nombres']." ".$row['nits_apellidos'],0,20);
		$pdf->SetY(65+($i*3));$pdf->SetX(150);$pdf->Cell(21,8,number_format($row['mov_valor'],2));
		$tot_cuenta+=$row['mov_valor'];$total += $row['mov_valor'];
		$i++;
	  }
	  else
	  {
		$pdf->SetFont('Arial','B',7);
		$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,"________________________________________________");
		$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,number_format($tot_cuenta,2));
		$temp = $row['mov_cuent'];
		$tot_cuenta=0;
		$i++;
		$pdf->SetY(65+($i*3));$pdf->SetX(10);$pdf->Cell(21,8,$temp);
		$pdf->SetY(65+($i*3));$pdf->SetX(35);$pdf->Cell(21,8,$row['cue_nombre'],0,20);
		$i++;
	  }
	  $num++;
	}
	$pdf->SetFont('Arial','B',7);  $i+=2;
	$pdf->SetY(65+($i*3));$pdf->SetX(100);$pdf->Cell(21,8,"Totales ________________________________________________");
	$pdf->SetY(65+($i*3));$pdf->SetX(150);$pdf->Cell(21,8,number_format($total,2));
  }

$pdf->Output();
?>