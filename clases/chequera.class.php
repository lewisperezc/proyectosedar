<?php
@include_once('../conexion/conexion.php');//MODIFICADO 7 DE FEBRERO

class chequera
{
	public function ins_chequera($cue_id,$che_con_ini,$che_con_fin,$che_estado)
	{
		$query = "INSERT INTO chequera(che_cue_pertenece,che_con_ini,che_con_fin,che_estado) vALUES($cue_id,$che_con_ini,$che_con_fin,$che_estado)"; 
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	public function act_consecutivo($cuenta,$valor)
	{
		$query = "UPDATE chequera SET che_consecutivo = $valor WHERE che_cue_pertenece = $cuenta"; 
		$ejecutar = mssql_query($query);
		if($ejecutar)
		   return true;
		else
		   return false;
	}
}
?>