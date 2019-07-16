<?php session_start();

include_once('../clases/nits.class.php');
include_once('../clases/contrato.class.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
//define('../FPDF_FONTPATH','/font/');//Foncion de librerias para fuentes 
//$pdf->selectFont('../pdf/fonts/courier.afm');
$pdf =& new Cezpdf('a4');

class PDF extends FPDF 
{ 
  //Cabecera de pï¿½gina 
  function Header() 
   {
	$this->SetFont('Arial','B',15); 
	$this->Ln(10); 
   }
}// fin de la clase

//Nï¿½mero de pï¿½gina	
$pdf=new FPDF();
$pdf->AliasNbPages();
$pdf->SetFont('Times','',12);
$pdf->AddPage();//orientacion y tamaï¿½o hoja

$ins_contrato=new contrato();
$nit = new nits();

$dat_hospital=$nit->dat_hospital($_GET['contrato_id'],1);

$datos_hospital=mssql_fetch_array($dat_hospital);
$fecha = date('d-m-Y');

//Linea borde hoja
$pdf->Line(10,15,200,15);
$pdf->Line(10,15,10,280); //Linea Horizontal 2
$pdf->Line(200,15,200,280);
$pdf->Line(10,280,200,280);
//Encabezado
$pdf->SetFont('Arial','',9);
$pdf->Image('../imagenes/logo_sedar_original.jpg',15,16,30,18);//15,16,20
$pdf->Line(10,35,200,35);
$pdf->Line(50,15,50,35);
$pdf->SetFont('Arial','B',8);
$pdf->Text(92,20,"SINOPSIS");
$pdf->SetFont('Arial','B',8);
$pdf->Text(85,25,"ADICIÓN U OTRO SI");
//$pdf->Text(65,30,"PRESTACIï¿½N DE SERVICIOS DE ANESTESIA");
$pdf->Line(140,15,140,35);
$pdf->SetFont('Arial','B',8);
$pdf->Text(140,20,"CODIFICACIÓN:");
$pdf->Text(140,30,"FECHA DE APROBACIÓN: ".$fecha);
//DATOS GENERALES DEL CONTRATO
$pdf->SetFont('Arial','B',8);
$pdf->Text(12,43,"CONTRATO No.: ");
$pdf->Line(40,39,80,39);
$pdf->Line(40,39,40,43);
$pdf->SetFont('Arial','B',8);

$valor=$datos_hospital['con_hos_consecutivo'];

$pdf->Text(45,42,$valor);
$pdf->Line(40,43,80,43);
$pdf->Line(80,39,80,43);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,43,"No. CONT/RENOV");
$pdf->Line(145,39,190,39);
$pdf->Line(145,39,145,43);
$pdf->Line(145,43,190,43);
$pdf->Line(190,39,190,43);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,53,"CIUDAD: ");
$pdf->Line(40,50,80,50);
$pdf->Line(40,50,40,54);
$pdf->SetFont('Arial','B',8);
$pdf->Text(50,53,$datos_hospital['ciu_nombre']);
$pdf->Line(40,54,80,54);
$pdf->Line(80,50,80,54);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,53,"DEPARTAMENTO: ");
$pdf->Line(145,50,190,50);
$pdf->Line(145,50,145,54);
$pdf->SetFont('Arial','B',8);
$pdf->Text(150,53,$datos_hospital['dep_nombre']);
$pdf->Line(145,54,190,54);
$pdf->Line(190,50,190,54);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,63,"CONTRATANTE: ");
$pdf->Line(40,60,150,60);
$pdf->Line(40,60,40,64);
$pdf->Text(50,63,$datos_hospital['nits_nombres']." ".$datos_hospital['nits_apellidos']);
$pdf->Line(40,64,150,64);
$pdf->Line(150,60,150,64);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,73,"NIT: ");
$pdf->Line(40,70,80,70);
$pdf->Line(40,70,40,74);
$pdf->Text(48,73,$datos_hospital['nits_num_documento']);
$pdf->Line(40,74,80,74);
$pdf->Line(80,70,80,74);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,73,"TIPO DE ENTIDAD: ");
$pdf->Line(145,70,190,70);
$pdf->Line(145,70,145,74);
if($datos_hospital['tip_con_nit_id']==1)
	$clase="PUBLICA";
else
	$clase="PRIVADA";
$pdf->Text(146,72,$clase);
$pdf->Line(145,74,190,74);
$pdf->Line(190,70,190,74);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,83,"REPREST. LEGAL ");
$pdf->Line(40,80,150,80);
$pdf->Line(40,80,40,84);
$pdf->Text(48,83,$datos_hospital['nits_representante']);
$pdf->Line(40,84,150,84);
$pdf->Line(150,80,150,84);

/////////////////////////////////////////////////////////////
//if()
$res_ult_adi_otrosi=$_GET['adi_otr_id'];
if(trim($res_ult_adi_otrosi)=="")
	$res_ult_adi_otrosi=$ins_contrato->con_ult_adi_otr_general();
$res_dat_adi_otrosi=$ins_contrato->ConDatAdiOtrSinopsis($res_ult_adi_otrosi);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,93,"DURACIÓN ");
$pdf->Line(40,90,80,90);
$pdf->Line(40,90,40,94);

$pdf->Text(55,93,$res_dat_adi_otrosi['adi_otr_meses']." MESES");
$pdf->Line(40,94,80,94);
$pdf->Line(80,90,80,94);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,100,"FECHA INICIO ");
$pdf->Line(40,100,80,100);
$pdf->Line(40,100,40,104);

