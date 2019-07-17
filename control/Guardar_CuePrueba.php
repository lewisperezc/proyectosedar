<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/cuenta.class.php');
$guar= new cuenta();

//CAPTURAR DATOS//
$cue1=$_POST['select2'];
$cuenta=$cue1.$_POST['sub_cue'];
$nombre=$_POST['nomb_cue'];
$cue_may=$_POST['cue_may'];
$sel_div=$_POST['sel_div'];
$por_iva=$_POST['por_iva'];
$ciudad=$_POST['ciudad'];
if($ciudad==0)
	$ciudad="NULL";
$cue_nomina=$_POST['cue_nomina'];
//////////////////

$verificar = $guar->verificar_existe($cuenta);
$result = mssql_num_rows($verificar);
if($result!=0)
{
	echo "<script>alert('La cuenta $concuenta ya existe.');</script>";
	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=5'>";
}
else
{
	//$cue_id,$cue_nombre,$cue_esmayor,$cue_subdivision,$cue_porcentaje,$cue_ciudad,$cuentabalancem,$cue_nomina
	$insert=$guar->insert_cuenta($cuenta,$nombre,$cue_may,$sel_div,$por_iva,$ciudad,$cue1,$cue_nomina);
	if ($insert)
	{
		echo "<script>alert('La cuenta $concuenta $nombre, se creo correctamente.');</script>";
		echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=5'>";
	}
	else
		echo "<script>alert('Error al guardar la cuenta, Intentelo de nuevo.');</script>";
		echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=5'>";
}
?>