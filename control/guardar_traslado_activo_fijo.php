<?php
include_once('../clases/activo_fijo.class.php');
$ins_act_fijo=new ActivoFijo();

$fecha=date('d-m-Y');
$act_fij_res_actual=$_POST['act_fij_res_actual'];
$elidpersona1=$_POST['elidpersona1'];//EL ACTUAL
$elidpersona2=$_POST['elidpersona2'];//EL NUEVO
$tip_traslado=$_POST['tip_traslado'];
$mot_traslado=$_POST['mot_traslado'];

$gua_traslado_1=$ins_act_fijo->GuaTraActFijo1($fecha,$elidpersona1,$elidpersona2,$tip_traslado,$mot_traslado);

$tra_act_fij_id=$ins_act_fijo->ConUltTraRegistrado();

$canitem=$_POST['canitem'];
$i=0;
while($i<=$canitem)
{
	$act_fij_id[$i]=$_POST['act_fij_id'.$i];
	$i++;
}

if($gua_traslado_1)
{
	$j=0;
	while($j<sizeof($act_fij_id))
	{
		$gua_traslado_2=$ins_act_fijo->GuaTraActFijo2($act_fij_id[$j],$tra_act_fij_id);
		if($gua_traslado_2)
			$act_res_act_fijo=$ins_act_fijo->AsiActFijPersona($elidpersona2,$act_fij_id[$j]);
		$j++;
	}
}

if($gua_traslado_2)
	echo "<script>alert('Traslado registrado correctamente.');history.back(-1);</script>";
else
	echo "<script>alert('Error al registrar el traslado, Intentelo de nuevo.');history.back(-1);</script>";
?>