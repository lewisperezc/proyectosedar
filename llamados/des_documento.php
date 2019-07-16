<?php session_start();
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/moviminetos_contables.class.php');
$movimiento = new movimientos_contables();
$mes = $_POST['compro'];
$doc_abiertos=$movimiento->consulDocumentos($mes,$_SESSION['elaniocontable']);
$html = "<option value='0'>--Seleccione--</option>";
while($row = mssql_fetch_array($doc_abiertos))
  $html.="<option value='".$row['mov_compro']."'>".$row['mov_compro']." - ".$row['cen_cos_nombre']."</option>";
echo $html;
?>