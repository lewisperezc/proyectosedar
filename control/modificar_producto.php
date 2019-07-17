<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/producto.class.php');
$prod=new producto();
$pro_id=$_POST['pro_id'];
$pro_nombre=strtoupper($_POST['pro_nombre']);
$pro_iva=$_POST['pro_iva'];
$pro_cue_retencion=$_POST['pro_cue_retencion'];
echo $pro_cue_retencion;
$tip_pro_id=$_POST['tip_pro_id'];
$guardar=$prod->editarProducto($pro_nombre,$pro_iva,$pro_cue_retencion,$tip_pro_id,$pro_id);
if($guardar)
{
	echo "<script>
		  	alert('Producto actualizado correctamente.');
		    location.href = '../index.php?c=19';
	     </script>";
}
else
{
	echo "<script>
		  	alert('Error al actualizar el producto, Intentelo de nuevo.');
		    location.href = '../index.php?c=19';
	     </script>";
}
?>