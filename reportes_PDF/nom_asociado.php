<?php 
session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/saldos.class.php');
include_once('../clases/compensacion_nomina.class.php');
include_once('../clases/reporte_jornadas.class.php');
include_once('../clases/nomina.class.php');
$reporte = new reporte_jornadas();
$ins_nit = new nits();
$saldo = new saldos();
$con_nomina = new compensacion_nomina();
$ins_nomina=new nomina();
$nit = $_GET['nit'];
$nomina = $_GET['nomina'];
$dat_nomina = split("-",$nomina,3);
$conse_nomina = $nomina;
$dat_nom = split("-",$nomina,3);
$conse_nom = $dat_nom[2];

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
$this->Image("../imagenes/reportes/aso_nom_causacion.jpg",0,0,200,'C');
//Arial bold 15 
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
$pdf->SetFont('Arial','B',11); 
$pdf->AddPage();
    $res_nomina=$ins_nomina->trae_datos_nomina($conse_nomina,$nit,2);
	$dat_asociado = mssql_fetch_array($res_nomina);
	//$admon=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'263535');
	$honorarios=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'23352501');
	$retefuente=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'23650501');
	$compenasociado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'25051001');
	$compenompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'25051001');
	$segsocialnomcau=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'13250594');
	
	$vacnompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'23803001');
	$fabspagado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'253010061');
	$fonretsindical=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'31400101');
	//$pubcontrato=$ins_nomina->trae_cuentas_nomina($conse_nomina,$nit,2,'61651035');
	$pubcontrato=0;
	$nov = $honorarios + $retefuente + $compenasociado + $vacnompagada + $fabspagado + $fonretsindical + $pubcontrato + $segsocialnomcau;
	$estado = $ins_nit->est_asociado($nit);
	if($estado==1)
	{
		
	$porcentaje=$nov*5/100;//4 DE ADMINISTRACIÓN Y 1 DE EDUCACIÓN = 5
	
	$novedad=$nov+$porcentaje;

 $factura = "SELECT mov_nume,fac_val_total,mov_cent_costo
 FROM movimientos_contables INNER JOIN factura on fac_id = mov_nume WHERE mov_compro='$nomina'";
 $dat_fac = mssql_query($factura);
 $datos_fac = mssql_fetch_array($dat_fac);
 $fac_id = $datos_fac['mov_nume'];
 $fac_valor = $datos_fac['fac_val_total'];
 $cen_cos = $datos_fac['mov_cent_costo'];
 
 $sum_descuentos = "SELECT SUM(des_monto) descuentos FROM descuentos d
 INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id
 INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id WHERE f.fac_id=$fac_id";

 $rec_caj_des = mssql_query($sum_descuentos);
 $dat_rec_caja = mssql_fetch_array($rec_caj_des);
 $total_descuentos = $dat_rec_caja['descuentos'];
 $cant_jornadas = $reporte->canJorFac($fac_id);
 $val_jornada = $fac_valor/$cant_jornadas;
 
 $jor_asociado = "select rep_jor_num_jornadas from reporte_jornadas rj 
inner join nits_por_cen_costo npcc on npcc.id_nit_por_cen = rj.id_nit_por_cen
where npcc.nit_id = ".$nit." and cen_cos_id = $cen_cos and rep_jor_num_factura = $fac_id";
 $jor_aso = mssql_query($jor_asociado);
 $can_jornadas = mssql_fetch_array($jor_aso);
 $cantidad = $can_jornadas['rep_jor_num_jornadas'];
 $val_facturado = $cantidad*$val_jornada;
 $por_facturado = ($cantidad*100)/$cant_jornadas;
 $por_descontado = $total_descuentos*($por_facturado/100);
 $descuento = $val_facturado-$por_descontado;
 //$descuento = $val_facturado-$por_descontado;
  $sql_glosa = "SELECT SUM(disGlo_valor) valor FROM reporte_jornadas rp INNER JOIN distGlosa d ON
  rp.rep_jor_id=d.disGlo_jornada INNER JOIN dbo.nits_por_cen_costo npcc on npcc.id_nit_por_cen = rp.id_nit_por_cen
  WHERE rp.rep_jor_num_factura = $fac_id AND npcc.nit_id = $nit";
$query_glosa = mssql_query($sql_glosa);
$dat_glosa = mssql_fetch_array($query_glosa);
 if($dat_glosa['valor']>0)
 {
	$descuento = $descuento-$dat_glosa['valor'];
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(135);$pdf->SetX(14);$pdf->Cell(18,6,"GLOSA HOSPITAL"); 
	$pdf->SetY(135);$pdf->SetX(108);$pdf->Cell(18,6,number_format($dat_glosa['valor'],0));
 }
 
 $administracion = $descuento*0.04;
 $educacion = $descuento*0.01;
$sql = "select mc.mov_nume,mc.mov_fec_elabo from movimientos_contables mc inner join cuentas cu on
mc.mov_cuent=cu.cue_id inner join conceptos con on con.con_id=mc.mov_concepto inner join nits nit
on nit_id = mov_nit_tercero where mov_compro = '$nomina' and mov_nit_tercero like ('$nit')";
$query_nume = mssql_query($sql);
$dat_nume = mssql_fetch_array($query_nume);

$sql_factura = "select * from factura inner join centros_costo on cen_cos_id = fac_cen_cos where fac_id=".$dat_nume['mov_nume'];
$query_fac = mssql_query($sql_factura);
$dat_factura = mssql_fetch_array($query_fac);
$dat_nit = $ins_nit->consul_nits($nit);
$datos_aso = mssql_fetch_array($dat_nit);

$fondos = $con_nomina->con_compensacion($nit,$nomina);
$dat_fondos = split("__",$fondos,3);//0=pabs,1=retiro,2=vacaciones

$pdf->SetY(66);
$pdf->SetX(55);
//echo $dat_factura['fac_descripcion'];
$mes = split(" ",$dat_factura['fac_descripcion']);
$impresion = $mes[7]." ".$mes[8]." ".$mes[9];
$pdf->Cell(21,8,$dat_factura['fac_consecutivo']);
$pdf->SetFont('Arial','I',8);
$pdf->SetY(80);
$pdf->SetX(46);
$pdf->Cell(21,8,$impresion);
$pdf->SetY(80);
$pdf->SetX(120);
$pdf->Cell(21,8,$dat_nume['mov_fec_elabo']);
$pdf->SetFont('Arial','B',11); 
$pdf->SetY(98);//renglonesss
$pdf->SetX(67);//filas ->
$pdf->SetFont('Arial','B',9);
$pdf->Cell(18,6,$dat_factura['cen_cos_nombre']);
$pdf->SetY(68);//renglonesss
$pdf->SetX(110);//filas ->
$pdf->SetFont('Arial','B',8);
$pdf->Cell(18,6,$datos_aso['nits_nombres']." ".$datos_aso['nits_apellidos']);
$i=0;

$pdf->SetFont('Arial','B',8);
$pdf->SetY(125);$pdf->SetX(14);$pdf->Cell(18,6,"VALOR FACTURADO"); 
$pdf->SetY(125);$pdf->SetX(66);$pdf->Cell(18,6,number_format($val_facturado,0)); 
$pdf->SetY(130);$pdf->SetX(14);$pdf->Cell(18,6,"DESCUENTOS LEGALIZACION CONTRATO"); 
$pdf->SetY(130);$pdf->SetX(108);$pdf->Cell(18,6,number_format($por_descontado,0));
$pdf->SetY(140);$pdf->SetX(14);$pdf->Cell(18,6,"FONDO SOCIAL FABS"); 
$pdf->SetY(142);$pdf->SetX(108);$pdf->Cell(18,6,number_format($fabspagado,0)); 
$pdf->SetY(142);$pdf->SetX(148);$pdf->Cell(18,6,$datos_aso['nits_por_pabs']."%");
$pdf->SetY(142);$pdf->SetX(175);$pdf->Cell(18,6,number_format($dat_fondos[0]));	
$pdf->SetY(144);$pdf->SetX(14);$pdf->Cell(18,6,"RETIRO FONDO SINDICAL"); 
$pdf->SetY(146);$pdf->SetX(108);$pdf->Cell(18,6,number_format($fonretsindical,0));
$pdf->SetY(146);$pdf->SetX(148);$pdf->Cell(18,6,"8%"); 
$pdf->SetY(146);$pdf->SetX(175);$pdf->Cell(18,6,number_format($dat_fondos[1],0));
$pdf->SetY(148);$pdf->SetX(14);$pdf->Cell(18,6,"FONDO DE VACACIONES"); 
$pdf->SetY(150);$pdf->SetX(108);$pdf->Cell(18,6,number_format($vacnompagada,0));
$pdf->SetY(150);$pdf->SetX(148);$pdf->Cell(18,6,$datos_aso['nit_por_fon_vacaciones']."%");
$pdf->SetY(150);$pdf->SetX(175);$pdf->Cell(18,6,number_format($dat_fondos[2],0));
$pdf->SetY(152);$pdf->SetX(14);$pdf->Cell(18,6,"ADMINISTRACION BASICA"); 
$pdf->SetY(154);$pdf->SetX(108);$pdf->Cell(18,6,number_format($administracion,0)); 
$pdf->SetY(154);$pdf->SetX(148);$pdf->Cell(18,6,"4%");
$pdf->SetY(156);$pdf->SetX(14);$pdf->Cell(18,6,"FONDO DE EDUCACION"); 
$pdf->SetY(158);$pdf->SetX(108);$pdf->Cell(18,6,number_format($educacion,0)); 
$pdf->SetY(158);$pdf->SetX(148);$pdf->Cell(18,6,"1%");
$pdf->SetY(160);$pdf->SetX(14);$pdf->Cell(18,6,"SEGURIDAD SOCIAL"); 
$pdf->SetY(162);$pdf->SetX(108);$pdf->Cell(18,6,number_format($segsocialnomcau,0)); 
$pdf->SetY(164);$pdf->SetX(14);$pdf->Cell(18,6,"RETENCION EN LA FUENTE"); 
$pdf->SetY(166);$pdf->SetX(108);$pdf->Cell(18,6,number_format($retefuente,0)); 

$des_sql = "SELECT * FROM des_anestecoop WHERE nit_id = $nit AND des_nomina = '$nomina'";
$des_query = mssql_query($des_sql);
$total = 0;
$entra = 0;
$j = 17;
	if(mssql_num_rows($des_query)>0)
	{
		while($row=mssql_fetch_array($des_query))
		{
			$pdf->SetY(146+(($j-8)*4));//renglonesss
			$pdf->SetX(14);//filas ->
			$pdf->Cell(18,6,$row['des_ane_descripcion']);
			$pdf->SetY(146+(($j-8)*4));//renglonesss
			$pdf->SetX(107);//filas ->
			$pdf->Cell(18,6,number_format($row['des_ane_dinero']));
			$total = $total+$row['des_ane_dinero'];
			$entra++;
			$j++;
		}
	}
	
	
	$recibo_caja = "SELECT trans.trans_fac_num recibo FROM transacciones tra INNER JOIN transacciones trans
	ON tra.tran_tran_id = trans.trans_id WHERE tra.trans_sigla='$nomina'";
$query_recibo = mssql_query($recibo_caja);
$dat_recibo = mssql_fetch_array($query_recibo);

	if($dat_recibo['recibo']!="")
	{
 		$dat_descuentos = "SELECT * FROM descuentos_compensacion WHERE des_nom_nit = $nit AND
 		des_nom_factura=$fac_id AND des_nom_rec_caja = ".$dat_recibo['recibo'];
		$des_factura = mssql_query($dat_descuentos);
		if(mssql_num_rows($des_factura)>0)
		{
			while($row=mssql_fetch_array($des_factura))
			{
				$pdf->SetY(146+(($j-8)*4));//renglonesss
				$pdf->SetX(14);//filas ->
				$cuen = $cuenta->getnomCuenta($row['des_nom_cuenta']);
				$pdf->Cell(18,6,$cuen);
				$pdf->SetY(146+(($j-8)*4));//renglonesss
				$pdf->SetX(107);//filas ->
				$pdf->Cell(18,6,number_format($row['des_nom_valor']));
				$total = $total+$row['des_ane_dinero'];
				$entra++;
				$j++;
			}
		}
	}
 }
 elseif($estado==3)
 {
	  $factura = "SELECT mov_nume,fac_val_total,mov_cent_costo FROM movimientos_contables
	  INNER JOIN factura on fac_id = mov_nume WHERE mov_compro='$nomina'";
	 $dat_fac = mssql_query($factura);
	 $datos_fac = mssql_fetch_array($dat_fac);
	 $fac_id = $datos_fac['mov_nume'];
	 $fac_valor = $datos_fac['fac_val_total'];
	 $cen_cos = $datos_fac['mov_cent_costo'];
	 
	 $sum_descuentos = "SELECT SUM(des_monto) descuentos FROM descuentos d INNER JOIN recibo_caja rc
	 ON d.des_factura=rc.rec_caj_id
	 INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id WHERE f.fac_id=$fac_id";
	 $rec_caj_des = mssql_query($sum_descuentos);
	 $dat_rec_caja = mssql_fetch_array($rec_caj_des);
	 $total_descuentos = $dat_rec_caja['descuentos'];
	 $cant_jornadas = $reporte->canJorFac($fac_id);
	 $val_jornada = $fac_valor/$cant_jornadas;
	 
	 $jor_asociado = "select rep_jor_num_jornadas from reporte_jornadas rj 
	 inner join nits_por_cen_costo npcc on npcc.id_nit_por_cen = rj.id_nit_por_cen
	 where npcc.nit_id = ".$nit." and cen_cos_id = $cen_cos and rep_jor_num_factura = $fac_id";
	 $jor_aso = mssql_query($jor_asociado);
	 $can_jornadas = mssql_fetch_array($jor_aso);
	 $cantidad = $can_jornadas['rep_jor_num_jornadas'];
	 $val_facturado = $cantidad*$val_jornada;
	 $por_facturado = ($cantidad*100)/$cant_jornadas;
	 $por_descontado = $total_descuentos*($por_facturado/100);
	 $descuento = $val_facturado-$por_descontado;
	 //$descuento = $val_facturado-$por_descontado;
	 $administracion = $descuento*0.12;
	 $pdf->SetY(152);$pdf->SetX(14);$pdf->Cell(18,6,"ADMINISTRACION BASICA"); 
	 $pdf->SetY(154);$pdf->SetX(108);$pdf->Cell(18,6,number_format($administracion,0)); 
	 $pdf->SetY(164);$pdf->SetX(14);$pdf->Cell(18,6,"RETENCION EN LA FUENTE"); 
	 $pdf->SetY(166);$pdf->SetX(108);$pdf->Cell(18,6,number_format($retefuente,0));
 }
//echo "el total es: ".$total;
$pdf->SetFont('Arial','B',10);
$pdf->SetY(251);$pdf->SetX(140);$pdf->Cell(18,6,number_format(($compenasociado-$total),0)); 

$pdf->Output();
?>