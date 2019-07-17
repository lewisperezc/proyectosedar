<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$id_asociado = $_SESSION['aso_id'];


$eli_tod_beneficiarios=$instancia_nits->EliminarTodosBeneficiariosAfiliado($id_asociado);



//INICIO CAPTURA BENEFICIARIOS ASOCIADO
$_SESSION['aso_ape_beneficiario'] = $_POST['aso_ape_beneficiario'];
$_SESSION['aso_nom_beneficiario'] = $_POST['aso_nom_beneficiario'];
$_SESSION['aso_tip_doc_beneficiario'] = $_POST['aso_tip_doc_beneficiario'];
$_SESSION['aso_num_doc_beneficiario'] = $_POST['aso_num_doc_beneficiario'];
$_SESSION['aso_num_doc_beneficiario'] = $_POST['aso_num_doc_beneficiario'];
$_SESSION['aso_por_ben_beneficiario'] = $_POST['aso_por_ben_beneficiario'];
$_SESSION['aso_id_beneficiario'] = $_POST['aso_id_beneficiario'];
$_SESSION['aso_parentesco'] = $_POST['aso_parentesco'];
//FIN CAPTURA BENEFICIARIOS ASOCIADO
$aso_ape_beneficiario = $_SESSION['aso_ape_beneficiario'];
$aso_nom_beneficiario = $_SESSION['aso_nom_beneficiario'];
$aso_tip_doc_beneficiario = $_SESSION['aso_tip_doc_beneficiario'];
$aso_num_doc_beneficiario = $_SESSION['aso_num_doc_beneficiario'];
$aso_por_ben_beneficiario = $_SESSION['aso_por_ben_beneficiario'];
$aso_id_beneficiario = $_SESSION['aso_id_beneficiario'];
$aso_parentesco = $_SESSION['aso_parentesco'];
$i = 0;
while($i<sizeof($aso_ape_beneficiario))
{
	$guardar_beneficiarios=$instancia_nits->actualizar_nuevos_beneficiarios($aso_num_doc_beneficiario[$i],strtoupper($aso_ape_beneficiario[$i]),strtoupper($aso_nom_beneficiario[$i]),$aso_tip_doc_beneficiario[$i],$aso_por_ben_beneficiario[$i],$aso_parentesco[$i],$id_asociado);
	$i++;
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

if($guardar_beneficiarios)
{
	echo "<script>alert('Afiliado actualizado correctamente.');
				location.href = '../formularios/consultar_asociado_5.php';
		  </script>";
}
else
{
	echo "<script>alert('Error al actualizar el afiliado, Intentelo de nuevo.');
			location.href = '../formularios/consultar_asociado_5.php';
		 </script>";
}
?>