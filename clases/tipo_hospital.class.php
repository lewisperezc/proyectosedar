<?php
	class tipo_hospital
	{
		public function crear_tipo($hos,$cuenta)
		{
			$query = "INSERT INTO tipo_hospital VALUES($hos,$cuenta)";
			$ejecutar = mssql_query($query);
			if($ejecutar)
			   return true;
			else
			   return false;   
		}
	}
?>