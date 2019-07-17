<?php
class tipo_cienta_bancaria
{
	public function con_tip_cuenta()
	{
		$query = "SELECT * FROM tipo_cuenta_bancaria";
		$ejecutar = mssql_query($query) or die('Error en la consulta de tipo cuenta bancaria!!!');
		return $ejecutar;
	}
}
?>