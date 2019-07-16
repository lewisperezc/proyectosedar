<?php 
session_start();
$ano=$_SESSION['elaniocontable'];
include_once('../clases/factura.class.php');
include_once('../clases/recibo_caja.class.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
$ins_factura=new factura();
$ins_rec_caja=new rec_caja();
$fecha=date('d-m-Y');
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/listado_facturas.jpg",0,0,200,'C');
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
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresión'.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 
$pdf=new PDF(); 
//Tipo y tama�o de lertra 
$pdf->AddPage();
$pdf->SetFont('Arial','',7);
$i=0;
if($_GET['eltipo']==1)//POR MES
	$con_dat_factura=$ins_factura->RepLisFacturas($_GET['elmes'],$_GET['eltipo'],$ano);
elseif($_GET['eltipo']==2)//TODAS
	$con_dat_factura=$ins_factura->RepLisFacturas(0,$_GET['eltipo'],0);
$totales=0;
while($row=mssql_fetch_array($con_dat_factura))
{
	if($i!=0)
		$j=$i*3;
	else
		$j=$i;  
	$pdf->SetY(60+$j);$pdf->SetX(5);
	$pdf->Cell(15+$j,8,$row['fac_fecha']);
	$pdf->SetY(60+$j);$pdf->SetX(30);
	$pdf->Cell(15+$j,8,$row['fac_consecutivo']);
	$pdf->SetY(60+$j);$pdf->SetX(48);
	$pdf->Cell(15+$j,8,$row['nits_num_documento']." ".$row['cen_cos_nombre']);
	$pdf->SetY(60+$j);$pdf->SetX(145);
	$nota=$ins_rec_caja->saldoNotas($row['fac_id']);
	$pdf->Cell(15+$j,8,number_format($row['fac_val_unitario']+$nota,0));
	$totales+=$row['fac_val_unitario']+$nota;
	//$pdf->Cell(18,6,number_format($dat_glosa['valor'],0),0,0,"R");
	$pdf->SetY(60+$j);$pdf->SetX(175);
	$pdf->Cell(15+$j,8,$row['fac_mes_servicio']);
	$i++;
	if($j>=205)
	{
		$i=0;
		$pdf->AddPage();
	}
}
$pdf->SetFont('Arial','B');
$pdf->SetY(65+$j);$pdf->SetX(130);
$pdf->Cell(15+$j,8,'TOTAL:');
$pdf->SetY(65+$j);$pdf->SetX(145);
$pdf->Cell(15+$j,8,number_format($totales,0));
$pdf->Output();
?>