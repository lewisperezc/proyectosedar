<?php
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$cen_costo=$_POST['pre_cen_costo'];
$fecha=$_POST['pre_fecha'];
$cuenta=$_POST['pre_cuenta'];
$valor=$_POST['pre_valor'];
$i=0;
while($i<sizeof($cuenta)){
	$gua_presupuesto=$ins_presupuesto->gua_presupuesto($cen_costo,$fecha,$cuenta[$i],$valor[$i]);
	$i++;
}
?>