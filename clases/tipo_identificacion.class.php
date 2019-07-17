<?php
class tipo_identificacion
{
	public function con_tip_identificacion()
	{
		$query="SELECT * FROM tipo_identificacion ORDER BY tip_ide_nombre ASC";
		$ejecutar=mssql_query($query) or die('Error al consultar los tipos de identificacion!!!');
		if($ejecutar)
                    return $ejecutar;
		else
                    return false;
	}
}
?>