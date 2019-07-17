<?php
@include_once('../conexion/conexion.php');
include_once('tipo_facturacion.class.php');
include_once('factura.class.php');

class rec_caja
 {    
	public function buscar_recibos($factura)
	{
		$sql = "SELECT * FROM recibo_caja WHERE rec_caj_factura = $factura";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function guardar_recibos($factura,$fecha,$monto,$desc,$conse)
	{
		$sql = "INSERT INTO recibo_caja(rec_caj_fecha,rec_caj_monto,rec_caj_factura,rec_caj_descripcion,rec_caj_consecutivo) VALUES('$fecha',$monto,$factura,'$desc',$conse)";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
/********Para obtener el consecutivo y actualizarlo, de recibo de caja y de comprobante de egreso********/
	public function obt_consecutivo($comprobante)
   {
	   $sql="SELECT tip_com_consecutivo FROM tipo_comprobante WHERE tip_com_id = $comprobante";
	   $query=mssql_query($sql);
	   if($query)
	   {
		   $row = mssql_fetch_array($query);
		   return $row['tip_com_consecutivo'];
	   }
	   return false;
   }
   
   public function act_consecutivo($comprobante)
   {
	   $sql = "UPDATE tipo_comprobante SET tip_com_consecutivo = tip_com_consecutivo + 1 WHERE tip_com_id = $comprobante";
	   $query = mssql_query($sql);
	   if($query)
		   return true;
		else   
	       return false;
   }
   /******************************************************/
   public function busRec_caja()
   {
	   $sql = "SELECT * FROM recibo_caja WHERE rec_caj_estado = 0";
	   $query = mssql_query($sql);
	   if($query)
	      return $query;
	   else
	     return false;
   }
   
   public function busFactura($recibo)
   {
	   $sql = "SELECT rec_caj_factura,rec_caj_monto FROM recibo_caja WHERE rec_caj_id = $recibo";
	   $query = mssql_query($sql);
	   if($query)
			 return $query;
		else
		  return false; 
   }
   public function sel_max_rec_caja()
   {
	   $query = "SELECT MAX(rec_caj_id) rec_caj_id FROM recibo_caja";
	   $ejecutar = mssql_query($query);
	   if($ejecutar)
	      return $ejecutar;
	   else
	     return false;
   }
   
   public function recibos_caja()
   {	
	   $query = "SELECT DISTINCT rec_caj_id,rec_caj_monto,rec_caj_consecutivo,rec_caj_factura,f.fac_id,f.fac_val_total,cen_cos_nombre,fac_consecutivo FROM recibo_caja rc RIGHT JOIN
	   factura f ON rc.rec_caj_factura=f.fac_id 
		INNER JOIN centros_costo cc ON cc.cen_cos_id = f.fac_cen_cos 
		WHERE rec_caj_estado = 0  ORDER BY cen_cos_nombre";
		
		//echo $query;
	   $ejecutar = mssql_query($query);
	   if($ejecutar)
	      return $ejecutar;
	   else
	      return false;
   }
   
   public function act_reciboCaja($recibo)
   {
	   $dat_recibo = split("-",$recibo);
	   $query = "UPDATE recibo_caja SET rec_caj_estado = 1 WHERE rec_caj_id =".$dat_recibo[0];
	   $ejecutar = mssql_query($query);
	   if($ejecutar)
	      return true;
	   else
	     return false;
   }
   
   public function buscar_recPagados($factura)
	{
		$sql = "SELECT * FROM recibo_caja rc INNER JOIN nomina nom ON nom.nom_recCaja=rc.rec_caj_id
							 INNER JOIN nits_por_cen_costo npcc ON nom.nom_nit_aso = npcc.id_nit_por_cen
							 INNER JOIN nits nit ON npcc.nit_id = nit.nit_id
							 INNER JOIN centros_costo cc ON npcc.cen_cos_id = cc.cen_cos_id
 				WHERE rec_caj_factura = $factura";	
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function datRecibos($recibo)
	{
		$sql = "SELECT * FROM recibo_caja rc INNER JOIN nomina nom ON nom.nom_recCaja=rc.rec_caj_id
							 INNER JOIN nits_por_cen_costo npcc ON nom.nom_nit_aso = npcc.id_nit_por_cen
							 INNER JOIN nits nit ON npcc.nit_id = nit.nit_id
							 INNER JOIN centros_costo cc ON npcc.cen_cos_id = cc.cen_cos_id
 				WHERE rec_caj_id = $recibo";			
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
	public function saldoRecibos($fac)
	{
	  $sql="SELECT ISNULL(SUM(rec_caj_monto),0) recibos from recibo_caja where rec_caj_factura = $fac AND rec_caj_anticipo IS NULL";
	  $query = mssql_query($sql);
	  if($query)
	    {
			$pagos = mssql_fetch_array($query);
			return $pagos['recibos'];
		}
	}
	
	public function saldoNotas($fac)
	{
	  $sql = "EXECUTE deferencia_nota $fac";
	  $query = mssql_query($sql);
	  if($query)
	  {
		  $pagos = mssql_fetch_array($query);
		  return $pagos['diferencia'];
	  }
	  else
	   return false;
	}
	
	public function saldoDescuentos($fac,$tipo)
	{
	  $sql="SELECT SUM(des_monto) monto FROM descuentos descu INNER JOIN recibo_caja rc on rc.rec_caj_id = descu.des_factura INNER JOIN factura fac ON fac.fac_id = rc.rec_caj_factura WHERE fac.fac_id = $fac AND des_tipo NOT IN($tipo)";
	  //echo $sql;
	  $query = mssql_query($sql);
	  if($query)
	    {
			$pagos = mssql_fetch_array($query);
			return $pagos['monto'];
		}
	}
	
	public function guardar_descuentos($factura,$monto,$tipo,$distribucion)
	{
		if($distribucion==2)
			$sql = "INSERT INTO descuentos(des_factura,des_monto,des_tipo) VALUES($factura,$monto,$tipo)";
		else
			$sql = "INSERT INTO descuentos(des_factura,des_monto,des_tipo,des_distribucion) VALUES($factura,$monto,$tipo,$distribucion)";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}
	
	public function totalDescuentos($recibo)
	{
		$sql = "SELECT ISNULL(SUM(des_monto),0) monto FROM descuentos WHERE des_factura = $recibo AND (des_tipo not in(1,2) OR des_distribucion IS NOT NULL)";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query)
		{
			$total = mssql_fetch_array($query);
			return $total['monto'];
		}
		else
		 return false;
	}
	
	public function totalDescuentosGlosas($recibo,$tipos)
	{
		$sql = "SELECT SUM(des_monto) monto FROM descuentos WHERE des_factura = $recibo AND (des_tipo not in($tipos) OR des_distribucion IS NOT NULL)";
		$query = mssql_query($sql);
		if($query)
		{
			$total = mssql_fetch_array($query);
			return $total['monto'];
		}
		else
		 return false;
	}
	
	public function valorRecibo($recibo)
	{
		$sql = "SELECT rec_caj_monto FROM recibo_caja WHERE rec_caj_id = $recibo";
		$query =mssql_query($sql);
		if($query)
		  {
			  $dat_valor = mssql_fetch_array($query);
			  return $dat_valor['rec_caj_monto'];
		  }
	}
	
	public function des_recibo($recibo)
	{
		$sql = "SELECT SUM(des_monto) monto FROM descuentos WHERE des_factura = $recibo AND des_tipo = 11";
		$query = mssql_query($sql);
		if($query)
		 {
			 $dat_des = mssql_fetch_array($query);
			 return $dat_des['monto'];
		 }
		else
		  return false; 
	}
	public function ConRecCajPorFacEstado($factura,$est_recibo)
	{
		$query="SELECT rec_caj_id,rec_caj_consecutivo,rec_caj_monto
				FROM dbo.recibo_caja
				WHERE rec_caj_factura=$factura AND rec_caj_estado IN ($est_recibo)";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function rec_factura($recibo)
	{
		$sql = "SELECT COUNT(*) recibos,rc.rec_caj_factura cantidad FROM recibo_caja rc INNER JOIN recibo_caja rec ON rec.rec_caj_factura=rc.rec_caj_factura  WHERE rc.rec_caj_consecutivo = $recibo GROUP BY rc.rec_caj_factura";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_query = mssql_fetch_array($query);
			if($dat_query['recibos']>1)
			   return $dat_query['cantidad'];
			else
			   return false;   
		}
	}
	
	public function ConDatRecibo()
	{
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$query="SELECT rc.rec_caj_id,rc.rec_caj_consecutivo,rc.rec_caj_monto,rc.rec_caj_factura,cc.cen_cos_id,cc.cen_cos_nombre,f.fac_id,f.fac_consecutivo FROM recibo_caja rc INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id INNER JOIN centros_costo cc ON cc.cen_cos_id=f.fac_cen_cos WHERE cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.")";
		}
		else
		{
			$query="SELECT rc.rec_caj_id,rc.rec_caj_consecutivo,rc.rec_caj_monto,rc.rec_caj_factura,cc.cen_cos_id,cc.cen_cos_nombre,f.fac_consecutivo,f.fac_id FROM recibo_caja rc INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id INNER JOIN centros_costo cc ON cc.cen_cos_id=f.fac_cen_cos";
		}
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConDatcentro()
	{
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$query="SELECT DISTINCT cc.cen_cos_id,cc.cen_cos_nombre FROM recibo_caja rc INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id INNER JOIN centros_costo cc ON cc.cen_cos_id=f.fac_cen_cos WHERE cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.") ORDER BY cen_cos_nombre";
		}
		else
		{
			$query="SELECT DISTINCT cc.cen_cos_id,cc.cen_cos_nombre FROM recibo_caja rc INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id INNER JOIN centros_costo cc ON cc.cen_cos_id=f.fac_cen_cos ORDER BY cen_cos_nombre";
		}
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConTodDatRecCajPorId($elid)
	{
		$query="SELECT DISTINCT n.nits_dir_residencia,rc.rec_caj_fecha,rc.rec_caj_monto,rc.rec_caj_factura,rc.rec_caj_descripcion,
rc.rec_caj_consecutivo,rc.rec_caj_estado,f.fac_consecutivo,cc.cen_cos_id,cc.cen_cos_nombre
				FROM recibo_caja rc
				INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id
				INNER JOIN centros_costo cc ON f.fac_cen_cos=cc.cen_cos_id
				INNER JOIN nits n ON n.nit_id=cc.cen_cos_nit
				WHERE rc.rec_caj_id=$elid";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function dat_recibo($recibo)
	{
		$sql="SELECT mv.mov_cuent,mv.mov_valor,c.cue_nombre,mv.mov_tipo FROM movimientos_contables mv 
INNER JOIN cuentas c ON c.cue_id=mv.mov_cuent
WHERE mv.mov_compro LIKE('rec_caj_".$recibo."')";
		$query=mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function ConRecCajPorIdCentro($elid)
	{
		$query="SELECT *
				FROM recibo_caja rc
				INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id
				WHERE f.fac_cen_cos=$elid";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function SumValRecPorFactura($factura)
	{
		$query="SELECT SUM(rec_caj_monto) suma
				FROM recibo_caja
				WHERE rec_caj_factura='$factura'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['suma'];
		}
		else
			return false;
	}

	public function act_recProvisional($cons,$est)
	{
		$sql="UPDATE recibo_caja SET rec_caj_estado=$est WHERE rec_caj_consecutivo=$cons";
		//echo $sql; 
		$ejecutar=mssql_query($sql);
		if($ejecutar)
			return true;
		else
			return false;	
	}

	public function guardar_recibos_provisional($factura,$fecha,$monto,$desc,$conse)
	{
		
		$sql = "INSERT INTO recibo_caja(rec_caj_fecha,rec_caj_monto,rec_caj_factura,rec_caj_descripcion,
		rec_caj_consecutivo,rec_caj_anticipo)
		VALUES('$fecha',$monto,$factura,'$desc',$conse,1)";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}

	public function guardar_legReciboProv($recibo,$legalizacion)
	{
		$sql="INSERT INTO descuentos(des_factura,des_monto,des_tipo) VALUES ($recibo,$legalizacion,11)";
		//echo $sql; 
		$query=mssql_query($sql);
		if($query)
			return true;
		else
			return false;
	}

	public function verLegRecibo($recibo)
	{
		$sql="SELECT COUNT(*) conteo FROM descuentos WHERE des_factura = $recibo";
		$query=mssql_query($sql);
		if($query)
		{
			$dat_recibo = mssql_fetch_array($query);
			return $dat_recibo['conteo'];
		}
		else
			return false;
	}
	
	public function ValorTotalAbonosFactura($fac_id)
	{
		
		$con_nit_id="SELECT fac_nit FROM factura WHERE fac_id='$fac_id'";
		$eje_nit_id=mssql_query($con_nit_id);
		$res_nit_id=mssql_fetch_array($eje_nit_id);
		$fac_nit_id=$res_nit_id['fac_nit'];
		
		
		$con_uni_funcional="SELECT nit_uni_funcional FROM nits WHERE nit_id=$fac_nit_id";
		$eje_uni_funcional=mssql_query($con_uni_funcional);
		$res_uni_funcional=mssql_fetch_array($eje_uni_funcional);
		$uni_funcional=$res_uni_funcional['nit_uni_funcional'];
		if($uni_funcional==NULL || $uni_funcional=="")
			$uni_funcional='13050501';
		
		
		$sql="SELECT SUM(total_abonos) AS total_abonos
		FROM
		(
		SELECT DISTINCT ISNULL(SUM(mov_valor),0) total_abonos FROM movimientos_contables mc INNER JOIN recibo_caja rc
		ON mc.mov_nume=rc.rec_caj_factura INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id
		WHERE mc.mov_compro LIKE('REC-CAJ_'+CAST(rc.rec_caj_consecutivo AS VARCHAR)) AND mc.mov_cuent='$uni_funcional'
		AND mc.mov_nume='$fac_id' AND f.fac_id='$fac_id' AND rc.rec_caj_anticipo IS NULL
		UNION
		SELECT DISTINCT ISNULL(SUM(mov_valor),0) total_abonos FROM movimientos_contables mc INNER JOIN factura f
		ON mc.mov_nume=f.fac_id AND mc.mov_documento=f.fac_id WHERE mc.mov_compro LIKE('NOT-CRE_%') AND
		mov_cuent=$uni_funcional AND fac_id='$fac_id') AS total_abonos";
		//echo $sql;
		$query=mssql_query($sql);
		if($query)
		{
			$val_abonos = mssql_fetch_array($query);
			return $val_abonos['total_abonos'];
		}
		else
			return false;
	}
	
	public function ValorTotalFactura($fac_id)
	{
		
		$con_nit_id="SELECT fac_nit FROM factura WHERE fac_id='$fac_id'";
		$eje_nit_id=mssql_query($con_nit_id);
		$res_nit_id=mssql_fetch_array($eje_nit_id);
		$fac_nit_id=$res_nit_id['fac_nit'];
		
		
		$con_uni_funcional="SELECT nit_uni_funcional FROM nits WHERE nit_id=$fac_nit_id";
		$eje_uni_funcional=mssql_query($con_uni_funcional);
		$res_uni_funcional=mssql_fetch_array($eje_uni_funcional);
		$uni_funcional=$res_uni_funcional['nit_uni_funcional'];
		if($uni_funcional==NULL || $uni_funcional=="")
			$uni_funcional='13050501';
		
		
		
		
		$sql="SELECT SUM(valor_total_facturas) AS valor_total_facturas FROM (
		SELECT DISTINCT ISNULL(SUM(fac_val_unitario),0) AS valor_total_facturas FROM factura f WHERE f.fac_id='$fac_id'
		UNION
		SELECT DISTINCT ISNULL(SUM(mov_valor),0) valor_total_facturas
		FROM movimientos_contables mc INNER JOIN factura f ON mc.mov_nume=f.fac_id AND mc.mov_documento=f.fac_id
		WHERE mc.mov_compro LIKE('NOT-DEB_%') AND mov_cuent=$uni_funcional AND fac_id='$fac_id'
		) AS valor_total_facturas";
		//echo $sql;
		$query=mssql_query($sql);
		if($query)
		{
			$val_abonos = mssql_fetch_array($query);
			return $val_abonos['valor_total_facturas'];
		}
		else
			return false;
	}
	
	public function ValorNotaDebitoOCredito($fac_id,$sigla)
	{
		
		$con_nit_id="SELECT fac_nit FROM factura WHERE fac_id='$fac_id'";
		$eje_nit_id=mssql_query($con_nit_id);
		$res_nit_id=mssql_fetch_array($eje_nit_id);
		$fac_nit_id=$res_nit_id['fac_nit'];
		
		
		$con_uni_funcional="SELECT nit_uni_funcional FROM nits WHERE nit_id=$fac_nit_id";
		$eje_uni_funcional=mssql_query($con_uni_funcional);
		$res_uni_funcional=mssql_fetch_array($eje_uni_funcional);
		$uni_funcional=$res_uni_funcional['nit_uni_funcional'];
		if($uni_funcional==NULL || $uni_funcional=="")
			$uni_funcional='13050501';
		
		
		$sql="SELECT DISTINCT ISNULL(SUM(mov_valor),0) total_nota
		FROM movimientos_contables mc INNER JOIN factura f ON mc.mov_nume=f.fac_id AND mc.mov_documento=f.fac_id
		WHERE mc.mov_compro LIKE('$sigla%') AND mov_cuent=$uni_funcional AND fac_id='$fac_id'";
		//echo $sql;
		$query=mssql_query($sql);
		if($query)
		{
			$val_nota = mssql_fetch_array($query);
			return $val_nota['total_nota'];
		}
		else
			return false;
	}
	
 }
?>