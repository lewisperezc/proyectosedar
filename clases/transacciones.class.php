<?php 
@include_once('../conexion/conexion.php');
include_once('centro_de_costos.class.php');
include_once('nits.class.php');
@include_once('../inicializar_session.php');
@include_once('inicializar_session.php');
$ct= new centro_de_costos();
$tc =$ct->cons_centro_costos();
class transacciones
{
	private $c;
	private $cen_costo;
	private $conc_numero ;
	private $conc_sigla ;
	private $ejecute;
   
    public function __construct()
    {
      $this->cen_costo = new centro_de_costos();
	  $this->nit = new nits();
    }
	
	public function buscar_centro_costos()
    {
	 return $this->cen_costo->cons_centro_costos();
    }
	
	public function con_cen_cos_ord_por_hospital()
	{
		return $this->cen_costo->con_cen_cos_ord_por_hospital();
	}
	
	public function obtener_concecutivo()
	{
	   $sql = "SELECT MAX(trans_id) max_id from transacciones";
	   $con = mssql_query($sql);
	   return $con;
	}
	
    public function guaTransaccionCau($sigla,$fecha_fact,$prov,$cen_cos,$val_total,$iva,$fec_ven,$num_oc_fa,$usu,$fecha,$mes_contable,$descripcion,$ano_contable)//SOLO GUARDA EN transacciones
	{
	  $sql = "EXECUTE transaccion '$sigla','$fecha_fact','$prov','$cen_cos',$val_total,$iva,'$fec_ven','$num_oc_fa','$usu','$fecha',$mes_contable,'$ano_contable'";
          $ins = mssql_query($sql);					 
	  if($ins)
		return true;
	  else
	   return false;
	}
	
	public function guaTransaccion($sigla,$fecha_fact,$prov,$cen_cos,$val_total,$iva,$fec_ven,$num_oc_fa,$usu,$fecha,$mes_contable,$ano_contable)//SOLO GUARDA EN transacciones
	{
	  $sql = "EXECUTE transaccion '$sigla','$fecha_fact',$prov,'$cen_cos',$val_total,$iva,'$fec_ven','$num_oc_fa','$usu','$fecha','$mes_contable','$ano_contable'";
	  //echo $sql."<br>";
	  $ins = mssql_query($sql);
	  if($ins)
		return true;
	  else
	   return false;
	}
	
   public function guaPagTransaccion($sigla,$fecha_fact,$prov,$cen_cos,$val_total,$iva,$fec_ven,$num_oc_fa,$usu,$fecha,$conce,$tran,$mes,$anio)
	{ 
	  $sql="EXECUTE pagTransaccion '$sigla','$fecha_fact','$prov','$cen_cos','$val_total','$iva','$fec_ven','$num_oc_fa','$usu','$fecha','$conce','$tran','$mes','$anio'";
          //echo $sql;
      $ins = mssql_query($sql);
	  if($ins)
		return true;
	  else
	   return false;
	}
	
	public function guaDetallePro($num_tran,$fecha,$producto,$cantidad,$valor,$iva,$cen_cos,$can,$sigla)//CONSULTA trans_id SEGUN EL DOCUMENTO.
	{
	 $ban = 0;
	 $sql = "EXECUTE dataTransaccion '$num_tran','$fecha',$producto,$cantidad,$valor,$iva,$cen_cos,'$sigla'";
	 //EXECUTE dataTransaccion 'CAU-FABS_538','15-05-2013',189,1,1000000,,1164,'Cau-Fabs_538'
	 $exe = mssql_query($sql);
	 if(!$exe)
	    $ban = 1;
	 if($ban == 0)
	   {
		 $probar = "select count(*) num from camino";
		 $pro = mssql_query($probar);
		 $prob = mssql_fetch_array($pro);
		 if($prob['num'] == $can)
		   {
		     $camino = "EXECUTE tran_det_final $can";
			 $ejecuta = mssql_query($camino);
			 if($ejecuta)
			   return true;
			 else
			   return false;
		   }
	   }
	}
	
	public function act_transaccion($transaccion,$concepto)
	{
		$sql = "UPDATE transacciones SET tran_con_id = $concepto WHERE trans_id = $transaccion";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}
	
