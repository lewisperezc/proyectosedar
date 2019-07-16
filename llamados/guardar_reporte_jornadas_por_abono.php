<?php session_start();
include_once('../clases/reporte_jornadas.class.php');
$ins_rep_jornadas=new reporte_jornadas();
$can_asociados=$_POST['can_asociados'];
$var_mes=explode("-",$_SESSION['mes'],2);
$mes_contable=$var_mes[1];
$i=0;
while($i<$can_asociados)
{
	//echo $_POST['num_jornadas'.$i]."<br>";
	$rep_jor[$i]=$_POST['num_jornadas'.$i];
	$i++;
}
$rec_consecutivo=$_POST['rec_consecutivo'];
$aso_id=$_POST['aso_id'];
$fact_seleccionada=$_POST['fact_seleccionada'];
$centro_de_costo=$_POST['centro_de_costo'];

$j=0;
while($j<$can_asociados)
{
	$gua_rep_jor_con_abono=$ins_rep_jornadas->gua_rep_jor_con_abono($rec_consecutivo,$aso_id[$j],$fact_seleccionada,$centro_de_costo,$mes_contable,$rep_jor[$j]);
	$j++;
}
if($gua_rep_jor_con_abono)
	echo "<script>
			alert('Reporte de jornadas registrado correctamente.');
			window.close();
		  </script>";
else
	echo "<script>
			alert('Error al registrar el reporte de jornadas, intentelo de nuevo.');
			window.close();
		  </script>";

unset($_SESSION['mes']);
?>