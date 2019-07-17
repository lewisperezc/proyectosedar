<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/contrato.class.php');
$instancia_contrato = new contrato();
//INICIO CAPTURO DATOS NUEVO IMPUESTO
$sel_contrato = $_SESSION['sele_contrato'];
$_SESSION['con_adm_ips_nom_imp_aseguradora_2'] = $_POST['con_adm_ips_nom_imp_aseguradora_2'];
$_SESSION['con_adm_ips_imp_nombre_2'] = $_POST['con_adm_ips_imp_nombre_2'];
$_SESSION['con_adm_ips_imp_porcentaje_2'] = $_POST['con_adm_ips_imp_porcentaje_2'];
$con_adm_ips_nom_imp_aseguradora_2 = $_SESSION['con_adm_ips_nom_imp_aseguradora_2'];
$con_adm_ips_imp_nombre_2 = $_SESSION['con_adm_ips_imp_nombre_2'];
$con_adm_ips_imp_porcentaje_2 = $_SESSION['con_adm_ips_imp_porcentaje_2'];
//FIN CAPTURO DATOS NUEVO IMPUESTO
$i = 0;
while($i < sizeof($con_adm_ips_nom_imp_aseguradora_2))
{
	if($con_adm_ips_nom_imp_aseguradora_2[$i] == "NULL" || $con_adm_ips_imp_nombre_2[$i] == "NULL" || $con_adm_ips_imp_porcentaje_2[$i] == ""){
		echo "Algunos ITEMS del formulario de IMPUESTOS llevaban campos vacios, Solo se insertaton los que llevaban los datos correspondientes.";
		echo "<br>";
	}
	else{
$guardar_otro_impuesto_contrato = $instancia_contrato->agregar_otra_poliza_contrato($sel_contrato,$con_adm_ips_nom_imp_aseguradora_2[$i],$con_adm_ips_imp_nombre_2[$i],$con_adm_ips_imp_porcentaje_2[$i]);
	}
$i++;
}

//LIMPIAR SESSIONES//
unset($_SESSION['con_adm_ips_nom_imp_aseguradora_2']);
unset($_SESSION['con_adm_ips_imp_nombre_2']);
unset($_SESSION['con_adm_ips_imp_porcentaje_2']);
/////////////////////

if($guardar_otro_impuesto_contrato)
{
	echo "<script>alert('Contrato actualizado Correctamente.');
			location.href = '../formularios/consultar_contrato_administracion_ips_3.php';
		 </script>";
}
else
{
	echo "<script>alert('Error al actualizar el contrato, Intentelo de nuevo.');
			      location.href = '../formularios/consultar_contrato_administracion_ips_3.php';
	      </script>";
}
?>