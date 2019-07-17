<?php
class tipo_poliza_o_impuesto
{
	public function con_tip_pol_impuesto($pol_imp_id)
	{
		$query = "SELECT * FROM dbo.tipos_polizas_o_impuestos where pol_imp_id = $pol_imp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
}	
?>