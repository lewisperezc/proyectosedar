<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();

$id_asociado = $_SESSION['aso_id'];

//INICIO DATOS EDUCACIÓN SUPERIOR ASOCIADO
$_SESSION['aso_uni_pregrado'] = strtoupper($_POST['aso_uni_pregrado']);
$_SESSION['aso_fec_pregrado'] = strtoupper($_POST['aso_fec_pregrado']);
$_SESSION['aso_tit_gra_obtenido'] = strtoupper($_POST['aso_tit_gra_obtenido']);
$_SESSION['aso_ciu_pregrado'] = strtoupper($_POST['aso_ciu_pregrado']);
$_SESSION['aso_uni_posgrado'] = strtoupper($_POST['aso_uni_posgrado']);
$_SESSION['aso_fec_posgrado'] = strtoupper($_POST['aso_fec_posgrado']);
$_SESSION['aso_tit_pos_obtenido'] = strtoupper($_POST['aso_tit_pos_obtenido']);
$_SESSION['ciu_posgrado'] = strtoupper($_POST['ciu_posgrado']);
$_SESSION['aso_uni_otros'] = strtoupper($_POST['aso_uni_otros']);
$_SESSION['aso_fec_otros'] = strtoupper($_POST['aso_fec_otros']);
$_SESSION['aso_tit_otr_obtenido'] = strtoupper($_POST['aso_tit_otr_obtenido']);
$_SESSION['aso_ciu_otr_obtenido'] = strtoupper($_POST['aso_ciu_otr_obtenido']);
//FIN DATOS EDUCACIÓN SUPERIOR ASOCIADO

$aso_uni_pregrado = $_SESSION['aso_uni_pregrado'];
$aso_fec_pregrado = $_SESSION['aso_fec_pregrado'];
$aso_tit_gra_obtenido = $_SESSION['aso_tit_gra_obtenido'];
$aso_ciu_pregrado = $_SESSION['aso_ciu_pregrado'];

$aso_uni_posgrado = $_SESSION['aso_uni_posgrado'];
$aso_fec_posgrado = $_SESSION['aso_fec_posgrado'];
$aso_tit_pos_obtenido = $_SESSION['aso_tit_pos_obtenido'];
$aso_ciu_posgrado = $_SESSION['ciu_posgrado'];

$aso_uni_otros = $_SESSION['aso_uni_otros'];
$aso_fec_otros = $_SESSION['aso_fec_otros'];
$aso_tit_otr_obtenido = $_SESSION['aso_tit_otr_obtenido'];
$aso_ciu_otr_obtenido = $_SESSION['aso_ciu_otr_obtenido'];

$actualizar_educacion_superior_asociado=$instancia_nits->act_dat_edu_sup_asociado($aso_uni_pregrado,$aso_fec_pregrado,$aso_tit_gra_obtenido,$aso_ciu_pregrado,$aso_uni_posgrado,$aso_fec_posgrado,$aso_tit_pos_obtenido,$aso_ciu_posgrado,$aso_uni_otros,$aso_fec_otros,$aso_tit_otr_obtenido,$aso_ciu_otr_obtenido,$id_asociado);

//LIMPIAR SESSIONES//
unset($_SESSION['aso_uni_pregrado']);
unset($_SESSION['aso_fec_pregrado']);
unset($_SESSION['aso_tit_gra_obtenido']);
unset($_SESSION['aso_ciu_pregrado']);
unset($_SESSION['aso_uni_posgrado']);
unset($_SESSION['aso_fec_posgrado']);
unset($_SESSION['aso_tit_pos_obtenido']);
unset($_SESSION['ciu_posgrado']);
unset($_SESSION['aso_uni_otros']);
unset($_SESSION['aso_fec_otros']);
unset($_SESSION['aso_tit_otr_obtenido']);
unset($_SESSION['aso_ciu_otr_obtenido']);
/////////////////////

if($actualizar_educacion_superior_asociado)
{
	echo "<script>alert('Afiliado actualizado correctamente.');
			location.href = '../formularios/consultar_asociado_6.php';
		 </script>";
}
else
{
	echo "<script>alert('Error al actualizar el afiliado, Intentelo de nuevo.');
			location.href = '../formularios/consultar_asociado_6.php';
		 </script>";
}
?>