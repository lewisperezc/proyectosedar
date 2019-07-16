<?php
@include_once('clases/ciudades.class.php');
@include_once('../clases/ciudades.class.php');
$ins_ciudades=new ciudades();
$con_ciu_por_dep=$ins_ciudades->con_ciu_por_departamento($_POST['dep_id']);
error_reporting(E_ALL);
$html="";
//$html.="<option  value='0'>--Seleccione--</option>";
while($unarray=mssql_fetch_array($con_ciu_por_dep))
{
    $html.='<option value="'.$unarray["ciu_id"].'">'.$unarray["ciu_nombre"].'</option>';
}
echo $html;

?>