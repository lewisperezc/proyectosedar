<?php
@include_once('../conexion/conexion.php');//MODIFICADO 7 DE FEBRERO
@include_once'../inicializar_session.php';
//session_start();

class insercion{

	public function saldo_cuenta($cuenta)
	{
	 $sql = "SELECT sald_mov_valor FROM saldos_cuentas_por_nits WHERE sald_cue_por_nits_id=$cuenta AND sald_mov_tipo = 1";
	 $query = mssql_query($sql);
	 if($query)
	 {
		 $sal_cue = mssql_fetch_array($query);
		 if($sal_cue['sald_mov_valor'])
			 return $sal_cue['sald_mov_valor'];
		 else
		   return false;
	 }
	}
	
	public function ConSalPorCueAnio($cuenta,$ano)
	{
		if(strlen($cuenta)>=8)
			$query="SELECT mes_nombre,sum(mov_valor) valor,mov_tipo,mov_mes_contable FROM movimientos_contables
				INNER JOIN mes_contable ON mes_id=mov_mes_contable
				WHERE mov_cuent LIKE('$cuenta') AND mov_ano_contable=$ano
				AND LEN(mov_cuent)>=8 AND mov_mes_contable<=13
				GROUP BY mes_nombre,mov_tipo,mov_mes_contable
				ORDER BY mov_mes_contable,mov_tipo ASC";
		else
			$query="SELECT mes_nombre,sum(mov_valor) valor,mov_tipo,mov_mes_contable FROM movimientos_contables
				INNER JOIN mes_contable ON mes_id=mov_mes_contable
				WHERE mov_cuent LIKE('$cuenta%') AND mov_ano_contable=$ano AND LEN(mov_cuent)=8 AND mov_mes_contable<=13
				GROUP BY mes_nombre,mov_tipo,mov_mes_contable
				ORDER BY mov_mes_contable,mov_tipo ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

	public function conSalCredito($credito,$ano)
	{
		$query="SELECT mes_nombre,sum(mov_valor) valor,mov_tipo,mov_mes_contable FROM movimientos_contables
				INNER JOIN mes_contable ON mes_id=mov_mes_contable
				WHERE (mov_doc_numer LIKE('$credito') OR mov_documento LIKE('C-$credito') OR (mov_compro LIKE('NOT-PRE%') AND mov_documento='$credito')) AND mov_ano_contable=$ano
				GROUP BY mes_nombre,mov_tipo,mov_mes_contable
				ORDER BY mov_mes_contable,mov_tipo ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	
	public function conSalCreditoSinInteres($credito,$ano,$cuenta)
	{
		$query="SELECT mes_nombre,sum(mov_valor) valor,mov_tipo,mov_mes_contable FROM movimientos_contables
				INNER JOIN mes_contable ON mes_id=mov_mes_contable
				WHERE (mov_doc_numer LIKE('$credito') OR mov_documento LIKE('C-$credito') OR (mov_compro LIKE('NOT-PRE%') AND mov_documento='$credito')) AND mov_ano_contable=$ano
				AND mov_cuent LIKE('$cuenta%')
				GROUP BY mes_nombre,mov_tipo,mov_mes_contable
				ORDER BY mov_mes_contable,mov_tipo ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConSalPorCueAnio2($cuenta,$mes,$anio)
	{
		$query="SELECT mov_compro,mov_fec_elabo,mov_cuent,mov_nit_tercero,nits_num_documento,mov_cent_costo,cen_cos_nombre,mov_valor,mov_tipo,f.fac_consecutivo
				FROM movimientos_contables mc
				LEFT JOIN nits n ON mc.mov_nit_tercero LIKE n.nit_id
				INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
				LEFT JOIN factura f ON f.fac_id=mc.mov_nume
				WHERE mov_cuent='$cuenta' AND mov_mes_contable=$mes AND mov_ano_contable=$anio";
                //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConSalPorNitAnio($nit,$ano,$cuenta)
	{
		$query="SELECT mes_nombre,sum(mov_valor) valor, mov_tipo,mov_mes_contable
				FROM movimientos_contables
				INNER JOIN mes_contable ON mes_id=mov_mes_contable
				WHERE mov_nit_tercero LIKE('$nit') AND mov_ano_contable=$ano AND mov_cuent='$cuenta'
				GROUP BY mes_nombre,mov_tipo,mov_mes_contable
				ORDER BY mov_mes_contable,mov_tipo ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConSalPorNitAni2($nit,$cuenta,$mes,$anio)
	{
		$query="SELECT DISTINCT id_mov,mov_compro,mov_fec_elabo,mov_cuent,mov_nit_tercero,nits_num_documento,mov_cent_costo,cen_cos_nombre,
		mov_valor,mov_tipo,n.nits_nombres,n.nits_apellidos,f.fac_consecutivo
		FROM movimientos_contables mc
		INNER JOIN nits n ON mc.mov_nit_tercero LIKE n.nit_id
		INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
		LEFT JOIN factura f ON f.fac_id=mc.mov_nume
		LEFT JOIN recibo_caja ON rec_caj_factura=mc.mov_nume
		WHERE mov_nit_tercero LIKE('$nit%') AND mov_cuent='$cuenta' AND mov_mes_contable=$mes AND mov_ano_contable=$anio";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConSalPorCenCosAnio($centro,$ano)
	{
		$query="SELECT mes_nombre,sum(mov_valor) valor, mov_tipo,mov_mes_contable
				FROM movimientos_contables
				INNER JOIN mes_contable ON mes_id=mov_mes_contable
				WHERE mov_cent_costo LIKE('$centro') AND mov_ano_contable=$ano
				GROUP BY mes_nombre,mov_tipo,mov_mes_contable
				ORDER BY mov_mes_contable,mov_tipo ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConSalPorCenCosAnio2($centro,$mes,$anio)
	{
		$query="SELECT mov_compro,mov_fec_elabo,mov_cuent,mov_nit_tercero,nits_num_documento,mov_cent_costo,cen_cos_nombre,mov_valor,mov_tipo,f.fac_consecutivo
				FROM movimientos_contables mc
				INNER JOIN nits n ON mc.mov_nit_tercero LIKE n.nit_id
				INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
				LEFT JOIN factura f ON f.fac_id=mc.mov_nume
				LEFT JOIN recibo_caja ON rec_caj_factura=mc.mov_nume
				WHERE mov_cent_costo='$centro' AND mov_mes_contable=$mes AND mov_ano_contable=$anio";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

	public function conSalCredito2($credito,$mes,$anio,$cuenta)
	{
		$query="SELECT mov_compro,mov_fec_elabo,mov_cuent,mov_nit_tercero,nits_num_documento,mov_cent_costo,cen_cos_nombre,
mov_valor,mov_tipo
				FROM movimientos_contables mc
				INNER JOIN nits n ON mc.mov_nit_tercero LIKE n.nit_id
				INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
				WHERE (mov_doc_numer LIKE('$credito') OR mov_documento LIKE('c-$credito')) AND mov_mes_contable=$mes AND mov_ano_contable=$anio AND mov_cuent LIKE('$cuenta%')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function cuentas_nits($nit)
	{
		$sql="SELECT DISTINCT mc.mov_cuent,c.cue_nombre
FROM movimientos_contables mc
LEFT JOIN cuentas c ON mc.mov_cuent=c.cue_id
WHERE mov_nit_tercero LIKE('$nit')";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false;
	}
	public function ConMovCueNit($cuenta,$nit,$mes,$anio)
	{
		$query="SELECT id_mov,mov_mes_contable,mov_compro,mov_nume,mov_cuent,mov_valor,mov_tipo,nit_id,nits_num_documento,
nits_nombres,nits_apellidos
				FROM movimientos_contables inner join nits on mov_nit_tercero like(nit_id)
where mov_cuent LIKE('$cuenta%') AND mov_nit_tercero LIKE($nit) AND mov_mes_contable='$mes' AND mov_ano_contable='$anio' ORDER BY mov_valor";
                //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConSalCuenta($cue_ini,$cue_fin,$mes,$ano)
	{
		$query="SELECT id_mov,mov_mes_contable,mov_compro,mov_nume,mov_cuent,mov_valor,mov_tipo,nit_id,nits_num_documento,nits_nombres,nits_apellidos
			FROM movimientos_contables LEFT join nits ON mov_nit_tercero LIKE(nit_id)
				WHERE mov_cuent BETWEEN $cue_ini AND $cue_fin AND mov_mes_contable=$mes AND mov_ano_contable = $ano AND mov_valor>0 ORDER BY mov_valor,mov_cuent";
        //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

	public function sal_cueAno($cuenta,$ano,$tipo)
	{
		if($tipo==1)
			$sql="SELECT ((SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=1),0))-(SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=2),0))) AS saldo";
		elseif ($tipo==2)
			$sql="SELECT ((SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=2),0))-(SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=1),0))) AS saldo";
		$query=mssql_query($sql);
		if($query)
		{
			$dat_query=mssql_fetch_array($query);
			return $dat_query['saldo'];
		}
		else
			return false;
	}

	public function sal_cueAnoTerc($nit,$ano,$cuenta,$tipo)
	{
		if($tipo==1)
		{
			$sql="SELECT ((SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent='$cuenta' AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=1 AND mov_nit_tercero='$nit'),0))-(SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent='$cuenta' AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=2 AND mov_nit_tercero='$nit'),0))) AS saldo";
		}
		elseif ($tipo==2)
		{
			$sql="SELECT ((SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent='$cuenta' AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=2 AND mov_nit_tercero='$nit'),0))-(SELECT ISNULL((SELECT SUM(mov_valor) FROM movimientos_contables WHERE mov_cuent='$cuenta' AND mov_mes_contable<=13 AND mov_ano_contable=$ano AND mov_tipo=1 AND mov_nit_tercero='$nit'),0))) AS saldo";
		}
		//echo $sql;
		$query=mssql_query($sql);
		if($query)
		{
			$dat_query=mssql_fetch_array($query);
			return $dat_query['saldo'];
		}
		else
			return false;
	}
	
	public function ConsultarSaldoCuentaAnioMes($cuenta,$ano,$mes)
	{
		$query_1="EXECUTE SaldoCuentaPorMes '$cuenta','$ano','$mes'";
		$ejecutar_1=mssql_query($query_1);
		if($ejecutar_1)
		{
			$query_2="select * from reportes order by tres";
			$ejecutar_2=mssql_query($query_2);
			if($ejecutar_2)
				return $ejecutar_2;
			else
				return false;
		
		}
		else
			return false;
		
	}
}//fin class
?>