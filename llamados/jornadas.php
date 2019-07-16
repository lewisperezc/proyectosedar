<?php
include_once('../conexion/conexion.php');
include_once('../clases/reporte_jornadas.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/contrato.class.php');
include_once('../clases/reporte_jornadas.class.php');
$fac = new factura();
$cen_cos = new centro_de_costos();
$contrato = new contrato();
$reporte = new reporte_jornadas();
$cen_costo = $_POST["centro"];
$mes_seleccionado = explode("-",$_POST["mes"]);
$mes=$mes_seleccionado[1];



if(strlen($mes)==1)
   $mes="0".$mes;
$ano = $_POST["ano"];
$cons_fac = $fac->obt_consecutivo(2);
$consecutivo =  $cons_fac+1;

$fecha = time();

$reportes = $reporte->cant_reportes($cen_costo,$mes,$ano);
$i=0;

if(mssql_num_rows($reportes)>0)
  {
	while($row = mssql_fetch_array($reportes))
	{
	   $res[$i]["consecutivo"]=$row['consecutivo'];
	   $res[$i]["cen_cos"]=$row['centro'];
	   $res[$i]["jornadas"]=$row['suma'];
	   $i++;
	}
  }
 
$busFactura = "SELECT fac_id FROM factura WHERE fac_fecha like('%$mes-$ano') AND fac_cen_cos = $cen_costo";
$queFactura = mssql_query($busFactura);
$resFactura = mssql_fetch_array($queFactura);
if($resFactura['fac_id']>0)
{
    $res[$i]["consecutivo"]="Ya existe Factura en el mes";
	$res[$i]["cen_cos"]="seleccionado, pero no ";
	$res[$i]["jornadas"]="tiene distribucion de jornadas"; 
}
else
{
	$res[$i]["consecutivo"]="";
	$res[$i]["cen_cos"]="";
	$res[$i]["jornadas"]=""; 
}
echo json_encode($res);
?>