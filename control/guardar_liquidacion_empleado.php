<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/nomina.class.php');
include_once('../clases/liquidacion_empleado.class.php');
$ins_liq_empleado = new Liquidacion_empleado();
$ins_nomina = new nomina();
$tran = new transacciones();
$movimiento = new movimientos_contables();
$ins_nits = new nits();
$ins_credito = new credito();
$ins_cen_cos = new centro_de_costos();
$nit_id = $_SESSION['nit_id'];//Asociado
$sum_saldo = $_POST['sum_saldo'];//Valor A Consignar
$cue_id = $_POST['cue_id'];//Cuenta
$naturaleza = $_POST['naturaleza'];//Naturaleza Del Movimiento
$valor = $_POST['valor'];//Valor Del Movimiento
$fecha = date("d-m-Y");

$bas_retencion=0;

$con_cre_por_nit_sin_pagar = $ins_credito->con_cre_por_nit_estado($nit_id,2);
$con_nom_por_nit_sin_pagar = $ins_nomina->con_nom_por_nit_estado($nit_id,2);
$num_creditos = mssql_num_rows($con_cre_por_nit_sin_pagar);
if($num_creditos < 1)
{
	$num_creditos = 0;
	$res_creditos = $num_creditos;
}
else
{
	while($creditos = mssql_fetch_array($con_cre_por_nit_sin_pagar))
		$res_creditos = $res_creditos.$creditos['cre_id'].",";
}

$num_nominas = mssql_num_rows($con_nom_por_nit_sin_pagar);
if($num_nominas < 1)
{
	$num_nominas = 0;
	$res_nominas = $num_nominas;
}
else
{
	while($nominas = mssql_fetch_array($con_nom_por_nit_sin_pagar))
		$res_nominas = $res_nominas.$nominas['nom_id'].",";
}
////////***DATOS FORM***////////
$liq_emp_observaciones = strtoupper($_POST['liq_emp_observaciones']);
$mes_sele = $_POST['mes_sele'];
$mes_contable = split("-",$mes_sele);//VOY AK, TENGO QUE ENVIARLO EN LA TRANSACCION
////////////////////////GUARDAR DEVOLUCIÓN APORTES//////////////////////
$fecha = date("d-m-Y");
$gua_liq_empleado = $ins_liq_empleado->ins_liq_empleado($fecha,$liq_emp_observaciones,$nit_id,$res_creditos,$res_nominas);
if($gua_liq_empleado)
	echo "<script>alert('Liquidacion de empleado registrada correctamente.');</script>";
///AK
if($sum_saldo < 0)
{
	$con_cod_credito = $ins_credito->con_codeudor($nit_id);
	$con_nom_nit = $ins_nits->cons_nombres_nit($con_cod_credito);
	$res_nom_nit = mssql_fetch_array($con_nom_nit);
	$nombres = $res_nom_nit['nombres'];
	/*INICIO ACTUALIZO EL ESTADO DEL NIT*/
	$act_est_nit = $ins_nits->act_est_nit(5,$nit_id);
	/*FIN ACTUALIZO EL ESTADO DEL NIT*/
	echo "<script>alert('Ud debe registrarle un credito a $nombres, debido a que el afiliado al que se le va a realizar la devolucion de aportes tiene un credito pendiente por pagar.');</script>";
	//INICIO EJECUTO EL MOVIMIENTO PARA BALANCEAR LAS CUENTAS
	$i = 0;
	while($i < sizeof($cue_id))
	{
		$con_cen_cos = $ins_cen_cos->con_cen_cos_pabs(65);
		$res_cen_cos_pabs = mssql_fetch_array($con_cen_cos);
		$resul_cen_cos_pabs = $res_cen_cos_pabs['cen_cos_id'];
		$consecutivo = $tran->obtener_concecutivo();
		$cue = mssql_fetch_array($consecutivo);
		$transacciones = $cue['max_id'];
		$conse = $cue['max_id'] + 1;
		$sigla = "Liq_Emp_".$conse;
		$nueTran = $tran->guaTransaccion($sigla,$fecha,$nit_id,$resul_cen_cos_pabs,$valor[$i],0,$fecha,0,$_SESSION["k_nit_id"],$fecha,$mes_contable[1],$ano);
		if($nueTran)
		{
			echo "<script>alert('Se ejecuto la transaccion correctamente.');</script>";		  
			$transacc = $tran->obtener_concecutivo();
			$num_tran = mssql_fetch_array($transacc);
			if($naturaleza[$i] == 1)
				$naturaleza[$i] = 2;
			else
				$naturaleza[$i] = 1;				
			$mov = $movimiento->guaMovimiento(strtoupper($sigla),$conse,$cue_id[$i],$cue_id[$i],$nit_id,$resul_cen_cos_pabs,$valor[$i],$naturaleza[$i],$conse,$_SESSION['k_nit_id'],1,0,1,$mes_contable[1],$ano,$bas_retencion);
			}
	$i++;
	}
}
else
	echo "Se debe crear una cuent por pagar al afiliado.";
?>