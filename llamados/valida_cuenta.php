<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);
$cue_id=$_GET['cue_id'];
$sql="SELECT cue_id FROM cuentas WHERE cue_id='$cue_id'";	
$qid=mssql_query($sql);
$num_filas=mssql_num_rows($qid);
echo $num_filas;
?>