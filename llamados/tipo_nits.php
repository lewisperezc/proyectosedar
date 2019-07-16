<?php
include_once('../conexion/conexion.php');
include_once('../clases/../clases/nits_tipo.class.php');
error_reporting(E_ALL);
$nit = new tipo_nit();
$nits = $nit->con_tip_nit($_POST['tipo']);
$html = "";
if($nits!=false)
  {
	while($row=mssql_fetch_array($nits))
	 $html.= '<option value="'.$row['nit_id'].'">'.substr($row['nits_nombres'].' '.$row['nits_apellidos'],0,50).'</option>';
  }
echo $html;
?>