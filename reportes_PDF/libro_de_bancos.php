<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/reporte.class.php');
@include_once('clases/reporte.class.php');
@include_once('../conexion/conexion.php');
@include_once('../clases/mes_contable.class.php');
@include_once('../clases/saldos.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('clases/saldos.class.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
$ins_reporte = new reporte();
$mes_con = new mes_contable();
$saldos = new saldos();
$mes=$_POST['mes'];
$ano=$_POST['ano'];
$con_libro_bancos=$ins_reporte->libro_bancos(1110,$mes,$ano);
$filas=mssql_num_rows($con_libro_bancos);
if($filas>0)
{
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/libro_bancos.jpg",0,0,200,'C');
//Movernos a la derecha 
//$this->Cell(10); 
$this->Ln(10); 
}

//Pie de p�gina 
function Footer() 
{ 
//Posici�n: a 1,5 cm del final 
$this->SetY(-15); 
//N�mero de p�gina 
$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C'); 
} 

}// fin de la clase 
$pdf=new PDF(); 
//Tipo y tama�o de lertra 
$pdf->AddPage();
$i=0;
$sum_debito=0;
$sum_credito=0;
$saldo=$saldos->salInicial($mes,$ano,11100524);
$pdf->SetFont('Arial','B',7);
$pdf->SetY(55);$pdf->SetX(65);$pdf->Cell(15,8,$mes_con->nomMes($mes));
$pdf->SetY(55);$pdf->SetX(150);$pdf->Cell(15,8,number_format($saldo,2));
$pdf->SetFont('Arial','',7);
while($row = mssql_fetch_array($con_libro_bancos))
{
	if($i!=0)
	  $j=$i*5;
	else
	  $j=$i;
	  
	$pdf->SetY(72+$j);
	$pdf->SetX(1);
	$pdf->Cell(15+$j,8,$row['mov_fec_elabo']);
	
	$pdf->SetY(72+$j);
	$pdf->SetX(20);
	$pdf->Cell(15+$j,8,$row['mov_compro']);
	
	$pdf->SetY(72+$j);
	$pdf->SetX(42);
	$pdf->Cell(15+$j,8,$row['nits_num_documento']);
	
    $pdf->SetY(72+$j);
	$pdf->SetX(60);
	$pdf->Cell(15+$j,8,substr($row['nits_nombres'],0,40));
	
	$pdf->SetY(72+$j);
	$pdf->SetX(80);
	$pdf->Cell(15+$j,8,"");
	
	if($row['mov_tipo']==1)
	{
		//DEBITO
		$pdf->SetY(72+$j);$pdf->SetX(125);$pdf->Cell(15+$j,8,number_format($row['mov_valor']));
		//CREDITO
		$pdf->SetY(72+$j);$pdf->SetX(150);$pdf->Cell(15+$j,8,number_format(0,2));
		$sum_debito=$sum_debito+$row['mov_valor'];
		$saldo+=$row['mov_valor'];
	}
	
	elseif($row['mov_tipo']==2)
	{
		//DEBITO
		$pdf->SetY(72+$j);
		$pdf->SetX(125);
		$pdf->Cell(15+$j,8,number_format(0,2));
		
		//CREDITO
		$pdf->SetY(72+$j);
		$pdf->SetX(150);
		$pdf->Cell(15+$j,8,number_format($row['mov_valor']));	
		$sum_credito=$sum_credito+$row['mov_valor'];
		$saldo-=$row['mov_valor'];
	}
	
	//PARCIAL
	$pdf->SetY(72+$j);$pdf->SetX(180);$pdf->Cell(15+$j,8,number_format($saldo,2));
	
	$i++;
	if($j>=190)
	{
		$i=0;
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
	}
	$_SESSION['val_j']=$j;
}

$pdf->SetY(72+$_SESSION['val_j']+2);$pdf->SetX(125);$pdf->Cell(15+$_SESSION['val_j'],8,"____________________________________________________");	
$pdf->SetY(72+$_SESSION['val_j']+6);$pdf->SetX(125);$pdf->Cell(15+$_SESSION['val_j'],8,number_format($sum_debito,2));	
$pdf->SetY(72+$_SESSION['val_j']+6);$pdf->SetX(150);$pdf->Cell(15+$_SESSION['val_j'],8,number_format($sum_credito));	
$pdf->SetY(72+$_SESSION['val_j']+6);$pdf->SetX(180);$pdf->Cell(15+$_SESSION['val_j'],8,number_format($saldo,2));	
$pdf->SetY(72+$_SESSION['val_j']+8);$pdf->SetX(125);$pdf->Cell(15+$_SESSION['val_j'],8,"____________________________________________________");	


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