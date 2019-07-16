<?php
 session_start();
 include_once('../clases/credito.class.php');
 include_once('../clases/nits.class.php');
 include_once('../clases/saldos.class.php');
 include_once('../clases/moviminetos_contables.class.php');
 $ano = $_SESSION['elaniocontable'];
 $ins_credito=new credito();
 $ins_nit=new nits();
 $ins_saldo =new saldos();
 $ins_mov_contable=new movimientos_contables();

 $fecha=date('d-m-Y');//$_POST['fecha'];
 $mes=date('m');
 $total_saldos=0;
 $creditos=$ins_credito->estCuenta($_POST['nit']);
 $dat_nit=mssql_fetch_array($ins_nit->consultar($_POST['nit']));
 //$_POST['mes_inicial'],$_POST['mes_final'],$_POST['ano_contable']
 
 //$cue1=$ins_mov_contable->saldo_cuenta(31400101,$_POST['nit'],$_POST['nit']);
 //$cue2=$ins_mov_contable->saldo_cuenta(23803009,$_POST['nit'],$_POST['nit']);
 
 $cue1=$ins_mov_contable->saldo_cuenta_nit(31400101,$ano,$mes,$_POST['nit']);
 $cue2=$ins_mov_contable->saldo_cuenta_nit(23803009,$ano,$mes,$_POST['nit']);

 
 $con_dat_cen_cos_ciudad=$ins_credito->ConsultarNucleoYCiudadEstadoCuenta($_POST['nit']);
 $res_dat_cen_cos_ciudad=mssql_fetch_array($con_dat_cen_cos_ciudad);
 
 //echo "datos: ".$cue1."___".$cue2."<br>";
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
$this->Image("../imagenes/reportes/estado_cuenta.jpg",0,0,211,'C');
//Arial bold 15 
$this->SetFont('Arial','B',15); 
$this->Ln(10); 
}
//Pie de p�gina
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

//N�mero de p�gina 
$pdf->addInfo($datacreator);//muestra los datos del creador en pro piedades del documento

$pdf=new PDF();
$pdf->AddPage();//orientacion y tama�o hoja
$pdf->SetFont('Arial','B',12);
$pdf->Text(123,63,$fecha); 
///////////////////////////////Datos Nit////////////////////////////////

$pdf->SetFont('Arial','B',9);
$pdf->Text(155,74,$dat_nit['nits_num_documento']); 
$pdf->Text(30,74,$dat_nit['nits_nombres']." ".$dat_nit['nits_apellidos']);

$pdf->Text(30,82,$res_dat_cen_cos_ciudad['cen_cos_nombre']);
 
$pdf->Text(30,89,$dat_nit['nit_fec_afiliacion']);

$pdf->Text(155,89,$res_dat_cen_cos_ciudad['ciu_nombre']);

/////////////////////////////////////////////////////////
/////////////////////////////Creditos////////////////////
$pdf->SetFont('Arial','',6);
$cont=1;
while($row=mssql_fetch_array($creditos))
{
	if($row['saldo']>0)
	{
		$can_cuo_descontadas=$ins_credito->CantidadCuotasDescontadasCredito($row['cre_id'],3);
		
		$pdf->SetY(100+($cont*4));$pdf->SetX(15);$pdf->Cell(1,1,$row['cre_id'],0,0,"R");
		$pdf->SetY(100+($cont*4));$pdf->SetX(20);$pdf->Cell(1,1,$row['con_nombre'],0,0,"L");
		$pdf->SetY(100+($cont*4));$pdf->SetX(52);$pdf->Cell(1,1,$row['cre_observacion'],0,0,"L");
		$pdf->SetY(100+($cont*4));$pdf->SetX(91);$pdf->Cell(1,1,$row['cre_fec_solicitud'],0,0,"R");
		$pdf->SetY(100+($cont*4));$pdf->SetX(111);$pdf->Cell(1,1,number_format($row['cre_valor']),0,0,"R");
		$pdf->SetY(100+($cont*4));$pdf->SetX(131);$pdf->Cell(1,1,$can_cuo_descontadas." De ".$row['cre_num_cuotas'],0,0,"R");
		$pdf->SetY(100+($cont*4));$pdf->SetX(165);$pdf->Cell(1,1,number_format($row['capital']),0,0,"R");
		$pdf->SetY(100+($cont*4));$pdf->SetX(195);$pdf->Cell(1,1,number_format($row['saldo']),0,0,"R");
		$saldo_estado+=$row['saldo'];
		$cont++;
		if($cont>=30)
	  	{
			$pdf->AddPage();
			$pdf->SetFont('Arial','',6);
			$cont=1;
	  	}
	}
}
$pdf->SetFont('Arial','B',8);
$pdf->SetY(100+($cont*4));$pdf->SetX(195);$pdf->Cell(1,1,"Total Saldos: _______________________",0,0,"R");$cont++;
$pdf->SetY(100+($cont*4));$pdf->SetX(195);$pdf->Cell(1,1,number_format($saldo_estado),0,0,"R");
///////////////////////////////////////////////////////////////////////////
//////////////////////////////////Datos saldos y fondo/////////////////////
//echo $cue1."___".$cue2."___".$saldo_estado;
$pdf->SetY(265);$pdf->SetX(70);$pdf->Cell(1,1,number_format($cue1+$cue2),0,0,"R");
$pdf->SetY(275);$pdf->SetX(130);$pdf->Cell(1,1,number_format(($cue1+$cue2)-$saldo_estado),0,0,"R");
$pdf->SetY(275);$pdf->SetX(190);$pdf->Cell(1,1,"CON CODEUDOR",0,0,"R");

$pdf->Output();
?>