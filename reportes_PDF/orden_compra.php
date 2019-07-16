<?php 
 session_start();
 $ano = $_SESSION['elaniocontable'];
 $_SESSION["centro"];
 $_SESSION["proveedor"];
 $_SESSION["cantidad"];
 $_SESSION["refe"];
 $_SESSION["tip_pro"];
 $_SESSION["produc"];
 $_SESSION["cantidad"];
 $_SESSION["valor"];
 $_SESSION["cant"];
 $_SESSION['iva'];

 include_once('../clases/orden_compra.class.php');
 include_once('../clases/nits.class.php');
 include_once('../clases/bancos.class.php');
 $orden = new orden_compra();
 $nit = new nits();
 $banco = new bancos();
 $consecutivo = $orden->obt_consecutivo();
 $dat_prove = $nit->consul_nits($_SESSION["proveedor"]);
 $datos_proveedor = mssql_fetch_array($dat_prove);
//buscamos en consecutivo y actualizamos la orden de desembolso
/////////////////////////////
define('../FPDF_FONTPATH','/font/');//Foncion de librerias para fuentes 
include('../pdf/class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../pdf/fonts/courier.afm');
require('../pdf/fpdf.php');					
class PDF extends FPDF 
{ 
//Cabecera de p�gina 
	function Header() 
	{ 
		$this->Image("../imagenes/reportes/ord_compra.jpg",0,0,200,'C');
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
}

$pdf=new PDF(); 
$pdf->AddPage();
$pdf->SetFont('Arial','B',25); 
//Datos proveedor
$pdf->SetY(60);$pdf->SetX(150);
$pdf->Cell(21,8,$consecutivo);
$pdf->SetFont('Arial','B',12); 
$pdf->SetY(53);$pdf->SetX(40);
$pdf->Cell(21,8,"Bogot�");

$pdf->SetY(64);$pdf->SetX(40);
$pdf->Cell(21,8,date('d-m-Y'));
//datos Proveedor
$pdf->SetY(82);$pdf->SetX(40);
$pdf->Cell(21,8,$datos_proveedor['nits_nombres']." ".$datos_proveedor['nits_apellidos']);
$pdf->SetY(82);$pdf->SetX(140);
$pdf->Cell(21,8,$datos_proveedor['nits_num_documento']);
$pdf->SetY(93);$pdf->SetX(40);
$pdf->Cell(21,8,$datos_proveedor['nits_dir_residencia']);
$pdf->SetY(93);$pdf->SetX(140);
$pdf->Cell(21,8,$datos_proveedor['nits_tel_residencia']);
$pdf->SetY(105);$pdf->SetX(40);
$pdf->Cell(21,8,$datos_proveedor['nits_contacto']);
$pdf->SetY(105);$pdf->SetX(140);
$pdf->Cell(21,8,$datos_proveedor['nits_tel_residencia']);

//Banco
if($datos_proveedor['nits_ban_id'])
{
	$pdf->SetY(145);$pdf->SetX(40);
	$dat_ban = $banco->datBancos($datos_proveedor['nits_ban_id']);
	$dat_bancos = mssql_fetch_array($dat_ban);
	$pdf->Cell(21,8,$dat_bancos['banco']."2");


	$pdf->SetY(151);$pdf->SetX(40);
	if($datos_proveedor['tip_cue_ban_id']==1)
		$pdf->Cell(21,8,'Ahorros');
	elseif($datos_proveedor['tip_cue_ban_id']==2)
   		$pdf->Cell(21,8,'Corriente');
}

for($i=0;$i<sizeof($_SESSION["produc"]);$i++)
{
	$pdf->SetFont('Arial','B',7);
	$pdf->SetY(173+($i*5));$pdf->SetX(15);
	$pdf->Cell(15,8,$_SESSION["refe"][$i]);
	$pdf->Line(15.3,174+($i*5),15.3,180+($i*5));
	$pdf->Cell(47,8,$_SESSION["produc"][$i]);
	$pdf->Line(29.5,174+($i*5),29.5,180+($i*5));
	$pdf->Cell(18,8,'1');
	$pdf->Line(76.4,174+($i*5),76.4,180+($i*5));
	$pdf->Cell(18,8,$_SESSION["cantidad"][$i]);
	$pdf->Line(93.9,174+($i*5),93.9,180+($i*5));
	$pdf->Cell(15,8,$_SESSION["valor"][$i]);
	$pdf->Line(111.4,174+($i*5),111.4,180+($i*5));
	$pdf->Cell(20,8,$_SESSION['iva'][$i]);
	$pdf->Line(128.4,174+($i*5),128.4,180+($i*5));
	$pdf->Cell(21,8,$_SESSION["cantidad"][$i]*$_SESSION["valor"][$i]);
	$pdf->Line(145.5,174+($i+5),145.5,180+($i*5));
	$pdf->Line(187.6,174+($i*5),187.6,180+($i*5));
	$pdf->Line(15.5,180+($i*5),187.5,180+($i*5));
}
$pdf->Output();

?>