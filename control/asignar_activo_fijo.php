<?php
include_once('../clases/activo_fijo.class.php');
$ins_act_fijo=new ActivoFijo();

$canitem=$_POST['canitem'];
$i=0;
while($i<=$canitem)
{
	$losdatos[$i]=$_POST['act_fij_responsable'.$i];
	$lapersona=explode("-",$losdatos[$i],2);
	$elresponsable[$i]=$lapersona[0];
	$elid[$i]=$_POST['act_fij_id'.$i];
	$i++;
}
$j=0;
while($j<sizeof($elid))
{
	$asignar=$ins_act_fijo->AsiActFijPersona($elresponsable[$j],$elid[$j]);
	$j++;
}
if($asignar)
	echo "<script>alert('Activos fijos asignados correctamente.');history.back(-1);</script>";
else
	echo "<script>alert('Error al asignar los activos fijos, Intentelo de nuevo.');history.back(-1);</script>";
?>