<?php
@include_once('../conexion/conexion.php');

class devolucion_aportes
{
	public function ins_dev_aportes($nit_id,$creitos,$nominas,$reg_dev_apo_val_consignar,$reg_dev_apo_fec_apr_retiro,$reg_dev_apo_con_adm_numero,$reg_dev_apo_nom_pre_consejo,$reg_dev_apo_nom_sec_consejo,$reg_dev_apo_fec_inicio,$reg_dev_apo_fec_fin)
	{
		$query = "INSERT INTO registro_devolucion_aportes VALUES($nit_id,'$creitos','$nominas',$reg_dev_apo_val_consignar,'$reg_dev_apo_fec_apr_retiro',$reg_dev_apo_con_adm_numero,'$reg_dev_apo_nom_pre_consejo','$reg_dev_apo_nom_sec_consejo','$reg_dev_apo_fec_inicio','$reg_dev_apo_fec_fin')";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		  return $ejecutar;
		else
		  return false;
	}
}
?>