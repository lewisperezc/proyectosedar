<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
$ins_mes = new mes_contable();
$mes = $_POST['mes'];
$estado = $_POST['estado'];
$mes_por_ano_con_id=$_POST['mes_por_ano_con_id'];
$j=0;
for($i=0;$i<sizeof($estado);$i++)
{
	$act_mes = $ins_mes->actMes($estado[$i],$mes_por_ano_con_id[$i]);
	if(!$act_mes)
	  $j = 1;
}
if($j==0)
  {
	 echo "<script type=\"text/javascript\">alert(\"Se actualizo el mes contable.\");</script>"; 
	 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=102'>";
  }
else
  {
  echo "<script type=\"text/javascript\">alert(\"No se pudo actualizar el mes contable, intentelo de nuevo.\");</script>"; 
	 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=102'>"; 
  }
?>