<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/producto.class.php');
$ins_producto=new producto();

$can_filas=$_POST['can_filas'];
$i=0;
while($i<=$can_filas)
{
	$tip_pro_id[$i]=$_POST['tip_pro_id'.$i];
	$pro_descripcion[$i]=$_POST['pro_descripcion'.$i];
	$pro_iva[$i]=$_POST['pro_iva'.$i];
	$pro_retencion[$i]=$_POST['pro_retencion'.$i];
	$guardar_producto=$ins_producto->crearProducto($tip_pro_id[$i],$pro_descripcion[$i],$pro_iva[$i],$pro_retencion[$i]);
	$i++;
}
if($guardar_producto)
{
   echo "<script>
   			alert('Producto creado correctamente.');
   			history.back(-1);
		</script>"; 
}
else
{
   echo "<script>
   			alert('Error al crear el producto, Intentelo de nuevo.');
			history.back(-1);
		</script>";
}
?>