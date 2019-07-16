<?php
include_once('../clases/factura.class.php');
$factura = new factura();
$centro = $_POST['centro'];
$mes = $_POST['mes'];
$fac_centro = $factura->bus_facMes($centro,$mes);
$html='';
while($dat_aso = mssql_fetch_array($fac_centro)) 
  $html.="<option value='".$dat_aso['fac_consecutivo']."' label='".$dat_aso['fac_consecutivo']."'>";

echo $html;
?>
