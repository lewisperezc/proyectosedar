<?php
include_once('../clases/reporte_jornadas.class.php');
$jornadas = new reporte_jornadas();
$factura = $_GET['fac_id'];
//echo $factura;
$reconfirmar = $jornadas->act_reconfirmadoPorFactura($factura);
if($reconfirmar)
   echo "<script>alert('Se reconfirmo el reporte de jornadas satisfactoriamente.');</script>";
else
   echo "<script>alert('No se pudo reconfirmar el reporte de jornadas, intentelo de nuevo.');</script>";   
echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=34'>";
?>