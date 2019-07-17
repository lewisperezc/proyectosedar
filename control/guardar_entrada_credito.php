<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/saldos.class.php');
include_once('../clases/comprobante.class.php'); ?>

<script src="../librerias/js/separador.js">
function abreFactura(URL)
{
	alert('entra al abrefactura');
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
}
</script>

<?php
$inst_credito = new credito();
$inst_transaccion = new transacciones();
$mov_con = new movimientos_contables();
$ins_cuenta = new cuenta();
$ins_saldos=new saldos();
$comprobante= new comprobante();

$mes_sele = $_POST['mes_sele'];
$mes_contable = split("-",$mes_sele);
$credito_selec = split("---",$cre_sele,2);
$obt_conse = $comprobante->cons_comprobante($_SESSION['elaniocontable'],$mes_contable[1],47);
$sig = $comprobante->sig_comprobante(47);
$comprobante->act_comprobante($_SESSION['elaniocontable'],$mes_contable[1],47);
$sigla=$sig.$obt_conse;
$fecha=date('d-m-Y');
$cuantos=$_POST['cuantos'];

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

$aud_mov_con_descripcion='CREACION DE ENTRADA DE CREDITO(CONSIGNACION)';



for($i=0;$i<$cuantos;$i++)
{
	//Inicio Datos Que Capturo Del Formulario form_ent_credito.php
	$tercero = strtoupper($_POST['ent_nit'.$i]);
	$credito = $_POST['ent_credito'.$i];
	$fec_pago = $_POST['ent_fec_pago'.$i];
	$valor = $_POST['ent_val_pago'.$i];
	$nota = strtoupper($_POST['ent_nota'.$i]);

	if(!empty($_POST['fabs'.$i]))
	{
		if($_POST['fabs'.$i]='on')
			$cuenta = $_POST['ent_cuentaFabs'.$i];
	}
	else
		$cuenta = $_POST['ent_cuenta'.$i];

	$con_dat_cen_costo=$inst_credito->ConsultarDatosCreditoPorId($credito);
	$res_cen_costo=mssql_fetch_array($con_dat_cen_costo);
	$centro = $res_cen_costo['cen_cos_id'];

	$mos_credito = $inst_credito->cueCreditos($credito,$tercero,$centro,$credito);
	$dat_retorno = split("--",$mos_credito);

	$act = $inst_transaccion->num_tran($credito);
	$act_tran = $inst_transaccion->act_transaccion($act,$dat_retorno[4]);
	$consecutivo = $inst_transaccion->obtener_concecutivo();
	$cue = mssql_fetch_array($consecutivo);

	$dat_retorno[1]=$_POST['ent_val_int'.$i];
	$total=$valor+$dat_retorno[1];

	$inst_credito->des_cuoCredito($valor,$dat_retorno[1],$credito,$credito,$fecha,$tercero,3,$sigla,$mes_contable[1],$ano);

	$sqlBanco ="EXECUTE insMovimiento '$sigla','$credito','$cuenta','$credito','$tercero','$centro','$total','1','$credito','$credito','0','1','$fecha','$mes_contable[1]','$ano','$bas_retencion'";
	$queryBanco=mssql_query($sqlBanco);

	$sqlContInteres="EXECUTE insMovimiento '$sigla','$credito','$dat_retorno[5]','$credito','$tercero','$centro','$dat_retorno[1]','2','$credito','$credito','0','1','$fecha','$mes_contable[1]','$ano','$bas_retencion'";
	$queryContInteres=mssql_query($sqlContInteres);
	$capital=$valor;
	$sqlCredito="EXECUTE insMovimiento '$sigla','$credito','$dat_retorno[2]','$credito','$tercero','$centro','$capital','2','$credito','$credito','0','1','$fecha','$mes_contable[1]','$ano','$bas_retencion'";
	$queryCredito=mssql_query($sqlCredito);

	$nueTran = $inst_transaccion->guaPagTransaccion(strtoupper($sigla),$fec_pago,$tercero,$centro,$capital,0,$fecha,$credito,$_SESSION['k_nit_id'],$fecha,$dat_retorno[4],$act,$mes_contable[1],$ano);
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
	WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_contable[1]' AND mov_ano_contable='$ano'
	AND tip_mov_aud_id IS NULL";
	//echo $que_aud_mov_contable;
	$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	
	
	
	
	$actualizar_cre = $inst_credito->ultimo_pago($credito);
	$saldo=0;
	$con_mov_credito=$ins_saldos->con_cuo_pagadas($dat_retorno[2],$tercero,$centro,$credito,2);
	$con_mov_debito=$ins_saldos->con_cuo_pagadas($dat_retorno[2],$tercero,$centro,$credito,2);
	while($res_credito=mssql_fetch_array($con_mov_credito))
		$credito=$credito+$res_credito['mov_valor'];
	while($res_debito=mssql_fetch_array($con_mov_debito))
		$debito=$debito+$res_debito['mov_valor'];
	$resultado=$debito-$credito;
	if($resultado==0)
		$act_est_credito=$inst_credito->act_est_credito(1,$credito);

}
echo "<script>abreFactura('../reportes_PDF/causacion_pago.php?sigla=".strtoupper($sigla)."&mes=".$mes_contable[1]."',1);</script>";
?>