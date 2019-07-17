<?php
@include_once('../conexion/conexion.php');
class novedad
{
	public function ins_nov_nit($nov_valor,$nit_id,$cue_id,$nov_estado,$nov_observacion)
	{
		$query = "INSERT INTO novedades(nov_valor,nit_id,cue_id,nov_estado,nov_observacion)
		          VALUES($nov_valor,$nit_id,$cue_id,$nov_estado,'$nov_observacion')";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	public function consul_novedades($nit)
	{
	 $query = "SELECT * FROM novedades WHERE nit_id = $nit AND nov_estado = 1";
	 $ejecutar = mssql_query($query);
	 if($ejecutar)
	  return $ejecutar;
	 else
	  return false;
	}
	
	public function actEstado($novedad)
	{
	  $query = "UPDATE novedades SET nov_estado = 2 WHERE nov_id = $novedad";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	    return true;
	  else
	    return false;
	}
	
	public function gua_nov_administrativa($nit_id,$observacion,$archivo)
	{
		$query = "INSERT INTO novedades_administrativas(nov_adm_nit_id,nov_adm_observaciones,nov_adm_adjunto)
		          VALUES('$nit_id','$observacion','$archivo')";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
}
?>