<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
include_once('../clases/contrato.class.php');
$ins_contrato=new contrato();
$nit = new nits();
if(!$_SESSION['con_hospital'])
   $dat_hospital = $nit->dat_hospital($_SESSION['con_jor_hospital']);
else
   $dat_hospital = $nit->dat_hospital($_SESSION['con_hospital']);  
$datos_hospital = mssql_fetch_array($dat_hospital);
$fecha = date('d-m-Y');

define('../FPDF_FONTPATH','/font/');//Foncion de librerias para fuentes 
include('../pdf/class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../pdf/fonts/courier.afm');
require('../pdf/fpdf.php');
					
class PDF extends FPDF 
{ 
  //Cabecera de página 
  function Header() 
   {   
	$this->SetFont('Arial','B',15); 
	$this->Ln(10); 
   }
}// fin de la clase 
//Número de página	
$pdf=new FPDF();
$pdf->AliasNbPages();
$pdf->SetFont('Times','',12);
$pdf->AddPage();//orientacion y tamaño hoja
//Linea borde hoja
$pdf->Line(10,15,200,15);
$pdf->Line(10,15,10,280); // Linea Horizontal 2
$pdf->Line(200,15,200,280);
$pdf->Line(10,280,200,280);
//Encabezado
$pdf->SetFont('Arial','',9);
$pdf->Image('../imagenes/logo_sedar_original.jpg',15,16,20);
$pdf->Line(10,35,200,35);
$pdf->Line(50,15,50,35);
$pdf->SetFont('Arial','B',8);
$pdf->Text(85,20,"SINOPSIS DE");
$pdf->SetFont('Arial','B',8);
$pdf->Text(85,25,"CONTRATOS");
$pdf->Text(65,30,"PRESTACION DE SERVICIOS DE ANESTESIA");
$pdf->Line(140,15,140,35);
$pdf->SetFont('Arial','B',8);
$pdf->Text(140,20,"CODIFICACION:");
$pdf->Text(140,30,"FECHA DE APROVACION: ".$fecha);

//DATOS GENERALES DEL CONTRATO
$pdf->SetFont('Arial','B',8);
$pdf->Text(12,43,"CONTRATO No.: ");
$pdf->Line(40,39,80,39);
$pdf->Line(40,39,40,43);
$pdf->SetFont('Arial','B',8);
if($_SESSION['con_num_consecutivo'])
   $valor = $_SESSION['con_num_consecutivo'];
else
   $valor = $_SESSION['con_jor_num_consecutivo'];  
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
$pdf->Text(146,72,"AGREMIACION");
$pdf->Line(145,74,190,74);
$pdf->Line(190,70,190,74);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,83,"REPREST. LEGAL ");
$pdf->Line(40,80,150,80);
$pdf->Line(40,80,40,84);
$pdf->Text(48,83,$datos_hospital['nits_representante']);
$pdf->Line(40,84,150,84);
$pdf->Line(150,80,150,84);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,93,"DURACION ");
$pdf->Line(40,90,80,90);
$pdf->Line(40,90,40,94);

if($_SESSION['con_jor_vigencia'])
   $valor = $_SESSION['con_jor_vigencia'];
else
   $valor = $_SESSION['con_vigencia'];
$pdf->Text(55,93,$valor);
$pdf->Line(40,94,80,94);
$pdf->Line(80,90,80,94);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,100,"FECHA INICIO ");
$pdf->Line(40,100,80,100);
$pdf->Line(40,100,40,104);
if($_SESSION['con_jor_fec_inicial'])
   $valor = $_SESSION['con_jor_fec_inicial'];
else
   $valor = $_SESSION['con_fec_inicial'];
$pdf->Text(50,103,$valor);
$pdf->Line(40,104,80,104);
$pdf->Line(80,100,80,104);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,103,"FECHA FIN");
$pdf->Line(145,100,190,100);
$pdf->Line(145,100,145,104);
if($_SESSION['con_jor_fec_fin'])
   $valor = $_SESSION['con_jor_fec_fin'];
else
   $valor = $_SESSION['con_fec_fin'];

$pdf->Text(155,103,$valor);
$pdf->Line(145,104,190,104);
$pdf->Line(190,100,190,104);

$pdf->Line(10,110,200,110);
$pdf->SetFont('Arial','B',8);
$pdf->Text(12,115,"OBJETO");
$pdf->Line(50,110,50,120);
$pdf->SetFont('Arial','B',9);
$pdf->Text(80,115,"PRESTACION DE SERVICIOS PROFESIONALES DE ANESTESIA");
$pdf->Line(10,120,200,120);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,133,"VLR TOTAL CONTRATO");
$pdf->Line(46,130,186,130);
$pdf->Line(46,130,46,134);
$pdf->Line(46,134,186,134);

if($_SESSION['con_jor_valor'])
   $valor = $_SESSION['con_jor_valor'];
else
   $valor = $_SESSION['con_valor'];
