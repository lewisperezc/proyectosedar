<?php session_start();
/*
? --> &aacute;
? --> &eacute;
? --> &iacute;
? --> &oacute;
? --> &uacute;
? --> &ntilde;
*/

//ob_end_clean();
include_once('../clases/nits.class.php');
include_once('../clases/contrato.class.php');
//include_once('./codificacion.php');
require('../pdf/fpdf.php');
include('../pdf/class.ezpdf.php');
//define('../FPDF_FONTPATH','/font/');//Foncion de librerias para fuentes 
//$pdf->selectFont('../pdf/fonts/courier.afm');
$pdf =& new Cezpdf('a4');

class PDF extends FPDF 
{
  function Header()
  {   
    $this->SetFont('Arial','B',15); 
    $this->Ln(10);
  }
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

//N?mero de p?gina	
$pdf=new FPDF();
$pdf->AliasNbPages();
$pdf->SetFont('Times','',12);
$pdf->AddPage();//orientacion y tama?o hoja

$ins_contrato=new contrato();
$nit = new nits();
$ano=$_SESSION['elaniocontable'];
if($_GET['tipo']==1)
	$dat_hospital = $nit->dat_hospital($_GET['elidcontrato'],1);
else
{
	if(!$_SESSION['hospital'])
	   $dat_hospital = $nit->dat_hospital($_SESSION['con_jor_hospital'],2);
	else
	   $dat_hospital = $nit->dat_hospital($_SESSION['hospital'],2);
}
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
$pdf->Text(85,20,"SINOPSIS DE");
$pdf->SetFont('Arial','B',8);
$pdf->Text(85,25,"CONTRATOS");
$pdf->Text(65,30,"PRESTACIÓN DE SERVICIOS DE ANESTESIA");
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
if($_GET['tipo']==1)
	$valor=$datos_hospital['con_hos_consecutivo'];
else
{
	if($_SESSION['con_num_consecutivo'])
    	$valor = $_SESSION['con_num_consecutivo'];
	else
    	$valor = $_SESSION['con_jor_num_consecutivo'];
}
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

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,93,"DURACIÓN ");
$pdf->Line(40,90,80,90);
$pdf->Line(40,90,40,94);

if($_GET['tipo']==1)
	$valor=$datos_hospital['con_vigencia'];
else
{
	if($_SESSION['con_jor_vigencia'])
	   $valor = $_SESSION['con_jor_vigencia'];
	else
	   $valor = $_SESSION['con_vigencia'];
}
$pdf->Text(55,93,$valor." MESES");
$pdf->Line(40,94,80,94);
$pdf->Line(80,90,80,94);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,100,"FECHA INICIO ");
$pdf->Line(40,100,80,100);
$pdf->Line(40,100,40,104);

if($_GET['tipo']==1)
	$valor=$datos_hospital['con_fec_inicio'];
else
{
	if($_SESSION['con_jor_fec_inicial'])
	   $valor = $_SESSION['con_jor_fec_inicial'];
	else
		$valor = $_SESSION['con_fec_inicial'];
}
$pdf->Text(50,103,$valor);
$pdf->Line(40,104,80,104);
$pdf->Line(80,100,80,104);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,103,"FECHA FIN");
$pdf->Line(145,100,190,100);
$pdf->Line(145,100,145,104);

if($_GET['tipo']==1)
	$valor=$datos_hospital['con_fec_fin'];
else
{
	if($_SESSION['con_jor_fec_fin'])
	   $valor = $_SESSION['con_jor_fec_fin'];
	else
	   $valor = $_SESSION['con_fec_fin'];
}
$pdf->Text(155,103,$valor);
$pdf->Line(145,104,190,104);
$pdf->Line(190,100,190,104);

$pdf->Line(10,110,200,110);
$pdf->SetFont('Arial','B',8);
$pdf->Text(12,115,"OBJETO");
$pdf->Line(50,110,50,120);
$pdf->SetFont('Arial','B',9);
$pdf->Text(80,115,"PRESTACIÓN DE SERVICIOS PROFESIONALES DE ANESTESIA");
$pdf->Line(10,120,200,120);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,133,"VAL TOTAL CONTRATO");
$pdf->Line(46,130,186,130);
$pdf->Line(46,130,46,134);
$pdf->Line(46,134,186,134);

