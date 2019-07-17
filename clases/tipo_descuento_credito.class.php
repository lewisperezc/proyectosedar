<?php
class tipo_descuento_credito
{
	public function con_tip_des_credito()
	{
		$query = "SELECT * FROM tipo_descuento_credito";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
}
?>