<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
//INICIO CAPTURA ID AFILIADO
$beneficiario=$_GET['benef_id'];
$afiliado=$_GET['asoci_id'];
//echo "El Asociado Es:".$beneficiario."___".$afiliado;

$eli_beneficiario=$instancia_nits->EliminarBeneficiariosAfiliado($beneficiario,$afiliado);
if($eli_beneficiario)
{
	echo "<script>alert('Beneficiario eliminado correctamente.');location.href = '../formularios/consultar_asociado_5.php';</script>";
}
else
{
	echo "<script>alert('Error al eliminar el beneficiario, intentelo de nuevo.');location.href = '../formularios/consultar_asociado_5.php';</script>";
}


//LIMPIAR SESSIONES//
unset($_SESSION['aso_ape_beneficiario']);
unset($_SESSION['aso_nom_beneficiario']);
unset($_SESSION['aso_tip_doc_beneficiario']);
unset($_SESSION['aso_num_doc_beneficiario']);
unset($_SESSION['aso_por_ben_beneficiario']);
unset($_SESSION['aso_id_beneficiario']);
unset($_SESSION['aso_parentesco']);
/////////////////////
?>