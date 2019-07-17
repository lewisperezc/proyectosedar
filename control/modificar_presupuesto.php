<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

$valor=$_POST['pre_valor'];
$cen_costo=$_POST['cen_cos_id'];
$fecha=$_POST['fecha'];
$pre_cuenta=$_POST['pre_cuenta'];
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$i=0;
while($i<sizeof($valor))
{
	if($valor[$i]=="NULL")
		$valor[$i]=0;
		
	$mod_presupuesto=$ins_presupuesto->mod_presupuesto($valor[$i],$cen_costo,$fecha,$pre_cuenta[$i]);
	$i++;
}
if($mod_presupuesto)
	echo "<script>alert('Presupuesto modificado correctamente.');</script>";
else
	echo "<script>alert('Error al modificar el presupuesto, intentelo de nuevo.');</script>";

echo "<script>history.back(-1);</script>";
?>