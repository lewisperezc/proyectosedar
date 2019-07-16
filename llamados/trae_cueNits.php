<?php session_start();
include_once('../clases/saldos_cuentas.class.php');
include_once('../clases/cuenta.class.php');
$ins_sal_cuentas= new insercion();
$nit=$_POST['nit'];
$res='';
$cuentas = $ins_sal_cuentas->cuentas_nits($nit);
while($row=mssql_fetch_array($cuentas))
   $res.="<option value='".$row['mov_cuent']."' label='".$row['mov_cuent']." ".$row['cue_nombre']."'>";
echo $res;
?>