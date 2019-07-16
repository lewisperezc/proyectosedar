<?php 
 session_start();
 $ano = $_SESSION['elaniocontable'];
 include_once('../clases/numerosALetras.class.php');
 include_once('../clases/recibo_caja.class.php');
 
 $recibo = new rec_caja();
 $elid=$_GET['elid'];
 $condatrecibo=$recibo->ConTodDatRecCajPorId($elid);
 $elresultado=mssql_fetch_array($condatrecibo);
 $dat_recibo = $recibo->dat_recibo($elresultado['rec_caj_consecutivo']);
 /*$instancia = new numerosALetras($_SESSION['valor']);
 $valor_letras = $instancia->resultado;*/
 
//buscamos en consecutivo y actualizamos la orden de desembolso
/////////////////////////////
require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tama�o,ubicacion)
	$this->Image("../imagenes/reportes/recibo_caja.jpg",0,0,200,'C');
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
$pdf->AddPage();

$pdf->SetY(18);//renglonesss
$pdf->SetX(137);//filas ->
$pdf->SetFont('arial','',18);
$pdf->Cell(28,6,$elresultado['rec_caj_consecutivo']);

$pdf->SetY(38);//renglonesss
$pdf->SetX(27);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,$elresultado['rec_caj_fecha']);

$pdf->SetY(46);//renglonesss
$pdf->SetX(34);//filas ->
$pdf->SetFont('arial','',8);
$pdf->Cell(28,6,$elresultado['cen_cos_nombre']);

$pdf->SetY(46);//renglonesss
$pdf->SetX(132);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(58,6,number_format($elresultado['rec_caj_monto'],0));
$pdf->SetY(55);//renglonesss
$pdf->SetX(35);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,$elresultado['nits_dir_residencia']);

$pdf->SetY(62);//renglonesss
$pdf->SetX(55);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,convertir($elresultado['rec_caj_monto']));

$pdf->SetY(71);//renglonesss
$pdf->SetX(40);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,$nom_concepto);
$pdf->SetFont('arial','',7);
$temp_val='';
$temp_tipo='';
$temp_vecPintar=array();
$i=1;
$pos_vector=1;
while($row=mssql_fetch_array($dat_recibo))
{
	if($i<=2)
	{
		$pdf->SetY(85+($i*5));$pdf->SetX(20);$pdf->Cell(28,6,$row['mov_cuent']);
		$pdf->SetY(85+($i*5));$pdf->SetX(45);$pdf->Cell(28,6,$row['cue_nombre']);
		if($row['mov_tipo']==1)
		  {$pdf->SetY(85+($i*5));$pdf->SetX(115);$pdf->Cell(28,6,number_format($row['mov_valor'],0));}
		else
		  {$pdf->SetY(85+($i*5));$pdf->SetX(155);$pdf->Cell(28,6,number_format($row['mov_valor'],0));}
	}
	else
	{
		if($i%2==1)
		{
			$temp_val=$row['mov_valor'];
			$temp_tipo=$row['mov_tipo'];
			$temp_cuent=$row['cue_nombre'];
			$temp_num_cuenta=$row['mov_cuent'];
			$temp_vecPintar[$pos_vector]['valor']=$temp_val;
			$temp_vecPintar[$pos_vector]['tipo']=$temp_tipo;
			$temp_vecPintar[$pos_vector]['cuenta']=$temp_cuent;
			$temp_vecPintar[$pos_vector]['num_cuenta']=$temp_num_cuenta;
		}
		else
		{
			if(($row['mov_valor']!=$temp_val)&&($row['mov_tipo']==$temp_tipo))
			{
				$temp_vecPintar[$pos_vector]['valor']=$temp_val;
				$temp_vecPintar[$pos_vector]['tipo']=$temp_tipo;
				$temp_vecPintar[$pos_vector]['cuenta']=$temp_cuent;
				$temp_vecPintar[$pos_vector]['num_cuenta']=$temp_num_cuenta;
				$pos_vector++;
				$temp_vecPintar[$pos_vector]['valor']=$row['mov_valor'];
				$temp_vecPintar[$pos_vector]['tipo']=$row['mov_tipo'];
				$temp_vecPintar[$pos_vector]['cuenta']=$row['cue_nombre'];
				$temp_vecPintar[$pos_vector]['num_cuenta']=$row['mov_cuent'];
				$pos_vector++;
			}
		}
	}
	$i++;
}

for($p=1;$p<=sizeof($temp_vecPintar);$p++)
{
	$pdf->SetY(85+($i*5));$pdf->SetX(20);$pdf->Cell(28,6,$temp_vecPintar[$p]['num_cuenta']);
	$pdf->SetY(85+($i*5));$pdf->SetX(45);$pdf->Cell(28,6,$temp_vecPintar[$p]['cuenta']);
	if($temp_vecPintar[$p]['tipo']==1)
	  {$pdf->SetY(85+($i*5));$pdf->SetX(115);$pdf->Cell(28,6,number_format($temp_vecPintar[$p]['valor'],0));}
	else
	  {$pdf->SetY(85+($i*5));$pdf->SetX(155);$pdf->Cell(28,6,number_format($temp_vecPintar[$p]['valor'],0));}
	 $i++;
}

$pdf->Output();
?>