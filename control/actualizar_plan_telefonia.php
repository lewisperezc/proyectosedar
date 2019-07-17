<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/plan_telefonia.class.php');
$ins_pla_telefonia = new plan_telefonia();
$nombre = strtoupper($_POST['cre_pla_tel_nombre']);
$valor = $_POST['cre_pla_tel_valor'];
$proveedor = $_POST['cre_pla_tel_proveedor'];
$plan_id = $_SESSION['planes'];

$actualizar_plan_telefonia = $ins_pla_telefonia->act_pla_telefonia($nombre,$valor,$proveedor,$plan_id);
if($actualizar_plan_telefonia)
{
	unset($_SESSION['planes']);
	echo "<script>alert('Plan de telefonia actualizado correctamente.');
	              location.href = '../index.php?c=89';
	      </script>";
}
else
{
	unset($_SESSION['planes']);
	echo "<script>alert('Error al actualizar el plan de telefonia, Intentelo de nuevo.');
	              location.href = '../index.php?c=89';
	      </script>";
}
?>