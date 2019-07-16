<?php
include_once('../clases/contrato.class.php');
$ins_contrato=new contrato();
$ins_nits = new nits();
$con_tod_afiliados=$ins_nits->con_nit_contrato(1,1,3,$_POST['centro']);
$consulta='';

while($row = mssql_fetch_array($con_tod_afiliados))
   $consulta.="<option value='".$row['nit_id']."'>".$row['nits_apellidos']." ".$row['nits_nombres']."</option>";

echo $consulta;
?>