if($_GET['tipo']==1)
{
	if(trim($valor)!="")
		$valor=$datos_hospital['con_valor'];
	else
		$valor=0;
}
else
{
	if($_SESSION['con_jor_valor'])
	   $valor = $_SESSION['con_jor_valor'];
	elseif(trim($valor!=""))
	   $valor=$_SESSION['con_valor'];
	else
		$valor=0;
}
$pdf->Text(55,133,number_format($valor));
$pdf->Line(86,130,86,134);
$pdf->Line(136,130,136,134);
$pdf->Line(186,130,186,134);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,143,"FORMA DE PAGO");
$pdf->Line(46,140,156,140);
$pdf->Line(46,140,46,144);

if($_GET['tipo']==1)
	$dias_habiles=$datos_hospital['con_fac_vencimiento'];
else
	$dias_habiles=$_SESSION['ven_fac'];

$pdf->Text(55,143,$dias_habiles." DIAS HÁBILES");
$pdf->Line(46,144,156,144);
$pdf->Line(156,140,156,144);

$pdf->SetFont('Arial','B',8);
$pdf->Text(12,153,"TARIFA HORA DIA ");
$pdf->Line(40,150,80,150);
$pdf->Line(40,150,40,154);
//echo "el tipo es: ".$_GET['tipo']."<br>";
if($_GET['tipo']==1)
{
	if(trim($valor)!="")
		$valor=$datos_hospital['con_val_hor_trabajada'];
	else
		$valor=0;
}
else
{
	if($_SESSION['con_jor_val_hor_trabajada'])
	   $valor = $_SESSION['con_jor_val_hor_trabajada'];
	elseif($_SESSION['con_mon_fij_val_hor_diurna'])
	   $valor=$_SESSION['con_mon_fij_val_hor_diurna'];
	else
		$valor=0;
}
if($valor=="NULL")
	$valor=0;
//echo "el valor es: ".$valor."<br>";
$pdf->Text(50,153,number_format($valor));
$pdf->Line(40,154,80,154);
$pdf->Line(80,150,80,154);

$pdf->SetFont('Arial','B',8);
$pdf->Text(110,153,"TARIFA HORA NOCHE");
$pdf->Line(145,150,190,150);
$pdf->Line(145,150,145,154);

if($_GET['tipo']==1)
	$valor_2=$datos_hospital['con_val_hor_nocturna'];
else
{
	if($_SESSION['con_jor_val_hor_nocturna'])
	   $valor_2 = $_SESSION['con_jor_val_hor_nocturna'];
	else
	   $valor_2 = $_SESSION['con_mon_fij_val_hor_nocturna'];
}
if($valor_2=="NULL")
	$valor_2=0;
$pdf->Text(150,153,number_format($valor_2));
$pdf->Line(145,154,190,154);
$pdf->Line(190,150,190,154);

if($_GET['tipo']==1)
{ $con_ult_contrato=$_GET['elidcontrato']; }
else
{ $con_ult_contrato=$ins_contrato->con_ult_contrato(); }

$con_pol_imp_contrato=$ins_contrato->consultar_poliza_o_impuesto($con_ult_contrato);

//PARA LAS POLIZAS INFORMATIVAS
$con_pol_imp_contrato_informativa=$ins_contrato->consultar_poliza_o_impuesto_informativo($con_ult_contrato);

$i=0;
$suma=0;
while($row=mssql_fetch_array($con_pol_imp_contrato))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;  
        $pdf->SetFont('Arial','B',6);
	$pdf->SetY(160+$j);$pdf->SetX(11);
	$pdf->Cell(12+$j,8,$row['con_nombre']." (".$row['con_por_con_observacion'].") "."...............................");
	
	/*$pdf->SetY(160+$j);$pdf->SetX(80);
	$pdf->Cell(12+$j,8,"......................................................................................................");*/
	
	$pdf->SetY(160+$j);$pdf->SetX(170);
	if(trim($row['con_por_con_porcentaje'])=="")
		$row['con_por_con_porcentaje']=0;
	$pdf->Cell(12,8,'$'.'  '.number_format($row['con_por_con_porcentaje'],2),0,0,"R");
	//ACUMULO LA SUMA DE LOS IMPUESTOS PARA MOSTRAR EL TOTAL AL FINAL
	$suma=$suma+$row['con_por_con_porcentaje'];
	$i++;
}

