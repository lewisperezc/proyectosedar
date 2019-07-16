<?php
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
error_reporting(E_ALL);
$nit = new nits();
$cant_pabs = $nit->cant_pabs($_POST['id']);
$html = "";
if($cant_pabs!=false)
  {
    $html .= '<option value="'.$cant_pabs.'">'.$cant_pabs.'</option>';
    $html .="";
    echo $html;
  }
?>