<?php
include_once('../clases/nits.class.php');
include_once('../clases/activo_fijo.class.php');
$ins_nits=new nits();
$ins_act_fijo=new ActivoFijo();
$lacedula=$_POST['lacedula'];
$elnitid=$ins_nits->busNit($lacedula);
$con_pla_por_persona=$ins_act_fijo->ConPlaActFijPorPersona($elnitid);
error_reporting(E_ALL);
$res="";
while($res_pla_por_persona=mssql_fetch_array($con_pla_por_persona))
{
$res.="<option value='".$res_pla_por_persona['act_fij_pla_actual']."' label='".$res_pla_por_persona['act_fij_pla_actual']."'>";
}
echo $res;
?>