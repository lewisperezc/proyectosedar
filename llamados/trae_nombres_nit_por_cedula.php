<?php
include_once('../clases/nits.class.php');
include_once('../clases/activo_fijo.class.php');
$ins_nits=new nits();
$ins_act_fijo=new ActivoFijo();
$lacedula=$_POST['lacedula'];
error_reporting(E_ALL);
$res="";
$res_nombres=$ins_nits->ConNomIdNitPorCedula($lacedula);
$res=$res_nombres;
echo $res;
?>