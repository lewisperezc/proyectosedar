<?php
include_once('../clases/cuenta.class.php');
$ins_cuenta=new cuenta();
error_reporting(E_ALL);
$cue_id=$_POST['id'];
$resultado="";
$res_nombre=$ins_cuenta->getnomCuenta($cue_id);
echo $res_nombre;
?>