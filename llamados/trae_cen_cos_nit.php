<?php
@include_once('../clases/nits.class.php');
$nit = $_POST['id']; 
$tipo = $_POST['id2'];
$ins_nits = new nits();

if($tipo == 1)
{
    $con_cen_cos_nit = $ins_nits->con_cen_cos_asociado($nit);
	error_reporting(E_ALL);
	$html = "";
	while($res_cen_cos_nit = mssql_fetch_array($con_cen_cos_nit))
	{
	$html .= '<option value="'.$res_cen_cos_nit["cen_cos_id"].'">'.$res_cen_cos_nit["cen_cos_nombre"].'</option>';
		$html .="";
		echo $html;
	}
}
?>