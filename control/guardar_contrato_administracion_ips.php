<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/contrato.class.php');
$instancia_contrato = new contrato();

//INICIO CAPTURO LOS DATOS QUE DIGITAN EN EL FORMULARIO ANTERIOR
$_SESSION['con_adm_ips_nom_imp_aseguradora'] = $_POST['con_adm_ips_nom_imp_aseguradora'];
$_SESSION['con_adm_ips_imp_nombre'] = $_POST['con_adm_ips_imp_nombre'];
$_SESSION['con_adm_ips_imp_porcentaje'] = $_POST['con_adm_ips_imp_porcentaje'];
//FIN CAPTURO LOS DATOS QUE DIGITAN EN EL FORMULARIO ANTERIOR
//Inicio Formulario 1
$con_adm_ips_num_consecutivo = $_SESSION['con_adm_ips_num_consecutivo'];
$con_adm_ips_hospital = $_SESSION['con_adm_ips_hospital'];
$con_adm_ips_vigencia = $_SESSION['con_adm_ips_vigencia'];
$con_adm_ips_valor = $_SESSION['con_adm_ips_valor'];
$con_adm_ips_cuo_mensual = $_SESSION['con_adm_ips_cuo_mensual'];
$con_adm_ips_fec_inicial = $_SESSION['con_adm_ips_fec_inicial'];
$con_adm_ips_fec_fin = $_SESSION['con_adm_ips_fec_fin'];
$con_adm_ips_estado = $_SESSION['con_adm_ips_estado'];
$con_adm_ips_est_legalizado = $_SESSION['con_adm_ips_est_legalizado'];
//Fin Formulario 1
//Inicio Formulario 2
$con_adm_ips_nom_pol_aseguradora = $_SESSION['con_adm_ips_nom_pol_aseguradora'];
$con_adm_ips_con_pol_nombre = $_SESSION['con_adm_ips_con_pol_nombre'];
$con_adm_ips_pol_porcentaje = $_SESSION['con_adm_ips_pol_porcentaje'];
//Fin Formulario 2
//Inicio Formulario 3
$con_adm_ips_nom_imp_aseguradora = $_SESSION['con_adm_ips_nom_imp_aseguradora'];
$con_adm_ips_imp_nombre = $_SESSION['con_adm_ips_imp_nombre'];
$con_adm_ips_imp_porcentaje = $_SESSION['con_adm_ips_imp_porcentaje'];
//Fin Formulario 3
//Inicio Obtener el ID del NIT que seleccionan como centro de costos(hospital)
$obt_nit_id = $instancia_contrato->obt_nit_id($con_adm_ips_hospital);
$consulta = mssql_fetch_array($obt_nit_id);
$nit_id = $consulta['nit_id'];
//Fin Obtener el ID del NIT que seleccionan como centro de costos(hospital)

$guardar_contrato_admin_ips = $instancia_contrato->ins_con_adm_ips($con_adm_ips_num_consecutivo,$nit_id,$con_adm_ips_vigencia,$con_adm_ips_valor,$con_adm_ips_cuo_mensual,$con_adm_ips_fec_inicial,$con_adm_ips_fec_fin,$con_adm_ips_estado,$con_adm_ips_est_legalizado);
$i = 0;
while($i < sizeof($con_adm_ips_pol_porcentaje))
{
	if($con_adm_ips_nom_pol_aseguradora[$i] == "NULL" || $con_adm_ips_con_pol_nombre[$i] == "NULL" || $con_adm_ips_pol_porcentaje[$i] == "")
	echo "Algunos ITEMS del formulario de POLIZAS llevaban campos vacios, solo se insertaton los que llevaban los datos correspondientes. <br>";
	else
	{	
	$guardar_poliza_contrato_admin_ips = $instancia_contrato->ins_pol_o_imp_por_contrato(strtoupper($con_adm_ips_con_pol_nombre[$i]),strtoupper($con_adm_ips_nom_pol_aseguradora[$i]),strtoupper($con_adm_ips_pol_porcentaje[$i]));
	}
$i++;
}
$j = 0;
while($j < sizeof($con_adm_ips_imp_porcentaje))
{
	if($con_adm_ips_nom_imp_aseguradora[$j] == "NULL" || $con_adm_ips_imp_nombre[$j] == "NULL" || $con_adm_ips_imp_porcentaje[$j] == "")
	{
	echo "Algunos ITEMS del formulario de IMPUESTOS llevaban campos vacios, solo se insertaton los que llevaban los datos correspondientes.";
	echo "<br>";
	}
	else
	{
		$guardar_impuesto_contrato_admin_ips = $instancia_contrato->ins_pol_o_imp_por_contrato(strtoupper($con_adm_ips_imp_nombre[$j]),strtoupper($con_adm_ips_nom_imp_aseguradora[$j]),strtoupper($con_adm_ips_imp_porcentaje[$j]));
	}
$j++;
}

//LIMPIAR SESSIONES//
unset($_SESSION['con_adm_ips_nom_imp_aseguradora']);
unset($_SESSION['con_adm_ips_imp_nombre']);
unset($_SESSION['con_adm_ips_imp_porcentaje']);
unset($_SESSION['con_adm_ips_num_consecutivo']);
unset($_SESSION['con_adm_ips_hospital']);
unset($_SESSION['con_adm_ips_vigencia']);
unset($_SESSION['con_adm_ips_valor']);
unset($_SESSION['con_adm_ips_cuo_mensual']);
unset($_SESSION['con_adm_ips_fec_inicial']);
unset($_SESSION['con_adm_ips_fec_fin']);
unset($_SESSION['con_adm_ips_estado']);
unset($_SESSION['con_adm_ips_est_legalizado']);
unset($_SESSION['con_adm_ips_nom_pol_aseguradora']);
unset($_SESSION['con_adm_ips_con_pol_nombre']);
unset($_SESSION['con_adm_ips_pol_porcentaje']);
unset($_SESSION['con_adm_ips_nom_imp_aseguradora']);
unset($_SESSION['con_adm_ips_imp_nombre']);
unset($_SESSION['con_adm_ips_imp_porcentaje']);
////////////////////

if($guardar_contrato_admin_ips)
		echo "<script>alert('El contrato de Administracion de IPS se creo correctamente.');</script>";
else
{
	echo "<script>
				alert('Error al crear el contrato de Administracion de IPS,Los campos del primer formulario son obligatorios,Intentelo de nuevo.');</script>";
}
?>