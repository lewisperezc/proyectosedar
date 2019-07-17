<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
$des_com_id=$_GET['des_com_id'];
@include_once('../clases/moviminetos_contables.class.php');
@include_once('clases/moviminetos_contables.class.php');
$ins_mov_contable=new movimientos_contables();
$eli_des_com=$ins_mov_contable->EliminarDescuentoLegalizacionAdicional($_GET['des_com_id']);
if($eli_des_com)
{ echo "<script>alert('Descuento de legalizacion eliminado correctamente.');history.back(-1);</script>"; }
else
{ echo "<script>alert('Error al eliminar el descuento de legalizacion, intentelo de nuevo.');history.back(-1);</script>"; }
echo "<script>window.close();window.opener.Recargar();</script>";
?>