<?php
@include_once('conexion/conexion.php');
@include_once('../conexion/conexion.php');
class ActivoFijo
{
	public function ConTodTipActFijos()
	{
		$query="SELECT * FROM tipos_activos_fijos";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConPlaActFijo()
	{
		$query="SELECT act_fij_id,act_fij_pla_actual FROM activos_fijos";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConPlaActFijPorPersona($lapersona)
	{
		$query="SELECT act_fij_id,act_fij_pla_actual FROM activos_fijos WHERE nit_id=$lapersona";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConActFijPorPlaca($laplaca)
	{
		$query="SELECT af.act_fij_id,af.act_fij_pla_actual,taf.tip_act_fij_nombre,af.act_fij_descripcion,
maf.mar_act_fij_nombre,af.act_fij_modelo,af.act_fij_serial,af.act_fij_color,af.act_fij_caracteristicas,
af.act_fij_propietario,eaf.est_act_fij_nombre,n.nit_id,n.nits_num_documento,n.nits_nombres,n.nits_apellidos
				FROM dbo.activos_fijos af
				INNER JOIN dbo.tipos_activos_fijos taf ON af.tip_act_fij_id=taf.tip_act_fij_id
				INNER JOIN dbo.marcas_activos_fijos maf ON af.mar_act_fij_id=maf.mar_act_fij_id
				INNER JOIN dbo.estados_activos_fijos eaf ON af.est_act_fij_id=eaf.est_act_fij_id
				INNER JOIN dbo.nits n ON af.nit_id=n.nit_id
				WHERE act_fij_pla_actual='$laplaca'";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function AsiActFijPersona($elresponsable,$elid)
	{
		$query="UPDATE dbo.activos_fijos SET nit_id=$elresponsable WHERE act_fij_id=$elid";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConTodTipTraslado()
	{
		$query="SELECT * FROM tipos_traslados_activos_fijos";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function GuaTraActFijo1($tra_act_fij_fec_registro,$tra_act_fij_res_actual,$tra_act_fij_res_nuevo,
$tip_tra_act_fij_id,$tra_act_fij_mot_traslado)
	{
		$query="INSERT INTO traslados_activos_fijos(tra_act_fij_fec_registro,tra_act_fij_res_actual,tra_act_fij_res_nuevo,
tip_tra_act_fij_id,tra_act_fij_mot_traslado)
				VALUES('$tra_act_fij_fec_registro',$tra_act_fij_res_actual,$tra_act_fij_res_nuevo,
$tip_tra_act_fij_id,'$tra_act_fij_mot_traslado')";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConUltTraRegistrado()
	{
		$query="SELECT MAX(tra_act_fij_id) AS ultimo FROM traslados_activos_fijos";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$elultimo=mssql_fetch_array($ejecutar);
			return $elultimo['ultimo'];
		}
		else
			return false;
	}
	
	public function GuaTraActFijo2($act_fij_id,$tra_act_fij_id)
	{
		$query="INSERT INTO activos_fijos_por_traslados_activos_fijos(act_fij_id,tra_act_fij_id)
				VALUES($act_fij_id,$tra_act_fij_id)";
		echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function GuaDarBajActFijo1($dar_baj_act_fij_fec_registro,$dar_baj_act_fij_res_actual,$tra_act_fij_mot_baja)
	{
		$query="INSERT INTO dar_baja_activos_fijos(dar_baj_act_fij_fec_registro,dar_baj_act_fij_res_actual,tra_act_fij_mot_baja)
				VALUES('$dar_baj_act_fij_fec_registro',$dar_baj_act_fij_res_actual,'$tra_act_fij_mot_baja')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	public function ConUltBajRegistrada()
	{
		$query="SELECT MAX(dar_baj_act_fij_id) AS ultimo FROM dar_baja_activos_fijos";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$elultimo=mssql_fetch_array($ejecutar);
			return $elultimo['ultimo'];
		}
		else
			return false;
	}
	
	public function GuaDarBajActFijo2($act_fij_id,$dar_baj_act_fij_id)
	{
		$query="INSERT INTO activos_fijos_por_dar_baja_activos_fijos(act_fij_id,dar_baj_act_fij_id)
				VALUES($act_fij_id,$dar_baj_act_fij_id)";
		echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function EliActFij($act_fij_id)
	{
		$query="DELETE FROM activos_fijos WHERE act_fij_id=$act_fij_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConTodMotRetActFijo()
	{
		$query="SELECT * FROM motivos_retiro_activos_fijos";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function GuaRetTemActFijo1($ret_tem_act_fij_fec_registro,$ret_tem_act_fij_res_actual,
$mot_ret_act_fij_id,$mot_ret_act_fij_id,$mot_ret_act_fij_observaciones)
	{
		$query="INSERT INTO retiro_temporal_acivos_fijos(ret_tem_act_fij_fec_registro,ret_tem_act_fij_res_actual,
mot_ret_act_fij_id,mot_ret_act_fij_id,mot_ret_act_fij_observaciones)
				VALUES('$ret_tem_act_fij_fec_registro',$ret_tem_act_fij_res_actual,
$mot_ret_act_fij_id,$mot_ret_act_fij_id,'$mot_ret_act_fij_observaciones')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
}
?>