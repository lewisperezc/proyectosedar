<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/pabs.class.php');
$mov_contable = new movimientos_contables();
$transaccion = new transacciones();
$ins_fabs=new pabs();

$cantidad = $_POST['can_registros'];
$comprobante = $_POST['compro'];
$num_comprobante = $_POST['nume'];
$fecha = $_POST["fecha"];
$mes = $_POST['mes_sele'];
$dat_mes = split('-',$mes,2);
$mes_pago=$dat_mes[1];

$bas_retencion=0;




$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=2;
$aud_mov_con_descripcion='MODIFICACION DE DOCUMENTO DE CONTABILIDAD';


if(strpos($comprobante, 'REC-CAJ')!== false)
	$borrar = $mov_contable->modificarRecibo($comprobante,$dat_mes[0],$dat_mes[1]);
else
	$borrar = $mov_contable->borrarDocumento($comprobante,$dat_mes[0],$dat_mes[1]);
if($borrar)
{
  for($i=0;$i<$cantidad;$i++)
  {
  		$asociado = $_POST['nom_gua'.$i];
		if($_POST['debito'.$i]!=0)
		  {
			$valor = explode(",",$_POST['debito'.$i]);
			$val_gua="";
			for($j=0;$j<sizeof($valor);$j++)
			  $val_gua.=$valor[$j];
			$naturaleza = 1;
		  }
		else
		  {
			$valor = explode(",",$_POST['credito'.$i]);
			$val_gua="";
			for($j=0;$j<sizeof($valor);$j++)
			  $val_gua.=$valor[$j];
			$naturaleza = 2;
		  }
		if($i==0)
		{
			$pro_id=189;
			$obs_devolucion="DEVOLUCION DE FABS";
			$nueTran = $transaccion->guaTransaccion(strtoupper($comprobante),$fecha,$asociado,$_POST['cen_gua'.$i],$val_gua,0,$fecha,$num_comprobante,$_SESSION['k_nit_id'],$fecha,$mes_pago,$ano);
			$gua_reg_com_fabs=$ins_fabs->guardar_compraPABS($asociado,$fecha,$pro_id,$val_gua,$obs_devolucion,1,$asociado,0,1,1,$comprobante,$mes_pago,$ano);
		}
		
		if(($_POST['cuenta'.$i]!=0 || $_POST['cuenta'.$i]!='') && ($asociado!=0 || $asociado!=''))
		{		
			$sql = "EXECUTE insMovimiento '$comprobante','$num_comprobante','".$_POST['cuenta'.$i]."','2','$asociado','".$_POST['cen_gua'.$i]."','$val_gua','$naturaleza','$num_comprobante','3','0','$cantidad','".$fecha."','$mes_pago','$ano','$bas_retencion'";
			//echo $sql."<br>";
			$query = mssql_query($sql);
		}
}
$query = "SELECT COUNT(*) cant FROM mov_contable";
$cant_mov = mssql_query($query);
$cantidad = mssql_fetch_array($cant_mov);
$mov = "EXECUTE movContable ".$cantidad['cant'];
$ins_mov = mssql_query($mov);


//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
aud_mov_con_descripcion='$aud_mov_con_descripcion'
WHERE mov_compro='$comprobante' AND mov_mes_contable='$mes_pago' AND mov_ano_contable='$ano'
AND tip_mov_aud_id IS NULL";
//echo $que_aud_mov_contable;
$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);



if($ins_mov)
	echo "<script> alert('Se modifico el documento satisfactoriamente.');</script>";
}
else
	echo "<script>alert('Error al modificar el movimiento, Intentelo de nuevo.');</script>";

echo "<script>history.back(-1);</script>";
//
?>