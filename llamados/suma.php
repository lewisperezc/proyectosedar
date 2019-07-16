<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);
$jornadas = $_POST['jornadas'];
$descu = $_POST['descu'];
$jor_nit= $_POST['jor_nit'];
$tipo = $_POST['tipo'];
$total = $_POST['tot'];
if($tipo==1)
{
  $num_descu = ($jornadas+$descu)/$jor_nit;
  $html= $descu/$num_descu;
}
else
{
  $val_jor = ($jornadas/$total)*$jor_nit;
  $val_ope_des = $jornadas/$descu;
  $html = $val_jor/$val_ope_des;
}
echo $html;
?>