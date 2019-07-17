<?php
	class estado_contrato
	{
		public function con_est_contrato()
		{
			$query = "SELECT * FROM dbo.estados_contratos";
			$ejecutar = mssql_query($query);
			return $ejecutar;
		}
	}
?>