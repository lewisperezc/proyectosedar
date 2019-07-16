<?php
include_once("../conexion/conexion.php");
error_reporting(E_ALL);
$hosp = $_POST['id'];
$sql = "select * from centros_costo cc INNER JOIN contrato on nit_id=cc.cen_cos_nit
WHERE cc.cen_cos_id = $hosp AND est_con_id=1";
$query = mssql_query($sql);
$html = "";
if($query!=false)
  {
    $unarray = mssql_fetch_array($query);
	if($unarray['tip_con_pre_id']==3)
	  $html .="";
	elseif($unarray['con_val_hor_trabajada'] != "" && $unarray['con_val_hor_nocturna']!="")
	  $html .="";
	elseif($unarray['tip_con_pre_id'] == 1)
	  $html .= $unarray['con_val_fac_mensual']; 
    else 
	  $html .= $unarray['con_valor'];
    echo $html;
  }
?>