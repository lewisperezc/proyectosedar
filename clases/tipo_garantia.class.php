<?php
class tipo_garantia{
	public function con_tod_tip_garantia(){
		$query = "SELECT * FROM tipos_garantia";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
}
?>