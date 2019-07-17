<?php
$base_datos='sedasoftrediseno';
mssql_connect('SISTEMAS1-PC','sa','@nestecoop12') or die('Error al conectar con el servidor');
mssql_select_db($base_datos) or die('No se encuentra la BD.');
ini_set("mssql.charset", "UTF-8");
//mssql_close();
?>