<?php
@include_once('../inicializar_session.php');
@include_once('inicializar_session.php');
@include_once('../conexion/conexion.php');
	class tipo_concepto
	{
		public function con_tip_concepto($id_tip_concepto)
		{
			$query = "SELECT * FROM conceptos WHERE con_tipo = $id_tip_concepto ORDER BY con_nombre ASC";
			$ejecutar = mssql_query($query);
			if($ejecutar)
				return $ejecutar;
			else
				return false;
	    }
	}
?>