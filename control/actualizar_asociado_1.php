<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
//INICIO CAPTURA ID EMPLEADO
$id_asociado = $_SESSION['aso_id'];
//echo "El Asociado Es:".$id_asociado;
//INICIO DATOS PERSONALES ASOCIADO
$_SESSION['aso_pri_apellido'] = strtoupper($_POST['aso_pri_apellido']);
$_SESSION['aso_seg_apellido'] = strtoupper($_POST['aso_seg_apellido']);
$_SESSION['aso_nombres'] = strtoupper($_POST['aso_nombres']);
$_SESSION['aso_tip_documento'] = strtoupper($_POST['aso_tip_documento']);
$_SESSION['aso_num_documento'] = strtoupper($_POST['aso_num_documento']);
$_SESSION['aso_nac_fecha'] = strtoupper($_POST['aso_nac_fecha']);
$_SESSION['aso_genero'] = strtoupper($_POST['aso_genero']);
$_SESSION['aso_est_civil'] = strtoupper($_POST['aso_est_civil']);
$_SESSION['aso_dir_residencia'] = strtoupper($_POST['aso_dir_residencia']);
$_SESSION['aso_tel_residencia'] = strtoupper($_POST['aso_tel_residencia']);
$_SESSION['aso_num_celular'] = strtoupper($_POST['aso_num_celular']);
$_SESSION['aso_cor_electronico'] = strtoupper($_POST['aso_cor_electronico']);

$_SESSION['porSegSocial']=$_POST['porSegSocial'];

$_SESSION['aso_cor_electronico_adicional'] = strtoupper($_POST['aso_cor_electronico_adicional']);
if($_SESSION['aso_cor_electronico_adicional'] == "")
$_SESSION['aso_cor_electronico_adicional'] == " ";

$_SESSION['aso_por_pabs'] = strtoupper($_POST['aso_por_pabs']);
if($_SESSION['aso_por_pabs'] == "")
$_SESSION['aso_por_pabs'] == 0;

$_SESSION['aso_tip_procedimiento']=$_POST['aso_tip_procedimiento'];
if($_SESSION['aso_tip_procedimiento']==1||$_SESSION['aso_tip_procedimiento']=="")
	$_SESSION['aso_por_ret_fuente2']=0;
else
{
	$_SESSION['elprocedimiento']=$_POST['elprocedimiento'];
	if($_SESSION['elprocedimiento']==1)
	{
		$_SESSION['aso_por_ret_fuente2']=strtoupper($_POST['aso_por_ret_fuente']);
		if($_SESSION['aso_por_ret_fuente2']==trim(""))
			$_SESSION['aso_por_ret_fuente2']=0;
	}
	else
	{
		$_SESSION['aso_por_ret_fuente2']=strtoupper($_POST['aso_por_ret_fuente2']);
		if($_SESSION['aso_por_ret_fuente2']==trim(""))
			$_SESSION['aso_por_ret_fuente2']=0;
	}
}

$_SESSION['aso_por_fon_retiro_sindical']=$_POST['aso_por_fon_retiro_sindical'];

$_SESSION['aso_fon_vacaciones'] = strtoupper($_POST['aso_fon_vacaciones']);
if($_SESSION['aso_fon_vacaciones'] == "ON"){
$_SESSION['aso_fon_vacaciones'] = "SI";
$_SESSION['aso_por_fon_pensiones'] = $_POST['aso_por_fon_pensiones'];
}
else{
$_SESSION['aso_fon_vacaciones'] = "NO";
$_SESSION['aso_por_fon_pensiones'] = "NULL";
}

$_SESSION['select2'] = strtoupper($_POST['select2']);
$_SESSION['select4'] = strtoupper($_POST['select4']);

$_SESSION['aso_fec_retiro'] = strtoupper($_POST['aso_fec_retiro']);
$_SESSION['aso_fec_afiliacion'] = strtoupper($_POST['aso_fec_afiliacion']);


