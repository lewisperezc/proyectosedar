<?php 
session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/bancos.class.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$banco = new bancos();
$ano = $_SESSION['elaniocontable'];
$fecha=date('d-m-Y');
$cant = $_POST['cant'];
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/compensacion.jpg",0,0,200,'C');
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
$suma=0;$j=0;
for($i=0;$i<$cant;$i++)
  {
  	//echo $_POST['cue'.$i]."--".$_POST['cue'.$i]."--".$_POST['nit'.$i]."<br>";
	if($_POST['cue'.$i]!= '11100524' && $_POST['cue'.$i]!= '23803004' && !empty($_POST['nit'.$i]))
	{
		$j++;
		$asociado = $nit->consultar($_POST['nit'.$i]);
		$dat_asociado = mssql_fetch_array($asociado);
		$pdf->SetY(20+(($j+2)*3));$pdf->SetX(5);$pdf->Cell(21,8,$dat_asociado['nits_num_documento']);
		$pdf->SetY(20+(($j+2)*3));$pdf->SetX(23);$pdf->Cell(21,8,substr($dat_asociado['nits_nombres']." ".$dat_asociado['nits_apellidos'],0,30));
		$bancos = $banco->datBancos($dat_asociado['nits_ban_id']);
		$dat_banco = mssql_fetch_array($bancos);
		$pdf->SetY(20+(($j+2)*3));$pdf->SetX(65);$pdf->Cell(21,8,substr($dat_banco['banco'],0,20));
		$pdf->SetY(20+(($j+2)*3));$pdf->SetX(93);$pdf->Cell(21,8,$dat_asociado['nits_num_cue_bancaria']);
		if($dat_asociado['tip_cue_ban_id']==1)
		  { $pdf->SetY(20+(($j+2)*3));$pdf->SetX(120);$pdf->Cell(21,8,"Ahorros");}
		else
		  { $pdf->SetY(20+(($j+2)*3));$pdf->SetX(120);$pdf->Cell(21,8,"Corriente");}
		$pdf->SetY(20+(($j+2)*3));$pdf->SetX(145);$pdf->Cell(21,8,$_POST['cue'.$i]);
		$pdf->SetY(20+(($j+2)*3));$pdf->SetX(185);$pdf->Cell(21,8,number_format($_POST['valor'.$i]));
		$suma+=$_POST['valor'.$i];
		if($j>66)
		{
			$pdf->AddPage();
			$j=0;
		}
	}
	$k=$j;
  }
$pdf->SetFont('Arial','B',7);
$pdf->SetY(20+(($k+1)*8));$pdf->SetX(15);$pdf->Cell(21,8,"TOTAL_________________________________________________________________________________________________________________".number_format($suma)); 
$pdf->Output();
?>