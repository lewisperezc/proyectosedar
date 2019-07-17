<?php
include_once('../conexion/conexion.php');
class TiposSaldos
{
	public function ConTodTipSaldos()
	{
		$tipos='3';//CENTRO DE COSTO
		$query="SELECT * FROM tipos_saldos WHERE tip_sal_id NOT IN($tipos)";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
}
?>