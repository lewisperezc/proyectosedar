<?php
session_start();
require_once("../librerias/dompdf/dompdf_config.inc.php");
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
$ins_nits=new nits();

$ano=$_POST['ano'];
$mes = $_POST['mes'];

if($_POST['mes']==1)
{
	$mes=11;
    $ano--;
}
elseif($_POST['mes']==2)
{
	$mes = 12;
	$ano--;
}
else
{
	$mes=$_POST['mes']-2;
}
//echo $mes."___".$ano

$nit_id=$ins_nits->busNit($_POST['nit_inicio']);

/*$query="SELECT DISTINCT mc.mov_nit_tercero,n.nits_apellidos,n.nits_nombres,n.nits_num_documento FROM movimientos_contables mc
INNER JOIN factura f ON mc.mov_nume=f.fac_id
INNER JOIN nits n ON mc.mov_nit_tercero=CAST (n.nit_id AS VARCHAR(50))
WHERE mc.mov_mes_contable='$mes' and mc.mov_ano_contable='$ano'
AND (mc.mov_compro like('CAU-NOM-%')) AND mc.mov_cuent IN('23809502')
AND n.nits_num_documento BETWEEN '".$_POST[nit_inicio]."' AND '".$_POST[nit_fin]."'
ORDER BY n.nits_apellidos ASC";
*/
$query="SELECT DISTINCT n.nit_id,n.nits_apellidos,n.nits_nombres,n.nits_num_documento FROM factura f
INNER JOIN reporte_jornadas rj ON f.fac_id=rj.rep_jor_num_factura
INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
INNER JOIN nits n ON npcc.nit_id=n.nit_id
WHERE n.nit_id='$nit_id' and fac_mes_servicio='$mes' and fac_ano_servicio='$ano'
AND rj.rep_jor_num_jornadas>0
AND f.fac_estado!=5";
$ejecutar=mssql_query($query);

