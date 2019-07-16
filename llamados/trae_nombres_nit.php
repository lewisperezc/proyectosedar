<?php
include_once('../clases/nits.class.php');
$ins_nits=new nits();
error_reporting(E_ALL);
$resultado="";

if(is_numeric($_POST['id']))
    $nit_id=$_POST['id'];
else
    $nit_id=0;

$con_nombres=$ins_nits->cons_nombres_nit($nit_id);
$res_nombres=mssql_fetch_array($con_nombres);
$resultado=$res_nombres['nits_num_documento']."-".$res_nombres['nombres'];
echo $resultado;
?>