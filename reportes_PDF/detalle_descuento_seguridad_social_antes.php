<?php
session_start();
@include_once('../conexion/conexion.php');
@include_once('conexion/conexion.php');
//$cedula='70086822';

function consul_nits($nit)
{
	if(!empty($nit))
	{
		$sql = "SELECT * FROM nits WHERE nits_num_documento='$nit' ORDER BY nits_apellidos ASC";
		$query = mssql_query($sql);
		if($query)
		{
			return $query;
		}
		else
			return false;
	}
	else
		return false;
}

function nomMes($mes)
{
	$sql="SELECT mes_nombre FROM mes_contable WHERE mes_id=$mes";
	$query = mssql_query($sql);
	if($query)
	{
		$dat_mes = mssql_fetch_array($query);
		return $dat_mes['mes_nombre'];
	}
}

$con_dat_nit=consul_nits($cedula);
$res_dat_nit=mssql_fetch_array($con_dat_nit);

$query="SELECT rp.rep_jor_num_jornadas, rp.rep_jor_mes, rp.rep_jor_num_factura, f.fac_id, f.fac_consecutivo,
f.fac_mes_servicio,f.fac_ano_servicio,id_nit_por_cen,f.fac_fecha,cc.cen_cos_nombre
FROM reporte_jornadas rp
INNER JOIN factura f ON f.fac_id=rp.rep_jor_num_factura
INNER JOIN centros_costo cc ON f.fac_cen_cos=cc.cen_cos_id
WHERE rp.id_nit_por_cen in (SELECT id_nit_por_cen FROM nits_por_cen_costo WHERE nit_id='$res_dat_nit[nit_id]') 
AND rep_jor_num_factura IN (
SELECT fac_id FROM factura WHERE fac_mes_servicio='$_GET[mes]' AND fac_ano_servicio='$_GET[elanio]'
AND fac_estado!=5 AND fac_cen_cos IN (
SELECT cen_cos_id FROM nits_por_cen_costo WHERE nit_id='$res_dat_nit[nit_id]'))
AND rep_jor_num_jornadas>0 AND f.fac_id IN (SELECT mov_nume FROM movimientos_contables WHERE 
mov_compro LIKE('CAU-NOM%') AND mov_mes_contable='$_GET[mes]' AND mov_ano_contable='$_GET[elanio]'
AND mov_nit_tercero LIKE('$res_dat_nit[nit_id]%') AND mov_cuent=25051002 AND mov_nit_tercero='$res_dat_nit[nit_id]')";
//echo $query;
$ejecutar=mssql_query($query);


function valor_fondo($nit,$mes,$anio,$cuenta,$factura)
{
	//$nuevo_mes=$mes;
	$sql="SELECT DISTINCT rj.rep_jor_num_jornadas,npcc.nit_id,f.fac_consecutivo,mc.mov_fec_elabo,mc.mov_compro,mc.mov_mes_contable,mc.mov_ano_contable,mov_valor
FROM reporte_jornadas rj
INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
INNER JOIN factura f ON rj.rep_jor_num_factura=f.fac_id
INNER JOIN movimientos_contables mc ON f.fac_id=mc.mov_nume
WHERE npcc.nit_id='$nit' AND mc.mov_nit_tercero LIKE('$nit%') AND mc.mov_mes_contable = '$mes' and
mc.mov_ano_contable = '$anio'
AND (mc.mov_compro like('CAU-NOM-%')) AND mc.mov_cuent IN('$cuenta') AND rj.rep_jor_num_jornadas>0 AND
fac_consecutivo='$factura'
GROUP BY rj.rep_jor_num_jornadas,npcc.nit_id,f.fac_consecutivo,mc.mov_fec_elabo,mc.mov_compro,mc.mov_mes_contable,
mc.mov_ano_contable,mov_cuent,mov_valor
ORDER BY fac_consecutivo";
//if($cuenta=='25301005')
	//echo $sql."<br><br>";
	$query = mssql_query($sql);
	if($query)
	{
		$dat_mes = mssql_fetch_array($query);
		return $dat_mes;
	}
	else
		return false;
}


function res_tot_descuentos($sigla,$nit,$mes,$anio)
{
	$query="SELECT SUM(mc.mov_valor) mov_valor,mov_compro,mov_mes_contable,mov_ano_contable FROM movimientos_contables mc
			  WHERE mov_compro='$sigla' AND mov_nit_tercero LIKE('$nit%') AND
			  mov_mes_contable='$mes' AND mov_ano_contable='$anio'
			  AND mov_cuent IN('25051002','25052001','25051004','25051006')
			  GROUP BY mov_compro,mov_mes_contable,mov_ano_contable";
		//echo $query."<br>";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $res_dat_total=mssql_fetch_array($ejecutar); 
		else
			return false;
}

