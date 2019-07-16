<?php
@include_once('clases/moviminetos_contables.class.php');
@include_once('moviminetos_contables.class.php');

@include_once('clases/factura.class.php');
@include_once('factura.class.php');

$ins_mov_contable=new movimientos_contables();


$consecutivo_factura='0491';

$que_fac_id="SELECT fac_id,fac_cen_cos FROM factura WHERE fac_consecutivo='$consecutivo_factura'";
$eje_fac_id=mssql_query($que_fac_id);
$con_fac_id=mssql_fetch_array($eje_fac_id);


$factura_id=$con_fac_id['fac_id'];
$mes='10';
$ano='2018';

$recibo_id='4016';
$sig_pago='PAG-COM-4919';
$fec_elabo='04-10-2018';

$centro=$con_fac_id['fac_cen_cos'];



$query_nits="SELECT npcc.nit_id FROM reporte_jornadas rj
INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
WHERE rep_jor_num_factura=$factura_id";
$ejecutar_nits=mssql_query($query_nits);


while($res_dat_nits=mssql_fetch_array($ejecutar_nits))
{
	$eje_ajuste=$ins_mov_contable->ajuste_causacion($factura_id,$mes,$ano,$recibo_id,$sig_pago,$res_dat_nits['nit_id'],$fec_elabo,$centro);
}


$sigla_ajuste='AJUS-NOM-'.$factura_id;

$usuario_actualizador=389;
$fecha_actualizacion=date('d-m-Y');

$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];


$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE AJUSTE NOMINA AFILIADOS';

//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA EL AJUSTE
$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
aud_mov_con_descripcion='$aud_mov_con_descripcion'
WHERE mov_compro='$sigla_ajuste' AND mov_mes_contable='$mes' AND mov_ano_contable='$ano'
AND tip_mov_aud_id IS NULL";
//echo $que_aud_mov_contable;
$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);


//ajuste_causacion($factura,$mes,$ano,$recibo_id,$sig_pago,$nit,$fec_elabo,$centro)

?>