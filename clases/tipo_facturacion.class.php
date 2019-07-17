<?php
	class tipo_facturacion
	{
		public function con_tip_facturacion()
		{
			$query = "SELECT * FROM tipo_facturacion";
			$ejecutar = mssql_query($query);
			return $ejecutar;
		}
	
	}
?>