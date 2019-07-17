<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
include_once('../clases/nits.class.php');
$instancia_nits=new nits();
$emp_por_cen_cos_id=$_POST['EmpId'];
$eliminar_cen_cos_empleado=$instancia_nits->eli_cen_cos_asociado($emp_por_cen_cos_id);
if($eliminar_cen_cos_empleado)
    echo "Centro de costo eliminado correctamente.";
else
    echo "Error al eliminar el centro de costo, intentelo de nuevo.";
?>