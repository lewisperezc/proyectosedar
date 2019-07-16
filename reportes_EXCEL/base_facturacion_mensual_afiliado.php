<?php
session_start();
//require_once("../librerias/dompdf/dompdf_config.inc.php");
include_once('../conexion/conexion.php');
include_once('../clases/varios.class.php');


$varios = new varios();
$sal_minimo = $varios->ConsultarDatosVariablesPorId(1);
$tope_seg_social= $varios->ConsultarDatosVariablesPorId(7);

$ano=$_POST['ano'];
$mes = $_POST['mes']-1;
if($mes<=0)
{
	$mes = 12+$mes;
	$ano--;
}


$query="SELECT DISTINCT mc.mov_nit_tercero,n.nits_apellidos,n.nits_nombres,n.nits_num_documento,
n.nit_val_seg_social,n.nit_mon_fij_seg_social,tss.tip_segSoc_porcentaje FROM movimientos_contables mc
INNER JOIN factura f ON mc.mov_nume=f.fac_id
RIGHT JOIN nits n ON mc.mov_nit_tercero=CAST (n.nit_id AS VARCHAR(50))
INNER JOIN tip_segSocial tss ON tss.tip_segSoc_id=n.nit_tip_segSocial
WHERE mc.mov_mes_contable='$mes' and mc.mov_ano_contable='$ano'
AND (mc.mov_compro like('CAU-NOM-%')) AND mc.mov_cuent IN('25051002')
AND n.nits_num_documento BETWEEN '".$_POST[nit_inicio]."' AND '".$_POST[nit_fin]."'
AND n.tip_nit_id=1 AND n.nit_est_id=1 ORDER BY n.nits_apellidos ASC";


/*$query="SELECT DISTINCT mc.mov_nit_tercero,n.nits_apellidos,n.nits_nombres,n.nits_num_documento,n.nit_val_seg_social,n.nit_mon_fij_seg_social,tss.tip_segSoc_porcentaje
FROM movimientos_contables mc
INNER JOIN factura f ON mc.mov_nume=f.fac_id
RIGHT JOIN nits n ON mc.mov_nit_tercero=CAST (n.nit_id AS VARCHAR(50))
INNER JOIN tip_segSocial tss ON tss.tip_segSoc_id=n.nit_tip_segSocial
WHERE mc.mov_mes_contable='$mes' and mc.mov_ano_contable='$ano'
AND (mc.mov_compro like('CAU-NOM-%')) AND mc.mov_cuent IN('23809502')
AND n.nits_num_documento BETWEEN '".$_POST[nit_inicio]."' AND '".$_POST[nit_fin]."' AND n.tip_nit_id=1 AND n.nit_est_id=1
UNION
SELECT DISTINCT mc.mov_nit_tercero,n.nits_apellidos,n.nits_nombres,n.nits_num_documento,n.nit_val_seg_social,n.nit_mon_fij_seg_social,tss.tip_segSoc_porcentaje
FROM movimientos_contables mc
INNER JOIN factura f ON mc.mov_nume=f.fac_id
RIGHT JOIN nits n ON mc.mov_nit_tercero=CAST (n.nit_id AS VARCHAR(50))
INNER JOIN tip_segSocial tss ON tss.tip_segSoc_id=n.nit_tip_segSocial
WHERE mov_nit_tercero NOT IN
(
SELECT DISTINCT mc.mov_nit_tercero
FROM movimientos_contables mc
INNER JOIN factura f ON mc.mov_nume=f.fac_id
RIGHT JOIN nits n ON mc.mov_nit_tercero=CAST (n.nit_id AS VARCHAR(50))
INNER JOIN tip_segSocial tss ON tss.tip_segSoc_id=n.nit_tip_segSocial
WHERE mc.mov_mes_contable='$mes' and mc.mov_ano_contable='$ano'
AND (mc.mov_compro like('CAU-NOM-%')) AND mc.mov_cuent IN('23809502')
AND n.nits_num_documento BETWEEN '".$_POST[nit_inicio]."' AND '".$_POST[nit_fin]."' AND n.tip_nit_id=1 AND n.nit_est_id=1
) AND n.tip_nit_id=1 AND n.nit_est_id=1
ORDER BY n.nits_apellidos ASC";
*/
//echo $query;
$ejecutar=mssql_query($query);
$html.="<table border='1'>";
$html.="<tr><th>CEDULA</th><th>NOMBRES</th><th>VALOR FACTURADO</th>";
$html.="<th>FABS</th><th>FDO RET SINDICAL</th><th>VACACIONES</th><th>ADMIN</th><th>BASE</th><th>REFERENCIA CIRCULAR</th><th>BASE DE COTIZACION</th></tr>";

