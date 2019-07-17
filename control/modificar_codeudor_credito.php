<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
$ins_credito = new credito();
$cre_codeudor = strtoupper($_POST['cre_codeudor']);
$cre_id = strtoupper($_SESSION['cre_id']);
$act_cod_credito = $ins_credito->mod_cod_credito($cre_codeudor,$cre_id);
if($act_cod_credito)
{
	echo "<script>
			alert('Credito actualizado correctamente.');
		 </script>";
}
else
{
	echo "<script>
			alert('Error al actualizar el credito, Intentelo de nuevo.');
		 </script>";
}
?>