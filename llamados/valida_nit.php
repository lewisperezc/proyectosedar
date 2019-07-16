<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);
$nit_id=$_GET['nit_id'];
$sql="SELECT nit_id FROM nits WHERE nit_id='$nit_id'";	
$qid=mssql_query($sql);
$num_filas=mssql_num_rows($qid);
echo $num_filas;
?>