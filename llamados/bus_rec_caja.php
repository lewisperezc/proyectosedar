<?php 
include_once('../conexion/conexion.php');
include_once('../clases/reporte_jornadas.class.php');
error_reporting(E_ALL);
$reporte = new reporte_jornadas();
$jor = $reporte->bus_con_reporte($_POST['id']);
$html = require_once("../formularios/mostrar_reporte.php?jor=".$jor);
echo $html;
?>