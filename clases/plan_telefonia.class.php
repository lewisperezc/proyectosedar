<?php
@include_once('../conexion/conexion.php');

class plan_telefonia
{
	public function ins_pla_telefonia($nombre,$valor,$proveedor)
	{
		$query = "INSERT INTO planes_telefonia(pla_tel_nombre,pla_tel_valor,nit_id)
				  VALUES ('$nombre',$valor,$proveedor)";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	public function cons_tod_pla_telefonia()
	{
		$query = "SELECT pla_tel_id,pla_tel_nombre FROM planes_telefonia";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else false;
	}
	public function cons_pla_por_id($id)
	{
		$query = "SELECT * FROM planes_telefonia WHERE pla_tel_id = $id";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	public function act_pla_telefonia($nombre,$valor,$proveedor,$id)
	{
		$query = "UPDATE planes_telefonia SET pla_tel_nombre = '$nombre',pla_tel_valor = $valor,nit_id = $proveedor
				 WHERE pla_tel_id = $id";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	///////////////////////////////////////////////
}
?>