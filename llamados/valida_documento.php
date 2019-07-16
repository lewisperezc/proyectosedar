<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);
$documento=$_GET['docum'];
$sql="SELECT * FROM nits WHERE nits_num_documento='$documento'";
//echo $sql;
$qid=mssql_query($sql);
$num_filas=mssql_num_rows($qid);
echo $num_filas;
?>