while($res_afiliados=mssql_fetch_array($ejecutar))
{
	$html.="<table border='1'>";
	$html.="<tr><th>AFILIADO:</th><td colspan='9'>".$res_afiliados['nits_num_documento']." - ".$res_afiliados['nits_apellidos']." ".$res_afiliados['nits_nombres']."</td></tr>";
	/*$query_1="SELECT DISTINCT id_mov,mc.mov_valor,rj.rep_jor_num_jornadas,npcc.nit_id,f.fac_consecutivo,mc.mov_fec_elabo,mc.mov_compro,mc.mov_mes_contable,mc.mov_ano_contable,mov_cuent
			FROM reporte_jornadas rj
			INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
			INNER JOIN factura f ON rj.rep_jor_num_factura=f.fac_id
			INNER JOIN movimientos_contables mc ON f.fac_id=mc.mov_nume
			WHERE  npcc.nit_id='".$res_afiliados['mov_nit_tercero']."' AND mc.mov_nit_tercero LIKE('".$res_afiliados['mov_nit_tercero']."%')
			AND mc.mov_mes_contable = '$mes' and mc.mov_ano_contable = '$ano' AND (mc.mov_compro like('CAU-NOM-%'))
			AND mc.mov_cuent IN('23809502','25301005','23809511','23809506') AND rj.rep_jor_num_jornadas>0
			GROUP BY mc.mov_valor,rj.rep_jor_num_jornadas,npcc.nit_id,f.fac_consecutivo,mc.mov_fec_elabo,mc.mov_compro,mc.mov_mes_contable,mc.mov_ano_contable,mov_cuent,id_mov
			ORDER BY fac_consecutivo,id_mov";*/
			
	$query_1="SELECT DISTINCT id_mov,mc.mov_valor,rj.rep_jor_causado,npcc.nit_id,f.fac_consecutivo,mc.mov_fec_elabo,
	mc.mov_compro,f.fac_mes_servicio,f.fac_ano_servicio,mov_cuent,mc.mov_mes_contable,mc.mov_ano_contable
	FROM reporte_jornadas rj INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
	INNER JOIN factura f ON rj.rep_jor_num_factura=f.fac_id INNER JOIN movimientos_contables mc
	ON f.fac_id=mc.mov_nume WHERE npcc.nit_id='$nit_id' AND mc.mov_nit_tercero LIKE('$nit_id%')
	AND f.fac_mes_servicio = '$mes' and f.fac_ano_servicio= '$ano' AND (mc.mov_compro like('CAU-NOM-%'))
	AND mc.mov_cuent IN('25051002','25052001','25051004','25051006') AND rj.rep_jor_num_jornadas>0
	GROUP BY mc.mov_valor,rj.rep_jor_causado,npcc.nit_id,f.fac_consecutivo,mc.mov_fec_elabo,mc.mov_compro,
	f.fac_mes_servicio,f.fac_ano_servicio,mov_cuent,id_mov,mc.mov_mes_contable,mc.mov_ano_contable
	ORDER BY fac_consecutivo,id_mov";
	//echo $query_1;
	$ejecutar_1=mssql_query($query_1);
	
	$html.="<tr><th>FACTURA</th><th>DOCUMENTO</th><th>MES SERVICIO</th><th>A&Ntilde;O SERVICIO</th><th>VALOR FACTURADO</th>";
	$html.="<th>FABS</th><th>FDO RET SINDICAL</th><th>VACACIONES</th><th>ADMIN</th><th>BASE</th></tr>";
	$tot_base=0;
	$tot_val_facturado=0;
	$temp=0;
	$cambio=0;
	$entradas=0;
	$suma_valores_admin=0;
	$administracion=0;
	while($res_dat_fac_afiliado=mssql_fetch_array($ejecutar_1))
	{
		$query_2="SELECT SUM(mc.mov_valor) mov_valor,mov_compro,mov_mes_contable,mov_ano_contable
		FROM movimientos_contables mc
		WHERE mov_compro='".$res_dat_fac_afiliado['mov_compro']."' AND mov_nit_tercero LIKE('".$res_dat_fac_afiliado['nit_id']."%') AND
		mov_mes_contable='".$res_dat_fac_afiliado['mov_mes_contable']."' AND mov_ano_contable='".$res_dat_fac_afiliado['mov_ano_contable']."'
		AND mov_cuent IN('25051002','25052001','25051004','25051006')
		GROUP BY mov_compro,mov_mes_contable,mov_ano_contable";
		//echo $query_2;
		$ejecutar_2=mssql_query($query_2);
		$res_dat_total=mssql_fetch_array($ejecutar_2);
		if($temp==0)
		{
			$cambio=$res_dat_fac_afiliado['mov_compro'];
			$html.="<tr>";
			$html.="<td>".$res_dat_fac_afiliado['fac_consecutivo']."</td>";
			$html.="<td>".$res_dat_fac_afiliado['mov_compro']."</td>";
			$html.="<td>".$res_dat_fac_afiliado['fac_mes_servicio']."</td>";
			$html.="<td>".$res_dat_fac_afiliado['fac_ano_servicio']."</td>";
			$html.="<td style='text-align:right'>".number_format($res_dat_fac_afiliado['rep_jor_causado'])."</td>";
			$html.="<td style='text-align:right'>".number_format($res_dat_fac_afiliado['mov_valor'])."</td>";//FABS
			$temp=1;
			$entrada++;
		}
		else
		{
			if($res_dat_fac_afiliado['mov_compro']==$cambio)
			{
				$entrada++;
				if($entrada==4)
				{
					
					$dif_fac_cau=$res_dat_fac_afiliado['rep_jor_causado']-$res_dat_total['mov_valor'];	
					$porcentaje_administracion=$dif_fac_cau*100/$res_dat_fac_afiliado['rep_jor_causado'];
					$val_administracion=($res_dat_fac_afiliado['rep_jor_causado']*$porcentaje_administracion)/100;
					$html.="<td style='text-align:right'>".number_format($val_administracion)."</td>";
				}
				$html.="<td style='text-align:right'>".number_format($res_dat_fac_afiliado['mov_valor'])."</td>";
			}
			else
			{
				$entrada=0;
				$cambio=$res_dat_fac_afiliado['mov_compro'];
				$html.="</tr><tr>";
				$html.="<td>".$res_dat_fac_afiliado['fac_consecutivo']."</td>";
				$html.="<td>".$res_dat_fac_afiliado['mov_compro']."</td>";
				$html.="<td>".$res_dat_fac_afiliado['fac_mes_servicio']."</td>";
				$html.="<td>".$res_dat_fac_afiliado['fac_ano_servicio']."</td>";
				$html.="<td style='text-align:right'>".number_format($res_dat_fac_afiliado['rep_jor_causado'])."</td>";
				$html.="<td style='text-align:right'>".number_format($res_dat_fac_afiliado['mov_valor'])."</td>";//FABS
				$entrada++;
			}
		}
		
		$tot_val_facturado+=$res_dat_fac_afiliado['rep_jor_causado'];
		$tot_base+=$res_dat_fac_afiliado['mov_valor'];
	}
	//$html.="<tr><th colspan='5' style='text-align:right'>TOTAL: </th><th style='text-align:right'>".number_format($tot_val_facturado)."</th><th style='text-align:right'>".number_format($tot_base)."</th></tr>";
	$html.="<table>";
	//$html.="<br/>";
}
//echo $html;

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper ('a4','landscape'); 
$dompdf->render();
$dompdf->stream("formulario.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR
?>