<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$id_asociado = $_SESSION['aso_id'];
//INICIO CAPTURO LOS DATOS DEL NUEVO BENEFICIARIO
$_SESSION['aso_ape_beneficiario_2'] = $_POST['aso_ape_beneficiario_2'];
$_SESSION['aso_nom_beneficiario_2'] = $_POST['aso_nom_beneficiario_2'];
$_SESSION['aso_tip_doc_beneficiario_2'] = $_POST['aso_tip_doc_beneficiario_2'];
$_SESSION['aso_num_doc_beneficiario_2'] = $_POST['aso_num_doc_beneficiario_2'];
$_SESSION['aso_por_ben_beneficiario_2'] = $_POST['aso_por_ben_beneficiario_2'];
//INICIO CAPTURO LOS DATOS DEL NUEVO BENEFICIARIO

$aso_ape_beneficiario = $_SESSION['aso_ape_beneficiario_2'];
$aso_nom_beneficiario = $_SESSION['aso_nom_beneficiario_2'];
$aso_tip_doc_beneficiario = $_SESSION['aso_tip_doc_beneficiario_2'];
$aso_num_doc_beneficiario = $_SESSION['aso_num_doc_beneficiario_2'];
$aso_por_ben_beneficiario = $_SESSION['aso_por_ben_beneficiario_2'];


$j = 0;
//$k = 0;
while($j < sizeof($aso_num_doc_beneficiario))
{
	if($aso_ape_beneficiario[$j] == "" || $aso_nom_beneficiario[$j] == "" || $aso_tip_doc_beneficiario[$j] == "NULL" || $aso_num_doc_beneficiario[$j] == "" || $aso_por_ben_beneficiario[$j] == ""){
	echo "<script>alert('El beneficiario N° $j no se pudo guardar porque llevaba campos vacios, de aqui en adelante no se guardaran mas beneficiarios.');
	location.href = '../formularios/consultar_asociado_5.php';</script>";
	break;
	}
	else{
	$guardar_otro_beneficiacio_asociado_1 = $instancia_nits->insertar_otro_beneficiario(strtoupper($aso_ape_beneficiario[$j]),strtoupper($aso_nom_beneficiario[$j]),strtoupper($aso_tip_doc_beneficiario[$j]),strtoupper($aso_num_doc_beneficiario[$j]),strtoupper($aso_por_ben_beneficiario[$j]));
	
		$guardar_otro_beneficiacio_asociado_2 = $instancia_nits->insertar_otro_beneficiario2($id_asociado);
	}//Cierra el else
	$j++;
}
if($guardar_otro_beneficiacio_asociado_1 && $guardar_otro_beneficiacio_asociado_2)
	echo "<script>alert('Afiliado actualizado correctamente.');location.href = '../formularios/consultar_asociado_5.php';
		  </script>";
else
	echo "<script>alert('Error al actualizar el afiliado, Intentelo de nuevo.');//location.href = '../formularios/consultar_asociado_5.php';</script>";
?>