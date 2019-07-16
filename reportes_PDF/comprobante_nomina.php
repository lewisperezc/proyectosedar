<?php 
 session_start();
 $ano = $_SESSION['elaniocontable'];
 include_once('../clases/nits.class.php');
 $nits = new nits();
 //$dat_cenAso = $nits->nit_centro($_SESSION['asociado'][0]);
 $datos = mssql_fetch_array($dat_cenAso);
 $centro = $datos['cc_nombre'];
 $fecha = $_SESSION['fecha'];
 $valor_total = $_SESSION['val_total'];

//buscamos en consecutivo y actualizamos la orden de desembolso
/////////////////////////////
define('../FPDF_FONTPATH','/font/');//Foncion de librerias para fuentes 
include('../pdf/class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../pdf/fonts/courier.afm');
require('../pdf/fpdf.php');
$datacreator = array (
					'Title'=>'Ejemplo PDF',
					'Author'=>'@nestecoop',
					'Subject'=>'PDF con Tablas',
					'Creator'=>'@nestecoop',
					'Producer'=>'http://'
					);
					
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tama�o,ubicacion)
//$this->Image("imagenes_efren/fondo_anestecoop6.jpg", 0, 0, 400, 'center');
//Arial bold 15 
$this->SetFont('Arial','B',15); 
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

//N�mero de p�gina 
$pdf->addInfo($datacreator);//muestra los datos del creador en pro piedades del documento

$pdf=new FPDF();
$pdf->AliasNbPages();
$pdf->SetFont('Times','',12);
/////
$pdf->AddPage('L','letter');//orientacion y tama�o hoja
$pdf->SetFont('Arial','B',9); 
$pdf->Text(83,17,"COOPERATIVA NACIONAL DE ANESTESIOLOGOS ANESTECOOP - 830.019.617-9"); 
//Salto de l�nea 

$pdf->SetFillColor(232,232,232);
// Principal 
$conse = $_SESSION['consecutivo'];         
$pdf->SetFont('Arial','B',9); 
$pdf->Text(80,23,"CAUSACION DE NOMINA $fecha--$centro--FACTURA: $conse"); 
    
$pdf->SetFont('Arial','B',7);
$pdf->Text(12,30,"Fecha:");
$pdf->SetFont('Arial','',7);
$pdf->Text(22,30,$fecha);

$pdf->SetFont('Arial','B',8); 
$pdf->Text(12,40,"DATOS FACTURACION"); 
$pdf->SetFont('Arial','',7); 
$pdf->Text(12,45,'VALOR FACTURADO');
$pdf->SetFont('Arial','B',8); 
$pdf->Text(55,40,'FACTURACION GENERAL');

$pdf->SetFont('Arial','B',8); 
$pdf->Text(59,45,$valor_total);
$pdf->Line(59,47,83,47);
$pdf->SetFont('Arial','B',8); 
$pdf->Text(59,51,$valor_total);

/*********** Datos del reporte de jornadas*************/
$pdf->SetFont('Arial','B',7); 
$pdf->Text(10,58,'Cedula');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(28,58,'Nombre');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(45,58,'Novedad');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(60,58,'Valor Teorico');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(80,58,'Fondo Social');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(100,58,'Aportes Sociales');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(125,58,'Fondo Vacaciones');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(150,58,'Seguridad Social');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(175,58,'Admon Basica');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(195,58,'Ingreso Base');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(215,58,'Ordinaria');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(230,58,'Extra-ordinaria');
$pdf->SetFont('Arial','B',7); 
$pdf->Text(250,58,'Honorarios');
$pdf->Line(10,60,275,60);
/////////////////////////////////////////////////////////

/**************Descripcion de los Asociados segun el cc************************/

$pdf->Output();
?>