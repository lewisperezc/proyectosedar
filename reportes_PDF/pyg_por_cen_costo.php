<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

$pyg_desde=$_POST['pyg_desde'];
$pyg_hasta=$_POST['pyg_hasta'];
$pyg_mes = $_POST['pyg_mes'];
$pyg_anio = $_POST['pyg_anio'];
$pyg_cen_costo=$_POST['pyg_cen_costo'];

$meses = array('NA','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
$mes_seleccionado=$meses[$pyg_mes];

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/pyg_por_cen_costo.jpg",0,0,200,'C');
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
$pdf->SetFont('Arial','',7);

$sql = "EXECUTE PyG_Por_Cen_Costo $pyg_mes,'$pyg_desde','$pyg_hasta',$pyg_cen_costo";
$query = mssql_query($sql);
if($query)
{
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY(50);$pdf->SetX(130);
	$pdf->Cell(21,8,"MES: ".date("m"));
	$debito=0;
	$credito=0;
	$diferencia=0;
	$balance = "SELECT * FROM reportes";
	//echo $balance;
	$que_balance = mssql_query($balance);
	$i=1;
	$pdf->SetFont('Arial','',7);
	$pdf->SetY(63);$pdf->SetX(120);$pdf->Cell(21,8,$mes_seleccionado,0,25);
	while($row = mssql_fetch_array($que_balance))
	{
	  if($row['tres']!=0 || $row['cinco']!=0)
	  {
			  $pdf->SetY(65+($i*3));$pdf->SetX(5);$pdf->Cell(21,8,$row['uno']);
			  $pdf->SetY(65+($i*3));$pdf->SetX(48);$pdf->Cell(21,8,substr($row['dos'],0,25));
			  
			  if(substr($row['uno'],0,1) == 1 || substr($row['uno'],0,1) == 5 || substr($row['uno'],0,1) == 6)
				{$pdf->SetY(65+($i*3));$pdf->SetX(168);$pdf->Cell(21,8,number_format($row['tres']-$row['cinco'],2));}
			  else
				{$pdf->SetY(65+($i*3));$pdf->SetX(168);$pdf->Cell(21,8,number_format($row['cinco']-$row['tres'],2));}
			  $i++;
			  if($i%67==0)
				{
					$pdf->AddPage();
					$pdf->SetFont('Arial','',7);
					$i=1;
				}
				$debito=$debito+$row['tres'];
				$credito=$credito+$row['cinco'];
	 	}
		$_SESSION['posicion']=$i;
	}
	$diferencia=$debito-$credito;
	$pdf->SetY(65+($_SESSION['posicion']*3));
	$pdf->SetX(150);
	$pdf->Cell(180,10,"__________________________________________");
	
	$pdf->SetY(72+($_SESSION['posicion']*3));
	$pdf->SetX(130);
	$pdf->Cell(150,10,"DEBITOS");
	
	$pdf->SetY(72+($_SESSION['posicion']*3));
	$pdf->SetX(168);
	$pdf->Cell(150,10,number_format($debito,2));
	
	$pdf->SetY(76+($_SESSION['posicion']*3));
	$pdf->SetX(130);
	$pdf->Cell(150,10,"CREDITOS");
	
	$pdf->SetY(76+($_SESSION['posicion']*3));
	$pdf->SetX(168);
	$pdf->Cell(150,10,number_format($credito,2));
	
	$pdf->SetY(80+($_SESSION['posicion']*3));
	$pdf->SetX(130);
	$pdf->Cell(150,10,"DIFERENCIA");
	
	$pdf->SetY(80+($_SESSION['posicion']*3));
	$pdf->SetX(168);
	$pdf->Cell(150,10,number_format($diferencia,2));
	
$pdf->Output();	
}
else
{
	echo "<script>alert('No se encontraron datos para mostrar, Intentelo de nuevo.');window.history(-1);</script>";
}
?>