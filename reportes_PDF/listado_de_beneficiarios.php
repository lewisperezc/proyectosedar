<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/reporte.class.php');
@include_once('clases/reporte.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
$ins_reporte = new reporte();
$documento=$_POST['aso_documento'];
$opcion=$_GET['opc'];
$tipo=1;
if($opcion==1)
	$con_nit_por_tipo=$ins_reporte->con_dat_bas_nit_por_documento($tipo,$documento);
elseif($opcion==2)
	$con_nit_por_tipo=$ins_reporte->con_dat_bas_nit($tipo);

$filas=mssql_num_rows($con_nit_por_tipo);
if($filas>0)
{
$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/listado_de_beneficiarios.jpg",0,0,200,'C');
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
$i=0;
while($row = mssql_fetch_array($con_nit_por_tipo))
{
	$pdf->SetFont('Arial','B',7);
	$pdf->SetY(70+($i*3));$pdf->SetX(5);$pdf->Cell(15,8,$row['nits_num_documento']);
	$pdf->SetY(70+($i*3));	$pdf->SetX(46);$pdf->Cell(15,8,$row['nombres']);
	$con_ben_asociado=$ins_reporte->con_ben_asociado($row['nit_id']);
	$i+=2;
	while($res_ben_asociado=mssql_fetch_array($con_ben_asociado))
	{
		$pdf->SetFont('Arial','',7);
		$pdf->SetY(70+($i*3));$pdf->SetX(5);$pdf->Cell(15,8,$res_ben_asociado['ben_num_identificacion']);
		$pdf->SetY(70+($i*3));$pdf->SetX(50);$pdf->Cell(15,8,$res_ben_asociado['nombres']);
		$pdf->SetY(70+($i*3));$pdf->SetX(100);$pdf->Cell(15,8,$res_ben_asociado['par_nombres']);	
		$pdf->SetY(70+($i*3));$pdf->SetX(150);$pdf->Cell(15,8,$res_ben_asociado['ben_por_beneficios']);
		$i+=2;
	}
	if($i>=60)
	{
		$i=0;
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
	}
}
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