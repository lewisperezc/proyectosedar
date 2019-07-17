<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/cuenta.class.php');
$guar= new cuenta();
$concuenta = strtoupper($_POST['select2']);
$nombre = strtoupper($_POST['nomb_cue']);
if($concuenta && $nombre)
{
	$insert=$guar->actualizar_cuenta($concuenta,$nombre);
  	if ($insert)
   	{
    	echo "<script>alert('Se actualizo la cuenta - $concuenta---');
        location.href='../index.php?c=7';</script>";
     	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=7'>";
   	}
  	else
  	{
		echo "<script>alert('No se pudo actualizar la cuenta, Intentelo de nuevo.');
		location.href='../index.php?c=7';</script>";
     	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=7'>";
  	}
}
else
{
	echo "<script>alert('No se pudo actualizar la cuenta, Intentelo de nuevo.');
	location.href='../index.php?c=7';</script>";
    echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=7'>";
}
?>