$pdf->Text(50,103,$res_dat_adi_otrosi['adi_otr_fec_inicio']);
$pdf->Line(40,104,80,104);
$pdf->Line(80,100,80,104);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,103,"FECHA FIN");
$pdf->Line(145,100,190,100);
$pdf->Line(145,100,145,104);

$pdf->Text(155,103,$res_dat_adi_otrosi['adi_otr_fec_fin']);
$pdf->Line(145,104,190,104);
$pdf->Line(190,100,190,104);

$pdf->Line(10,110,200,110);
$pdf->SetFont('Arial','B',8);
$pdf->Text(12,115,"OBJETO");
$pdf->Line(50,110,50,120);
$pdf->SetFont('Arial','B',9);
$pdf->Text(80,115,$res_dat_adi_otrosi['adi_o_otr_nombre']." - ".$res_dat_adi_otrosi['tip_adi_nombre']);
$pdf->Line(10,120,200,120);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,133,"VALOR");
$pdf->Line(46,130,186,130);
$pdf->Line(46,130,46,134);
$pdf->Line(46,134,186,134);

$pdf->Text(55,133,number_format($res_dat_adi_otrosi['adi_otr_valor']));
$pdf->Line(86,130,86,134);
$pdf->Line(136,130,136,134);
$pdf->Line(186,130,186,134);

$con_pol_imp_contrato=$ins_contrato->ConDatPolImpAdiOtrSinopsis(122,$res_ult_adi_otrosi);

$con_pol_imp_contrato_informativa=$ins_contrato->consultar_poliza_o_impuesto_informativo_adicion_otrosi($res_ult_adi_otrosi);

$i=0;
$suma=0;

//DESCONTABLE
while($row = mssql_fetch_array($con_pol_imp_contrato))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;  
	$pdf->SetY(160+$j);$pdf->SetX(11);
	$pdf->Cell(12+$j,8,$row['con_nombre']);
	
	$pdf->SetY(160+$j);$pdf->SetX(80);
	$pdf->Cell(12+$j,8,"......................................................................................................");
	
	$pdf->SetY(160+$j);$pdf->SetX(170);
	if(trim($row['con_por_con_porcentaje'])=="")
		$row['con_por_con_porcentaje']=0;
	$pdf->Cell(12+$j,8,'$'.'  '.number_format($row['con_por_con_porcentaje'],2));
	//ACUMULO LA SUMA DE LOS IMPUESTOS PARA MOSTRAR EL TOTAL AL FINAL
	$suma=$suma+$row['con_por_con_porcentaje'];
	$i++;
	/*if($j>=132)
	{
		$i=0;
		$pdf->AddPage('l','letter');
	}*/
}

//INFORMATIVAS

while($row = mssql_fetch_array($con_pol_imp_contrato_informativa))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;  
	$pdf->SetY(160+$j);$pdf->SetX(11);
	$pdf->Cell(12+$j,8,$row['con_nombre']);
	
	$pdf->SetY(160+$j);$pdf->SetX(80);
	$pdf->Cell(12+$j,8,"......................................................................................................");
	
	$pdf->SetY(160+$j);$pdf->SetX(170);
	if(trim($row['con_por_con_porcentaje'])=="")
		$row['con_por_con_porcentaje']=0;
	$pdf->Cell(12+$j,8,'$'.'  '.number_format($row['con_por_con_porcentaje'],2));
	//ACUMULO LA SUMA DE LOS IMPUESTOS PARA MOSTRAR EL TOTAL AL FINAL
	$suma=$suma+$row['con_por_con_porcentaje'];
	$i++;
	/*if($j>=132)
	{
		$i=0;
		$pdf->AddPage('l','letter');
	}*/
}



$i++;
$pdf->SetY(155+($i*4));$pdf->SetX(80);
$pdf->Cell(15+($i*4),8,"______________________________________________________________________");

$pdf->SetY(160+($i*4));$pdf->SetX(132);
$pdf->Cell(15+($i*4),8,"TOTAL");

$pdf->SetY(160+($i*4));$pdf->SetX(170);
if(trim($suma)=="")
	$suma=0;
$pdf->Cell(15+($i*4),8,'$'.'  '.number_format($suma,2));


$pdf->SetFont('Arial','B',9);
$pdf->Text(12,198,"OBSERVACIONES");
$pdf->SetFont('Arial','',7);
$pdf->SetY(200);$pdf->SetX(12);
$pdf->MultiCell(178,6,strtoupper($res_dat_adi_otrosi['adi_otr_nota']),1);

$pdf->Line(10,235,200,235);
$pdf->SetFont('Arial','B',8);
$pdf->Text(20,239,"REVISÓ");
$pdf->Line(10,240,200,240);
$pdf->SetFont('Arial','B',8);
$pdf->Text(65,239,"APROBÓ");
$pdf->SetFont('Arial','B',8);
$pdf->Text(110,239,"REGISTRÓ");
$pdf->SetFont('Arial','B',8);
$pdf->Text(155,239,"FECHA DE RECIBIDO");

$pdf->Line(10,260,200,260);
$pdf->Line(10,265,200,265);
$pdf->Line(10,270,200,270);

$pdf->Line(50,235,50,270);
$pdf->Line(90,235,90,270);
$pdf->Line(140,235,140,270);

$pdf->Ln(23); 
$pdf->Ln(20); 

$pdf->Output();
?>