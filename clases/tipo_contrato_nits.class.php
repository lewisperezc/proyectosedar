<?php
class tipo_contrato_nits
{
	public function con_tip_contrato()
	{
		$query = "SELECT * FROM tipo_contrato_nits";	
		$ejecutar = mssql_query($query) or die('Error en la consulta de tipos de contrato!!!');
		return $ejecutar;
	}
}
?>