require('../pdf/fpdf.php'); 
include('../pdf/class.ezpdf.php');
 
class PDF extends FPDF 
{ 
//Cabecera de página 
function Header() 
{ 
//Logo 
$this->Image("../imagenes/reportes/detalle_descuento_seguridad_social.jpg",0,0,200,'C');
//Arial bold 15 
$this->SetFont('Arial','B',7);
//Movernos a la derecha 
//$this->Cell(10); 
$this->Ln(10); 
}
//Pie de página 
function Footer() 
{ 
//Posici�n: a 1,5 cm del final 
$this->SetY(-15); 
//Arial italic 8 
$this->SetFont('Arial','I',7); 
//N�mero de p�gina 
$this->Cell(0,10,'Pagina '.$this->PageNo().'      Fecha Impresion '.(date('d-m-Y')),0,0,'C'); 
} 

}// fin de la clase 

$pdf=new PDF(); 
//Tipo y tamaño de lertra 
$pdf->SetFont('Arial','B',7);
$k=0;



while($row=mssql_fetch_array($ejecutar))
{
        $pdf->AddPage();
        $pdf->SetY(69);$pdf->SetX(34);$pdf->Cell(21,5,$res_dat_nit['nits_num_documento']." - ".$res_dat_nit['nits_apellidos']." ".$res_dat_nit['nits_nombres']);
		$pdf->SetY(69);$pdf->SetX(145);$pdf->Cell(21,5,$row['fac_fecha']);
        $pdf->SetY(80);$pdf->SetX(34);$pdf->Cell(21,5,$row['fac_consecutivo']);
		$pdf->SetY(80);$pdf->SetX(90);$pdf->Cell(21,5,$row['cen_cos_nombre']);
		$que_pag_fondos="select * from movimientos_contables where mov_cuent=13250594 and
		mov_nit_tercero='$res_dat_nit[nit_id]' and mov_mes_contable='$row[fac_mes_servicio]'
		and mov_ano_contable='$row[fac_ano_servicio]' and mov_tipo=1";
		$eje_pag_fondos=mssql_query($que_pag_fondos);
		$res_pag_fondos=mssql_fetch_array($eje_pag_fondos);
		
		$nom_mes=nomMes($row['fac_mes_servicio']);
		
		$pdf->SetY(92);$pdf->SetX(65);$pdf->Cell(21,5,"$ ".number_format($res_pag_fondos['mov_valor']));
		$pdf->SetY(92);$pdf->SetX(105);$pdf->Cell(21,5,$nom_mes." de ".$row['fac_ano_servicio']);
		
		$pdf->SetY(101);$pdf->SetX(55);$pdf->Cell(21,5,$row['fac_mes_servicio']." - ".$nom_mes);
		
		$con_fac_base="SELECT DISTINCT rj.rep_jor_num_jornadas,npcc.nit_id,f.fac_consecutivo,mov_compro,fac_mes_servicio,fac_ano_servicio,mov_mes_contable,mov_ano_contable
						FROM reporte_jornadas rj
						INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
						INNER JOIN factura f ON rj.rep_jor_num_factura=f.fac_id
						INNER JOIN movimientos_contables mc ON f.fac_id=mc.mov_nume
						WHERE npcc.nit_id='$res_dat_nit[nit_id]' AND mc.mov_nit_tercero LIKE('$res_dat_nit[nit_id]%') AND mc.mov_mes_contable = '$row[fac_mes_servicio]' and mc.mov_ano_contable = '$row[fac_ano_servicio]'
						AND (mc.mov_compro like('CAU-NOM-%'))
						AND mc.mov_cuent IN('25051002','25052001','25051004','25051006') AND
						rj.rep_jor_num_jornadas>0
						GROUP BY rj.rep_jor_num_jornadas,npcc.nit_id,f.fac_consecutivo,mov_compro,fac_mes_servicio,
						fac_ano_servicio,mov_mes_contable,mov_ano_contable
						ORDER BY fac_consecutivo";
		//echo $con_fac_base;
		$eje_fac_base=mssql_query($con_fac_base);
		$i=0;
		
		$total_facturado=0;
		$total_fabs=0;
		$total_retiro=0;
		$total_vacaciones=0;
		$total_administracion=0;
		$res_dat_total=0;
		$num_facturas=mssql_num_rows($eje_fac_base);	
		
		while($res_fac_base=mssql_fetch_array($eje_fac_base))
		{
			$res_dat_total=res_tot_descuentos($res_fac_base['mov_compro'],$res_dat_nit['nit_id'],$res_fac_base['mov_mes_contable'],$res_fac_base['mov_ano_contable']);	
			
			$pdf->SetY(132+$i);$pdf->SetX(17);$pdf->Cell(21,5,$res_fac_base['fac_consecutivo']);
			$pdf->SetY(132+$i);$pdf->SetX(34);$pdf->Cell(21,5,number_format($res_fac_base['rep_jor_num_jornadas']));
			
			//FABS
			$valor_fabs=valor_fondo($res_dat_nit['nit_id'],$res_fac_base['mov_mes_contable'],$res_fac_base['mov_ano_contable'],'25301005',$res_fac_base['fac_consecutivo']);
			$pdf->SetY(132+$i);$pdf->SetX(70);$pdf->Cell(21,5,number_format($valor_fabs['mov_valor']));
			
			//RETIRO
			$valor_retiro=valor_fondo($res_dat_nit['nit_id'],$res_fac_base['mov_mes_contable'],$res_fac_base['mov_ano_contable'],'23809511',$res_fac_base['fac_consecutivo']);
			$pdf->SetY(132+$i);$pdf->SetX(92);$pdf->Cell(21,5,number_format($valor_retiro['mov_valor']));
			
			//VACACIONES
			$valor_vacaciones=valor_fondo($res_dat_nit['nit_id'],$res_fac_base['mov_mes_contable'],$res_fac_base['mov_ano_contable'],'23809506',$res_fac_base['fac_consecutivo']);
			$pdf->SetY(132+$i);$pdf->SetX(125);$pdf->Cell(21,5,number_format($valor_vacaciones['mov_valor']));
			
			//NOMINAS/CAUSADAS
			$valor_nom_causadas=valor_fondo($res_dat_nit['nit_id'],$res_fac_base['mov_mes_contable'],$res_fac_base['mov_ano_contable'],'23809502',$res_fac_base['fac_consecutivo']);
			//$valor_nom_causadas['mov_valor']
			
			//ADMINISTRACION
			$dif_fac_cau=$res_fac_base['rep_jor_num_jornadas']-$res_dat_total['mov_valor'];
			//echo $dif_fac_cau.""."<br>";
			$porcentaje_administracion=$dif_fac_cau*100/$res_fac_base['rep_jor_num_jornadas'];
			$valor_administracion=($res_fac_base['rep_jor_num_jornadas']*$porcentaje_administracion)/100;
			$pdf->SetY(132+$i);$pdf->SetX(150);$pdf->Cell(21,5,number_format($valor_administracion));
			
			
			//BASE
			$tot_base=$res_fac_base['rep_jor_num_jornadas']-$valor_fabs['mov_valor']-$valor_retiro['mov_valor']-$valor_vacaciones['mov_valor']-$valor_administracion;
			$pdf->SetY(132+$i);$pdf->SetX(173);$pdf->Cell(21,5,number_format($tot_base));
			
			
			$i+=4;
			
			$total_facturado+=$res_fac_base['rep_jor_num_jornadas'];
			
			if($k==0)
			{
				
				$pdf->SetY(150+$i);$pdf->SetX(17);$pdf->Cell(21,5,"TOTALES");
				$pdf->SetY(150+$i);$pdf->SetX(34);$pdf->Cell(21,5,number_format($total_facturado));
				$pdf->SetY(150+$i);$pdf->SetX(70);$pdf->Cell(21,5,number_format($total_fabs));
				$pdf->SetY(150+$i);$pdf->SetX(92);$pdf->Cell(21,5,number_format($total_retiro));
				$pdf->SetY(150+$i);$pdf->SetX(125);$pdf->Cell(21,5,number_format($total_vacaciones));
				$pdf->SetY(150+$i);$pdf->SetX(150);$pdf->Cell(21,5,number_format($total_administracion));
			}
			$k++;
		}
}


$pdf->SetY(150+$i);$pdf->SetX(17);$pdf->Cell(21,5,"TOTALES");
$pdf->SetY(150+$i);$pdf->SetX(34);$pdf->Cell(21,5,number_format($total_facturado));
$pdf->SetY(150+$i);$pdf->SetX(70);$pdf->Cell(21,5,number_format($total_fabs));
$pdf->SetY(150+$i);$pdf->SetX(92);$pdf->Cell(21,5,number_format($total_retiro));
$pdf->SetY(150+$i);$pdf->SetX(125);$pdf->Cell(21,5,number_format($total_vacaciones));
$pdf->SetY(150+$i);$pdf->SetX(150);$pdf->Cell(21,5,number_format($total_administracion));

$pdf->Output();
?>