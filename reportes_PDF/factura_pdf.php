<?php
   session_start();
include_once('../clases/numerosALetras.class.php');
$ano = $_SESSION['elaniocontable'];
$_SESSION['fecha'];
$_SESSION['dat_contrato'];
$_SESSION['nits_nombres'];
$_SESSION['nits_num_documento'];
$_SESSION['nits_dir_residencia'];
$_SESSION['nits_tel_residencia'];
$telefono =$_SESSION['nits_tel_residencia'];
//$_SESSION['fecha']=date("d/m/Y");//fecha del sistema de lservidar
$_SESSION['consecutivo'];
$_SESSION["mes_contable"];
$_SESSION['dias'];
$_SESSION['ciudad'];

function suma_fechas_dias($fecha,$ndias)        
{
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$ao)=split("/", $fecha);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
              list($dia,$mes,$ao)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$ao) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d-m-Y",$nueva);
      return ($nuevafecha);  
}

require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tamaño,ubicacion)
$this->Image("../imagenes/reportes/fac_anes3.jpg",0,0,200,'C');
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

$pdf->SetY(15);
$pdf->SetX(190);
$pdf->Cell(21,8,$_SESSION['consecutivo']);
$pdf->SetY(20);//renglonesss
$pdf->SetX(27);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(18,6,$_SESSION['fecha']); 
$pdf->SetY(20);//renglonesss
$pdf->SetX(100);//filas ->
$pdf->Cell(18,6,suma_fechas_dias($_SESSION['fecha'],$_SESSION['dias']));
$pdf->SetY(27);//renglonesss
$pdf->SetX(150);//filas ->
$pdf->Cell(18,6,$_SESSION['nits_num_documento']);
$pdf->SetY(20);//renglonesss
$pdf->SetX(160);//filas ->
$pdf->Cell(18,6,$_SESSION['ciudad']);
$pdf->SetY(27);//renglonesss
$pdf->SetX(25);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(18,6,$_SESSION['nits_nombres']); 
$pdf->SetY(35);//renglonesss
$pdf->SetX(25);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(18,6,$_SESSION['nits_dir_residencia']);
$pdf->SetY(34);//renglonesss
$pdf->SetX(155);//filas ->
$pdf->SetFont('arial','',12);
$pdf->Cell(18,6,$_SESSION['nits_tel_residencia']);
$pdf->SetY(52);//renglonesss

$conmayusculas=strtoupper($_SESSION['descripcion']);

$conmayusculas=strtoupper($_SESSION['descripcion']);
$eltamanio=strlen($conmayusculas);
$i=0;
$aumenta=2;
//
$pdf->MultiCell(120,5,$conmayusculas);


//////////////////
$pdf->SetY(50);//renglonesss
$pdf->SetX(134);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(18,6,'$'.'  '.number_format($_SESSION['val_unitario'],2)); 

//////////////////
$pdf->SetY(50);//renglonesss
$pdf->SetX(166);//filas ->
$pdf->SetFont('arial','',10);
$pdf->Cell(18,6,'$'.'  '.number_format($_SESSION['val_total'],2)); 

$pdf->SetY(105);//renglonesss
$pdf->SetX(5);//filas ->
$pdf->SetFont('arial','',4);
$pdf->Cell(18,6,convertir($_SESSION['val_total'])." PESOS M/L");

unset($_SESSION['fecha']);
unset($_SESSION['dat_contrato']);
unset($_SESSION['nits_nombres']);
unset($_SESSION['nits_num_documento']);
unset($_SESSION['nits_dir_residencia']);
unset($_SESSION['nits_tel_residencia']);
unset($_SESSION['nits_tel_residencia']);
unset($_SESSION['fecha']);//fecha del sistema del servidor
unset($_SESSION['descripcion']);
unset($_SESSION['val_unitario']);
unset($_SESSION['val_total']);
unset($_SESSION['consecutivo']);
unset($_SESSION["mes_contable"]);
unset($_SESSION['dias']);
unset($_SESSION['ciudad']);

$pdf->Output();
?>