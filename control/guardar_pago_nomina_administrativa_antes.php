<?PHP session_start();
$ano=$_SESSION['elaniocontable'];
$documento=$_GET['id'];
@include_once('clases/transacciones.class.php');
@include_once('clases/factura.class.php');
@include_once('../clases/transacciones.class.php');
@include_once('../clases/factura.class.php');
@include_once('clases/nomina.class.php');
@include_once('../clases/nomina.class.php');
$ins_nomina=new nomina();
$ins_transaccion=new transacciones();
$ins_factura=new factura();
$obt_con_pago=$ins_factura->obt_consecutivo(32);
$con_pago=$obt_con_pago+1;
$sigla_pago="PAG_NOM_ADM-".$con_pago;
//INICIO CAPTURA DATOS FORMULARIO ANTERIOR
$com_pag_nomina=$_GET['lasigla'];
$val_pag_nomina=$_POST['val_pag_nomina'];
//FIN CAPTURA DATOS FORMULARIO ANTERIOR


$bas_retencion=0;

$fecha=date('d-m-Y');
$mes=explode("-",$_POST['elmes'.$_GET['laposicion']],2);
//SEDAR
$nit_id=380;
//PRINCIPAL
$cen_cos_id=1169;
//QUINCENA
$com_pag_nomina=$_POST['com_pag_nomina'];
$num_quincena=$ins_transaccion->con_num_quincena($com_pag_nomina);

//TRAER EMPLEADOS QUE ESTÁN EN LA NOMINA SELECCIONADA//
$con_emp_por_nomina=$ins_factura->con_emp_por_nomina($com_pag_nomina);
$numero_filas=mssql_num_rows($con_emp_por_nomina);
///////////////////////////////////////////////////////

$mes_pagado=$ins_nomina->ConMesPagNomAdministrativa($com_pag_nomina);
/*
$gua_transaccion = $ins_transaccion->guaTransaccion(strtoupper($sigla_pago),$fecha,$nit_id,$cen_cos_id,$val_pag_nomina,$con_pago,$fecha,$num_quincena,$_SESSION['k_nit_id'],$fecha,$mes[1]);
*/

$query="INSERT INTO dbo.transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,trans_fec_vencimiento,trans_fac_num,trans_user,trans_fec_grabado,tran_mes_contable,trans_ano_contable,trans_observacion)VALUES('$sigla_pago','$fecha','$nit_id','$cen_cos_id','$val_pag_nomina','0','$fecha','$num_quincena','$_SESSION[k_nit_id]','$fecha','$mes[1]','$ano','$com_pag_nomina')";
$ejecutar=mssql_query($query);



/*
$cant_cuentas=0;
$a=0;
//while($a<$numero_filas){
if($gua_transaccion)
{
	while($res_empleados=mssql_fetch_array($con_emp_por_nomina))
	{
		$con_val_pagar=$ins_factura->con_cue_pag_nomina($com_pag_nomina,$res_empleados['mov_nit_tercero'],25050501);
		$res_val_pagar=mssql_fetch_array($con_val_pagar);
		//CUENTA POR PAGAR
		//$num_quincena Queda en la columna mov_concepto de movimientos_contables
		$query="EXECUTE insMovimiento '$sigla_pago',$con_pago,'25050501',$num_quincena,'$res_empleados[mov_nit_tercero]',$cen_cos_id,$res_val_pagar[mov_valor],1,'$mes_pagado','$_SESSION[k_nit_id]',0,0,'$fecha','$mes[1]',$ano,'$bas_retencion'";
		//echo $query;
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
		//CUENTA BANCO
		//$num_quincena Queda en la columna mov_concepto de movimientos_contables
		$query="EXECUTE insMovimiento '$sigla_pago',$con_pago,'11100524',$num_quincena,'$res_empleados[mov_nit_tercero]',$cen_cos_id,$res_val_pagar[mov_valor],2,'$mes_pagado','$_SESSION[k_nit_id]',0,0,'$fecha','$mes[1]',$ano,'$bas_retencion'";
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
		$a++;
	}
	//////////FIN CRUZAR CUENTA POR PAGAR CON CUENTA DEL BANCO///////////
	$mov = "EXECUTE movContable $cant_cuentas";
	$ins_mov = mssql_query($mov);
	if($ins_mov)
	{
		//EL ESTADO 1 ES CAUSADO Y EL 2 ES PAGADO
		$act_est_nom_adm_causada=$ins_transaccion->act_est_nom_adm_causada(2);
		$act_est_nom_administrativa=$ins_transaccion->act_est_nom_adm_pagada(2,$com_pag_nomina);
		//////////INICIO CRUZAR CUENTA POR PAGAR CON CUENTA DEL BANCO///////////
		$act_consecutivo=$ins_factura->act_consecutivo(32);
		echo "<script>alert('Nomina pagada correctamente!!!');</script>";
	}
}
*/
?>