<?php
$conexion = mysqli_connect("localhost","root","@") or die ("No conecta con SQLSERVER");
mysqli_select_db("bd_facturacion") or die ("<b>No se encuentra la Base De Datos</b>");
ini_set("mssql.charset", "UTF-8");
?>