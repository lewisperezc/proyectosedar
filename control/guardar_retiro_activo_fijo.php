<?php
include_once('../clases/activo_fijo.class.php');
$ins_act_fijo=new ActivoFijo();

$fecha=date('d-m-Y');
$elidpersona=$_POST['elidpersona'];
$mot_baja=$_POST['mot_baja'];

$gua_baj_act_fijo_1=$ins_act_fijo->GuaDarBajActFijo1($fecha,$elidpersona,$mot_baja);

$dar_baj_act_fij_id=$ins_act_fijo->ConUltBajRegistrada();

$canitem=$_POST['canitem'];
$i=0;
while($i<=$canitem)
{
	$act_fij_id[$i]=$_POST['act_fij_id'.$i];
	$i++;
}

if($gua_baj_act_fijo_1)
{
	$j=0;
	while($j<sizeof($act_fij_id))
	{
		$gua_baj_act_fijo_2=$ins_act_fijo->GuaDarBajActFijo2($act_fij_id[$j],$dar_baj_act_fij_id);
		if($gua_baj_act_fijo_2)
			$eli_act_fijo=$ins_act_fijo->EliActFij($act_fij_id[$j]);
		$j++;
	}
}
if($gua_baj_act_fijo_2)
	echo "<script>alert('Traslado registrado correctamente.');history.back(-1);</script>";
else
	echo "<script>alert('Error al registrar el traslado, Intentelo de nuevo.');history.back(-1);</script>";
?>