<?php
include_once('../conexion/conexion.php');
error_reporting(E_ALL);

$sql = "SELECT cue.cue_id, cue.cue_nombre FROM cuentas cue INNER JOIN ciudades ciu ON ciu.ciu_id = cue.cue_ciudad
        INNER JOIN nits_por_ciudades npc ON npc.ciu_id = cue.cue_ciudad INNER JOIN nits nit ON nit.nit_id = npc.nit_id
        WHERE nit.nit_id =".$_POST['prove']." AND nit.reg_id IN (".$_POST['tipo'].",".$_POST['tipo1'].",NULL)";	
$qid = mssql_query($sql);
$html = "";
if($qid!=false)
  {
	$html.='<option value="0">--Seleccione--</option>';
    while($unarray = mssql_fetch_array($qid))
       $html .= '<option value="'.$unarray["cue_id"].'" onclick="valida();">'.$unarray['cue_id']." ".$unarray['cue_nombre'].'</option>';
    $html .="";
    echo $html;
  }
?>