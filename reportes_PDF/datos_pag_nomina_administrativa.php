<?php 
session_start();
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
include_once('../clases/nomina.class.php');
$ins_nomina=new nomina();
$num_quincena=$_GET['num_quincena'];
$mes=$_GET['mes'];
$anio=$_GET['anio'];
$dat_fecha="-".$mes."-".$anio;
$doc_inicial=$_GET['doc_inicial'];
$doc_final=$_GET['doc_final'];
$con_dat_nomina=$ins_nomina->ConNomPagadas('PAG_NOM_ADM-',11100524,$num_quincena,$dat_fecha,$doc_inicial,$doc_final);
$fecha=date('d-m-Y');
$pdf=new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tamaño,ubicacion)
$this->Image("../imagenes/reportes/desprendible_nomina_administrativa.jpg",0,0,200,'C');
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
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresion'.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 


$pdf=new PDF(); 
$pdf->AddPage();
//Tipo y tamaño de lertra 
$pdf->SetFont('Arial','B',14); 
$res_encabezado=mssql_num_rows($con_dat_nomina);
$pdf->SetY(15);
	$pdf->SetX(190);
	$pdf->Cell(21,8,$res_encabezado);

while($res_encabezado=mssql_fetch_array($con_dat_nomina))
{
	$pdf->SetY(15);
	$pdf->SetX(190);
	$pdf->Cell(21,8,"PRUEBA");
	
}
$pdf->Output();
?>