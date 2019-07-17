<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
$ins_mes = new mes_contable();

if($_GET['crear']==1)
{
	$ins_mes->cre_ano(2);
	echo "<script>alert('A\u00f1o creado correctamente.');history.back(-1);</script>";
}
else
{
	for($i=0;$i<$_POST['cant'];$i++)
	{
		//<select name='sele".$cont."'id='../control/'>";
		$dat_sele = explode('-', $_POST['sele'.$i]);
		$ins_mes->actAno($dat_sele[0],$dat_sele[1]);
	}
	echo "<script>alert('A\u00f1o actualizado correctamente.');history.back(-1);</script>";	
}

?>