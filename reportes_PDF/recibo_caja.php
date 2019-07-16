<?php 
 session_start();
 $ano = $_SESSION['elaniocontable'];
 include_once('../clases/concepto.class.php');
 include_once('../clases/cuenta.class.php');
 include_once('../clases/numerosALetras.class.php');
 include_once('../clases/nits.class.php');
 include_once('../clases/recibo_caja.class.php');
 
 $nit = new nits();
 $concepto = new concepto();
 $cuenta = new cuenta();
 $recibo = new rec_caja();
 $conce = 112;
 $conse_recibo = $_GET['recibo'];
 $dat_nit = $nit->consultar($_SESSION['nit_imp']);
 $datos_nit = mssql_fetch_array($dat_nit);
 
 $nom_concepto = $concepto->getcon_nombre($conce);
 
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
$pdf->Cell(28,6,$conse_recibo);

$pdf->SetY(38);//renglonesss
$pdf->SetX(27);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,date('d-m-Y')); 

$pdf->SetY(46);//renglonesss
$pdf->SetX(34);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,$datos_nit[nits_nombres]);

$pdf->SetY(46);//renglonesss
$pdf->SetX(132);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(58,6,"$".number_format($_SESSION['valor'],2));
$pdf->SetY(55);//renglonesss
$pdf->SetX(35);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,$datos_nit['nits_dir_residencia']);

$pdf->SetY(62);//renglonesss
$pdf->SetX(55);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,convertir($_SESSION['valor']));

$pdf->SetY(71);//renglonesss
$pdf->SetX(40);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(28,6,$nom_concepto);

$dat_concepto = split('-',$_SESSION['cue_concep'][0],3);

$pdf->SetY(93);//renglonesss
$pdf->SetX(20);//filas ->
$pdf->SetFont('arial','',7);
$pdf->Cell(28,6,$dat_concepto[0]);
$nom_cuenta = $cuenta->verificar_existe($dat_concepto[0]);
$dat_cuen = mssql_fetch_array($nom_cuenta);
$pdf->SetY(93);//renglonesss
$pdf->SetX(50);//filas ->
$pdf->SetFont('arial','',7);
$pdf->Cell(28,6,$dat_cuen['cue_nombre']);
if($dat_concepto[1]==1)
{
	$pdf->SetY(88);//renglonesss
	$pdf->SetX(155);//filas ->
	$pdf->SetFont('arial','',7);
	$pdf->Cell(28,6,"$".number_format($dat_concepto[2],2));
}
else
{
	$pdf->SetY(88);//renglonesss
	$pdf->SetX(115);//filas ->
	$pdf->SetFont('arial','',7);
	$pdf->Cell(28,6,"$".number_format($dat_concepto[2],2));	
}

$dat_concepto = split('-',$_SESSION['cue_concep'][1],3);
$pdf->SetY(88);//renglonesss
$pdf->SetX(20);//filas ->
$pdf->SetFont('arial','',7);
$pdf->Cell(28,6,$dat_concepto[0]);
$nom_cuenta = $cuenta->verificar_existe($dat_concepto[0]);
$dat_cuen = mssql_fetch_array($nom_cuenta);
$pdf->SetY(88);//renglonesss
$pdf->SetX(50);//filas ->
$pdf->SetFont('arial','',7);
$pdf->Cell(28,6,$dat_cuen['cue_nombre']);
if($dat_concepto[1]==1)
{
	$pdf->SetY(93);//renglonesss
	$pdf->SetX(155);//filas ->
	$pdf->SetFont('arial','',7);
	$pdf->Cell(28,6,"$".number_format($dat_concepto[2],2));
}
else
{
	$pdf->SetY(93);//renglonesss
	$pdf->SetX(115);//filas ->
	$pdf->SetFont('arial','',7);
	$pdf->Cell(28,6,"$".number_format($dat_concepto[2],2));
	
	
}
for($i=1;$i<=$_SESSION["cuentas"];$i++)
{
	if($_SESSION['cue_descuentos'][$i]!="")
	{
		$j=$i+2;
		$temp = "";
		$dat_concepto = split('-',$_SESSION['cue_descuentos'][$i],3);
		$pdf->SetY(93+($j*5));//renglonesss
		$pdf->SetX(20);//filas ->
		$pdf->SetFont('arial','',7);
		$pdf->Cell(28,6,$dat_concepto[0]);
		if($dat_concepto[0]!="" && $dat_concepto[0]!=$temp)
		{
			$temp = $dat_concepto[0];
			$nom_cuenta = $cuenta->verificar_existe($dat_concepto[0]);
			$dat_cuen = mssql_fetch_array($nom_cuenta);
			$pdf->SetY(93+($j*5));//renglonesss
			$pdf->SetX(50);//filas ->
			$pdf->SetFont('arial','',7);
			$pdf->Cell(28,6,$dat_cuen['cue_nombre']);
		}
		if($dat_concepto[1]==1||$dat_concepto[1]==2)
		{
			$pdf->SetY(93+($j*5));//renglonesss
			$pdf->SetX(116);//filas ->
			$pdf->SetFont('arial','',7);
			$pdf->Cell(27,1,"$".number_format($dat_concepto[2],2));
		}
		else
		{
			$pdf->SetY(93+($j*5));//renglonesss
			$pdf->SetX(116);//filas ->
			$pdf->SetFont('arial','',7);
			$pdf->Cell(27,1,"$".number_format($dat_concepto[2],2));
		}
	}
}

//LIMPIAR SESSIONES//
unset($_SESSION['persona_id']);unset($_SESSION['cre_consecutivo']);unset($_SESSION['nombres']);unset($_SESSION['direccion']);unset($_SESSION['credito']);unset($_SESSION['cuen_credito']);unset($_SESSION['mes']);unset($_SESSION['conc']);unset($_SESSION['val_pagar']);unset($_SESSION['monto']);unset($_SESSION['des_impuestos']);unset($_SESSION['consecutivo']);
unset($_SESSION['nit']);unset($_SESSION['nit_imp']);unset($_SESSION['cen_costo']);unset($_SESSION['total_fac']);
unset($_SESSION['abono']);unset($_SESSION['valor_recibo']);unset($_SESSION['fecha_recibo']);unset($_SESSION['nota_recibo']);
unset($_SESSION['concepto']);unset($_SESSION['nota_recibo']);unset($_SESSION['concepto']);unset($_SESSION['glosa_aceptada']);unset($_SESSION['administracion']);unset($_SESSION['glosa_pendi']);unset($_SESSION['descuento']);unset($_SESSION['impuesto']);unset($_SESSION['retencion']);unset($_SESSION['pro_hospital']);unset($_SESSION['ica']);unset($_SESSION['pro_desarrollo']);unset($_SESSION['otros_descuentos']);unset($_SESSION['neto']);unset($_SESSION['cen_costo']);

/////////////////////
$pdf->Output();
?>