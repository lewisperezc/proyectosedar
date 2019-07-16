<?php
@include_once('../clases/activo_fijo.class.php');
@include_once('clases/activo_fijo.class.php');
$ins_act_fijos=new ActivoFijo();
$laplaca = $_POST['laplaca'];
$con_dat_act_fij_por_placa=$ins_act_fijos->ConActFijPorPlaca($laplaca);
$res="";
$lasfilas=mssql_num_rows($con_dat_act_fij_por_placa);
if($lasfilas>0)
{
	$res_dat_act_fij_por_placa=mssql_fetch_array($con_dat_act_fij_por_placa);
	
	$res.=$res_dat_act_fij_por_placa['act_fij_id']."#".$res_dat_act_fij_por_placa['act_fij_pla_actual']."#".$res_dat_act_fij_por_placa['tip_act_fij_nombre']."#".$res_dat_act_fij_por_placa['act_fij_descripcion']."#".$res_dat_act_fij_por_placa['mar_act_fij_nombre']."#".$res_dat_act_fij_por_placa['act_fij_modelo']."#".$res_dat_act_fij_por_placa['act_fij_serial']."#".$res_dat_act_fij_por_placa['act_fij_color']."#".$res_dat_act_fij_por_placa['act_fij_caracteristicas']."#".$res_dat_act_fij_por_placa['act_fij_propietario']."#".$res_dat_act_fij_por_placa['est_act_fij_nombre']."#".$res_dat_act_fij_por_placa['nit_id']."#".$res_dat_act_fij_por_placa['nits_num_documento']."#".$res_dat_act_fij_por_placa['nits_nombres']."#".$res_dat_act_fij_por_placa['nits_apellidos'];
	error_reporting(E_ALL);
	echo $res;
}
?>