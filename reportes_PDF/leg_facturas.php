<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/varios.class.php');
include_once('../clases/mes_contable.class.php');

require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
$nit = new nits();
$varios=new varios();
$mes_contable = new mes_contable();

$pdf =& new Cezpdf('a4');
//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/leg_factura.jpg",0,0,300,'C');
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
$pdf->AddPage('L');
$i=1;$p=0;
$pdf->SetFont('Arial','B',8);
$fec_dia = date('d-m-Y');

$sql_rep = "SELECT SUM(d.des_monto) valor,rc.rec_caj_id,f.fac_consecutivo,m.mes_nombre,n.nits_num_documento,
			n.nits_nombres,n.nits_cor_electronico FROM factura f 
			INNER JOIN recibo_caja rc ON f.fac_id=rc.rec_caj_factura
			INNER JOIN descuentos d ON d.des_factura=rc.rec_caj_id
			INNER JOIN nits n ON n.nit_id=f.fac_nit
			INNER JOIN mes_contable m ON m.mes_id=f.fac_mes_servicio
			GROUP BY n.nits_num_documento,n.nits_nombres,n.nits_cor_electronico,f.fac_consecutivo,
			m.mes_nombre,rc.rec_caj_id";
$query_rep = mssql_query($sql_rep);
$num_rows = mssql_num_rows($query_rep);
while($row=mssql_fetch_array($query_rep))
{
	$pdf->SetY(35+($i*4));$pdf->SetX(5);$pdf->Cell(21,8,$row['nits_num_documento']);
	$pdf->SetY(35+($i*4));$pdf->SetX(25);$pdf->Cell(21,8,$row['nits_nombres']);
	$pdf->SetY(35+($i*4));$pdf->SetX(125);$pdf->Cell(21,8,$row['fac_consecutivo']);
	
	$pdf->SetY(35+($i*4));$pdf->SetX(178);$pdf->Cell(21,8,$row['mes_nombre']);
	$pdf->SetY(35+($i*4));$pdf->SetX(245);$pdf->Cell(21,8,number_format((float)$row['valor'],0));
	$i++;
	if($i==36)
	{
		$i=1;
		$pdf->AddPage('L');
	}
}
$pdf->Output();
?>