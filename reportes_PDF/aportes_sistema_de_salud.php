<?php 
session_start();
include_once('../clases/reporte.class.php');
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$mes=$_POST['mes'];
$anio=$_POST['anio'];
$fecha="-".$mes."-".$anio;
$cuenta="23701001";
$sigla="CAU_NOM_ADM-";
$ins_reporte = new reporte();
$con_dat_empleado=$ins_reporte->con_dat_apo_sis_salud_1(2);
$con_dat_empleado_2=$ins_reporte->con_dat_apo_sis_salud_1(2);

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/aportes_sistema_de_salud.jpg",0,0,280,'C');
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
$pdf->AddPage('l','letter');
$pdf->SetFont('Arial','',7);
while($row = mssql_fetch_array($con_dat_empleado))
{
	$valor=$ins_reporte->con_dat_apo_sis_salud_2($fecha,$cuenta,$row['nit_id'],$sigla);
	$datos=$datos."-".$valor;
}

$cadena=substr($datos,1);
$numero=substr($cadena,-1,1);
if(is_numeric($numero))
{
	$valores=split("-",$cadena);
	$i=0;
	$a=0;
	while($row_2 = mssql_fetch_array($con_dat_empleado_2))
	{
		if($i!=0)
		  $j=$i*4;
		else
		  $j=$i;
		$pdf->SetY(100+$j);
		$pdf->SetX(6);
		$pdf->Cell(15+$j,8,$row_2['nits_num_documento']);
		
		$pdf->SetY(100+$j);
		$pdf->SetX(25);
		$pdf->Cell(15+$j,8,$row_2['nombres']);
		
		$pdf->SetY(100+$j);
		$pdf->SetX(70);
		$pdf->Cell(15+$j,8,$row_2['nits_nombres']);
		
		$pdf->SetY(100+$j);
		$pdf->SetX(90);
		$pdf->Cell(15+$j,8,"");
		
		$pdf->SetY(100+$j);
		$pdf->SetX(137);
		$pdf->Cell(15+$j,8,number_format($valores[$a],2));
		
		$pdf->SetY(100+$j);
		$pdf->SetX(163);
		$pdf->Cell(15+$j,8,number_format(0,2));
		
		$pdf->SetY(100+$j);
		$pdf->SetX(184);
		$pdf->Cell(15+$j,8,number_format(0,2));
		
		$pdf->SetY(100+$j);
		$pdf->SetX(202);
		$pdf->Cell(15+$j,8,number_format($valores[$a],2));
		
		$pdf->SetY(100+$j);
		$pdf->SetX(224);
		$pdf->Cell(15+$j,8,number_format(0,2));
		
		$pdf->SetY(100+$j);
		$pdf->SetX(250);
		$pdf->Cell(15+$j,8,number_format($valores[$a],2));
		
		$i++;
		if($j>=60)
		{
			$i=0;
			$pdf->AddPage('l','letter');
			$pdf->SetFont('Arial','',7);
		}
		$a++;
	}
$pdf->Output();
}
else
{
	echo "<script>
				alert('No se encontraron registros.');
				history.back(-1);
		  </script>";
}
?>