<?php
 session_start();
 include_once('../clases/credito.class.php');
 include_once('../clases/nits.class.php');
 include_once('../clases/saldos.class.php');
 $ano = $_SESSION['elaniocontable'];
 $ins_credito=new credito();
 $ins_nit=new nits();
 $ins_saldo =new saldos();

 $fecha=date('d-m-Y');//$_POST['fecha'];
 $total_saldos=0;

 $fecha=date('d-m-Y');
 $total_saldos=0;
 $nit=$_GET['nit'];
 $credito=$_GET['credito'];
 $capital=0;

 $dat_nit=mssql_fetch_array($ins_nit->consultar($nit));
 $dat_descuento=$ins_credito->dat_descuento($credito);
 $dat_credito=$ins_credito->dat_creditos($credito);
 $datos_credito=mssql_fetch_array($dat_credito);
 $saldo=$datos_credito['cre_valor'];
//buscamos en consecutivo y actualizamos la orden de desembolso
/////////////////////////////
define('../FPDF_FONTPATH','/font/');//Foncion de librerias para fuentes 
include('../pdf/class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../pdf/fonts/courier.afm');
require('../pdf/fpdf.php');
$datacreator = array ('Title'=>'Ejemplo PDF','Author'=>'@nestecoop','Subject'=>'PDF con Tablas','Creator'=>'@nestecoop','Producer'=>'http://');
					
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{
//Logo
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tama�o,ubicacion)
$this->Image("../imagenes/reportes/extracto_cuenta.jpg",0,0,211,'C');
//Arial bold 15
$this->SetFont('Arial','B',15);
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

//N�mero de p�gina 
$pdf->addInfo($datacreator);//muestra los datos del creador en pro piedades del documento

$pdf=new PDF();
$pdf->AddPage();//orientacion y tama�o hoja
$pdf->SetFont('Arial','B',12);
$pdf->Text(153,63,$fecha); 
///////////////////////////////Datos Nit////////////////////////////////

$pdf->SetFont('Arial','B',9);
$pdf->Text(155,70,$dat_nit['nits_num_documento']); 
$pdf->Text(30,70,$dat_nit['nits_nombres']." ".$dat_nit['nits_apellidos']); 
$pdf->Text(30,86,$dat_nit['nit_fec_creacion']);$pdf->Text(155,86,number_format($datos_credito['cre_valor']));
/////////////////////////////////////////////////////////
/////////////////////////////Creditos////////////////////
$pdf->SetFont('Arial','',7);
$cont=1;
while($row=mssql_fetch_array($dat_descuento))
{
	$capital+=$row['des_cre_capital'];
	$pdf->SetY(100+($cont*4));$pdf->SetX(25);$pdf->Cell(1,1,$row['des_cre_pagCompensacion'],0,0,"R");
	$pdf->SetY(100+($cont*4));$pdf->SetX(30);$pdf->Cell(1,1,$row['des_cre_fecha'],0,0,"L");
	$pdf->SetY(100+($cont*4));$pdf->SetX(55);$pdf->Cell(1,1,"Descuento de Nomina",0,0,"L");
	$pdf->SetY(100+($cont*4));$pdf->SetX(103);$pdf->Cell(1,1,number_format($row['des_cre_total']),0,0,"R");
	$pdf->SetY(100+($cont*4));$pdf->SetX(135);$pdf->Cell(1,1,number_format($row['des_cre_capital']),0,0,"R");
	$pdf->SetY(100+($cont*4));$pdf->SetX(165);$pdf->Cell(1,1,number_format($row['des_cre_interes']),0,0,"R");
	$pdf->SetY(100+($cont*4));$pdf->SetX(195);$pdf->Cell(1,1,number_format($datos_credito['cre_valor']-$capital),0,0,"R");
	$saldo-=$capital;
	$cont++;
}
$pdf->SetFont('Arial','B',8);
$pdf->SetY(100+($cont*4));$pdf->SetX(195);$pdf->Cell(1,1,"Total Saldos: _______________________",0,0,"R");$cont++;
$pdf->SetY(100+($cont*4));$pdf->SetX(195);$pdf->Cell(1,1,number_format($saldo),0,0,"R");
///////////////////////////////////////////////////////////////////////////
//////////////////////////////////Datos saldos y fondo/////////////////////

$pdf->Output();
?>