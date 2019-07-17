<?php
	class tipo_contrato_prestacion
	{
		public function con_tip_con_prestacion()
		{
			$query = "SELECT * FROM tipo_contrato_prestacion";
			$ejecutar = mssql_query($query);
			return $ejecutar;
		}
		
		public function con_nom_tip_con_prestacion($id)
		{
			$query = "SELECT * FROM tipo_contrato_prestacion WHERE tip_con_pre_id=$id";
			$ejecutar = mssql_query($query);
			if($ejecutar){	
				$resultado=mssql_fetch_array($ejecutar);
				return $resultado['tip_con_pre_nombre'];
			}
			else
				return false;
		}
		
	}
?>