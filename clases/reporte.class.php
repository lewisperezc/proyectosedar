<?php
include_once('../conexion/conexion.php');
include_once('centro_de_costos.class.php');
include_once('estado_civil.class.php');
include_once('nits.class.php');
include_once('moviminetos_contables.class.php');

class reporte
{
	private $cen_costo;
	private $est_civil;
	private $nit;
	public function __construct()
  	{
		$this->cen_costo = new centro_de_costos();
		$this->est_civil = new estado_civil();
		$this->nit = new nits();
	}
	
	public function cons_centro_costos()
	{
	  return $this->cen_costo->cons_centro_costos();
    }
	
	public function con_est_civil()
	{
	  return $this->est_civil->con_est_civil();
    }
	
	public function con_doc_tel_dir_nit($tipo)
	{
		return $this->nit->con_doc_tel_dir_nit($tipo);
	}
	
	public function con_cue_por_rangos($desde,$hasta)
	{
		$query="SELECT *
				FROM cuentas
				WHERE CAST(cue_id AS VARCHAR) BETWEEN '$desde' AND '$hasta'
				ORDER BY CAST(cue_id AS VARCHAR) ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_inf_sal_nits($tip_nit_id)
	{
		$query="SELECT n.nit_id,n.nits_num_documento,n.nits_nombres+' '+n.nits_apellidos nombres,n.nits_salario,cen_cos_nombre,
n.nit_aux_transporte
				FROM nits n
				INNER JOIN contrato c ON n.nit_id=c.nit_id
				INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
				INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
				WHERE tip_nit_id = $tip_nit_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_inf_sal_por_nit($tip_nit_id,$nit_documento)
	{
		$query="SELECT n.nit_id,n.nits_num_documento,n.nits_nombres+' '+n.nits_apellidos nombres,n.nits_salario,cen_cos_nombre,
n.nit_aux_transporte
				FROM nits n
				INNER JOIN contrato c ON n.nit_id=c.nit_id
				INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
				INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
				WHERE tip_nit_id = $tip_nit_id AND n.nits_num_documento = '$nit_documento'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_nit_por_est_tipo($tip_nit,$estado)
	{
		$query="SELECT nits_num_documento,nits_nombres+' '+nits_apellidos nombres,nit_est_nombre
				FROM nits n INNER JOIN nits_estados ne ON n.nit_est_id=ne.nit_est_id
				WHERE n.tip_nit_id = $tip_nit AND n.nit_est_id = $estado";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_nit_por_tipo($tip_nit)
	{
		$query="SELECT nits_num_documento,nits_nombres+' '+nits_apellidos nombres,nit_est_nombre
				FROM nits n INNER JOIN nits_estados ne ON n.nit_est_id=ne.nit_est_id
				WHERE n.tip_nit_id = $tip_nit";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_cum_ani_mes($tipo,$mes)
	{
		$query="SELECT nits_num_documento,nits_nombres+' '+nits_apellidos nombres,cen_cos_nombre,nits_fec_nacimiento
				FROM nits n
				INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
				INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
				WHERE  tip_nit_id IN($tipo) AND nits_fec_nacimiento LIKE ('%-$mes-%')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_dat_apo_sis_salud_1($tipo)
	{
		$query="SELECT n1.nit_id,n1.nits_num_documento,n1.nits_nombres+' '+n1.nits_apellidos nombres,n2.nits_nombres
				FROM nits n1
				INNER JOIN nits n2 ON n1.nits_eps=n2.nit_id
				WHERE n1.tip_nit_id=$tipo
				ORDER BY n1.nits_apellidos ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	public function con_dat_apo_sis_salud_2($fecha,$cuenta,$nit,$sigla)
	{
		$query="SELECT mc.mov_valor
				FROM movimientos_contables mc
				WHERE mc.mov_fec_elabo LIKE('%$fecha') AND mc.mov_cuent = $cuenta AND mov_nit_tercero LIKE('$nit%')
				AND mc.mov_compro LIKE('$sigla%')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['mov_valor'];
		}
		else
			return false;
	}
	
	public function con_dat_apo_sis_pension_1($tipo)
	{
		$query="SELECT n1.nit_id,n1.nits_num_documento,n1.nits_nombres+' '+n1.nits_apellidos nombres,n2.nits_nombres
				FROM nits n1
				INNER JOIN nits n2 ON n1.nits_arp=n2.nit_id
				WHERE n1.tip_nit_id=$tipo ORDER BY n1.nits_apellidos ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_dat_bas_nit($tipo)
	{
		$query="SELECT nit_id,nits_nombres+' '+nits_apellidos nombres,nits_num_documento
				FROM nits
				WHERE tip_nit_id=$tipo";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_dat_bas_nit_por_documento($tipo,$documento)
	{
		$query="SELECT nit_id,nits_nombres+' '+nits_apellidos nombres,nits_num_documento
				FROM nits
				WHERE tip_nit_id=$tipo AND nits_num_documento='$documento'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_ben_asociado($asociado)
	{
		$query="SELECT b.ben_num_identificacion,b.ben_nombres+' '+b.ben_apellidos nombres,p.par_nombres,b.ben_por_beneficios
				FROM beneficiarios b
				INNER JOIN nits_por_beneficiarios npb ON b.ben_id=npb.ben_id
				INNER JOIN parentescos p ON b.par_id=p.par_id
				WHERE npb.nit_id=$asociado";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
		
	}
	
	public function con_tod_nits()
	{
		$query="SELECT nits_num_documento,nits_nombres,nits_apellidos
				FROM nits
				ORDER BY tip_nit_id,nits_nombres ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function libro_bancos($cuenta,$mes,$ano)
	{
		$query="SELECT mov_compro,mov_fec_elabo,mov_nit_tercero,nits_nombres,mov_valor,mov_tipo,nits_num_documento
				FROM movimientos_contables INNER JOIN nits on mov_nit_tercero=nit_id
				WHERE mov_cuent like('$cuenta%') AND mov_mes_contable IN ($mes) AND mov_fec_elabo LIKE ('%$ano') ORDER BY CAST(mov_compro AS varchar) ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_tod_cen_costo()
	{
		$query="SELECT * FROM centros_costo";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_tod_aso($tip_nit)
	{	
		$query="SELECT n.nit_id,nits_num_documento,nits_nombres,nits_apellidos,nits_dir_residencia,nits_tel_residencia,nits_num_celular,
		nits_cor_electronico
		FROM nits n INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
		INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
		WHERE tip_nit_id=$tip_nit
		ORDER BY nits_nombres,nits_apellidos ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ord_pago($nomina,$recibo)
	{
		if($recibo==1)
		{
		 $sql="SELECT mv.mov_compro,mv.mov_nume,mv.mov_fec_elabo,mv.mov_nit_tercero,cc.cen_cos_nombre,nit.nit_id,
				nit.nits_num_documento,nit.nits_apellidos,nit.nits_nombres,nit.nits_num_cue_bancaria,nit.nits_ban_id,
				ban.banco,mv.mov_valor,fac.fac_consecutivo,fac.fac_val_total
				FROM movimientos_contables mv INNER JOIN factura fac on mv.mov_nume = fac.fac_id 
				INNER JOIN nits nit on nit.nit_id=mv.mov_nit_tercero
				INNER JOIN bancos ban on ban.cod_banco=nits_ban_id INNER JOIN centros_costo cc on cc.cen_cos_id=mv.mov_cent_costo WHERE mov_compro = '$nomina' AND mov_cuent = '25051001'";
		}
		else
		{
		 $sql="SELECT SUM(mc.mov_valor) mov_valor,mc.mov_compro,mc.mov_nume,mc.mov_fec_elabo,cc.cen_cos_nombre,n.nit_id,n.nits_num_documento,
n.nits_apellidos,n.nits_nombres,n.nits_num_cue_bancaria,n.nits_ban_id,b.banco,f.fac_consecutivo,
rc.rec_caj_monto,rc.rec_caj_id
FROM movimientos_contables mc
INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
INNER JOIN nits n ON mc.mov_nit_tercero=CAST(n.nit_id AS VARCHAR)
left JOIN bancos b ON n.nits_ban_id=b.cod_banco
INNER JOIN factura f ON mc.mov_nume=f.fac_id
INNER JOIN recibo_caja rc ON f.fac_id=rc.rec_caj_factura
WHERE mov_compro = '$nomina' AND rc.rec_caj_id=$recibo AND mc.mov_cuent = '25051001'
GROUP BY mc.mov_compro,mc.mov_nume,mc.mov_fec_elabo,cc.cen_cos_nombre,n.nit_id,n.nits_num_documento,
n.nits_nombres,n.nits_apellidos,n.nits_num_cue_bancaria,b.banco,f.fac_consecutivo,rc.rec_caj_monto,rc.rec_caj_id,n.nits_ban_id";
                 //echo $sql;
		}
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false;   
	}
	
	public function des_recibo($rec_caja)
	{
		$sql = "SELECT SUM(des_monto) descuento FROM descuentos WHERE des_factura=$rec_caja";
		$query = mssql_query($sql);
		if($query)
		{
		   $dat_valor = mssql_fetch_array($query);
		   return $dat_valor['descuento'];
		}
		else
		   return false;
	}

	public function des_recibo_2($rec_caja,$des_tipo)
	{
		$sql = "SELECT SUM(des_monto) descuento FROM descuentos WHERE des_factura=$rec_caja AND des_tipo IN($des_tipo)";
		$query = mssql_query($sql);
		if($query)
		{
		   $dat_valor = mssql_fetch_array($query);
		   return $dat_valor['descuento'];
		}
		else
		   return false;
	}
	
	public function descuentos($nit,$nomina)
	{
		$sql = "SELECT SUM(des_ane_dinero) suma FROM des_anestecoop WHERE nit_id=$nit and des_nomina='$nomina'";
		$query = mssql_query($sql);
		if($query)
		 {
			 $dat_des = mssql_fetch_array($query);
			 if($dat_des['suma']>0)
			    return $dat_des['suma'];
			 else
			    return false;	
		 }
	}
	
	public function contratos($est_con_id)
	{
		$sql =" SELECT con.con_id,con.con_vigencia,con.con_fec_inicio,con.con_fec_fin,nit.nits_nombres,MAX(ISNULL(ao.adi_otr_fec_fin,'01-01-2014')) as adi_otr_fec_fin,tao.tip_adi_nombre,ISNULL(ao.tip_adi_otr_id,0)
				FROM contrato con
				INNER JOIN nits nit ON nit.nit_id=con.nit_id
				LEFT JOIN adiciones_otrosi ao ON ao.con_id=con.con_id
				LEFT JOIN tipos_adicion_otrosi tao ON ao.tip_adi_otr_id=tao.tip_adi_otr_id
				WHERE est_con_id=$est_con_id AND (ao.tip_adi_otr_id IN (7,8) OR ao.tip_adi_otr_id IS NULL)
				GROUP BY con.con_id,con.con_vigencia,con.con_fec_inicio, con.con_fec_fin,nit.nits_nombres,tao.tip_adi_nombre,ao.tip_adi_otr_id
				ORDER BY con_id ASC";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
}
?>