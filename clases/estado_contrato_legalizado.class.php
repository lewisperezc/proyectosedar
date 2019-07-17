<?php
	class estado_contrato_legalizado
	{
		public function con_est_con_legalizado()
		{
			$query = "SELECT * FROM dbo.estados_contrato_legalizado";
			$ejecutar = mssql_query($query);
			return $ejecutar;
		}
	}
?>