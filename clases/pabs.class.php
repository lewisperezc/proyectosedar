<?php
@include_once('conexion/conexion.php');
@include_once('clases/nits.class.php');
@include_once('clases/recibo_caja.class.php');
@include_once('../clases/nits.class.php');
@include_once('../clases/recibo_caja.class.php');
@include_once('../clases/moviminetos_contables.class.php');
@include_once('../inicializar_session.php');

class pabs
{
   private $recibo;
   private $nit;
   
   public function __construct()
   {
	   $this->recibo = new rec_caja();
	   $this->nit = new nits();
   }
     
   public function registrar_PABS($aso,$con,$bene,$prod,$descri,$tip_pago,$valor,$fecha,$cant_pro,$conse)
   {
	   $tip_pago = 1;
	   $sql = "INSERT INTO pabs(pab_nit_id,cue_id,pab_beneficiario,pab_producto,pab_descripcion,pab_tip_pago,pab_valor,
							    pab_fecha,pab_cantidad,pab_consecutivo) 
	           VALUES ($aso,$con,$bene,$prod,'$descri',$tip_pago,$valor,'$fecha',$cant_pro,$conse)";
	   $query = mssql_query($sql);
	   if($query)
	   {
		  $nit = new nits();
		  $mod_pabs = $nit->compra_pabs($aso,$valor);
		  if($mod_pabs)
		    {
			 echo "<script type=\"text/javascript\">alert(\"Se modifico el PABS del Dr. $aso\");</script>";
			 return true;
			}
		  else
		    {
				echo "<script type=\"text/javascript\">alert(\"No se modifico el PABS del Dr. $aso\");</script>";
			    return false;
			}
	   }
	   else
	     return false;
   }
   
   public function registrar_telefonia_por_pabs($aso,$con,$bene,$prod,$descri,$valor,$fecha,$cant_pro,$conse)
   {
	   $sql = "INSERT INTO pabs(pab_nit_id,cue_id,pab_beneficiario,lin_tel_id,pab_descripcion,pab_valor,
							    pab_fecha,pab_cantidad,pab_consecutivo) 
	           VALUES ($aso,$con,$bene,$prod,'$descri',$valor,'$fecha',$cant_pro,$conse)";
	   $query = mssql_query($sql);
	   if($query)
	   {
		  $nit = new nits();
		  $mod_pabs = $nit->compra_pabs($aso,$valor);
		  if($mod_pabs)
		    {
			 echo "<script type=\"text/javascript\">alert(\"Se modifico el PABS del Dr. $aso!!\");</script>";
			 return true;
			}
		  else
		    {
				echo "<script type=\"text/javascript\">alert(\"No se modifico el PABS del Dr. $aso!!\");</script>";
			    return false;
			}
	   }
	   else
	     return false;	 
   }
   /*************************************/
   
 /////////////////////// va en tesoria/////////////////////////////  
   public function tip_pago()
   {
	   $sql = "SELECT * FROM TIP_PAGO";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
   
   public function obt_consecutivo()
   {
	 return $this->recibo->obt_consecutivo(11);
   }
   
   public function act_consecutivo()
   {
	 return $this->recibo->act_consecutivo(11);
   }
   
   public function cons_PABS($consecutivo,$mes,$ano)
   {
	   $sql = "SELECT * FROM reg_com_pabs INNER JOIN productos ON pro_id=reg_com_producto INNER JOIN nits ON reg_com_nit=nit_id WHERE reg_com_sigla = '$consecutivo'
	   AND reg_com_mes=$mes AND reg_com_ano=$ano";
	   //echo $sql;
	   $query = mssql_query($sql);
	   if($query)
	    return $query;
	   else
	    return false;	 
   }
   
   public function mod_pabs($consecutivo,$asociado,$beneficiario,$fecha,$centro,$valor)
    {
	   $sql = "DELETE FROM pabs WHERE pab_consecutivo = $consecutivo AND pab_nit_id = $asociado";
	   $query = mssql_query($sql);
	   if($query)
	    {
		 $sql = "SELECT SUM(trans_val_total) total FROM transacciones WHERE trans_sigla = 'pabs-$consecutivo' AND trans_nit = $beneficiario AND trans_centro = $centro";
		 $query = mssql_query($sql);
		 if($query)
		   {
			   $can = mssql_fetch_array($query);
			   $act_pabs = $this->nit->act_pabs($asociado,$valor);
			   $sql = "DELETE FROM transacciones WHERE trans_sigla = 'pabs-$consecutivo' AND trans_nit = $asociado AND trans_centro = $centro";
			   $query = mssql_query($sql);
			   if($query)
			   {
				$sql = "DELETE FROM movimientos_contables WHERE mov_compro = 'pabs-$consecutivo' AND mov_nit_tercero = $asociado AND mov_cent_costo = $centro";
			   $query = mssql_query($sql);
			    if($query)
				   return true; 
			   }
			   else
			     return false;
		   }
		}
	   else
	    return false;
    }
	
	public function lineasPABS()
	{
		$sql = "SELECT * FROM lineas_pabs";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function guardar_compraPABS($nit,$fecha,$prod,$valor,$descr,$linea,$provee,$tel,$canti,$med_pago,$sigla,$mes,$ano)
	{
		$sql = "INSERT INTO reg_com_pabs(reg_com_nit,reg_com_fecha,reg_com_producto,reg_com_valor,reg_com_descrip,reg_com_linea,reg_com_prove,reg_com_telefono,reg_com_cantidad,reg_com_med_pago,reg_com_sigla,reg_com_mes,reg_com_ano) VALUES ('$nit','$fecha','$prod',$valor,'$descr',$linea,'$provee',$tel,$canti,1,'$sigla',$mes,$ano)";
		$query = mssql_query($sql);
		if($query)
		   return true;
		 else
		   return false;
	}
	
	public function cuenta_pabs($tipo)
	{
		$sql = "SELECT pabs_cuenta FROM lineas_pabs WHERE pabs_id = $tipo";
		$query = mssql_query($sql);
		if($query)
		 {
			 $dat_cuenta = mssql_fetch_array($query);
			 return $dat_cuenta['pabs_cuenta'];
		 }
		else
		  return false;
	}
	
	public function borrFabs($con,$mes,$ano)
	{
		$sql = "DELETE FROM reg_com_pabs WHERE reg_com_sigla='$con' AND reg_com_mes=$mes AND reg_com_ano=$ano";
		$query = mssql_query($sql);
		if($query)
		  return false;
		else
		  return true;  
	}
	
	public function pagFabs()
	{
		/*Hay que colocar para el otro pago, lo que es credito y lo que es debito*/
		$sql = "SELECT DISTINCT mov_nit_tercero,nits_num_documento,nits_nombres+' '+nits_apellidos nombre,cen_cos_nombre,mov_valor,mov_fec_elabo,mov_mes_contable,nits_num_cue_bancaria,banco
		FROM movimientos_contables INNER JOIN nits ON nit_id=mov_nit_tercero 
		INNER JOIN bancos ON cod_banco=tip_cue_ban_id INNER JOIN centros_costo ON cen_cos_id=mov_cent_costo 
		INNER JOIN reg_com_pabs ON reg_com_sigla=mov_compro WHERE mov_cuent IN (23359501) 
		AND tip_nit_id = 1 AND mov_compro LIKE ('Cau-Fabs_%') AND reg_com_linea = 9
		ORDER BY mov_nit_tercero";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false; 
	}
	
	public function ConRegFabPorCenCosto($ano,$mes)
	{
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$id_user=$_SESSION["k_nit_id"];
		$query1="";
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			//NO TIENE CC PRINCIPAL
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$query="SELECT DISTINCT n.nit_id,n.nits_num_documento,n.nits_nombres+' '+n.nits_apellidos nombres,mc.mov_compro,tra.trans_user,mc.mov_fec_elabo,m.mes_nombre,mov_valor
					FROM nits n
					LEFT JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
					LEFT JOIN centros_costo cc ON npcc.cen_cos_id=cc.cen_cos_id
					INNER JOIN movimientos_contables mc ON mc.mov_nit_tercero LIKE (n.nit_id)
					INNER JOIN transacciones tra ON tra.trans_sigla=mc.mov_compro
					INNER JOIN mes_contable m ON m.mes_id=mc.mov_mes_contable
					WHERE (cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros."))
					AND mc.mov_compro LIKE('Cau-Fabs_%') AND mov_ano_contable=$ano AND mov_mes_contable=$mes AND mov_valor>0";
                        //echo $query;
		}
		else
		{
			$query="SELECT DISTINCT n.nit_id,n.nits_num_documento,n.nits_nombres+' '+n.nits_apellidos nombres,mc.mov_compro,tra.trans_user,mc.mov_fec_elabo,m.mes_nombre,mov_valor
					FROM nits n
					LEFT JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
					LEFT JOIN centros_costo cc ON npcc.cen_cos_id=cc.cen_cos_id
					INNER JOIN movimientos_contables mc ON mc.mov_nit_tercero LIKE (n.nit_id)
					INNER JOIN transacciones tra ON tra.trans_sigla=mc.mov_compro
					INNER JOIN mes_contable m ON m.mes_id=mc.mov_mes_contable
					WHERE mc.mov_compro LIKE('Cau-Fabs_%') AND mov_ano_contable=$ano AND mov_mes_contable=$mes AND mov_valor>0";
		}
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

	public function res_pabs($ano,$mes)
	{
		$id_user=$_SESSION["k_nit_id"];
		$query1="SELECT DISTINCT n.nit_id,n.nits_num_documento,n.nits_nombres+' '+n.nits_apellidos nombres,mc.mov_compro,tra.trans_user,mc.mov_fec_elabo,m.mes_nombre,mov_valor 
				     FROM nits n 
				     LEFT JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id 
				     LEFT JOIN centros_costo cc ON npcc.cen_cos_id=cc.cen_cos_id 
                                     INNER JOIN movimientos_contables mc ON mc.mov_nit_tercero LIKE (n.nit_id) INNER JOIN transacciones tra ON tra.trans_sigla=mc.mov_compro 
				     INNER JOIN mes_contable m ON m.mes_id=mc.mov_mes_contable WHERE mc.mov_compro LIKE('Cau-Fabs_%') AND mov_ano_contable=$ano AND mov_mes_contable=$mes AND mov_valor>0
				     AND trans_user=$id_user order by mov_compro";
                //echo "<br>".$query1."<br>";
		$ejecutar=mssql_query($query1);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

	public function ciu_causado($nit)
	{
		
		$sql="SELECT cc.cen_cos_nombre FROM nits n INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
		INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id WHERE n.nit_id='$nit'";
		//echo $sql."<br>";
		$query=mssql_query($sql);
		if($query)
		{
			$dat_query=mssql_fetch_array($query);
			return $dat_query['cen_cos_nombre'];
		}
		else
			return false;
		
	}

	public function fabs_tercero($tercero,$mes)
	{
		$sql="SELECT trans_id,reg_com_sigla,reg_com_valor FROM reg_com_pabs INNER JOIN transacciones ON trans_sigla=reg_com_sigla AND reg_com_nit=trans_nit WHERE reg_com_nit=$tercero
		AND est_tra_id IS NULL AND tran_mes_contable=$mes";
		$query=mssql_query($sql);
		if($query)
			return $query;
		else
			return false;
	}
        
        public function ConsolidadoFabsPorMes($mes,$anio,$doc_inicial,$doc_final)
        {
            $query="SELECT reg_com_nit,ni.nits_num_documento,ni.nits_apellidos,ni.nits_nombres,n.nits_num_documento nit_proveedor,n.nits_nombres nombre_proveedor,reg_com_valor,reg_com_sigla,
            reg_com_mes,
                    reg_com_ano,reg_com_prove m
                    FROM reg_com_pabs INNER JOIN nits ni on nit_id=reg_com_nit INNER JOIN nits n on n.nit_id=reg_com_prove
                    WHERE reg_com_mes IN($mes) and reg_com_ano=$anio AND ni.nits_num_documento BETWEEN '$doc_inicial' AND '$doc_final' ORDER BY nits_apellidos ASC";
            //echo $query;
            $ejecutar=mssql_query($query);
            if($ejecutar)
                return $ejecutar;
            else
                return false;
        }
    
    public function ConsolidadoFabsALaFecha($cuenta,$fecha1,$fecha2,$tipo_nit)
    {
        $query="SELECT id_mov,mov_cuent,mov_nit_tercero,mov_compro,CONVERT(VARCHAR,CAST(mov_fec_elabo AS datetime),105)
		AS mov_fec_elabo,nits_num_documento,nits_nombres,nits_apellidos,mov_valor,mov_tipo,
		mov_mes_contable,mov_ano_contable,mov_cuent
		from movimientos_contables 
		inner join nits on mov_nit_tercero like (CAST(nit_id AS varchar)+'%')
		where
		(mov_cuent IN(SELECT dis_por_con_fab_cue_uno FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('CAU-FABS_%') AND mov_ano_contable>=2018) OR
		(mov_cuent IN(SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('PAG-COM-%') AND mov_ano_contable>=2018) OR
		(mov_cuent IN(SELECT dis_por_con_fab_cue_uno FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('DEV-FABS_%') AND mov_ano_contable>=2018) OR
		(mov_cuent IN(SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('CIE-2017') AND mov_ano_contable>=2018)
		order by mov_nit_tercero,mov_ano_contable,mov_mes_contable,mov_compro,mov_fec_elabo";
/*
 AND
mov_fec_elabo BETWEEN CONVERT(VARCHAR,CAST('$fecha1' AS DATETIME),105) AND CONVERT(VARCHAR,CAST('$fecha2' AS DATETIME),105)
 */
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
            return $ejecutar;
        else
            return false;
    }

        public function saldo_inicial($ano,$mes,$nit)
        {
        	
		  $cue_fab_causados='25052001';
		  
		  $sql = "SELECT dbo.sal_iniFABS ($ano,$mes,$nit,$cue_fab_causados) AS saldo";
		  $query = mssql_query($sql);
		  if($query)
		  {
		  	$dat_query=mssql_fetch_array($query);
		  	return $dat_query['saldo'];
		  }
        }

        public function repFabs($mes,$ano)
        {
        	$sql = "SELECT id_mov,mov_nit_tercero,mov_compro,mov_fec_elabo,nits_num_documento,nits_nombres,
        	nits_apellidos,mov_valor,mov_tipo FROM movimientos_contables 
        	INNER JOIN nits ON mov_nit_tercero LIKE (CAST(nit_id AS varchar)+'%') WHERE mov_cuent IN(
			SELECT dis_por_con_fab_cue_uno FROM distribucion_porcentajes_conceptos_fabs
			)
        	AND mov_valor>0 AND mov_mes_contable=$mes AND mov_ano_contable=$ano 
        	ORDER BY mov_ano_contable,mov_mes_contable,mov_nit_tercero";
			//echo $sql;
        	$query = mssql_query($sql);
        	if($query)
        		return $query;
        	else
        		return false;
        }
		
		public function ConsultarFechaFabs($mes,$ano,$sigla)
        {
        	$sql = "SELECT DISTINCT reg_com_fecha
			from reg_com_pabs
			where reg_com_mes='$mes' and reg_com_ano='$ano' and reg_com_sigla='$sigla'";
        	$query = mssql_query($sql);
        	if($query)
			{
				$res_fecha=mssql_fetch_array($query);
				return $res_fecha['reg_com_fecha'];	
			}
        	else
        		return false;
        }
		
		public function ConUsuRegFabs($sigla,$mes,$ano)
        {
        	$query="SELECT DISTINCT t.trans_sigla,t.trans_user,t.trans_fec_doc,t.tran_mes_contable,t.trans_ano_contable,
			n.nit_id,n.nits_num_documento,n.nits_apellidos,n.nits_nombres
			FROM transacciones t
			INNER JOIN nits n ON t.trans_user=n.nit_id
			WHERE t.trans_sigla='$sigla' AND t.tran_mes_contable='$mes'
			AND t.trans_ano_contable='$ano'";
			//echo $query;
        	$ejecutar=mssql_query($query);
        	if($ejecutar)
        		return $ejecutar;
        	else
        		return false;
        }
		
		
		public function ConTodPorFonFabs()
        {
        	$query="SELECT * FROM distribucion_porcentajes_conceptos_fabs ORDER BY dis_por_con_fab_id ASC";
			//echo $query;
        	$ejecutar=mssql_query($query);
        	if($ejecutar)
        		return $ejecutar;
        	else
        		return false;
        }
		
		
	public function ConDatLinFabPorId($fabs_id)
	{
		$sql="SELECT * FROM lineas_pabs WHERE pabs_id='$fabs_id'";
		$query=mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
}
?>