//INFORMATIVAS
while($row=mssql_fetch_array($con_pol_imp_contrato_informativa))
{
	if($i!=0)
	  $j=$i*4;
	else
	  $j=$i;  
        $pdf->SetFont('Arial','B',6);
	$pdf->SetY(160+$j);$pdf->SetX(11);
	$pdf->Cell(12+$j,8,$row['con_nombre']." (".$row['con_por_con_observacion'].") "."...............................");
	
	/*$pdf->SetY(160+$j);$pdf->SetX(80);
	$pdf->Cell(12+$j,8,"......................................................................................................");*/
	
	$pdf->SetY(160+$j);$pdf->SetX(170);
	if(trim($row['con_por_con_porcentaje'])=="")
		$row['con_por_con_porcentaje']=0;
	$pdf->Cell(12,8,'$'.'  '.number_format($row['con_por_con_porcentaje'],2),0,0,"R");
	//ACUMULO LA SUMA DE LOS IMPUESTOS PARA MOSTRAR EL TOTAL AL FINAL
	$suma=$suma+$row['con_por_con_porcentaje'];
	$i++;
}

$i++;
$pdf->SetY(155+($i*4));$pdf->SetX(80);
$pdf->Cell(15+($i*4),8,"______________________________________________________________________________________");

$pdf->SetY(160+($i*4));$pdf->SetX(132);
$pdf->Cell(15+($i*4),8,"TOTAL");

$pdf->SetY(160+($i*4));$pdf->SetX(151);
if(trim($suma)==""||$suma=="NULL")
	$suma=0;
$pdf->Cell(15+($i*4),8,'$'.'  '.number_format($suma,2),0,0,"R");

if($_GET['tipo']==1)
	$ladescripcion=$datos_hospital['con_observacion'];
else
	$ladescripcion=$_SESSION['observa'];


//echo "el tam de la cadena es: ".$ladescripcion;

$pdf->SetFont('Arial','B',9);
$pdf->Text(12,198,"OBSERVACIONES");
$pdf->SetFont('Arial','',7);
$pdf->SetY(200);$pdf->SetX(12);
$pdf->MultiCell(178,6,strtoupper($ladescripcion),1);

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

//INICIO LAS ADICIONES Y OTROSIS QUE HA TENIDO EL CONTRATO
/*$con_adiciones=$ins_contrato->ConDatAdiOtrSinopsis($_GET['elidcontrato']);
while($res_adiciones=mssql_fetch_array($con_adiciones))
{
}*/
//FIN LAS ADICIONES Y OTROSIS QUE HA TENIDO EL CONTRATO

unset($_SESSION['con_num_consecutivo']);unset($_SESSION['hospital']);unset($_SESSION['con_vigencia']);unset($_SESSION['con_valor']);
        unset($_SESSION['con_cuo_mensual']);unset($_SESSION['ven_fac']);unset($_SESSION['con_mon_fij_val_hor_diurna']);
        unset($_SESSION['con_mon_fij_val_hor_nocturna']);unset($_SESSION['con_fec_inicial']);
        unset($_SESSION['con_fec_fin']);unset($_SESSION['con_estado']);unset($_SESSION['fec_legalizado']);
        unset($_SESSION['sel_tip_con_pre_servicios']);unset($_SESSION['observa']);
        
        unset($_SESSION['cant_campos']);unset($_SESSION['ary_con_nom_pol_aseguradora']);unset($_SESSION['ary_con_pol_nombre']);
        unset($_SESSION['ary_con_pol_porcentaje']);unset($_SESSION['ary_tip_pol_impuesto']);unset($_SESSION['ary_obs_pol_impuesto']);
        
        unset($_SESSION['aso_cen_costos']);
$pdf->Output();
?>