<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$id_asociado = $_SESSION['aso_id'];
//echo "dato: ".$_POST['aso_mon_fij_seg_social']."<br>";
//INICIO CAPTURO LOS DATOS DE LOS DATOS DE ASOCIACIÓN
$_SESSION['aso_banco'] = strtoupper($_POST['aso_banco']);
$_SESSION['aso_tip_cuenta'] = strtoupper($_POST['aso_tip_cuenta']);
$_SESSION['aso_num_cuenta'] = strtoupper($_POST['aso_num_cuenta']);
$_SESSION['aso_eps'] = strtoupper($_POST['aso_eps']);
$_SESSION['aso_arp'] = strtoupper($_POST['aso_arp']);
$_SESSION['aso_tip_seg_social'] = $_POST['aso_tip_seg_social'];
$_SESSION['aso_mon_fij_seg_social'] = $_POST['aso_mon_fij_seg_social'];
$_SESSION['tipo_descuento_seg_social'] = $_POST['tipo_descuento_seg_social'];
$_SESSION['aso_mes_ini_cot_mon_fijo']=$_POST['aso_mes_ini_cot_mon_fijo'];
$_SESSION['aso_ano_ini_cot_mon_fijo']=$_POST['aso_ano_ini_cot_mon_fijo'];
$_SESSION['aso_fec_act_ini_cot_mon_fijo']=date('d-m-Y');




//FIN CAPTURO LOS DATOS DE LOS DATOS DE ASOCIACIÓN
$aso_banco = $_SESSION['aso_banco'];
$aso_tip_cue_bancaria = $_SESSION['aso_tip_cuenta'];
$aso_num_cue_bancaria = $_SESSION['aso_num_cuenta'];
$aso_eps = $_SESSION['aso_eps'];
$aso_arp = $_SESSION['aso_arp'];
$aso_tip_seg_social = $_SESSION['aso_tip_seg_social'];
$aso_mon_fij_seg_social = $_SESSION['aso_mon_fij_seg_social'];
$tipo_descuento_seg_social=$_SESSION['tipo_descuento_seg_social'];

$aso_mes_ini_cot_mon_fijo=$_SESSION['aso_mes_ini_cot_mon_fijo'];
$aso_ano_ini_cot_mon_fijo=$_SESSION['aso_ano_ini_cot_mon_fijo'];
$aso_fec_act_ini_cot_mon_fijo=$_SESSION['aso_fec_act_ini_cot_mon_fijo'];

if(trim($aso_mes_ini_cot_mon_fijo)=="")
	$aso_mes_ini_cot_mon_fijo=0;

if(trim($aso_ano_ini_cot_mon_fijo)=="")
	$aso_ano_ini_cot_mon_fijo=0;
//echo "Los datos son: ".$aso_mes_ini_cot_mon_fijo."___".$aso_ano_ini_cot_mon_fijo."___".$aso_fec_act_ini_cot_mon_fijo."<br>";

if($tipo_descuento_seg_social)
{
	$tipo_descuento_seg_social=1;
}
else
{
	$tipo_descuento_seg_social=2;
	$aso_mon_fij_seg_social=0;
}

//echo $aso_mon_fij_seg_social;
$actualizar_datos_asociacion_asociado=$instancia_nits->act_dat_aso_asociado($aso_banco,$aso_tip_cue_bancaria,$aso_num_cue_bancaria,$aso_eps,$aso_arp,$aso_tip_seg_social,$aso_mon_fij_seg_social,$tipo_descuento_seg_social,$aso_mes_ini_cot_mon_fijo,$aso_ano_ini_cot_mon_fijo,$aso_fec_act_ini_cot_mon_fijo,$id_asociado);

//LIMPIAR SESSIONES//
unset($_SESSION['aso_banco']);
unset($_SESSION['aso_tip_cuenta']);
unset($_SESSION['aso_num_cuenta']);
unset($_SESSION['aso_eps']);
unset($_SESSION['aso_arp']);
unset($_SESSION['aso_tip_seg_social']);
unset($_SESSION['aso_mon_fij_seg_social']);
/////////////////////

if($actualizar_datos_asociacion_asociado)
{
	echo "<script>alert('Asociado actualizado correctamente');
			location.href = '../formularios/consultar_asociado_3.php';
	     </script>";
}
else
{
	echo "<script>alert('Error al actualizar el afiliado, Intentelo de nuevo.');
			location.href = '../formularios/consultar_asociado_3.php';
	     </script>";
}
?>