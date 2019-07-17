<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../conexion/conexion.php');
$seleccionado=$_GET['con_id'];
$con_seleccionado = split("-",$seleccionado,2);
$_SESSION['id_contrato'] = $con_seleccionado[0];
$otra[99];
if($con_seleccionado[1])
{
	$otra[1]=$con_seleccionado[1];
	$sqlcas="SELECT * FROM dbo.formularios_consultar_contrato_externo WHERE tip_con_ext_id = '$otra[1]'";
	$concas=mssql_query($sqlcas);
	$filcas=mssql_fetch_array($concas);
	$otra[99]=$filcas['for_con_con_ext_nombre'];
}
if($con_seleccionado[1])
   include_once($otra[99]);
?>