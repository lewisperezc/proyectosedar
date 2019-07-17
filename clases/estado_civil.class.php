<?php
class estado_civil
{
	public function con_est_civil()
	{
		$query = "SELECT * FROM estados_civiles";
		$ejecutar = mssql_query($query) or die('Error en la consulta de Estado Civil');
		return $ejecutar;
	}
}
?>