$pdf->Text(55,133,number_format($valor));
$pdf->Line(86,130,86,134);
$pdf->Line(136,130,136,134);
$pdf->Line(186,130,186,134);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,143,"FORMA DE PAGO");
$pdf->Line(46,140,156,140);
$pdf->Line(46,140,46,144);
$pdf->Text(55,143,$_SESSION['ven_fac']." Dias habiles");
$pdf->Line(46,144,156,144);
$pdf->Line(156,140,156,144);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,153,"TARIFA HORA DIA ");
$pdf->Line(40,150,80,150);
$pdf->Line(40,150,40,154);
if($_SESSION['con_jor_val_hor_trabajada'])
   $valor = $_SESSION['con_jor_val_hor_trabajada'];
else
   $valor = $_SESSION['con_mon_fij_val_hor_diurna'];
$pdf->Text(50,153,$valor);
$pdf->Line(40,154,80,154);
$pdf->Line(80,150,80,154);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,153,"TARIFA HORA NOCHE");
$pdf->Line(145,150,190,150);
$pdf->Line(145,150,145,154);
if($_SESSION['con_jor_val_hor_nocturna'])
   $valor_2 = $_SESSION['con_jor_val_hor_nocturna'];
else
   $valor_2 = $_SESSION['con_mon_fij_val_hor_nocturna'];
$pdf->Text(150,153,$valor_2);
$pdf->Line(145,154,190,154);
$pdf->Line(190,150,190,154);



$con_ult_contrato=$ins_contrato->con_ult_contrato();
$con_pol_imp_contrato=$ins_contrato->consultar_poliza_o_impuesto(122,$con_ult_contrato);

$i=0;
$suma=0;
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
$pdf->Cell(15+($i*4),8,'$'.'  '.number_format($suma,2));



$pdf->SetFont('Arial','B',9);
$pdf->Text(12,200,"OBSERVACIONES");
$pdf->Line(12,203,198,203);
$pdf->Line(12,220,198,220);
$k=0;
for($i=0;$i<strlen($_SESSION['con_observa']);$i++)
  {
	  $cadena[$k] .= $_SESSION['con_observa'][$i];
	  if($i%180)
		  $k++;
  }
$pdf->SetFont('Arial','',7);
for($i=0;$i<sizeof($cadena);$i++)
  {
	  $dato = 15+$i*3;
      $pdf->Text($dato,210,$cadena[$i]);
  }
$pdf->Line(12,203,12,220);
$pdf->Line(198,203,198,220);
$pdf->Line(10,230,200,230);
$pdf->Line(145,190,145,194);
$pdf->Line(10,235,200,235);
$pdf->SetFont('Arial','B',8);
$pdf->Text(20,233,"REVISO");
$pdf->SetFont('Arial','B',8);
$pdf->Text(65,233,"APROBO");
$pdf->SetFont('Arial','B',8);
$pdf->Text(110,233,"REGISTRO");
$pdf->SetFont('Arial','B',8);
$pdf->Text(155,233,"FECHA DE RECIBIDO");

$pdf->Line(10,260,200,260);
$pdf->Line(10,265,200,265);
$pdf->Line(10,270,200,270);
$pdf->Line(50,230,50,270);
$pdf->Line(90,230,90,270);
$pdf->Line(140,230,140,270);

$pdf->Ln(23); 
$pdf->Ln(20); 
$pdf->Output();

unset($_SESSION['aso_cen_costos']);
unset($_SESSION['con_jor_num_consecutivo']);
unset($_SESSION['con_jor_hospital']);
unset($_SESSION['con_jor_vigencia']);
unset($_SESSION['con_jor_valor']);
unset($_SESSION['con_jor_val_hor_trabajada']);
unset($_SESSION['con_jor_fec_inicial']);
unset($_SESSION['con_jor_fec_fin']);
unset($_SESSION['con_jor_estado']);
unset($_SESSION['con_jor_est_legalizado']);
unset($_SESSION['con_observa']);
unset($_SESSION['fec_legalizacion']);
unset($_SESSION['ven_fac']);
unset($_SESSION['con_jor_val_hor_diurna']);
unset($_SESSION['con_jor_val_hor_nocturna']);
unset($_SESSION['con_jor_nom_pol_aseguradora']);
unset($_SESSION['con_jor_pol_nombre']);
unset($_SESSION['con_jor_pol_porcentaje']);
unset($_SESSION['con_jor_nom_imp_aseguradora']);
unset($_SESSION['con_jor_imp_nombre']);
unset($_SESSION['con_jor_imp_porcentaje']);


//LIMPIAR SESSIONES//
unset($_SESSION['aso_cen_costos']);
unset($_SESSION['con_num_consecutivo']);
unset($_SESSION['con_hospital']);
unset($_SESSION['con_vigencia']);
unset($_SESSION['con_valor']);
unset($_SESSION['con_cuo_mensual']);
unset($_SESSION['ven_fac']);
unset($_SESSION['con_mon_fij_val_hor_diurna']);
unset($_SESSION['con_mon_fij_val_hor_nocturna']);
unset($_SESSION['con_fec_inicial']);
unset($_SESSION['con_fec_fin']);
unset($_SESSION['con_estado']);
unset($_SESSION['con_observa']);
unset($_SESSION['fec_legalizacion']);
unset($_SESSION['con_nom_pol_aseguradora']);
unset($_SESSION['con_pol_nombre']);
unset($_SESSION['con_pol_porcentaje']);
unset($_SESSION['aso_cen_costos']);
/////////////////////
?>
