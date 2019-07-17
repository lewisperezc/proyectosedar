<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
$des_com_id=$_GET['des_com_id'];
@include_once('../clases/compensacion_nomina.class.php');
@include_once('clases/compensacion_nomina.class.php');
$ins_com_nomina=new compensacion_nomina();
$eli_des_com=$ins_com_nomina->EliminarDescuentoNominaAdministrativa($des_com_id);
if($eli_des_com)
{ echo "<script>alert('Descuento eliminado correctamente.');history.back(-1);</script>"; }
else
{ echo "<script>alert('Error al eliminar el descuento, intentelo de nuevo.');history.back(-1);</script>"; }
?>