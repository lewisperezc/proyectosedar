<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
$_SESSION['fecha'];
$_SESSION['dat_contrato'];
$_SESSION['nits_nombres'];
$_SESSION['nits_num_documento'];
$_SESSION['nits_dir_residencia'];
$_SESSION['nits_tel_residencia'];
$telefono =$_SESSION['nits_tel_residencia'];
$_SESSION['fecha']=date("d/m/Y");//fecha del sistema de lservidar
$_SESSION['descripcion'];
$_SESSION['val_unitario'];
$_SESSION['val_total']; 
$_SESSION['consecutivo'];
$_SESSION["mes_contable"];
$_SESSION['dias'];
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
$this->Image("../imagenes/fondo_anestecoop6.jpg", 0, 0, 400, 'center');
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
$pdf->SetFont('Times','',7); 
$pdf->AddPage(); 
$condicion_final= $_SESSION['condicion']; 
// Las sesiones se pierden si el usuario cierra el navegador o si se 
// destruye la sesión desde el servidor: 
$datacreator = array (
					'Title'=>'Ejemplo PDF',
					'Author'=>'@nestecoop',
					'Subject'=>'PDF con Tablas',
					'Creator'=>'@nestecoop',
					'Producer'=>'http://'
					); 
////////titulo de la factura
  ////////////////////////////////////////////////prueba para linea final/
  //$pdf->SetY(270);//renglonesss
  //$pdf->SetX(10);//filas ->
  //$pdf->SetFont('arial','',10);
  //$pdf->Cell(18,6,'|------------------------------------------------------------------------------------------------------------------------------------------------------------- ','L');
  
///////////////////////////////////////fiN///////////
///////////titulo o encabezado de la facturaaaaa
/////esto va en el segundo campo del emcabezado
$pdf->SetY(20);//renglonesss
$pdf->SetX(92);//filas ->
 $pdf->SetFont('arial','',12);
$pdf->Cell(18,6,'NIT. 830.019.617-9');
$pdf->SetY(24);//renglonesss
$pdf->SetX(69);//filas ->
 $pdf->SetFont('arial','',12);
$pdf->Cell(18,6,'RESOLUCION DIAN: 310000042884');
$pdf->SetY(28);//renglonesss
$pdf->SetX(92);//filas ->
 $pdf->SetFont('arial','',12);
$pdf->Cell(18,6,'Fecha: 2009/12/020');
///////////////
////////esta va en el tercer campo del encabezado de la factura						
$pdf->SetY(20);
$pdf->SetX(142);
$pdf->Cell(18,6,'FACTURA DE VENTA No');

$pdf->SetY(24);
$pdf->SetX(145);
$pdf->Cell(18,6,'FAC-'.$_SESSION['consecutivo']);

//////////////////////////fin titulo o encabezadooo
/////////arganizando para ubica en los campos estaticos del primer renglon la factura
$pdf->SetY(50);//renglonesss
  $pdf->SetX(18);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'FECHA'); 
//////////////////
$pdf->SetY(50);//renglonesss
  $pdf->SetX(62);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'CIUDAD'); 
//////////////////
$pdf->SetY(50);//renglonesss
  $pdf->SetX(120);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'NIT'); 
  //////////////////
$pdf->SetY(50);//renglonesss
  $pdf->SetX(160);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'TELEFONO'); 
  
  //////////////////////////////////////////////////////FIN DEL 1///////////// 
//////////////////////////////////////////////////////////////////////////////////////////////
/////////aca va los datos a cambiar en  segundo renglon de la fatura
$pdf->SetY(57);//renglonesss
  $pdf->SetX(12);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,$_SESSION['fecha']); 
//////////////////////////////////////////
$pdf->SetY(57);//renglonesss
  $pdf->SetX(43);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,$_SESSION['dat_contrato']); 
 //////////////////
$pdf->SetY(57);//renglonesss
  $pdf->SetX(110);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,$_SESSION['nits_num_documento']);
