<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$ins_nits=new nits();

$tipo=$_POST['tip_nit_id'];

$con_tod_nits=$ins_nits->ConAfiCrePorMesAnio($tipo);
$filas=mssql_num_rows($con_tod_nits);
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
$this->Image("../imagenes/reportes/listado_afiliados_creados_por_mes.jpg",0,0,200,'C');
//Arial bold 15
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
/*$pdf->SetFont('Arial','',18);
$pdf->SetY(73);$pdf->SetX(71);$pdf->Cell(15,8,$mes);
$pdf->SetY(73);$pdf->SetX(100);$pdf->Cell(15,8,$ano);*/


$pdf->SetFont('Arial','',7);
$i=0;
while($row=mssql_fetch_array($con_tod_nits))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;
	
	$pdf->SetY(68+$j);
	$pdf->SetX(3);
	$pdf->Cell(15+$j,8,$row['nits_num_documento']);
	
	$pdf->SetY(68+$j);
	$pdf->SetX(25);
	$pdf->Cell(15+$j,8,$row['nits_nombres']." ".$row['nits_apellidos']);
	
	$pdf->SetY(68+$j);
	$pdf->SetX(85);
	$pdf->Cell(15+$j,8,$row['nit_fec_creacion']);
	
	
	$pdf->SetY(68+$j);
	$pdf->SetX(120);
	$pdf->Cell(15+$j,8,$row['nit_fec_afiliacion']);
	
	$pdf->SetY(68+$j);
	$pdf->SetX(145);
	$pdf->Cell(15+$j,8,$row['nit_est_nombre']);
	
	$pdf->SetY(68+$j);
	$pdf->SetX(180);
	$pdf->Cell(15+$j,8,$row['nit_fec_retiro']);
	
	$i++;
	if($j>=170)
	{
		$i=0;
		$pdf->AddPage();
	}
}
$pdf->Output();
}
else
{
	echo "<script>
			alert('No se encontro informacion relacionada con los datos ingresados.');
			history.back(-1);
		</script>";
}
?>