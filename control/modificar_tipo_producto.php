<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/tipo_producto.class.php');
$tip= new tipo_producto();
$tipo=strtoupper($_SESSION["tipo"]);
$descripcion=strtoupper($_POST['cambio']);
$cuenta=$_POST['cue_id'];
$concepto=$_POST['con_id'];
$editar=$tip->editarTipo($descripcion,$cuenta,$concepto,$tipo);
if($editar)
{
	unset($_SESSION["tipo"]);
	echo "<script>
	  		alert('Tipo Producto Actualizado Correctamente!!!');
			location.href = '../index.php?c=14';
		  </script>";
}
?>