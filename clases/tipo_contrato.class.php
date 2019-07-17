<?php
	class tipo_contrato
	{
		public function consulta_tipo_contrato()
		{
			$query = "SELECT * FROM tipo_contrato";
			$ejecutar = mssql_query($query);
			return $ejecutar;
		}
	}
?>