<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
	@include_once('../conexion/conexion.php');
    $seleccionado = $_POST['sel_tip_con_externo'];
	$otra[99];
	if($seleccionado)
	{
		$otra[1]=$seleccionado;
		$sqlcas="select * from dbo.formularios_contrato_externo where tip_con_ext_id='$otra[1]'";
		$concas=mssql_query($sqlcas);
		$filcas=mssql_fetch_array($concas);
		$otra[99]=$filcas['for_con_externo_nombre'];
	}
	if($seleccionado)
	   include_once($otra[99]);
?>