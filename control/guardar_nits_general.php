<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$nits = new nits();
$tip_id=$_SESSION['sel_tip_nit'];
$raz= $_POST['raz'];
$nit= $_POST['nit'];
$regimen = $_POST['regimen'];
$tipo_regimen = $_POST['tipo_regimen'];
$ciudad =$_POST['select2'];
$dir=strtoupper($_POST['dir']);
$tel=strtoupper($_POST['tel']);
$contacto=strtoupper($_POST['contacto']);
$cel=$_POST['cel'];
$correo = strtoupper($_POST['correo']);
$dato = $nits->crear_nits_general($raz,$nit,$regimen,$tipo_regimen,$ciudad,$dir,$tel,$cel,$contacto,$correo,$tip_id);
if($dato)
	echo "<script>alert('NIT creado correctamente.');</script>";
else
	echo "<script>alert('Error al crear el NIT, Intentelo de nuevo.');</script>";
?>