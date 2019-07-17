<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

    include_once('../conexion/conexion.php');
	$seleccionado = $_POST['sel_tip_con_pre_servicios'];
	session_register('otra');
	$otra[99];
	if($seleccionado)
	{
		$otra[1]=$seleccionado;
		$sqlcas="SELECT * FROM formularios_tipo_contrato_prestacion where tip_cont_id='$otra[1]'";
		$concas=mssql_query($sqlcas);
		$filcas=mssql_fetch_array($concas);
		$otra[99]=$filcas['for_tip_con_pres_nombre'];
	}
	if($seleccionado)
	   include_once($otra[99]);
?>