while($res_afiliados=mssql_fetch_array($ejecutar))
{
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=Reporte");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	//$html.="<tr><th>AFILIADO:</th><td colspan='9'>".$res_afiliados['nits_num_documento']." - ".$res_afiliados['nits_apellidos']." ".$res_afiliados['nits_nombres']."</td></tr>";
	$fabs=0;$fondo=0;$retiro=0;$admin=0;$compensacion=0;$jornadas=0;$basess=0;	
	$query_1="SELECT SUM(mc.mov_valor) mov_valor,SUM(rj.rep_jor_num_jornadas) jornadas,npcc.nit_id,
	mc.mov_mes_contable,mc.mov_ano_contable,mov_cuent
	FROM reporte_jornadas rj
	INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
	INNER JOIN factura f ON rj.rep_jor_num_factura=f.fac_id
	INNER JOIN movimientos_contables mc ON f.fac_id=mc.mov_nume
	WHERE  npcc.nit_id='".$res_afiliados['mov_nit_tercero']."'
	AND mc.mov_nit_tercero LIKE('".$res_afiliados['mov_nit_tercero']."%')
	AND mc.mov_mes_contable = '$mes' and mc.mov_ano_contable = '$ano' AND (mc.mov_compro like('CAU-NOM-%'))
	AND mc.mov_cuent IN('25051002','25052001','25051004','25051006','23352501') AND rj.rep_jor_num_jornadas>0
	GROUP BY mov_cuent,npcc.nit_id,mc.mov_mes_contable,mc.mov_ano_contable";
	$ejecutar_1 = mssql_query($query_1);
	
	while($res_dat_fac_afiliado=mssql_fetch_array($ejecutar_1))
	{
		$jornadas=$res_dat_fac_afiliado['jornadas'];
		if($res_dat_fac_afiliado['mov_cuent']==25051002)
			$compensacion=$res_dat_fac_afiliado['mov_valor'];
		elseif($res_dat_fac_afiliado['mov_cuent']==25051006)
			$vacaciones=$res_dat_fac_afiliado['mov_valor'];
		elseif($res_dat_fac_afiliado['mov_cuent']==25051004)
			$retiro=$res_dat_fac_afiliado['mov_valor'];
		elseif($res_dat_fac_afiliado['mov_cuent']==25052001)
			$fabs=$res_dat_fac_afiliado['mov_valor'];
		elseif($res_dat_fac_afiliado['mov_cuent']==23352501)
			$noafiliado=$res_dat_fac_afiliado['mov_valor'];
	}
	$admin=$jornadas-$compensacion-$vacaciones-$retiro-$fabs-$noafiliado;
	$html.="<tr>";$html.="<td>".$res_afiliados['nits_num_documento']."</td>";$html.="<td>".$res_afiliados['nits_nombres']." ".$res_afiliados['nits_apellidos']."</td>";
	//$html.="<td>".$res_dat_fac_afiliado['mov_mes_contable']."</td>";
	//$html.="<td>".$res_dat_fac_afiliado['mov_ano_contable']."</td>";
	$html.="<td style='text-align:right'>".$jornadas."</td>";
	$html.="<td style='text-align:right'>".$fabs."</td>";$html.="<td style='text-align:right'>".$retiro."</td>";$html.="<td style='text-align:right'>".$vacaciones."</td>";
	$html.="<td style='text-align:right'>".$admin."</td>";
	if($compensacion==0)
		$html.="<td style='text-align:right'>".$noafiliado."</td>";
	else
		$html.="<td style='text-align:right'>".$compensacion."</td>";

	if($res_afiliados['nit_mon_fij_seg_social']==1)
	{
		$html.="<td style='text-align:right'>0</td>";
		$html.="<td style='text-align:right'>".$res_afiliados['nit_val_seg_social']."</td>";	
	}
	else
	{
		$tope=round($sal_minimo['var_valor']*$tope_seg_social['var_valor'],-2);
		$a=round($compensacion,-2);
		$basess=round($a/(1+($res_afiliados['tip_segSoc_porcentaje']/100)),-2);
		if($basess>$tope)
			$basess=$tope;
		if($basess<($sal_minimo['var_valor']*2))
		{
			$html.="<td style='text-align:right'>0</td>";
			$html.="<td style='text-align:right'>".($sal_minimo['var_valor']*2)."</td>";
		}
		else
		{
			$html.="<td style='text-align:right'>".($compensacion-$basess)."</td>";
			$html.="<td style='text-align:right'>".$basess."</td>";	
		}	
	}
}
echo $html;
/*
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper ('a4','landscape'); 
$dompdf->render();
$dompdf->stream("formulario.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR*/
?>