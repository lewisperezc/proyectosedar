<?php
class forma_liquidacion_credito
{
	public function con_for_liq_credito()
	{
		$query = "SELECT * FROM forma_liquidacion_credito";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
}
?>