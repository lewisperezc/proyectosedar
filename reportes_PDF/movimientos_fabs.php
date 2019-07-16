<?php 
 session_start();
 $ano = $_SESSION['elaniocontable'];
//buscamos en consecutivo y actualizamos la orden de desembolso
/////////////////////////////
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

@include_once('../clases/moviminetos_contables.class.php');
@include_once('../clases/nits.class.php');
$ins_nits=new nits();
$ins_mov_contable=new movimientos_contables();
if($_POST['nit']!=""){
$_SESSION['nit_id']=$_POST['nit'];
$_SESSION['desde']=$_POST['desde'];
$_SESSION['hasta']=$_POST['hasta'];
}
$con_dat_aso_fabs=$ins_mov_contable->con_dat_aso_fabs($_SESSION['nit_id'],$_SESSION['desde'],$_SESSION['hasta']);


$numero_filas=mssql_num_rows($con_dat_aso_fabs);
if($numero_filas<=0)
echo "<script>
			alert('No se encontraron datos relacionados al NIT en el rango de fechas ingresado.');
			location.href='../formularios/movimientos_FABS_asociado.php';
	  </script>";
else
{
$con_dat_nit=$ins_nits->cons_nombres_nit($_SESSION['nit_id']);
$res_nom_doc_nit=mssql_fetch_array($con_dat_nit);
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tama�o,ubicacion)
	$this->Image("../imagenes/reportes/movimientos_fabs.jpg",0,0,280,'C');
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
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresi�n'.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 

$pdf=new PDF(); 
$pdf->AddPage('l','letter');


$pdf->SetY(38);//renglonesss
$pdf->SetX(136);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,52,"DESDE ".$_SESSION['desde']." HASTA ".$_SESSION['hasta']);

$pdf->SetY(64);//renglonesss
$pdf->SetX(47);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,15,$res_nom_doc_nit['nits_num_documento']);

$pdf->SetY(64);//renglonesss
$pdf->SetX(125);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(58,15,$res_nom_doc_nit['nombres']);


//Tipo y tama�o de lertra 
$i=0;
while($row = mssql_fetch_array($con_dat_aso_fabs))
{
	if($i!=0)
	  $j=$i*5;
	else
	  $j=$i;  
	$pdf->SetFont('arial','',10);
	$pdf->SetY(84+$j);
	$pdf->SetX(2);
	$pdf->Cell(15+$j,5,$row['pab_fecha']);
	
	$pdf->SetY(84+$j);
	$pdf->SetX(27);
	$pdf->Cell(15+$j,5,$row['nits_nombres']);
	
	$pdf->SetY(84+$j);
	$pdf->SetX(80);
	$pdf->Cell(15+$j,5,$row['nits_nombres']);

	$pdf->SetY(84+$j);
	$pdf->SetX(128);
	$pdf->Cell(15+$j,5,$row['pro_nombre']);
	
	//
	$pdf->SetY(84+$j);
	$pdf->SetX(188);
	$pdf->Cell(15+$j,5,number_format($row['pab_valor']));
	
	$pdf->SetY(84+$j);
	$pdf->SetX(228);
	$pdf->Cell(15+$j,5,$row['pab_cantidad']);
	
	$valor_total=$row['pab_valor']*$row['pab_cantidad'];
	
	$pdf->SetY(84+$j);
	$pdf->SetX(255);
	$pdf->Cell(15+$j,5,number_format($valor_total));
	
	$i++;
	if($j>=80)
	{
		$i=0;
		$pdf->AddPage('l','letter');
	}
}
$pdf->Output();
}
/*$_SESSION['nit_id']="";
$_SESSION['desde']="";
$_SESSION['hasta']="";*/
?>