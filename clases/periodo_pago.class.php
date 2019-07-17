<?php
class periodo_pago
{
	public function con_per_pago()
	{
		$query = "SELECT * FROM periodos_pago_nits";	
		$ejecutar = mssql_query($query) or die('Error en la consulta de periodos de pago!!!');
		return $ejecutar;
	}
}
?>