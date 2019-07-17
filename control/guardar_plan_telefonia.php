<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/plan_telefonia.class.php');
$ins_plan_telefonia = new plan_telefonia();

$can_filas=$_POST['can_filas'];
$i=0;
while($i<=$can_filas)
{
	$nombre[$i]=strtoupper($_POST['cre_pla_tel_nombre'.$i]);
	$valor[$i]=$_POST['cre_pla_tel_valor'.$i];
	$proveedor[$i]=$_POST['cre_pla_tel_proveedor'.$i];
	$guardar_plan_telefonia = $ins_plan_telefonia->ins_pla_telefonia($nombre[$i],$valor[$i],$proveedor[$i]);
	$i++;
}
if($guardar_plan_telefonia)
	echo "<script>
		  	alert('Plan de telefonia creado correctamente.');
			history.back(-1);
		 </script>";
else
{
	echo "<script>
		  	alert('Error al crear el plan de telefonia, Intentelo de nuevo.');
			history.back(-1);
	 	  </script>";
}
?>