//////////////////
$pdf->SetY(57);//renglonesss
  $pdf->SetX(140);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,$_SESSION['nits_tel_residencia']);
 //////////////////////////////////////////////////////FIN DEL 2///////////// 
/////////arganizando para ubica en los campos estaticos del segundo renglon la factura
$pdf->SetY(63);//renglonesss
  $pdf->SetX(25);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'DIRECCION'); 
 //////////////////
$pdf->SetY(63);//renglonesss
  $pdf->SetX(93);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'CLIENTE'); 
  $pdf->SetY(63);$pdf->SetX(73);$pdf->Cell(18,6,'');
  //////////////////
$pdf->SetY(63);//renglonesss
  $pdf->SetX(1500);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'OTROS DATOS4');
  //////////////////////////////////////////////////////FIN DEL 3///////////// 
  /////////arganizando para ubicar los datos cambiantes en el segundo reglon 
$pdf->SetY(69);//renglonesss
  $pdf->SetX(12);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,$_SESSION['nits_dir_residencia']);

$pdf->SetY(69);//renglonesss
  $pdf->SetX(150);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,$_SESSION['dias']);
   
//////////////////
$pdf->SetY(69);//renglonesss
  $pdf->SetX(73);//filas ->
  $pdf->SetFont('arial','',10);
   $pdf->Cell(18,6,$_SESSION['nits_nombres']); 
//////////////////
$pdf->SetY(69);//renglonesss
  $pdf->SetX(140);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'');
/////////////////////////////////////////////////////// 
  /////////arganizando para ubica en los campos estaticos de la descripcion del a factura
$pdf->SetY(80);//renglonesss
  $pdf->SetX(20);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'DESCRIPCION'); 
//////////////////
$pdf->SetY(80);//renglonesss
  $pdf->SetX(93);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'VALOR UNITARIO'); 
//////////////////
 $pdf->SetY(80);//renglonesss
  $pdf->SetX(160);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'VALOR TOTAL'); 
 ////////////////////////////////////////////////////////////////// 
 /////////arganizando para ubica en los campos de la descripcion de la factura

$pdf->SetY(86);//renglonesss
  $pdf->SetX(13);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,$_SESSION['descripcion']); 
//////////////////
$pdf->SetY(86);//renglonesss
  $pdf->SetX(74);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'$'.'  '.$_SESSION['val_unitario']); 

//////////////////
$pdf->SetY(86);//renglonesss
  $pdf->SetX(141
  );//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'$'.'  '.$_SESSION['val_total']); 
  
  /////////////////////////////////////////////////////////////////// 
 /////////aca va los datos fijos del los valores de la factura 
$pdf->SetY(262);//renglonesss
  $pdf->SetX(80);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'TOTAL FACTURA'); 
//////////////////
$pdf->SetY(266);//renglonesss
  $pdf->SetX(80);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'IVA RETENCION'); 
//////////////////
$pdf->SetY(270);//renglonesss
  $pdf->SetX(80);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'VALOR TOTAL FACTURA'); 
//////////////////////////////////////////////////////FIN ////////// 
 /////////arganizando para ubica en los campos dinamicos de los valores de la factura
 /////
 // aca se coloca las operaciones de los valores de la factura
 ////
$pdf->SetY(262);//renglonesss
  $pdf->SetX(141);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'$'.'  '.$_SESSION['val_total']); 
//////////////////
$pdf->SetY(266);//renglonesss
  $pdf->SetX(141);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'$'); 

//////////////////
$pdf->SetY(270);//renglonesss
  $pdf->SetX(141);//filas ->
  $pdf->SetFont('arial','',10);
  $pdf->Cell(18,6,'$'.'  '.$_SESSION['val_total']); 
  
  //////////////////////////////////////////////////////FIN DEL  LA FACTURA/////////////

$pdf->Output(); 
?>
