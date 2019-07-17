<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$nits= new nits();
$raz=strtoupper($_POST['raz']);
$nit=strtoupper($_POST['nit']);

$rep_legal = $_POST['repre'];
$tip_identificacion = $_POST['tipo'];

$regimen = strtoupper($_POST['regimen']);
$tip_regimen = strtoupper($_POST['tipo_regimen']);
$nit_id=strtoupper($_POST['nit_ant']);
$ciudad=strtoupper($_POST['select2']);
$dir=strtoupper($_POST['dir']);
$tel=strtoupper($_POST['tel']);
$contacto=strtoupper($_POST['contacto']);
$tel_o_cel = strtoupper($_POST['cel']);
$correo=strtoupper($_POST['correo']);

$banco = $_POST['banco'];
$tip_cue_bancaria = $_POST['tipo_cuenta'];
$num_cuenta = $_POST['num_cuenta'];
$nit_retencion = $_POST['nit_retencion'];

$tipo_nit_seleccionado=$_POST['tipo_nit_seleccionado'];

$nit_gen=$nits->actualizar_nit_gen($raz,$nit,$regimen,$tip_regimen,$dir,$tel,$cel,$contacto,$correo,$tipo_nit_seleccionado,$nit_id,$ciudad);
if($nit_gen)
{
	unset($_SESSION['nit_ant']);
	unset($_SESSION['sel_nit']);
	echo "<script>
	      	alert('NIT actualizado correctamente.');
			history.back(-1);
	      </script>";
}
else
{
	unset($_SESSION['nit_ant']);
	unset($_SESSION['sel_nit']);
	echo "<script>
	      	alert('Error al actualizar el NIT, Intentelo de nuevo.');
			history.back(-1);
	      </script>";	
}
?>