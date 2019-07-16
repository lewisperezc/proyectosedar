<?php 
session_start();
$ano=$_SESSION['elaniocontable'];
include_once('../clases/factura.class.php');
include_once('../clases/reporte_jornadas.class.php');
include_once('../clases/recibo_caja.class.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
$ins_factura=new factura();
$ins_rep_jornadas=new reporte_jornadas();
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
$this->Image("../imagenes/reportes/listado_facturas_suma_jornadas.jpg",0,0,200,'C');
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
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresion '.(date('d-m-Y')),0,0,'C'); 
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
$total_factura=0;
$total_jornada=0;
while($row=mssql_fetch_array($con_dat_factura))
{
	$sum_rep_jor=$ins_rep_jornadas->canJorFac($row['fac_id']);
	$sum_rep_jor_con_recibo=$ins_rep_jornadas->canJorFacConRecibo($row['fac_id']);
	$sum_total=$sum_rep_jor+$sum_rep_jor_con_recibo;
	
	if($i!=0)
		$j=$i*3;
	else
		$j=$i;
	//if($row['fac_rep_reconfirmado']!=''&&$row['fac_contrato']!=''&&$row['fac_mes_servicio']!='')
	//{
		$pdf->SetY(60+$j);$pdf->SetX(0);
		$pdf->Cell(15+$j,8,$row['fac_fecha']);
		$pdf->SetY(60+$j);$pdf->SetX(20);
		$pdf->Cell(15+$j,8,$row['fac_consecutivo']);
		$pdf->SetY(60+$j);$pdf->SetX(40);
		$pdf->Cell(15+$j,8,substr($row['cen_cos_nombre'],0,42));
		$pdf->SetY(60+$j);$pdf->SetX(110);
		$nota=$ins_rec_caja->saldoNotas($row['fac_id']);
		$pdf->Cell(15+$j,8,number_format($row['fac_val_unitario']+$nota));
		$total_factura+=$row['fac_val_unitario']+$nota;
		$pdf->SetY(60+$j);$pdf->SetX(140);
		$pdf->Cell(15+$j,8,number_format($sum_total));
		$total_jornada+=$sum_total;
		$pdf->SetY(60+$j);$pdf->SetX(165);
		$pdf->Cell(15+$j,8,$row['fac_mes_servicio']);
		
		$pdf->SetY(60+$j);$pdf->SetX(175);
		$pdf->Cell(15+$j,8,$row['fac_ano_servicio']);
		
		$i++;
		if($j>=205)
		{
			$i=0;
			$pdf->AddPage();
		}
	//}
}
$pdf->SetFont('Arial','B');
$pdf->SetY(65+$j);$pdf->SetX(85);
$pdf->Cell(15+$j,8,'TOTALES:');
$pdf->SetY(65+$j);$pdf->SetX(110);
$pdf->Cell(15+$j,8,number_format($total_factura));
$pdf->SetY(65+$j);$pdf->SetX(140);
$pdf->Cell(15+$j,8,number_format($total_jornada));
$pdf->Output();
?>