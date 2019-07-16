<?php
define('FPDF_FONTPATH', '/font/'); 
// Requerim el fitxer amb les definicions de la classe pdf 
 
require('../pdf/fpdf.php'); 

// Estenem les possibilitats de la classe original (fpdf) 
// en una de nova 
class PDF extends FPDF 
{ 
//Capçalera amb logo 
function Header() 
{ 
//logo 
$this->Image('Imatges/logoescola.jpg',10,10,40); 
$this->SetFont('Arial','B',16); 
$this->Text(125,20,'ENTREVISTES TUTORIA'); 
$this->SetFont('Arial','I',12); 
$this->Text(250,15,'REF:'); 
$this->Text(250,25,'REV:'); 
$this->Ln(10); 
} 
//Peu de pàgina 
function Footer() 
{ 
$this->SetY(-10); 
$this->SetFont('Arial','',8); 
//número de pàgina 
$this->PageNo(); 
} 
} 
$pdf = new PDF('L','mm','A5'); 
$pdf->Open(); 
$pdf->AddPage(); 
$sql="SELECT * FROM va_entrevista WHERE idalumne='39'"; 
$entre = mysql_query($sql); 
$dataent = $dataent; 
$solicita = $solicita; 
$assistents = $assistents; 
$temes= $temes; 
$pdf->SetFont('Arial','BI',12); 
$pdf->SetFillColor(204,204,204); 
$pdf->Ln(5); 
$pdf->Cell(80,5,'DATA: '.$dataent,0,0,'L',1); 
$pdf->SetFont('Arial','I',10); 
$pdf->Ln(3); 
$pdf->Cell(40,30,'SOL·LICITA: '.$solicita); 
$pdf->Ln(5); 
$pdf->Cell(40,30,'ASSITENTS: '.$assistents); 
$pdf->Ln(5); 
$pdf->Cell(40,30,'TEMES: '.$temes); 
$pdf->Ln(5); 
$pdf->Output();
?>