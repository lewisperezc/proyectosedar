<?php
class tipo_contrato_externo
{
	public function con_tip_con_externo()
	{
		$query = "SELECT * FROM dbo.tipo_contrato_externo";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
}