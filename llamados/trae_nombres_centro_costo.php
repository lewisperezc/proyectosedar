<?php
include_once('../clases/centro_de_costos.class.php');
$ins_cen_costo=new centro_de_costos();
error_reporting(E_ALL);

$resultado="";
if(is_numeric($_POST['id']))
    $cen_cos_id=$_POST['id'];
else
    $cen_cos_id=0;
$con_nombres=$ins_cen_costo->datos_centro_por_id($cen_cos_id);
$resultado=$con_nombres['cen_cos_nombre'];
echo $resultado;
?>