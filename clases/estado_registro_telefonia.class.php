<?php
class estado_registro_telefonia{
	public function con_tod_est_reg_telefonia(){
		$query = "SELECT * FROM estados_registros_telefonia";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
}
?>