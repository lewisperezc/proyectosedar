<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/novedad.class.php');
$ins_novedad = new novedad();

$tipo_persona=$_POST['tipo_persona'];
if($tipo_persona==1)
	$nit_id = $_SESSION['aso_id'];
elseif($tipo_persona==2)
	$nit_id = $_SESSION['emp_id'];

$nov_estado = 1;
$can_filas=$_POST['can_filas'];
$i=0;
while($i<=sizeof($can_filas))
{
	$cue_id[$i]=$_POST['nov_nombre'.$i];
	$nov_valor[$i]=$_POST['nov_valor'.$i];
	$nov_observacion[$i]=$_POST['nov_observacion'.$i];
	$gua_novedad=$ins_novedad->ins_nov_nit($nov_valor[$i],$nit_id,$cue_id[$i],$nov_estado,$nov_observacion[$i]);
   	$i++;
   	$_SESSION['contador']=$i;
}
if($gua_novedad)
{
	if($_SESSION['contador']==1)
		echo "<script>alert('Novedad registrada correctamente.');</script>";
	elseif($_SESSION['contador']>1)
		echo "<script>alert('Novedades registradas correctamente.');</script>";
}
echo "<script>window.close();</script>";

?>