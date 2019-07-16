<?php
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/nits.class.php');
$ins_mov_contables = new movimientos_contables();
$ins_nits=new nits();
$nit_id = $_POST['id'];
$res="";
if($nit_id )
{
	$cuenta="0";
	//25301006
	$con_nombres=$ins_nits->cons_nombres_nit($nit_id);
	$res_nombres=mssql_fetch_array($con_nombres);
	$debito=$ins_mov_contables->con_sal_fabs_asociado($nit_id,$cuenta,1);
	if($debito=="NULL")
		$debito=0;
	$credito= $ins_mov_contables->con_sal_fabs_asociado($nit_id,$cuenta,2);
	if($credito=="NULL")
		$credito=0;
	$resultado=$credito-$debito;
	error_reporting(E_ALL);
	$res.=number_format($resultado)."#".$res_nombres['nits_num_documento']."#".$res_nombres['nombres'];
	echo $res;
}
?>