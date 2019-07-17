<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$nit_por_cen_cos_id =  base64_decode($_GET['nit_por_cen_cos_id']);

$eliminar_cen_cos_asociado = $instancia_nits->eli_cen_cos_asociado($nit_por_cen_cos_id);

if($eliminar_cen_cos_asociado)
{
	echo "<script>alert('Centro de costo eliminado correctamente.');
						location.href = '../formularios/consultar_asociado_7.php';
	      </script>";
}
else
{
	echo "<script>alert('Error al eliminar el centro de costo, Intentelo de nuevo.');
						location.href = '../formularios/consultar_asociado_7.php';
	      </script>";
}
?>