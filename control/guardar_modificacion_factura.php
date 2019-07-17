<?php
session_start();
@include_once('../clases/mes_contable.class.php');
@include_once('../clases/factura.class.php');
$ins_factura=new factura();
$ins_mes_contable=new mes_contable();

$fac_id=$_POST['num_fac'];
$fac_conse=$_POST['fac_conse'];
$fac_nit=$_POST['fac_nit'];
$fac_centro=$_POST['fac_centro'];
$fac_sigla='FAC-'.$fac_id;
$cau_sigla='CAU-NOM-'.$fac_id;
$usuario_id=$_SESSION['k_nit_id'];

$con_dat_mes_ano_factura=$ins_factura->ConMesAnoFacturaPorConsecutivoIdSiglaNitCentro($fac_conse,$fac_id,$fac_nit,$fac_centro);
$res_dat_mes_ano_factura=mssql_fetch_array($con_dat_mes_ano_factura);

$con_dat_mes_factura=$ins_mes_contable->DatosMesesAniosContablesPorAnioMes($res_dat_mes_ano_factura['mov_ano_contable'],$res_dat_mes_ano_factura['mov_mes_contable']);
$res_dat_mes_factura=mssql_fetch_array($con_dat_mes_factura);
//echo $res_dat_mes_factura['mes_estado'];

if($res_dat_mes_factura['mes_estado']==1)//EL MES CON EL QUE ESTÁ GRABADO LA FACTURA ESTÁ CERRADO
{
	echo "<script>alert('La factura se encuentra guardada en un mes de solo lectura, por favor abra el mes e intentelo de nuevo.');
	location.href='../index.php?c=170';</script>";
}
else//EL MES CON EL QUE ESTÁ GRABADO LA FACTURA ESTÁ ABIERTO
{
	//AHORA VERIFICO SI EL MES EN EL QUE LA VAN A GRABAR DE NUEVO SE ENCUENTRA ABIERTO
	$nue_fec_factura=$_POST['fec_factura'];
	$fec_factura=explode('-',$_POST['fec_factura']);
	$ano_nuevo=$fec_factura[2];
	$mes_nuevo=$fec_factura[1];
	//echo $ano_nuevo."___".$mes_nuevo."<br>";
	$con_dat_nue_mes_factura=$ins_mes_contable->DatosMesesAniosContablesPorAnioMes($ano_nuevo,$mes_nuevo);
	$res_dat_nue_mes_factura=mssql_fetch_array($con_dat_nue_mes_factura);
	
	if($res_dat_nue_mes_factura['mes_estado']==1)//EL NUEVO MES DE LA FACTURA ESTÁ CERRADO
	{
		echo "<script>alert('Mes de solo lectura.');location.href='../index.php?c=170';</script>";
	}
	else//EL NUEVO MES DE LA FACTURA ESTÁ ABIERTO, ACTUALIZO LA FECHA
	{
		$act_fec_factura=$ins_factura->ActualizarFechaFactura($nue_fec_factura,$fac_id,$fac_sigla,$fac_nit,$fac_centro,$cau_sigla,$res_dat_mes_ano_factura['mov_mes_contable'],$res_dat_mes_ano_factura['mov_ano_contable'],$mes_nuevo,$ano_nuevo,$usuario_id);
		if($act_fec_factura)
			echo "<script>alert('Fecha de factura actualizada correctamente.');location.href='../index.php?c=170';</script>";
	}
	
}
?>