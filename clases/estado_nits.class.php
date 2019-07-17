<?php
@include_once('../conexion/conexion.php');
@include_once('conexion/conexion.php');

class estado_nits
{
	public function con_est_nits()
	{
		$query = "SELECT * FROM nits_estados";
		$ejecutar = mssql_query($query);
		return $ejecutar;
    }
}
?>