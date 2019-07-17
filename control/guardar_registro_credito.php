<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/transacciones.class.php');
$instancia_credito = new credito();
$movimiento = new movimientos_contables();
$tran = new transacciones();
//INICIO CAPTURO LO QUE VIENE DEL FORMULARIO ANTERIOR
$_SESSION['num_cuotas'] = $_POST['num_cuotas'];
$_SESSION['fecha3'] = $_POST['fecha3'];
$_SESSION['pagar'] = $_POST['pagar'];
$_SESSION['capital'] = $_POST['capital'];
$_SESSION['interes_pag'] = $_POST['interes_pag'];
$_SESSION['cre_valor_1'] = $_POST['cre_valor_1'];
//FIN CAPTURO LO QUE VIENE DEL FORMULARIO ANTERIOR
//Inicio Captura Formulario 1
$sel_persona = $_SESSION['sel_persona'];
//Fin Captura Formulario 1
//Inicio Captura Formulario 2
$cre_linea = $_SESSION['cre_linea'];
$cre_cen_cos = $_SESSION['cre_cen_cos'];
$cre_observacion = strtoupper($_SESSION['cre_observacion']);
$cre_valor = $_SESSION['cre_valor'];

$cre_men = $_SESSION['cre_men'];
$cre_dtf = $_SESSION['cre_dtf'];
//
$cre_interes = $_SESSION['cre_interes'];
//
$cre_num_cuotas = $_SESSION['cre_num_cuotas'];
$cre_tip_descuento = $_SESSION['cre_tip_descuento'];
$cre_codeudor = $_SESSION['cre_codeudor'];
$cre_fec_solicitud = $_SESSION['cre_fec_solicitud'];
$cre_fec_pri_pago = $_SESSION['cre_fec_pri_pago'];
$cre_fec_vencimiento = $_SESSION['cre_fec_vencimiento'];
$cre_for_liquidacion = $_SESSION['cre_for_liquidacion'];

$cre_garantia = $_SESSION['cre_garantia'];
$cre_tip_garantia = $_SESSION['cre_tip_garantia'];
$cre_sec_tra_carro = $_SESSION['cre_sec_tra_carro'];

$cre_num_pla_carro = strtoupper($_SESSION['cre_num_pla_carro']);
if($cre_num_pla_carro=="")
$cre_num_pla_carro = "NULL";

$cre_num_esc_casa = $_SESSION['cre_num_esc_casa'];
if($cre_num_esc_casa=="")
$cre_num_esc_casa = "NULL";


$cre_num_not_casa = strtoupper($_SESSION['cre_num_not_casa']);
if($cre_num_not_casa=="")
$cre_num_not_casa = "NULL";

$cre_fec_con_casa = $_SESSION['cre_fec_con_casa'];
if($cre_fec_con_casa=="")
$cre_fec_con_casa = "NULL";


$cre_nota = strtoupper($_SESSION['cre_nota']);

//Fin Captura Formulario 2
//Inicio Captura Formulario 4
$num_cuotas = $_SESSION['num_cuotas'];
$fecha3 = $_SESSION['fecha3'];

$pagar = $_SESSION['pagar'];
$capital = $_SESSION['capital'];
$interes_pag = $_SESSION['interes_pag'];
$cre_valor_1 = $_SESSION['cre_valor_1'];
//Fin Captura Formulario 4
$conse = $instancia_credito->obt_consecutivo();
$act_conse = $instancia_credito->act_consecutivo();
?>
<script>
function VentanaEmergente(tipo,credito_id,persona_id)
{
	//tipo=1 creacion, tipo=2 consulta
	URL='../reportes_PDF/tabla_amortizacion_credito.php?cre_id='+credito_id+'&per_id='+persona_id+'&tip_reporte='+tipo;
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=900,height=500,left=240,top=112');");
}

</script>
<?php
$guardar_registro_credito = $instancia_credito->ins_reg_credito($sel_persona,$cre_linea,$cre_cen_cos,$cre_observacion,str_replace(',','',$cre_valor),$cre_men,$cre_dtf,$cre_num_cuotas,$cre_tip_descuento,$cre_fec_solicitud,$cre_fec_pri_pago,$cre_fec_vencimiento,$cre_for_liquidacion,$cre_garantia,$cre_tip_garantia,$cre_sec_tra_carro,$cre_num_pla_carro,$cre_num_esc_casa,$cre_num_not_casa,$cre_fec_con_casa,$cre_nota,$cre_codeudor);
if($guardar_registro_credito)
{
	$j = 0;
	while($j < sizeof($num_cuotas))
	{
		$fec = split('-',$fecha3[$j],3);
		$mes=$fec[1]-1;
		$ano=$fec[2];
		if($mes<=0)
		{
			$mes = 12;
			$ano--;
		}
		
		$fecha3[$j]=$fec[0]."-".$mes."-".$ano;
		$tam_fechas=sizeof($fecha3);
		$fec_ven_credito=$fecha3[$tam_fechas-1];
		$guardar_tabla_amortizacion = $instancia_credito->ins_tab_amortizacion($num_cuotas[$j],$fecha3[$j],str_replace(',','',$pagar[$j]),str_replace(',','',$capital[$j]),str_replace(',','',$interes_pag[$j]),str_replace(',','',$cre_valor_1[$j]),$fec_ven_credito);
		$j++;
	}
}

if($guardar_tabla_amortizacion)
{
	$credito_id=$instancia_credito->ultCredito();
	echo "<script>alert('Credito registrado correctamente, pagare # ".$credito_id."');</script>";
	echo "<script>VentanaEmergente(1,$credito_id,$sel_persona);</script>";
}
else
	echo "<script>alert('Error al registrar el credito, Intentelo de nuevo.');//location.href = '../index.php?c=47';</script>";
				  
				  
				  

//LIMPIAR SESSIONES//
unset($_SESSION['num_cuotas']);
unset($_SESSION['fecha3']);
unset($_SESSION['pagar']);
unset($_SESSION['capital']);
unset($_SESSION['interes_pag']);
unset($_SESSION['cre_valor_1']);
unset($_SESSION['sel_persona']);
unset($_SESSION['cre_linea']);
unset($_SESSION['cre_cen_cos']);
unset($_SESSION['cre_observacion']);
unset($_SESSION['cre_valor']);
unset($_SESSION['cre_dtf']);
unset($_SESSION['cre_interes']);
unset($_SESSION['cre_num_cuotas']);
unset($_SESSION['cre_tip_descuento']);
unset($_SESSION['cre_codeudor']);
unset($_SESSION['cre_fec_solicitud']);
unset($_SESSION['cre_fec_pri_pago']);
unset($_SESSION['cre_fec_vencimiento']);
unset($_SESSION['cre_for_liquidacion']);
unset($_SESSION['cre_garantia']);
unset($_SESSION['cre_tip_garantia']);
unset($_SESSION['cre_sec_tra_carro']);
unset($_SESSION['cre_num_pla_carro']);
unset($_SESSION['cre_num_esc_casa']);
unset($_SESSION['cre_num_not_casa']);
unset($_SESSION['cre_fec_con_casa']);
unset($_SESSION['cre_nota']);
unset($_SESSION['cre_men']);
/////////////////////

?>