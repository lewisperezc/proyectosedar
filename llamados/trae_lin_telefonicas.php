<?php
include_once('../clases/telefonia.class.php');
include_once('../clases/nits.class.php');
$ins_telefonia = new telefonia();
$nit_id = $_POST['id'];
$concep = $_POST['conce'];
$credito=$_POST['cred'];
$res="";
if($credito){
	$con_lin_tel_por_nit = $ins_telefonia->activas($nit_id,2);
	error_reporting(E_ALL);
	if($con_lin_tel_por_nit!=false)
  	{
    	while($unarray = mssql_fetch_array($con_lin_tel_por_nit))       
		  	$res.="<option value='".$unarray['lin_tel_id']."'>".$unarray["lin_tel_nombres"]."</option>";
		echo $res;	
  	}
}
if($concep==4)
{
	$con_lin_tel_por_nit = $ins_telefonia->activas($nit_id,1);
	error_reporting(E_ALL);
	if($con_lin_tel_por_nit!=false)
  	{
    	while($unarray = mssql_fetch_array($con_lin_tel_por_nit))       
		  	$res.="<option value='".$unarray['lin_tel_id']."'>".$unarray["lin_tel_nombres"]."</option>";
		echo $res;	
  	}
}
?>