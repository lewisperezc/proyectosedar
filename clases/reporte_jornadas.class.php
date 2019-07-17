<?php
@include_once('../conexion/conexion.php');
@include_once('clases/factura.class.php');
@include_once('../clases/factura.class.php');

class reporte_jornadas
{
  private $rep_jor_numero;
  private $rep_jor_mes;
  private $rep_jor_con_hospital;
  private $rep_jos_asociado;
  public function setRep_jor_mes($rep_jor_mes)
  {
    $this->rep_jor_mes = $rep_jor_mes;
  }

  public function getRep_jor_mes()
  {
    return $this->rep_jor_mes;
  }

  public function setRep_jor_con_hospital($rep_jor_con_hospital)
  {
    $this->rep_jor_con_hospital = $rep_jor_con_hospital;
  }

  public function getRep_jor_con_hospital()
  {
    return $this->rep_jor_con_hospital;
  }
  
  public function registrarReporte_factura($rep_jor_num,$nit_por_cen,$tipo,$nota,$consecutivo,$tip_reporte,$factura,$num_jor_afiliado)
  {
	  $mes = date("m/Y");
	  $sql = "INSERT INTO reporte_jornadas (rep_jor_num_jornadas,rep_jor_mes,id_nit_por_cen,rep_jor_tipo,rep_jor_consecutivo,
	  rep_jor_causado,rep_jor_num_factura,rep_jor_nota)
	  VALUES ($rep_jor_num,'$mes',$nit_por_cen,$tipo,$consecutivo,$rep_jor_num,$factura,'$num_jor_afiliado')";
	  $query = mssql_query($sql);
	  if($query)
	  {
		  $fac = new factura();
		  $fac->act_consecutivo(7);
          return true;
	  }
	  else
	     return false;
  }
  
  public function registrarReporte_jornadas($rep_jor_num,$nit_por_cen,$tipo,$consecutivo,$tip_reporte,$ano,$mes,$num_jor_afiliado)
  {
         /*if($mes<=9)
         {
          $mes="0".$mes;
         }*/
	 $mes = $mes."/".$ano;
	 if($tip_reporte==1)
	 {
            if($tipo == 0)
            {
                $sqlReg_jor = "INSERT INTO reporte_jornadas (rep_jor_num_jornadas,rep_jor_mes,id_nit_por_cen,rep_jor_tipo,rep_jor_consecutivo,rep_jor_causado,rep_jor_nota)
                                VALUES ('$rep_jor_num','$mes','$nit_por_cen','$tipo','$consecutivo','$rep_jor_num','$num_jor_afiliado')";
            }	
	    elseif($tipo == 1)
	    {
                $sqlReg_jor = "INSERT INTO reporte_jornadas (rep_jor_num_jornadas,rep_jor_mes,id_nit_por_cen,rep_jor_tipo,rep_jor_consecutivo,rep_jor_causado,rep_jor_nota)
                               VALUES('$rep_jor_num','$mes','$nit_por_cen','$tipo','$consecutivo','$rep_jor_num','$num_jor_afiliado')";
	    }
	 }
	 else
	 {
            if($tipo == 0)
	    {
	    	//echo "entra por el if";
                $sqlReg_jor = "INSERT INTO reporte_jornadas(rep_jor_num_jornadas,rep_jor_mes,id_nit_por_cen,rep_jor_tipo,rep_jor_consecutivo,rep_jor_nota,rep_jor_causado)
                               VALUES ('$rep_jor_num','$mes','$nit_por_cen','$tipo','$consecutivo','$num_jor_afiliado','$rep_jor_num')";
            }
            elseif($tipo == 1)
            {
            	//echo "entra por el else";
                $sqlReg_jor="INSERT INTO reporte_jornadas (rep_jor_num_jornadas,rep_jor_mes,id_nit_por_cen,rep_jor_tipo,rep_jor_consecutivo,rep_jor_nota,rep_jor_causado)
                             VALUES('$rep_jor_num','$mes','$nit_por_cen','$tipo','$consecutivo','$num_jor_afiliado','$rep_jor_num')";
            }
	 }
	/*
	 entra por el else
	 INSERT INTO reporte_jornadas (rep_jor_num_jornadas,rep_jor_mes,id_nit_por_cen,rep_jor_tipo,rep_jor_nota,rep_jor_consecutivo) VALUES(288640.00,'10/2015',,1,'',25035)
	  */
	 //echo $sqlReg_jor;
	 //echo "<br>";

	 $conReg_jornadas=mssql_query($sqlReg_jor);
	 if($conReg_jornadas)
     {
     	$fac = new factura();
        $fac->act_consecutivo(7);
        return true;
	 }
	 else
     { return false; }
}
  
  public function repJornadas($rep_jornada)
  {
	  $sql = "SELECT * FROM reporte_jornadas WHERE rep_jor_id = $rep_jornada";
          //echo "<br>Los datos: ".$sql."<br>";
	  $query = mssql_query($sql);
	  if($query)
	  {
		if(mssql_num_rows($query)>0)
		  return $query;
		else
		{
			$sql = "SELECT * FROM reporte_jornadas WHERE rep_jor_consecutivo = $rep_jornada";
			$query = mssql_query($sql);
			if($query)
			  return $query;
			else
			  return false;  
		}
	  }
	  else
	   return false;
  }
  
  public function cantJornadas($hospital,$mes,$ano)
  {
	  $conRepHos = "SELECT rep_jor_id,rep_jor_consecutivo FROM reporte_jornadas GROUP BY rep_jor_consecutivo,rep_jor_id";
	  $resRepHos = mssql_query($conRepHos);				
	  
  }
  
  public function buscarReporteJornadas_Hospital($hospital,$mes,$ano)
  {
   $fecha = $mes."/".$ano;
   $conRepHos = "SELECT rj.rep_jor_id jor_id,rj.rep_jor_consecutivo consecutivo,rj.rep_jor_num_jornadas num_jor,npcc.cen_cos_id cen_cos, npcc.nit_id nit_id,nit.nits_nombres nombres,nit.nits_apellidos apellidos, nit.nit_est_id estado FROM reporte_jornadas rj INNER JOIN nits_por_cen_costo npcc on rj.id_nit_por_cen = npcc.id_nit_por_cen INNER JOIN nits nit on npcc.nit_id = nit.nit_id WHERE rj.rep_jor_mes = '$fecha' and npcc.cen_cos_id = $hospital"; 		 
   $resRepHos = mssql_query($conRepHos);
   return $resRepHos;
  }
  
   public function modificarReporte($jornada,$nit_cen,$tipo)
   {
    $conModRep = "UPDATE reporte_jornadas SET rep_jor_num_jornadas = $jornada WHERE rep_jor_id = $nit_cen";
    //,rep_jor_causado = $jornada 
    //echo $conModRep;
	$resModRep = mssql_query($conModRep);
    if($resModRep)
      return $resModRep;
    else
      return false;
   }
  
  public function buscarReporteJornadas_Factura($factura)
  {
   $conRepFac = "SELECT * FROM reporte_jornadas rp INNER JOIN nits_por_cen_costo npcc ON rp.id_nit_por_cen=npcc.id_nit_por_cen INNER JOIN nits n ON n.nit_id=npcc.nit_id WHERE rep_jor_num_factura = $factura AND rep_jor_num_jornadas>0 ORDER BY n.nits_apellidos ASC";
   //echo $conRepFac;
   $resRepFac = mssql_query($conRepFac);
   if($resRepFac)
      return $resRepFac;
   else
      return false;	  
  }
  
  public function buscarReporteJornadas_Factura_Abono($factura)
  {
   $conRepFac = "SELECT DISTINCT * FROM rep_jor_con_recibo rp INNER JOIN nits n ON n.nit_id=rp.nit_id WHERE fac_id = $factura ORDER BY n.nits_apellidos ASC";
   //echo $conRepFac;
   $resRepFac = mssql_query($conRepFac);
   if($resRepFac)
      return $resRepFac;
   else
      return false;	  
  }
  
  public function buscarReporteJornadas_abono($factura,$recibo)
  {
   $conRepFac = "SELECT * FROM rep_jor_con_recibo rp INNER JOIN factura f on rp.fac_id=f.fac_id INNER JOIN nits n on n.nit_id=rp.nit_id WHERE rp.fac_id=$factura AND rp.rec_caj_consecutivo=$recibo";
   $resRepFac = mssql_query($conRepFac);
   if($resRepFac)
      return $resRepFac;
   else
      return false;	  
  }
  
  public function buscar_repJornadas($factura)
  {
    $conBusFac = "SELECT * FROM reporte_jornadas WHERE rep_jor_num_factura = $factura";
	$resBusFac = mssql_query($conBusFac);
   if($resBusFac)
      return $resBusFac;
   else
      return false;
  }
  
  public function existeReporte($hospital,$mes,$ano)
  { 
     $fecha = $mes."/".$ano;
	 $sql = "SELECT COUNT(*) num_rep FROM reporte_jornadas rep_jor INNER JOIN nits_por_cen_costo npcc 
	         ON npcc.id_nit_por_cen = rep_jor.id_nit_por_cen WHERE rep_jor_mes = '$fecha' AND cen_cos_id = $hospital";
	 $query = mssql_query($sql);
	 $cant = mssql_fetch_array($query);
	 if($can['num_rep']!=0)
	    return false;
	else
	   return true;
  }
  
  public function reporteMes($mes)
  {
    $sql = "SELECT rep_jor_num_jornadas, rep_jor_mes, id_nit_por_cen FROM reporte_jornadas WHERE rep_jor_mes like('$mes%')";
	$query = mssql_query($sql);
	if($query)
	  return $query;
	else
	  return false;  
  }
  
  public function canJornadas($cen_cos,$mes,$ano)
  {
	  $fecha = (string)"0".$mes."/".$ano;
	  $sql = "SELECT SUM(rep_jor_num_jornadas) rep_jornadas FROM reporte_jornadas rj INNER JOIN nits_por_cen_costo npcc
			  ON rj.id_nit_por_cen = npcc.id_nit_por_cen WHERE cen_cos_id = $cen_cos AND rep_jor_mes = '$fecha' AND rep_jor_num_factura IS NULL";
	  $query = mssql_query($sql);
	  if($query)
	  {
	    $cant = mssql_fetch_array($query);
		return $cant['rep_jornadas'];
	  }
	  return false;
  }
  
    public function canJorFac($fac)
    {
	  $fecha = $mes."/".$ano;
	  $sql = "SELECT SUM(rep_jor_causado) rep_jornadas FROM reporte_jornadas WHERE rep_jor_num_factura = $fac";
	  //echo $sql;
	  $query = mssql_query($sql);
	  if($query)
	  {
	    $cant = mssql_fetch_array($query);
		return $cant['rep_jornadas'];
	  }
	  return false;
   }
  
  //Esta es para colocar la factura que aguarda los reportes de jornadas
  public function actReporte_jor($factura,$rep_jornadas)
  {
	 $rep_jor = "UPDATE reporte_jornadas SET rep_jor_num_factura = $factura WHERE rep_jor_consecutivo = $rep_jornadas";
	 $query_jor = mssql_query($rep_jor);
	 if($query_jor)
	    return true;
	 else
	   return false;
  }
  
  public function cant_reportes($cen_cos,$mes,$ano)
  {
	  $fecha = $mes."/".$ano;
	  $sql = "SELECT f.fac_consecutivo,rp.rep_jor_consecutivo consecutivo,npcc.cen_cos_id centro,sum(rep_jor_num_jornadas)-no.not_monto suma,f.fac_id
	  FROM reporte_jornadas rp INNER JOIN nits_por_cen_costo npcc ON npcc.id_nit_por_cen = rp.id_nit_por_cen 
	  INNER JOIN nits n ON n.nit_id=npcc.nit_id 
	  INNER JOIN factura f ON f.fac_id=rp.rep_jor_num_factura 
	  LEFT JOIN notas no ON no.not_factura=f.fac_id 
	  WHERE npcc.cen_cos_id = $cen_cos AND f.fac_mes_servicio='$mes' AND fac_ano_servicio='$ano' AND n.nit_est_id IN(1,3)
	  GROUP BY rp.rep_jor_consecutivo,npcc.cen_cos_id,f.fac_consecutivo,no.not_monto,f.fac_id";
      //echo "el resultado es: ".$sql;
	  $query = mssql_query($sql);
	  if($query)
	     return $query;
	  else
	    return false;
  }
  
    public function reportes($conse)
    {
        $conRepHos = "SELECT rj.rep_jor_id jor_id,rj.rep_jor_consecutivo consecutivo,rj.rep_jor_num_jornadas num_jor,rj.rep_jor_num_factura factura,npcc.cen_cos_id cen_cos, npcc.nit_id nit_id,nit.nits_nombres nombres, nit.nits_apellidos apellidos, nit.nit_est_id estado, nit.nits_num_documento,fac.fac_id,fac.fac_consecutivo consecu,fac.fac_mes_servicio
        FROM reporte_jornadas rj INNER JOIN nits_por_cen_costo npcc on rj.id_nit_por_cen = npcc.id_nit_por_cen INNER JOIN nits nit on npcc.nit_id = nit.nit_id INNER JOIN factura fac ON fac.fac_id=rj.rep_jor_num_factura
                             WHERE rj.rep_jor_consecutivo = $conse";
        $resRepHos = mssql_query($conRepHos);
        if($resRepHos)
        return $resRepHos;
        else
        return false;
    }
    
    
     public function reportesPorFactura($fac_id)
    {
        $conRepHos = "SELECT rj.rep_jor_id jor_id,rj.rep_jor_consecutivo consecutivo,rj.rep_jor_num_jornadas num_jor,rj.rep_jor_num_factura factura,npcc.cen_cos_id cen_cos, npcc.nit_id nit_id,nit.nits_nombres nombres, nit.nits_apellidos apellidos, nit.nit_est_id estado, nit.nits_num_documento,fac.fac_id,fac.fac_consecutivo consecu,fac.fac_mes_servicio
        FROM reporte_jornadas rj INNER JOIN nits_por_cen_costo npcc on rj.id_nit_por_cen = npcc.id_nit_por_cen INNER JOIN nits nit on npcc.nit_id = nit.nit_id INNER JOIN factura fac ON fac.fac_id=rj.rep_jor_num_factura
        WHERE rj.rep_jor_num_factura = $fac_id ORDER BY nit.nits_apellidos ASC";
        //echo $conRepHos;
        $resRepHos = mssql_query($conRepHos);
        if($resRepHos)
        return $resRepHos;
        else
        return false;
    }
  
  public function bus_con_reporte($rec_caja)
  {
	  $rep_jor = "SELECT rep_jor_consecutivo FROM reporte_jornadas rj INNER JOIN factura fac ON fac.fac_id = rj.rep_jor_num_factura INNER JOIN recibo_caja rc ON fac.fac_id = rc.rec_caj_factura WHERE rc.rec_caj_id = $rec_caja";
	  //echo $rep_jor;
	 $query_jor = mssql_query($rep_jor);
	 if($query_jor)
	   { 
		$dato = mssql_fetch_array($query_jor);
		return $dato['rep_jor_consecutivo'];
	   }
	 else
	   return false;
  }
  
  public function bus_con_rep_adelanto($factura)
  {
	 $rep_jor = "SELECT rep_jor_consecutivo FROM reporte_jornadas WHERE rep_jor_num_factura = $factura";
	 $query_jor = mssql_query($rep_jor);
	 if($query_jor)
	   { 
		$dato = mssql_fetch_array($query_jor);
		return $dato['rep_jor_consecutivo'];
	   }
	 else
	   return false;
  }
  
  public function bus_factura($jornada)
  {
	 $rep_jor = "SELECT rep_jor_num_factura FROM reporte_jornadas WHERE rep_jor_id = $jornada";
	 $query_jor = mssql_query($rep_jor);
	 if($query_jor)
	   { 
		$dato = mssql_fetch_array($query_jor);
		return $dato['rep_jor_num_factura'];
	   }
	 else
	   return false;
  }
  
  public function bus_datCompensacion()
  {
	  $sql = "SELECT * FROM datos_nomina";
	  $query = mssql_query($sql);
	  if($query)
	    return $query;
	  else
	    return $false;	
  }
  
  public function fac_facturas($reporte)
  {
	 $sql = "SELECT rep_jor_num_factura FROM reporte_jornadas WHERE rep_jor_consecutivo = $reporte AND rep_jor_num_factura IS NULL";
	 $query = mssql_query($sql);
	 if($query)
	   {
		   $cant = mssql_num_rows($query);
		   if($cant>0)
			  return 1;
		   else
		      return 0;   
	   }
  }
  
  public function fac_reporte($reporte)
  {
	 $sql = "SELECT * FROM reporte_jornadas WHERE rep_jor_consecutivo = $reporte";
	 $query = mssql_query($sql);
	 if($query)
	   {
		   $cant = mssql_fetch_array($query);
		   return $cant['rep_jor_num_factura'];
	   }
	 else
	   return false;
  }
  
  public function mod_segSocial($id,$valor)
  {
	  $sql = "UPDATE tip_segSocial SET tip_segSoc_porcentaje = '$valor' WHERE tip_segSoc_id=$id";
	  //echo $sql."<br>";
	  $query = mssql_query($sql);
	  if($query)
	    return true;
	  else
	   	return false;
  }
  
  public function act_causado($reporte,$valor)
  {
	  $sql = "UPDATE reporte_jornadas SET rep_jor_causado = $valor WHERE rep_jor_id = $reporte";
	  $query = mssql_query($sql);
	  if($query)
	    return true;
	  else
	    return false;	
  }
  
  public function recorfimado($jornada)
  {
	  $sql = "SELECT fac_rep_reconfirmado FROM factura fac INNER JOIN reporte_jornadas rj ON fac.fac_id = rj.rep_jor_num_factura WHERE rj.rep_jor_consecutivo = $jornada";
	  $query = mssql_query($sql);
	  if($query)
	  {
		  $dat = mssql_fetch_array($query);
		  return $dat['fac_rep_reconfirmado'];
	  }
	  else
	    return false; 
  }
  
  public function recorfimadoPorFactura($fac_id)
  {
	  $sql = "SELECT fac_rep_reconfirmado FROM factura fac WHERE fac.fac_id=$fac_id";
	  //echo $sql;
	  $query = mssql_query($sql);
	  if($query)
	  {
		  $dat = mssql_fetch_array($query);
		  return $dat['fac_rep_reconfirmado'];
	  }
	  else
	    return false; 
  }
  
  
  
  public function act_reconfirmado($jornada)
  {
	  $reporte = $this->reportes($jornada);
	  if($reporte)
	    {
			$fact = mssql_fetch_array($reporte);
			$sql = "UPDATE factura SET fac_rep_reconfirmado = 1 WHERE fac_id = ".$fact['factura'];
			$query = mssql_query($sql);
			if($query)
			   return true;
			else
			   return false;   
		}
	  return false;	
  }
  
  public function act_reconfirmadoPorFactura($factura)
  {
  	
  	$sql = "UPDATE factura SET fac_rep_reconfirmado = 1 WHERE fac_id = $factura";
	//echo $sql;
	$query = mssql_query($sql);
	if($query)
		return true;
	else
	 	return false;
  }
  
  public function distGlosa($jornada,$valor,$rec_caja)
  {
	  $sql = "INSERT INTO distGlosa(disGlo_jornada,disGlo_valor,disGlo_estado,disGlo_rec_caj_id) VALUES('$jornada','$valor','0','$rec_caja')";
	  $query = mssql_query($sql);
	  if($query)
	    return true;
	  else
	    return false;	
  }
  
  

  
  public function distGlosaAbono($nit,$valor,$recibo)
  {
	  $sql = "INSERT INTO distGlosa(disGlo_jornada,disGlo_valor,disGlo_estado,disGlo_recAbono) VALUES($nit,$valor,0,$recibo)";
	  $query = mssql_query($sql);
	  if($query)
	    return true;
	  else
	    return false;	
  }
  
  public function consulGlosa($nit,$jornada,$recibo)
  {
	  $sql="SELECT * FROM distGlosa WHERE disGlo_recAbono='$recibo' AND disGlo_jornada='$jornada'";
	  //echo $sql;
	  $query=mssql_query($sql);
	  $cant_abono=mssql_num_rows($query);
	  if($cant_abono>0)
	  {
		  $dat_query = mssql_fetch_array($query);
	      return $dat_query['disGlo_valor'];
	  }
	  else
	  {
		  $sql = "SELECT SUM(disGlo_valor) valor FROM distGlosa WHERE disGlo_jornada = $jornada AND disGlo_estado=0";
          //echo $sql;
		  $query = mssql_query($sql);
		  if($query)
		  {
			  $dat_query = mssql_fetch_array($query);
			  return $dat_query['valor'];
		  }
		  else
	    	return 0;
	  }
  }
  
  public function actGlosa($jornada,$compensacion,$nit)
  {
	  $sql = "UPDATE distGlosa SET disGlo_estado = 1,disGlo_compensacion='$compensacion',disGlo_nit=$nit 
	  		  WHERE disGlo_jornada = $jornada";
	  $query = mssql_query($sql);
	  if($query)
	     return true;
	  else
	    return false;
  }
  
  public function gua_rep_jor_con_abono($rec_caj_consecutivo,$nit_id,$fac_id,$cen_cos_id,$rep_jor_con_rec_mes_contable,
$rep_jor_con_rec_numero)
  {
	  $rep_jor_con_rec_fec_creacion=date("d-m-Y");
	  $rep_jor_con_rec_anio=$_SESSION['elaniocontable'];
	  $query="INSERT INTO rep_jor_con_recibo(rec_caj_consecutivo,nit_id,fac_id,cen_cos_id,rep_jor_con_rec_mes_contable,
							   rep_jor_con_rec_fec_creacion,rep_jor_con_rec_anio,rep_jor_con_rec_numero)
			  VALUES($rec_caj_consecutivo,$nit_id,$fac_id,$cen_cos_id,$rep_jor_con_rec_mes_contable,
							   '$rep_jor_con_rec_fec_creacion',$rep_jor_con_rec_anio,$rep_jor_con_rec_numero)";
	  $ejecutar=mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  public function reporRecibo($recibo)
  {
	$sql = "SELECT rpcr.rep_jor_con_rec_id jor_id,rpcr.rec_caj_consecutivo consecutivo,rpcr.rep_jor_con_rec_numero num_jor,rpcr.fac_id factura,rpcr.cen_cos_id cen_cos,rpcr.nit_id nit_id,
	nit.nits_nombres nombres,nit.nits_apellidos apellidos,nit.nit_est_id estado,nit.nits_num_documento,fac.fac_consecutivo consecu,fac.fac_id,fac.fac_mes_servicio
 	FROM dbo.rep_jor_con_recibo rpcr
 	INNER JOIN recibo_caja rc ON rc.rec_caj_consecutivo=rpcr.rec_caj_consecutivo AND rc.rec_caj_factura=rpcr.fac_id
 	INNER JOIN nits nit ON nit.nit_id=rpcr.nit_id INNER JOIN factura fac ON fac.fac_id = rpcr.fac_id
 	WHERE rc.rec_caj_id = $recibo ORDER BY nit.nits_apellidos ASC";
    $query = mssql_query($sql);
    //echo $sql;
	if($query)
	{
	  $cant_recibo = mssql_num_rows($query);
	  if($cant_recibo>0)
	     return $query;
	  else
	     return false;
	}
	else
	  return false;
  }
  
  public function totalGlosa($factura)
  {
	  $sql = "SELECT SUM(disGlo_valor) valor FROM distGlosa INNER JOIN reporte_jornadas on rep_jor_id=disGlo_jornada 
WHERE rep_jor_num_factura = $factura";
	  $query = mssql_query($sql);
	  if($query)
	  {
		  $dat_query = mssql_fetch_array($query);
	      return $dat_query['valor'];
	  }
	  else
	    return 0;
  }
  
  public function canJorFacConRecibo($fac)
  {
  	$sql = "SELECT SUM(rep_jor_con_rec_numero) rep_jornadas FROM rep_jor_con_recibo WHERE fac_id=$fac";
	$query = mssql_query($sql);
	if($query)
	{
		$cant = mssql_fetch_array($query);
		return $cant['rep_jornadas'];
	}
	 return false;
   }
}
?>