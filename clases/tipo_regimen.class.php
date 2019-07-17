<?php
class tipo_regimen{
private $tipo;

public function cons_tipo_regimen()
{
	$sql= "SELECT *FROM TIPO_REGIMEN";
	$ejecutar=mssql_query($sql);
	return $ejecutar;
}
}
?>