	public function dat_transaccion($transaccion)
	{
		$sql = "SELECT * FROM transacciones WHERE trans_id = $transaccion";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
	public function num_tran($fac)
	{
		$sql = "SELECT trans_id,trans_fac_num FROM transacciones WHERE trans_fac_num = '$fac' AND tran_tran_id IS NULL";
		$query = mssql_query($sql);
		if($query)
		  {
			  $dat_tran = mssql_fetch_array($query);
			  return $dat_tran['trans_id'];
		  }
		 else
		   return false;
	}
	
	public function num_tranRecibo($recibo)
	{
		$sql = "select trans_id,trans_fac_num from transacciones where trans_sigla = 'REC-CAJ_$recibo'";
		$query = mssql_query($sql);
		if($query)
		  {
			  $dat_tran = mssql_fetch_array($query);
			  return $dat_tran['trans_id'];
		  }
		 else
		   return false;
	}
	
	public function tran_proveedores($proveedor,$ano,$mes,$nit_id)
	{
		//NULL: Sin pagos
		//1: Abonos
		//2: pago total
		
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			//NO TIENE CC PRINCIPAL
    		$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$query="EXECUTE pago_ter_banco_por_centro '$proveedor',$ano,$mes,$nit_id";
		}
		else
		{
			//echo "entra por el else";
			//PERTENECE AL PRINCIPAL
			$query="EXECUTE pago_ter_banco '$proveedor',$ano,$mes";
		}
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function tran_proveedores2()
	{
		$query="SELECT * FROM pago_terceros ORDER BY trans_sigla";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ord_desembolso($tran)
	{
		$sql = "SELECT tra.*, n.nits_nombres+' '+n.nits_apellidos nombre,n.nits_num_documento nit FROM transacciones tra
				INNER JOIN nits n on n.nit_id=tra.trans_nit 
				WHERE tra.trans_sigla = '$tran' AND est_tra_id IS NULL";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false;   
	}
	
	public function tran_contable($tran,$valor,$tercero,$mes,$ano)
	{
		$sql = "SELECT trans_sigla FROM transacciones WHERE trans_id = $tran";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		 {
			 $sigla = mssql_fetch_array($query);
			 $sql_mov="SELECT DISTINCT m.id_mov,m.mov_compro,m.mov_cuent,m.mov_nit_tercero,m.mov_concepto,m.mov_valor valor 
			 			 FROM movimientos_contables m INNER JOIN transacciones t ON t.trans_sigla=m.mov_compro
						 INNER JOIN nits n ON CAST(n.nit_id AS VARCHAR)=m.mov_nit_tercero
			 		     WHERE t.trans_sigla='".$sigla['trans_sigla']."' AND mov_valor=$valor AND mov_nit_tercero='$tercero' AND mov_mes_contable=$mes AND mov_ano_contable=$ano";
                         //echo $sql_mov;
			 $query_mov = mssql_query($sql_mov);
			 if($query_mov)
			 {
				 $dat_query_mov=mssql_fetch_array($query_mov);
				 /*$sql_movimiento="SELECT m.mov_compro,m.mov_cuent,m.mov_concepto,m.mov_valor AS valor FROM movimientos_contables m 
								  WHERE m.mov_nit_tercero = '".$dat_query_mov['mov_nit_tercero']."' AND m.mov_compro='".$sigla['trans_sigla']."'
								  AND mov_mes_contable=$mes AND mov_ano_contable=$ano
								  --GROUP BY m.mov_compro,m.mov_cuent,m.mov_concepto";*/
				 $sql_movimiento="SELECT m.mov_compro,m.mov_cuent,m.mov_concepto,m.mov_valor AS valor FROM movimientos_contables m 
								  WHERE m.mov_nit_tercero = '".$dat_query_mov['mov_nit_tercero']."' AND m.mov_compro='".$sigla['trans_sigla']."' 
								  AND mov_mes_contable=$mes AND mov_ano_contable=$ano AND id_mov>= (SELECT TOP 1 id_mov FROM movimientos_contables m WHERE m.mov_nit_tercero = '".$dat_query_mov['mov_nit_tercero']."' AND m.mov_compro='".$sigla['trans_sigla']."'
								  AND mov_mes_contable=$mes AND mov_ano_contable=$ano AND mov_valor=".$dat_query_mov['valor'].")";
				 //echo $sql_movimiento;
				 $query_movimiento = mssql_query($sql_movimiento);
				 if($query_movimiento)
			       return $query_movimiento;
				 else
				   return false;
			 }
			 else
			    return false;
		 }
		else
		  return false;  
	}
	
	public function guaObservacion($observacion,$opcion,$valor,$tran)
	{
		$transaccion = $this->obtener_concecutivo();
		$dat_transaccion = mssql_fetch_array($transaccion);
		if($opcion==1)
		   $sql = "UPDATE transacciones SET trans_observacion='$observacion' WHERE trans_id=".$dat_transaccion['max_id'];
		else
		  $sql = "UPDATE transacciones SET trans_observacion='$observacion' WHERE trans_id=".$dat_transaccion['max_id']; 
		$query = mssql_query($sql);
		if($query)
		{
			$sql="UPDATE transacciones SET est_tra_id=$opcion,tran_con_id=4 WHERE trans_id=$tran";
			$query = mssql_query($sql);
			if($query)
			 	return true;
			else
				return false;
		}
		else
		  return false;  
	}
	
	public function documen_pagar($user)
	{
		$sql="SELECT DISTINCT n.nits_num_documento,n.nits_nombres FROM transacciones t INNER JOIN nits n ON n.nit_id=t.trans_nit WHERE(trans_sigla LIKE ('Pag-pro-%') OR trans_sigla LIKE ('Pag-pro_%')) AND est_tra_id IS NULL
			  AND trans_user IN(SELECT DISTINCT n.nit_id FROM nits_por_cen_costo npcc INNER JOIN nits n ON n.nit_id=npcc.nit_id WHERE cen_cos_id IN(SELECT cen_cos_id FROM CENTROS_COSTO 
			  WHERE ciud_ciu_id IN(SELECT DISTINCT cc.ciud_ciu_id FROM nits n INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id WHERE n.nit_id=$user)) AND tip_nit_id=2)	ORDER BY nits_nombres";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false;   
	}
	
	public function doc_pago($sigla)
	{
		$sql = "SELECT transacciones.* FROM transacciones INNER JOIN nits ON nit_id=trans_nit WHERE nits_num_documento='$sigla' AND (trans_sigla LIKE ('Pag-pro-%') OR trans_sigla LIKE ('Pag-pro_%')) AND est_tra_id IS NULL";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function cuenta_pagar($tran_id,$nit)
	{
		if(TRIM($tran_id)=="")
			$tran_id=1;
		$sql = "SELECT mov.mov_cuent,t.trans_val_total valor FROM movimientos_contables mov INNER JOIN transacciones t ON t.trans_sigla = mov.mov_compro WHERE t.trans_id = 
		$tran_id AND (mov.mov_cuent LIKE ('2380%') OR mov.mov_cuent LIKE ('2335%') OR mov.mov_cuent LIKE ('2370%')) AND mov.mov_nit_tercero LIKE ($nit) GROUP BY mov_cuent,trans_val_total";
		$query = mssql_query($sql);
		if($query)
			return $query;
		else
			return false;	
	}
	
	public function actu_transaccion($transaccion,$cheque)
	{
		if(empty($cheque))
			$sql = "UPDATE transacciones SET est_tra_id=1, pag_che_id=$cheque WHERE trans_id = $transaccion";
		else
			$sql = "UPDATE transacciones SET est_tra_id=1 WHERE trans_id = $transaccion";
		$query = mssql_query($sql);
		if($query)
		   return true;
		else
		  return false;
	}
	
	public function con_num_quincena($sigla){
		$query="SELECT trans_fac_num FROM transacciones WHERE trans_sigla='$sigla'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['trans_fac_num'];
		}
		else
			return false;
	}
	
	public function act_est_nom_adm_causada($nue_estado)
	{
		$query="UPDATE dbo.transacciones SET estado_nomina_admin = $nue_estado
				WHERE trans_id=(SELECT MAX(trans_id) ultimo FROM transacciones)";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function act_est_nom_adm_pagada($nue_estado,$sigla)
	{
		$query="UPDATE dbo.transacciones SET estado_nomina_admin = $nue_estado
				WHERE trans_sigla='$sigla'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function borrTransaccion($con,$mes,$ano)
	{
		$sql = "DELETE FROM transacciones WHERE trans_sigla='$con' AND tran_mes_contable=$mes AND trans_fec_doc LIKE ('%$ano')";
		$query = mssql_query($sql);
		if($query)
		  return false;
		else
		  return true;  
	}
	
	public function act_tran_diferido($sigla)
	{
		$sql = "UPDATE transacciones SET trans_canDiferido=trans_canDiferido+1 WHERE trans_sigla = '$sigla'";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}
        
    Public function ConDatOrdDesembolso($anio)
     {
        $query="SELECT t.trans_id,trans_fec_doc,t.trans_sigla,n.nit_id,n.nits_nombres,t.trans_val_total,t.est_tra_id,t.tran_mes_contable
                FROM transacciones t
                INNER JOIN nits n ON t.trans_nit=n.nit_id
                WHERE (trans_sigla LIKE('PAG-PRO-%') OR trans_sigla LIKE('PAG-PRO_%')) AND trans_fec_doc LIKE('%-%-$anio')
                ORDER BY CAST(trans_fec_doc AS DATETIME)";
        //ECHO $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
           return $ejecutar;
        else
           return false;
    }
        
    public function TodDatOrdDesembolso($sigla,$mes,$fecha)
    {
        $query="SELECT t.*,n.nit_id,n.nits_nombres nombre,n.nits_apellidos,n.nits_num_documento nit
                FROM transacciones t INNER JOIN nits n ON t.trans_nit=n.nit_id WHERE trans_sigla='$sigla' AND tran_mes_contable=$mes AND trans_fec_doc='$fecha'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
           return $ejecutar;
        else
           return false;
    }

    public function exis_orden($sigla,$mes)
    {
    	$sql="SELECT COUNT(*) veces FROM transacciones WHERE tran_tran_id IN (SELECT trans_id FROM transacciones WHERE trans_sigla='$sigla' AND tran_mes_contable=$mes)";
    	//echo $sql;
    	$query=mssql_query($sql);
    	if($query)
    	{
    		$dat_query=mssql_fetch_array($query);
    		return $dat_query['veces'];
    	}
    }

    public function elim_ordenDesembolso($orden){
    	$sql="SELECT tran_tran_id FROM transacciones WHERE trans_id=$orden";
    	$query=mssql_query($sql);
    	if($query)
    	{
    		$dat_query=mssql_fetch_array($query);
    		$sql="UPDATE transacciones SET est_tra_id=null, tran_con_id=null WHERE trans_id=".$dat_query['tran_tran_id'];
    		$sql.="DELETE transacciones WHERE trans_id=$orden";
    		$query=mssql_query($sql);
    		if($query)
    			return true;
    		else
    			return false;
    	}
    }

    public function desTransacciones($sigla,$mes,$ano)
    {
    	$sql="SELECT trans_observacion FROM transacciones WHERE trans_sigla IN ('$sigla') AND tran_mes_contable=$mes AND trans_fec_grabado LIKE ('%$ano')";
    	//echo $sql; 
    	$query=mssql_query($sql);
    	if($query)
    	{
    		$dat_query = mssql_fetch_array($query);
    		//echo $dat_query['trans_observacion'];
    		return $dat_query['trans_observacion'];
    	}
    	else
    		return false;
    }

    public function consulTransaccion($sigla,$mes,$ano)
    {
    	$sql="SELECT * FROM movimientos_contables WHERE mov_compro='$sigla' AND mov_mes_contable=$mes AND mov_ano_contable=$ano";
		//echo $sql;
    	$query=mssql_query($sql);
    	if($query)
    		return $query;
    	else
    		return false;
    }
	
	public function consulTransaccionPorNit($sigla,$mes,$ano,$nit)
    {
    	$sql="SELECT * FROM movimientos_contables
    	WHERE mov_compro='$sigla' AND mov_mes_contable=$mes AND mov_ano_contable=$ano AND mov_nit_tercero='$nit'";
		//echo $sql;
    	$query=mssql_query($sql);
    	if($query)
    		return $query;
    	else
    		return false;
    }

    public function consulOrden($sigla,$mes,$ano)
    {
    	$sql="SELECT * FROM ordenes_compra
    	WHERE ord_com_conse='$sigla' AND ord_com_mes=$mes AND ord_com_ano=$ano";
		//echo $sql;
    	$query=mssql_query($sql);
    	if($query)
    		return $query;
    	else
    		return false;
    }

}
?>