//FIN DATOS PERSONALES ASOCIADO
$aso_apellidos = $_SESSION['aso_pri_apellido']." ".$_SESSION['aso_seg_apellido'];
$aso_nombres = $_SESSION['aso_nombres'];
$aso_tip_documento = $_SESSION['aso_tip_documento'];
$aso_num_documento = $_SESSION['aso_num_documento'];
$aso_nac_fecha = $_SESSION['aso_nac_fecha'];
$aso_genero = $_SESSION['aso_genero'];
$aso_est_civil = $_SESSION['aso_est_civil'];
$aso_dir_residencia = $_SESSION['aso_dir_residencia'];
$aso_tel_residencia = $_SESSION['aso_tel_residencia'];
$aso_num_celular = $_SESSION['aso_num_celular'];
$aso_cor_electronico = $_SESSION['aso_cor_electronico'];

$aso_cor_electronico_adicional = $_SESSION['aso_cor_electronico_adicional'];

$aso_por_pabs = $_SESSION['aso_por_pabs'];

$aso_tip_procedimiento=$_SESSION['aso_tip_procedimiento'];
$aso_por_ret_fuente = $_SESSION['aso_por_ret_fuente2'];

$aso_por_fon_retiro_sindical=$_SESSION['aso_por_fon_retiro_sindical'];

$aso_fon_vacaciones = $_SESSION['aso_fon_vacaciones'];

$aso_por_fon_pensiones = $_SESSION['aso_por_fon_pensiones'];

$aso_ciu_nacimiento = $_SESSION['select2'];
$des_ubi_1_asociado = 1;
$aso_ciu_residencia = $_SESSION['select4'];
$des_ubi_2_asociado = 2;

$porSegSocial = $_SESSION['porSegSocial'];
if($porSegSocial==trim(""))
	$porSegSocial=0;
	

$aso_fec_retiro=$_SESSION['aso_fec_retiro'];
$aso_fec_afiliacion=$_SESSION['aso_fec_afiliacion'];

$actualizar_datos_personales_asociado = $instancia_nits->act_dat_per_asociado($aso_apellidos,$aso_nombres,$aso_tip_documento,$aso_num_documento,$aso_nac_fecha,$aso_genero,$aso_est_civil,$aso_dir_residencia,$aso_tel_residencia,$aso_num_celular,$aso_cor_electronico,
$aso_cor_electronico_adicional,$aso_por_pabs,$aso_por_ret_fuente,$aso_fon_vacaciones,$aso_por_fon_pensiones,$porSegSocial,$aso_tip_procedimiento,$aso_por_fon_retiro_sindical,$aso_fec_retiro,$aso_fec_afiliacion,$id_asociado);

$actualizar_ciudad_dpto_1_asociado=$instancia_nits->act_ciu_dep_1_asociado($aso_ciu_nacimiento,$des_ubi_1_asociado,$id_asociado);

$actualizar_ciudad_dpto_2_asociado=$instancia_nits->act_ciu_dep_1_asociado($aso_ciu_residencia,$des_ubi_2_asociado,$id_asociado);

if($actualizar_datos_personales_asociado&&$actualizar_ciudad_dpto_1_asociado&&$actualizar_ciudad_dpto_2_asociado)
	echo "<script>alert('Afiliado actualizado correctamente.');location.href = '../formularios/consultar_asociado_2.php';</script>";
else
	echo "<script>alert('Error al actualizar el afiliado, Intentelo de nuevo.');location.href = '../formularios/consultar_asociado_2.php';</script>";


//LIMPIAR SESSIONES//
unset($_SESSION['aso_pri_apellido']);
unset($_SESSION['aso_seg_apellido']);
unset($_SESSION['aso_nombres']);
unset($_SESSION['aso_tip_documento']);
unset($_SESSION['aso_num_documento']);
unset($_SESSION['aso_nac_fecha']);
unset($_SESSION['aso_genero']);
unset($_SESSION['aso_est_civil']);
unset($_SESSION['aso_dir_residencia']);
unset($_SESSION['aso_tel_residencia']);
unset($_SESSION['aso_num_celular']);
unset($_SESSION['aso_cor_electronico']);
unset($_SESSION['aso_cor_electronico_adicional']);
unset($_SESSION['aso_por_pabs']);
unset($_SESSION['aso_por_ret_fuente2']);
unset($_SESSION['aso_fon_vacaciones']);
unset($_SESSION['aso_por_fon_pensiones']);
unset($_SESSION['select2']);
unset($_SESSION['select4']);
/////////////////////

?>