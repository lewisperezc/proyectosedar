<?php 
session_start();

include_once("../conexion/conexion.php");
$dato = $_GET['sigla'];
$mes = $_GET['mes'];


$sql = "SELECT mc.mov_cuent,c.cue_nombre,mc.mov_nit_tercero,cc.cen_cos_nombre,mc.mov_valor,mc.mov_tipo,
		mc.mov_nume,n.nits_nombres,n.nits_apellidos,n.nits_num_documento
        FROM movimientos_contables mc
        INNER JOIN cuentas c on c.cue_id = mc.mov_cuent LEFT JOIN nits n on
        cast(n.nit_id as varchar(20)) = mc.mov_nit_tercero
        INNER JOIN centros_costo cc on mc.mov_cent_costo=cc.cen_cos_id
        WHERE mov_compro='$dato' and mov_mes_contable=$mes AND mov_ano_contable='$_SESSION[elaniocontable]'";
//echo $sql."<br>";
$query = mssql_query($sql);

$con_fec_documento="SELECT DISTINCT * FROM transacciones WHERE trans_sigla='$dato' AND tran_mes_contable='$mes'
AND trans_ano_contable='$_SESSION[elaniocontable]' ORDER BY trans_id DESC";
//echo $con_fec_documento;
$eje_fec_documento=mssql_query($con_fec_documento);
$res_fec_documento=mssql_fetch_array($eje_fec_documento);

require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');

//include("../comunes/libreria_generales.php"); 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/logo_sedar_original.jpg", 10, 10, 55, 'left');
//$this->Image("../imagenes/anestecoop.jpg", 20, 10, 35, 'left');//("",renglones,filas,tamaño,ubicacion)
//Arial bold 15 
$this->SetFont('Arial','B',8); 
$this->SetFont('Arial','B',8);
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
$this->SetFont('Arial','I',8); 
//Número de página 
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresion '.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 


$pdf=new PDF(); 
//Tipo y tamaño de lertra 
$pdf->SetFont('Arial','B',8); 
$pdf->AddPage();

//$pdf->SetY(15);$pdf->SetX(65);$pdf->Cell(21,8,"CAUSACIONES");
$pdf->SetFont('Arial','B',8);
$pdf->SetY(31);$pdf->SetX(95);$pdf->Cell(21,8,"Consecutivo: ".$dato);
$pdf->SetY(35);$pdf->SetX(88);$pdf->Cell(21,8,"Centro de Costo:");

setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
$pdf->SetFont('Arial','B',8);$pdf->SetY(50);$pdf->SetX(20);$pdf->Cell(20,8,"Fecha:");

$pdf->SetFont('Arial','B',8);$pdf->SetY(50);$pdf->SetX(50);$pdf->Cell(20,8,$res_fec_documento['trans_fec_doc']);

//$pdf->SetFont('Arial','B',12);$pdf->SetY(50);$pdf->SetX(50);$pdf->Cell(20,8,strftime("%A %d de %B del %Y"));
$pdf->SetY(55);$pdf->SetX(20);$pdf->Cell(20,8,"Proveedor:");

$pdf->SetY(65);$pdf->SetX(20);$pdf->Cell(20,8,"Codigo");
$pdf->SetY(65);$pdf->SetX(45);$pdf->Cell(20,8,"Cuentas");
$pdf->SetY(65);$pdf->SetX(100);$pdf->Cell(20,8,"Nit");
$pdf->SetY(65);$pdf->SetX(125);$pdf->Cell(20,8,"Pagare");
$pdf->SetY(65);$pdf->SetX(150);$pdf->Cell(20,8,"Debito");
$pdf->SetY(65);$pdf->SetX(180);$pdf->Cell(20,8,"Credito");
$i=5;
$p=0;
$debito = 0;
$credito =0;
$pdf->SetFont('Arial','B',8); 
while($row = mssql_fetch_array($query))
{
	if($p==0)
	{
	   $pdf->SetY(55);$pdf->SetX(50);$pdf->Cell(20,8,$row['nits_nombres']." ".$row['nits_apellidos']);
	   $pdf->SetY(35);$pdf->SetX(118);$pdf->Cell(21,8,$row['cen_cos_nombre']);
	   $p++;
	}
	$pdf->SetY(65+$i);$pdf->SetX(20);$pdf->Cell(20,8,$row['mov_cuent']);
	$pdf->SetY(65+$i);$pdf->SetX(45);$pdf->Cell(20,8,substr($row['cue_nombre'],0,26));
	$pdf->SetY(65+$i);$pdf->SetX(100);$pdf->Cell(20,8,$row['nits_num_documento']);
	$pdf->SetY(65+$i);$pdf->SetX(125);$pdf->Cell(20,8,$row['mov_nume']);
	if($row['mov_tipo']==1)//debito
	{
		$pdf->SetY(65+$i);$pdf->SetX(150);$pdf->Cell(20,8,number_format($row['mov_valor'],0));
		$pdf->SetY(65+$i);$pdf->SetX(180);$pdf->Cell(20,8,0);
		$debito+=$row['mov_valor'];
	}
	else //credito
	{
		$pdf->SetY(65+$i);$pdf->SetX(180);$pdf->Cell(20,8,number_format($row['mov_valor'],0));
		$pdf->SetY(65+$i);$pdf->SetX(150);$pdf->Cell(20,8,0);
		$credito+=$row['mov_valor'];
	}
	$i = $i+5;
	if($i>=190)
	{
		$i=1;
		$pdf->AddPage();
	}
}

$i = $i+5;
$pdf->SetY(65+$i);$pdf->SetX(130);$pdf->Cell(20,8,"Totales");
$pdf->SetY(65+$i);$pdf->SetX(150);$pdf->Cell(20,8,number_format($debito,0));
$pdf->SetY(65+$i);$pdf->SetX(180);$pdf->Cell(20,8,number_format($credito,0));

$pdf->Output(); 
?>