<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);
$sql = "SELECT pro_id,pro_nombre FROM productos WHERE tip_pro_id = ".$_POST['id'];
$qid = mssql_query($sql);
$html = "";
if($qid!=false)
  {
    while($unarray = mssql_fetch_array($qid))       
       $html .= '<option value="'.$unarray["pro_id"].'">'.substr($unarray["pro_nombre"],0,20).'</option>';
    $html .="";
    echo $html;
  }
?>