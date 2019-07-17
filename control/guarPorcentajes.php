<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/reporte_jornadas.class.php');
$datos = $_POST['segSocial'];

$seg_soc_id=$_POST['seg_soc_id'];

$rep_jor = new reporte_jornadas();
for($i=1;$i<=sizeof($datos);$i++)
{
	$val_nuevo=str_replace(",",".",$datos[$i]);
	$upPor = $rep_jor->mod_segSocial($seg_soc_id[$i],$val_nuevo);
}
echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=93'>";
?>