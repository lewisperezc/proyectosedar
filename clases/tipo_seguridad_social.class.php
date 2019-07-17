<?php
class tipo_seguridad_social
{
	public function con_tip_seg_social(){
		$query = "SELECT * FROM dbo.tip_segSocial";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
}
?>