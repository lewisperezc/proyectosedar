<?php session_start();
@include_once('../clases/transacciones.class.php');@include_once('clases/transacciones.class.php');
@include_once('../clases/pabs.class.php');@include_once('clases/pabs.class.php');
@include_once('../clases/factura.class.php');@include_once('clases/factura.class.php');
@include_once('../clases/comprobante.class.php');@include_once('clases/comprobante.class.php');

$ins_transacciones=new transacciones();
$ins_fabs=new pabs();
$ins_factura=new factura();
$comprobante= new comprobante();
//obt_consecutivo($comprobante)

$fecha=$_POST['dev_fab_fecha'];
//CAPTURAR DATOS//
$nit_id=$_POST['nit_id'];
$cen_cos_id=1169;
$val_devolucion=$_POST['val_devolucion'];
$mes_contable=explode("-",$_POST['mes_contable'],2);
$cue_devolucion=$_POST['cue_devolucion'];
$obs_devolucion=$_POST['obs_devolucion'];
$ano = $_SESSION['elaniocontable'];

$bas_retencion=0;


$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE DEVOLUCION DE FABS';


if($_POST['tie_retencion']=="on")
{
	$cuenta_retencion=$_POST['cue_retencion'];
	$val_retencion=$_POST['val_retencion'];
}
else
	$val_retencion=0;
//////////////////
$obt_conse = $comprobante->cons_comprobante($_SESSION['elaniocontable'],$mes_contable[1],38);
$sig = $comprobante->sig_comprobante(38);
$comprobante->act_comprobante($_SESSION['elaniocontable'],$mes_contable[1],38);
$lasigla=$sig.$obt_conse;

$gua_transaccion=$ins_transacciones->guaTransaccion(strtoupper($lasigla),$fecha,$nit_id,$cen_cos_id,$val_devolucion,$obt_conse,$fecha,$obt_conse,$_SESSION['k_nit_id'],$fecha,$mes_contable[1],$ano);
if($gua_transaccion)
{
	$cant_cuentas=0;
	$query="EXECUTE insMovimiento '$lasigla','$obt_conse','$cue_devolucion','$obt_conse','$nit_id','$cen_cos_id','$val_devolucion','1','$obt_conse','$_SESSION[k_nit_id]','0','0','$fecha','$mes_contable[1]','$_SESSION[elaniocontable]','$bas_retencion'";
	$ejecutar=mssql_query($query);
	$cant_cuentas++;
	if($query)
	{
		$cuenta_fabs='25052007';
		$nit_fabs=$nit_id."_1";
		
		$val_total=$val_devolucion-$val_retencion;
		
		$query2="EXECUTE insMovimiento '$lasigla','$obt_conse','$cuenta_fabs','$obt_conse','$nit_fabs','$cen_cos_id','$val_total','2','$obt_conse','$_SESSION[k_nit_id]','0','0','$fecha','$mes_contable[1]','$_SESSION[elaniocontable]','$bas_retencion'";
		$ejecutar=mssql_query($query2);
		$cant_cuentas++;
		if($query2)
		{
			
			if($_POST['tie_retencion']=="on")
			{
			$nit_retencion=$nit_id;
			$query3="EXECUTE insMovimiento '$lasigla','$obt_conse','$cuenta_retencion','$obt_conse','$nit_retencion','$cen_cos_id','$val_retencion','2','$obt_conse','$_SESSION[k_nit_id]','0','0','$fecha','$mes_contable[1]','$_SESSION[elaniocontable]','$bas_retencion'";
			$ejecutar=mssql_query($query3);
			$cant_cuentas++;
			}
				$mov="EXECUTE movContable $cant_cuentas";
				$ins_mov=mssql_query($mov);
				if($mov)
				{
					
					//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
					$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
					aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
					aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
					aud_mov_con_descripcion='$aud_mov_con_descripcion'
					WHERE mov_compro='$lasigla' AND mov_mes_contable='$mes_contable[1]' AND mov_ano_contable='$_SESSION[elaniocontable]'
					AND tip_mov_aud_id IS NULL";
					//echo $que_aud_mov_contable;
					$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
					
					
					$pro_id=189;
					$gua_reg_com_fabs=$ins_fabs->guardar_compraPABS($nit_id,$fecha,$pro_id,$val_total,$obs_devolucion,1,$nit_id,0,1,1,$lasigla,$mes_contable[1],$_SESSION['elaniocontable']);
					if($gua_reg_com_fabs)
						echo "<script>alert('Devolucion de FABS registrada correctamente.');history.back(-1);</script>";
					else
						echo "<script>alert('Error al registrar la devolucion de FABS, Intentelo de nuevo.');history.back(-1);</script>";
				}
		}
	}
}
?>