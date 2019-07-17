<?php
@include_once('../conexion/conexion.php');//MODIFICADO 7 DE FEBRERO
@include_once('conexion/conexion.php');
include_once('nits.class.php');
include_once('tipo_descuento_credito.class.php');
include_once('forma_liquidacion_credito.class.php');
include_once('concepto.class.php');
include_once('recibo_caja.class.php');
include_once('saldos.class.php');
include_once('cuenta.class.php');
include_once('tipo_garantia.class.php');
include_once('moviminetos_contables.class.php');

class credito
{
	private $nits;
	private $tip_des_credito;
	private $for_liq_credito;
	private $concepto;
	private $recibo;
	private $sal_cuentas;
	private $cuenta;
	private $ciudades;
	private $tipo_garantia;
	private $mov_contable;
	
	public function __construct()
  	{
		$this->nits = new nits();
		$this->tip_des_credito = new tipo_descuento_credito();
		$this->for_liq_credito = new forma_liquidacion_credito();
		$this->concepto = new concepto();
		$this->recibo = new rec_caja();
		$this->sal_cuentas = new saldos();
		$this->cuenta = new cuenta();
		$this->tipo_garantia = new tipo_garantia();
		$this->mov_contable = new movimientos_contables();
	}
	
	public function con_tod_tip_garantia(){
		return $this->tipo_garantia->con_tod_tip_garantia();
	}
	
	public function consultar_ciudades(){
		return $this->nits->consultar_ciudades();
	}
	
	public function busPorCuenta($cuenta)
	{
		return $this->cuenta->busPorCuenta($cuenta);
	}
	//Inicio Buscar Cuentas Bancarias
	public function cuentas_bancarias()
	{
		return $this->cuenta->cuentas_bancarias();
	}
	//Fin Buscar Cuentas Bancarias
	
	public function cen_cos_prin()
    {
	    return $this->nits->cen_cos_prin();
    }
  
	public function cen_cos_sec()
	{
		return $this->nits->cen_cos_sec();
	}
	
	public function gru_concepto()
	{
		return $this->concepto->concep_credito();
	}
	
	public function con_nit_por_id_estado($tip_nit_id,$nit_estado)
	{
		return $this->nits->con_aso_por_id_estado($tip_nit_id,$nit_estado);
	}
	
	
	public function con_nit_codeudor($tip_nit_id,$nit_estado,$nit_id)
	{
		return $this->nits->con_nit_codeudor($tip_nit_id,$nit_estado,$nit_id);
	}
	
	public function con_nit_con_cre_cre_registrado($tip_nit,$est_nit)
	{
		return $this->nits->con_nit_con_cre_cre_registrado($tip_nit,$est_nit);
	}
	
	public function con_cen_cos_credito($id_asociado,$id_empleado)
    {
	  return $this->nits->con_cen_cos_credito($id_asociado,$id_empleado);
    }
	
	public function con_cen_cos_credito2($per_cen_cos)
    {
	  return $this->nits->con_cen_cos_credito2($per_cen_cos);
    }
	
	public function con_tip_des_credito()
	{
		return $this->tip_des_credito->con_tip_des_credito();
	}
	
	public function con_for_liq_credito()
	{
		return $this->for_liq_credito->con_for_liq_credito();
	}
	
    public function obt_consecutivo()
    {
	      return $this->recibo->obt_consecutivo(9);
    }
   
    public function act_consecutivo()
    {
	   return $this->recibo->act_consecutivo(9);
    }
   
	//INICIO FUNCIONES PARA REGISTRAR CREDITO
	
	public function ins_reg_credito($sel_persona,$cre_linea,$cre_cen_cos,$cre_observacion,$cre_valor,$cre_dtf,$cre_interes,$cre_num_cuotas,$cre_tip_descuento,$cre_fec_solicitud,$cre_fec_pri_pago,$cre_fec_vencimiento,$cre_for_liquidacion,$cre_garantia,$cre_tip_garantia,$cre_sec_tra_carro,$cre_num_pla_carro,$cre_num_esc_casa,$cre_num_not_casa,$cre_fec_con_casa,$cre_nota,$cre_codeudor)
	{
		$query="EXECUTE RegistrarCredito
$sel_persona,$cre_linea,$cre_cen_cos,'$cre_observacion',$cre_valor,'$cre_dtf','$cre_interes',$cre_num_cuotas,$cre_tip_descuento,'$cre_fec_solicitud','$cre_fec_pri_pago','$cre_fec_vencimiento',$cre_for_liquidacion,'$cre_garantia',$cre_tip_garantia,$cre_sec_tra_carro,'$cre_num_pla_carro','$cre_num_esc_casa','$cre_num_not_casa','$cre_fec_con_casa','$cre_nota',$cre_codeudor";
//echo $query;
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ins_tab_amortizacion($num_cuotas,$fecha3,$pagar,$capital,$interes_pag,$cre_valor_1,$cre_fec_vencimiento)
	{
		$query = "EXECUTE CrearTablaAmortizacion $num_cuotas,'$fecha3',$pagar,$capital,$interes_pag,$cre_valor_1,'$cre_fec_vencimiento'";
		/*
		cre_id,tab_amo_num_cuota,tab_amo_fecha,tab_amo_cuota,
                                           tab_amo_cap_abonado,tab_amo_intereses,tab_amo_saldo,
										   est_con_tab_amo_id
		*/
		//echo $query;
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
   //FIN FUNCIONES PARA REGISTRAR CREDITO
   
   //INICIO FUNCIONES CONSULTAR CREDITO
   public function con_dat_credito_1($nit_id)
   {
	   	$query = "EXECUTE ConsCredito4 $nit_id";
                //echo $query;
		$ejecutar = mssql_query($query);
		return $ejecutar;
   }
   public function con_dat_credito($cre_id)
   {
	   $query = "EXECUTE ConsCredito1 $cre_id";
       //echo $query;
	   $ejecutar = mssql_query($query);
	   return $ejecutar;
   }
   public function con_dat_cod_credito($cre_id)
   {
	   $query = "EXECUTE ConsCredito2 $cre_id";
	   $ejecutar = mssql_query($query);
	   return $ejecutar;
   }
   public function con_dat_tab_amo_credito($cre_id)
   {
	   $query="EXECUTE ConsCredito3 $cre_id";
           //echo $query;
	   $ejecutar = mssql_query($query);
	   return $ejecutar;
   }
   //FIN FUNCIONES CONSULTAR CREDITO
   
   //INICIO FUNCIONES REGISTRAR PAGO DE CUOTA
   public function con_aso_emp_credito()
   {
		$query = "EXECUTE ConTodAsoYEmpCredito";
		$ejecutar = mssql_query($query);
		return $ejecutar;
   }
   
    public function con_aso_emp_cre_registrados($nit)
   {
		$query = "EXECUTE creditos_saldos";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		{
			$sql = "SELECT * FROM cre_pintar WHERE nit_cre = $nit";
			$query = mssql_query($sql);
			if($query)
			 {
				 $sql1= "TRUNCATE TABLE cre_pintar";
				 $query1 = mssql_query($sql1);
				 return $query;
			 }
		}
   }
   
   public function cre_salNits($nit,$cre_seleccionado)
   {
   		$sql="SELECT * FROM creditos c WHERE nit_id=$nit AND cre_valor>(
   		SELECT ISNULL(SUM(des_cre_capital),0) FROM des_credito WHERE des_cre_estado=3 AND des_cre_credito=c.cre_id)
   		AND (cre_fec_desembolso IS NOT NULL OR cre_id='$cre_seleccionado')";
		//echo $sql."<br>";
   		$query=mssql_query($sql);
   		if($query)
   			return $query;
   		else
   			return false;
   }

   public function con_cre_por_nit($nit_id)
   {
	   $query = "EXECUTE ConCrePorNit $nit_id";
	   $ejecutar = mssql_query($query);
	   return $ejecutar;
   }
   
   /*public function cre_nits($nit)
   {
	   $sql = "SELECT * FROM transacciones tra INNER JOIN creditos cre ON tra.trans_nit = cre.nit_id
			   INNER JOIN nits nit ON nit.nit_id = cre.nit_id
			   WHERE cre.cre_fec_desembolso is not null AND cre.nit_id = $nit AND trans_sigla LIKE('CRE_%')";	   
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;	 
   }*/
   
      public function con_cre_por_consecutivo($cre_id)
   {
	   $query = "EXECUTE ConCrePorConsecutivo $cre_id";
	   $ejecutar = mssql_query($query);
	   return $ejecutar;
   }
   
   public function ult_pago($credito)
   {
	   $sql = "SELECT dbo.numero_dias($credito) AS dias";
	   $query = mssql_query($sql);
	   if($query)
	   {
		  $dat_dias = mssql_fetch_array($query);
		  return $dat_dias['dias'];
	   }
	   else
	    return false;
   }
   
   public function dat_creditos($credito)
   {
	   $sql="SELECT * FROM creditos WHERE cre_id = $credito";
	   $query = mssql_query($sql);
	   if($query)
		  return $query;
	   else
	    return false; 
   }
   
   /*public function bus_cue_concepto($credito)
   {
	   $sql = "SELECT form.for_cue_afecta1,form.for_cue_afecta2,form.for_cue_afecta3,form.for_cue_afecta4,
	   form.for_cue_afecta5,form.for_cue_afecta6,form.for_cue_afecta7,form.for_cue_afecta8,form.for_cue_afecta9,
	   form.for_cue_afecta10,form.for_cue_afecta11,form.for_cue_afecta12,form.for_cue_afecta13,form.for_cue_afecta14,
	   form.for_cue_afecta15,form.for_cue_afecta16,form.for_cue_afecta17,form.for_cue_afecta18,form.for_cue_afecta19
	   FROM formulas form INNER JOIN conceptos con ON con.form_for_id = form.for_id
		                  INNER JOIN creditos cre ON cre.con_id = con.con_id
	   WHERE cre_id = $credito";
	   $query = mssql_query($sql);
	   if($query)
	   {
			$row = mssql_fetch_array($query);
			$i = 0;
        	while($i<=19)
        	{
			 	$arre = split(",",$row["for_cue_afecta".$i]);
			 	$cuenta = $arre[1];
			 	$naturaleza = $arre[2];
				if($c==1)
				{
					$saldo_cuenta = $this->sal_cuentas->saldo_cuenta($cuenta);
				}
				$i++;
	    	}
   		}
   }*/
   public function con_linea($tipo)
   {
	  $sqln= "SELECT con_id,con_nombre FROM conceptos WHERE con_tipo=$tipo";
	  $consultar =mssql_query($sqln);
	  if($consultar)
	    return $consultar;
	  else
	    return false;	
   }
   
   //INICIO CONSULTAR EL % DEL INTERÉS DE LA LINEA DE CREDITO
   public function nits_credito()
   {
	 $sql="SELECT cre.cre_id cre_id,cre.cue_id cue_id,cue.con_nombre cue_nombre,cre.nit_id nit, cre.cen_cos_id centro,
	       cre.cre_valor valor,cre.cre_fec_solicitud,
	       cre.cre_nota nota,nit.nit_id nit_id,nit.nits_nombres nombres, nit.nits_apellidos apellidos 
		   FROM creditos cre INNER JOIN nits nit on cre.nit_id = nit.nit_id 
		   INNER JOIN conceptos cue ON cue.con_id = cre.cue_id WHERE cre.cre_fec_desembolso IS NULL";
	 $query = mssql_query($sql);
	 if($query)
	   return $query;
	 else
	   return false;
   }
   //FIN CONSULTAR EL % DEL INTERÉS DE LA LINEA DE CREDITO
  
  public function act_credito($cre_seleccionado,$fecha)
  {
  	  if($fecha=='')
  	  	$fecha = date('d-m-Y');
	  $sql = "UPDATE creditos SET cre_fec_desembolso = '$fecha' WHERE cre_id = '$cre_seleccionado'";
	  //echo $sql;
	  $ejecutar = mssql_query($sql);
	  if($ejecutar)
	    return true;
	  else
	    return false;	
  }
  
  public function ultimo_pago($cre_seleccionado)
  {
	  $fecha = date('d-m-Y');
	  $sql = "UPDATE creditos SET cre_fec_ult_pago = '$fecha' WHERE cre_id = $cre_seleccionado";
	  if($cre_seleccionado=='3379')
	  	//echo $sql."<br>"; 
	  $ejecutar = mssql_query($sql);
	  if($ejecutar)
	    return true;
	  else
	    return false;	
  }
  
  public function cuenta_cre($cre_seleccionado)
  {
	  $sql = "SELECT cue_id FROM creditos WHERE cre_id = $cre_seleccionado";
	  $ejecutar = mssql_query($sql);
	  if($ejecutar)
	  {
	    $dat_cue = mssql_fetch_array($ejecutar);
		return $dat_cue['cue_id'];
	  }
	  else
	    return false;	
  }
  
  public function ins_reg_cre_por_telefonia($nit_id,$observacion,$valor,$num_cuotas,$fecha,$cue_id,$cen_cos)
  {
	  $query = "INSERT INTO dbo.creditos
	            (nit_id,cre_observacion,cre_valor,cre_num_cuotas,cre_fec_desembolso,cue_id,cen_cos_id)
                VALUES($nit_id,'$observacion',$valor,$num_cuotas,'$fecha',$cue_id,$cen_cos)";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  public function con_codeudor($nit_id)
  {
	  $query = "SELECT cre_codeudor FROM creditos WHERE nit_id =$nit_id";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  {
		$res_cod_cre = mssql_fetch_array($ejecutar);
	  	return $res_cod_cre['cre_codeudor'];
	  }
	  else
	  	return false;
  }
  
  public function con_cre_por_nit_estado($nit_id,$cre_estado)
  {
	  $query = "SELECT cre_id FROM creditos WHERE nit_id = $nit_id AND est_cre_id = $cre_estado";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  /******************MODIFICAR CODEUDOR CREDITO******************/
  public function mod_cod_credito($cre_codeudor,$cre_id)
  {
	  $query = "UPDATE creditos SET cre_codeudor = $cre_codeudor WHERE cre_id = $cre_id";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  public function buscar_cuenta($conce)
  {
	  $sql = "SELECT form.for_cue_afecta1 cue FROM formulas form INNER JOIN conceptos con ON con.form_for_id = form.for_id
	   		  WHERE con.con_id = $conce";		  
	  $query = mssql_query($sql);
	  if($query)
	  {
		  $dat_for = mssql_fetch_array($query);
		  $dat_cue = split(",",$dat_for['cue'],3);
		  return $dat_cue[1];
	  }
  }
  
  public function nominas($estado)
  {
	  $query = "SELECT DISTINCT nom_consecutivo FROM nomina WHERE nom_estado = $estado";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	    return false;
  }
  
  public function con_nit_por_nomina($id_nomina){
	  $query = "SELECT trans_nit FROM transacciones WHERE trans_fac_num = $id_nomina";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  
 /* public function contabilizar_creditos1(){
	  $query = "SELECT tab_amo_id FROM tabla_amortizacion";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  public function contabilizar_creditos2($tab_amo_id){
	  $query = "SELECT dbo.RestarFechas($tab_amo_id) resultado";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  */public function contaCreditos($user,$ano,$factura)
  {
	$query = "EXECUTE cont_credito $user,$ano,$factura";
	//echo $query; 
  	$consulta = mssql_query($query);
  	if($consulta)
      return true;	
  	else
      return false;
  }
  
  public function cueCreditos($credito,$nit,$centro,$documento) // Busca el saldo de las cuentas que tiene el credito tanto en interes como capital
  {
	 $cue_interes = "";
	 $cue_credito = "";
	 //$sql = "SELECT DISTINCT c.cue_id FROM creditos c INNER JOIN movimientos_contables mc ON c.cre_id=mc.mov_doc_numer INNER JOIN transacciones t ON mc.mov_compro=t.trans_sigla WHERE c.cre_id=$credito";
	 $sql="SELECT DISTINCT c.cue_id FROM creditos c WHERE c.cre_id=$credito AND c.cre_fec_desembolso IS NOT NULL";
	 //echo $sql;
	 $query = mssql_query($sql);
	 if($query)
	 {
		 $dat_sql = mssql_fetch_array($query);
		 $concepto = $dat_sql['cue_id'];
		 //echo "el valor de concepto es: ".$concepto."<br>";
		 $sql_cuentas="SELECT * FROM formulas form INNER JOIN conceptos con ON con.form_for_id=form.for_id WHERE con.con_id =".$concepto;
		 //echo "la consulta es: ".$sql_cuentas."<br>";
		 $query_sql = mssql_query($sql_cuentas);
		 $cuentas = mssql_fetch_array($query_sql);
		 for($i=1;$i<4;$i++)
		 {
			 $dat_cuentas = split(",",$cuentas['for_cue_afecta'.$i],4);
			 if($i==1)
			 	$cue_credito = $dat_cuentas[1];
			
			if($i==2)
				$cue_cont_interes=$dat_cuentas[1];
				
			 if(substr($dat_cuentas[1],0,6)=='134510')
			 {	
				$cue_interes = $dat_cuentas[1];
				break;
			 }
		 }
		//$deb_int = $this->sal_cuentas->saldos_cuenta($cue_interes,$nit,2);
		
		$deb_int = $this->sal_cuentas->saldos_cuenta_documento($cue_interes,$nit,2,$centro,$documento);
		$cre_int = $this->sal_cuentas->saldos_cuenta_documento($cue_interes,$nit,1,$centro,$documento);
		$deb_cap = $this->sal_cuentas->saldos_cuenta_documento($cue_credito,$nit,1,$centro,$documento);
		$cre_cap = $this->sal_cuentas->saldos_cuenta_documento($cue_credito,$nit,2,$centro,$documento);		
		
		$resInteres=$cre_int-$deb_int;
		$resCapital=$cre_cap-$deb_cap;
		return $cue_interes."--".$resInteres."--".$cue_credito."--".$resCapital."--".$concepto."--".$cue_cont_interes;
	 }
  }
  //GUARDAR CREDITO POR LINEA TELEFONICA
  public function ins_reg_cre_tel($nit_id,$cue_id,$cre_observacion,$cre_valor,$cre_num_cuotas,$tip_des_cre_id,$cre_fec_solicitud,$cre_nota)
	{
		$query = "EXECUTE RegistrarCreditoTelefonia $nit_id,$cue_id,'$cre_observacion',$cre_valor,$cre_num_cuotas,$tip_des_cre_id,'$cre_fec_solicitud','$cre_nota'";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
  public function creTelefonia($linea)
  {
	  $sql = "SELECT * FROM creditos INNER JOIN lineas_telefonia ON cre_valor = lin_tel_id WHERE cre_valor = $linea";
	  $query = mssql_query($sql);
	  if($query)
	  {
		 $dat_credito = mssql_fetch_array($query);
		 return $dat_credito['cre_id']."_".$dat_credito['cue_id'];
	  }
  }
  
  public function saldo_credito($cre_id)
  {
	  $query="SELECT (SELECT cre_valor FROM creditos WHERE cre_id=$cre_id)-ISNULL(SUM(des_cre_capital),0) AS saldo FROM des_credito WHERE des_cre_credito=$cre_id AND des_cre_estado NOT IN (1,2)";
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   {
	   	 $dat_credito=mssql_fetch_array($ejecutar);
	   	 return $dat_credito['saldo'];
	   }
	   else
	   	  return false;
  }
  
  
  public function con_tod_cre_activos($estado){
	  $query="SELECT * FROM creditos WHERE est_cre_id=$estado";
	  $ejecutar=mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  public function act_est_credito($estado,$cre_id){
	  $query="UPDATE creditos SET est_cre_id = $estado WHERE cre_id = $cre_id";
	  $ejecutar=mssql_query($query);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  
  public function recaudo($fac)
  {
  	  if($fac==0)
	  {
	      
  	  	$sql="SELECT DISTINCT mc.mov_mes_contable,con.con_nombre,n.nit_id,nits_num_documento,tb.cre_id,tb.tab_amo_cap_abonado as capDescuento, n.nits_nombres,n.nits_apellidos,c.cre_valor-(SELECT CAST(ISNULL(SUM(des_cre_capital),0)
AS INT) FROM des_credito WHERE des_cre_credito=tb.cre_id AND des_cre_estado=3) as saldo FROM tabla_amortizacion tb
INNER JOIN creditos c ON tb.cre_id=c.cre_id
INNER JOIN nits n on n.nit_id=c.nit_id
INNER JOIN movimientos_contables mc ON mc.mov_compro='CRE_'+CAST(c.cre_id AS VARCHAR(100))
INNER JOIN conceptos con ON c.cue_id=con.con_id
WHERE c.cre_valor>(SELECT ISNULL(SUM(des_cre_capital),0) FROM des_credito
WHERE des_cre_credito=c.cre_id AND des_cre_estado=3) AND tip_nit_id=2 AND nit_est_id=1 order by nits_apellidos";
	  }
	 else
	 {
	  $sql= "SELECT DISTINCT mc.mov_mes_contable,con.con_nombre,n.nit_id,nits_num_documento,tb.cre_id,tb.tab_amo_cap_abonado as capDescuento,
			n.nits_nombres,n.nits_apellidos,c.cre_valor-(SELECT CAST(ISNULL(SUM(des_cre_capital),0) AS INT) FROM des_credito WHERE des_cre_credito=tb.cre_id AND des_cre_estado=3) as saldo
			FROM tabla_amortizacion tb INNER JOIN creditos c ON tb.cre_id=c.cre_id inner join nits n on n.nit_id=c.nit_id
			INNER JOIN movimientos_contables mc ON mc.mov_compro='CRE_'+CAST(c.cre_id AS VARCHAR(100))
			INNER JOIN conceptos con ON c.cue_id=con.con_id
			WHERE c.cre_valor>(SELECT ISNULL(SUM(des_cre_capital),0) FROM des_credito WHERE des_cre_credito=c.cre_id AND des_cre_estado=3) 
			AND n.nit_id IN (SELECT nit_id FROM nits_por_cen_costo WHERE cen_cos_id=(SELECT fac_cen_cos FROM factura WHERE fac_id=$fac)) AND cre_fec_desembolso IS NOT NULL ORDER BY nits_nombres";
	 }
	//echo $sql;
	  $query = mssql_query($sql);
	  if($query)
	    return $query;
	  else
	    return false;
  }
  
   public function con_dat_cre_por_id($cre_id)
  {
	  $sql = "SELECT * FROM creditos WHERE cre_id=$cre_id";
	  $query = mssql_query($sql);
	  if($query)
	  {
		$res_dat_credito=mssql_fetch_array($query);
	  	return $res_dat_credito;
	  }
	  else
	  	return false;	 
  }
  
  public function tab_recaudo($cuota,$interes)
  {
	  $sql = "UPDATE dbo.tabla_amortizacion SET tab_recaudado=1,tab_interes_descontado=$interes WHERE tab_amo_id = $cuota";
	  $query = mssql_query($sql);
	  if($query)
	     return true;
	  else
	     return false;	 
  }

   public function dev_recaudo($cuota)
   {
	  $sql = "UPDATE tabla_amortizacion SET est_tab_amo_id=NULL,est_con_tab_amo_id=1,tab_amo_fec_contabilizado=NULL WHERE tab_amo_id = $cuota";
	  $query = mssql_query($sql);
	  if($query)
	     return true;
	  else
	     return false;	 
   }
  
  public function buscar_descuento($nit)
  {
	$sql = "SELECT des_cre_id,nits_nombres+' '+nits_apellidos as nombre, cre.cre_id, des_cre_interes, des_cre_capital, des_cre_total
			FROM dbo.des_credito tb INNER JOIN dbo.creditos cre ON cre.cre_id=tb.des_cre_credito 
			INNER JOIN dbo.nits n ON n.nit_id=cre.nit_id WHERE n.nit_id = $nit AND des_cre_estado=2";
	//echo $sql;
	$query = mssql_query($sql);
	if($query)
	  return $query;
	else
	  return false;  
  }
  
  public function act_cuota($cuota,$recibo,$val_interes,$val_capital)
  {
	  $sql = "UPDATE des_credito SET des_cre_estado=1,des_cre_recCaja=$recibo,des_cre_interes=$val_interes,des_cre_capital=$val_capital,des_cre_total=$val_interes+$val_capital WHERE des_cre_id = $cuota";
	  //echo $sql;
	  $query = mssql_query($sql);
	  if($query)
	    return true;
	  else
	    return false;
  }
  
  public function desCre_factura($credito,$cuota,$factu,$nit,$total,$interes,$capital)
  {
	$sql="INSERT INTO des_cuotaFactura(des_cuoFac_credito,des_cuoFac_cuota,des_cuoFac_factura,des_cuoFac_nit,des_cuoFac_totPagar,des_cuoFac_interes,des_cuoFac_capital) VALUES($credito,$cuota,$factu,$nit,$total,$interes,$capital)";
	$query=mssql_query($sql);
	if($query)
	   return true;
	else
	   return false;   
  }
  
  public function TotalDescuentoCreditos($factura,$nit,$estado)
  {
	$sql="SELECT ISNULL(SUM(des_cre_total),0) des_cre_total FROM des_credito WHERE des_cre_factura=$factura AND des_cre_nit=$nit AND des_cre_estado=$estado";
	$query=mssql_query($sql);
	if($query)
	{
		$res_datos=mssql_fetch_array($query);
		return $res_datos['des_cre_total'];
	}
	else
	   return false;   
  }
  
  public function bus_cuoDescontar($factura,$nit,$estado)
  {
	$sql="SELECT * FROM des_credito WHERE des_cre_factura=$factura AND des_cre_nit=$nit AND des_cre_estado=$estado";
	
	//if($nit==1281)
	//echo $sql,"<br>";
	$query=mssql_query($sql);
	if($query)
	   return $query;
	else
	   return false;   
  }

  public function des_cuoCredito($capital,$interes,$credito,$factura,$fecha,$nit,$estado,$sigla,$mes,$anio)
  {
  	//$fecha=date('d-m-Y');
  	$sql="INSERT INTO des_credito (des_cre_capital,des_cre_interes,des_cre_total,des_cre_credito,des_cre_factura,des_cre_fecha,des_cre_estado,des_cre_nit,des_cre_pagCompensacion,des_cre_mes_contable,des_cre_ano_contable)
  		  VALUES($capital,$interes,$capital+$interes,$credito,$factura,'$fecha',$estado,$nit,'$sigla','$mes','$anio')";
  	//echo $sql;
  	$query=mssql_query($sql);
	if($query)
	   return $query;
	else
	   return false;
  }

  public function act_cuoCredito($estado,$cuota,$compe,$recibo_caja,$mes_contable,$ano_contable)
  {
  	$sql = "UPDATE des_credito SET des_cre_estado=$estado,des_cre_pagCompensacion='$compe',des_cre_recCaja='$recibo_caja',des_cre_mes_contable='$mes_contable',des_cre_ano_contable='$ano_contable' WHERE des_cre_id = $cuota";
	//echo $sql."<br>";
	$query = mssql_query($sql);
	if($query)
	  return true;
	else
	  return false;
  }
  
  public function act_saldo_credito($des_cre_saldo,$des_cre_id)
  {
  	$sql = "UPDATE des_credito SET des_cre_saldo='$des_cre_saldo' WHERE des_cre_id = $des_cre_id";
	//echo $sql."<br>";
	$query = mssql_query($sql);
	if($query)
	  return true;
	else
	  return false;
  }
  
  public function con_saldo_credito_por_nomina($des_cre_pagCompensacion,$des_cre_factura,$des_cre_recibo,$des_cre_credito,$des_cre_nit)
  {
  	$sql = "SELECT * FROM des_credito WHERE des_cre_saldo IS NOT NULL AND des_cre_pagCompensacion='$des_cre_pagCompensacion' AND des_cre_factura='$des_cre_factura'
  	AND des_cre_recCaja='$des_cre_recibo' AND des_cre_credito='$des_cre_credito' AND des_cre_nit='$des_cre_nit'";
	//echo $sql."<br>";
	$query = mssql_query($sql);
	if($query)
	{
		$res_datos=mssql_fetch_array($query);
		return $res_datos;
	}
	else
	  return false;
  }
  
  
  
  
  public function ActEstDesNomNegativa($estado,$recibo_caja,$factura)
  {
  	$sql = "UPDATE des_credito SET des_cre_estado=$estado WHERE des_cre_recCaja='$recibo_caja' AND des_cre_factura='$factura'";
  	//echo $sql; 
	$query = mssql_query($sql);
	if($query)
	  return true;
	else
	  return false;
  }

  public function elimCreRecaudo($id)
  {
  	$sql="DELETE FROM des_credito WHERE des_cre_id=$id";
  	$query = mssql_query($sql);
	if($query)
	  return true;
	else
	  return false;
  }

  public function ultCredito()
  {
  	$sql="SELECT MAX(cre_id) ult_cre FROM creditos";
  	$query = mssql_query($sql);
	if($query)
	{
		$dat_credito = mssql_fetch_array($query);
		return $dat_credito['ult_cre'];
	}
	else
	  return false;	
  }

  public function estCuenta($nit)
  {
  	$sql="SELECT DISTINCT cre.cre_id,c.con_nombre,cre.cre_observacion,cre.cre_fec_desembolso,cre_fec_solicitud,cre.cre_num_cuotas,cre_valor,ISNULL((SELECT SUM(des_cre_capital)
FROM des_credito
WHERE des_cre_credito=cre.cre_id AND des_cre_estado=3),0) AS capital, cre_valor-ISNULL((
SELECT SUM(des_cre_capital) FROM des_credito WHERE des_cre_credito=cre.cre_id AND des_cre_estado=3),0) saldo FROM creditos cre INNER JOIN conceptos c ON c.con_id=cre.cue_id
INNER JOIN movimientos_contables mc ON 'CRE_'+CAST(cre.cre_id AS VARCHAR)=mc.mov_compro
where nit_id='$nit' AND cre.cre_fec_desembolso IS NOT NULL ORDER BY cre.cre_id ASC";
	//echo $sql;
  	$query = mssql_query($sql);
	if($query)
		return $query;
	else
	  return false;		
  }

  	public function dat_descuento($credito)
	{
  		$sql="SELECT * FROM des_credito WHERE des_cre_credito=$credito";
  		$query = mssql_query($sql);
		if($query)
			return $query;
		else
		  	return false;
  	}

  public function borrarCredito($credito)
  {
  	$sql="DELETE tabla_amortizacion WHERE cre_id=$credito";
  	$query=mssql_query($sql);
  	if($query)
  	{
  		$sql="DELETE creditos WHERE cre_id=$credito";
  		$query=mssql_query($sql);
  		if($query)
  			return true;
  		else
  			return false;	
  	}
  	else
  		return false;
  }

  public function provCreditos($doc,$num_doc,$mes,$ano)
  {
  	$pro_cre_afiliados='13992505';
  	$cue_provisiones='61057505';
  	
  	$sql="execute proCreditos '$doc',$num_doc,$mes,$ano,'$pro_cre_afiliados','$cue_provisiones'";
  	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
  		return true;
  	else
  		return false;
  }
  
  public function BalanceDetalladoCreditoPorNit($cedula_1,$cedula_2)
  {
  	$sql="SELECT con.con_nombre,n.nits_num_documento,n.nits_apellidos,n.nits_nombres,c.cre_id,c.cre_valor,SUM(dc.des_cre_capital) capital
FROM creditos c INNER JOIN des_credito dc ON c.cre_id=dc.des_cre_credito
INNER JOIN nits n ON n.nit_id=dc.des_cre_nit
INNER JOIN conceptos con ON c.cue_id=con.con_id
WHERE n.nits_num_documento BETWEEN '$cedula_1' AND '$cedula_2'
GROUP BY con.con_nombre,c.cre_id,n.nits_num_documento,n.nits_apellidos,n.nits_nombres,c.cre_valor
ORDER BY con_nombre,nits_apellidos ASC";
  	$query=mssql_query($sql);
  	if($query)
  		return $query;
  	else
  		return false;
  }
  
  public function ConsultarFacturasConRecaudo()
  {
  	$sql="SELECT DISTINCT f.fac_id,f.fac_consecutivo FROM factura f
			INNER JOIN des_credito dc ON f.fac_id=dc.des_cre_factura
			--INNER JOIN recibo_caja rc ON dc.des_cre_recCaja=rc.rec_caj_id
			ORDER BY f.fac_consecutivo";
  	$query=mssql_query($sql);
  	if($query)
  		return $query;
  	else
  		return false;
  }
  
 public function ConsultarRecaudoPorFactura($fac_id,$no_contiene)
  {
  	$sql="SELECT dc.*,n.nits_num_documento,n.nits_apellidos,n.nits_nombres FROM factura f
			INNER JOIN des_credito dc ON f.fac_id=dc.des_cre_factura
			INNER JOIN nits n ON dc.des_cre_nit=n.nit_id
			WHERE fac_id='$fac_id' AND dc.des_cre_pagCompensacion NOT LIKE('$no_contiene')
			ORDER BY n.nits_apellidos";
			//AND (des_cre_recCaja IS NOT NULL OR des_cre_pagCompensacion LIKE('PAG-COM%'))
	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
  		return $query;
  	else
  		return false;
  }
  
  public function ConsultarSaldoCreditoRecaudo($cre_id)
  {
  	$sql="SELECT c.cre_valor-ISNULL(SUM(dc.des_cre_capital),0) descontado
	FROM creditos c LEFT JOIN des_credito dc ON c.cre_id=dc.des_cre_credito
	WHERE c.cre_id='$cre_id'
	GROUP BY c.cre_valor";
	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
  	{
  		$res_datos=mssql_fetch_array($query);
  		return $res_datos['descontado'];
  	}
  	else
  		return false;
  }
  
  public function ConsultarRecaudoPorMesAnioEmpleados($mes,$anio,$tip_nit)
  {
  	if($mes<=9)
		$mes_recaudo="0$mes";
	else
		$mes_recaudo="$mes";
  	$sql="SELECT dc.*,n.nits_num_documento,n.nits_apellidos,n.nits_nombres,n.tip_nit_id
			FROM des_credito dc
			INNER JOIN nits n ON dc.des_cre_nit=n.nit_id
			WHERE des_cre_fecha LIKE('%-$mes_recaudo-$anio') AND n.tip_nit_id='$tip_nit' AND (des_cre_pagCompensacion LIKE('CAU-NOM_ADM_%') OR des_cre_pagCompensacion IS NULL
			OR des_cre_pagCompensacion='')
			ORDER BY n.nits_apellidos";
	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
  		return $query;
  	else
  		return false;
  }
  
  public function ConsultarCreditosPorNit($nits_num_documento)
  {
  	$sql="SELECT * FROM creditos WHERE nit_id=(SELECT nit_id FROM nits WHERE nits_num_documento='$nits_num_documento')
  	AND cre_fec_desembolso IS NOT NULL";
	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
  		return $query;
  	else
  		return false;
  }
  
  public function ConsultarEncabezadoExtractoDeCredito($nit_num_documento,$cre_id)
  {
  	$sql="SELECT con.con_nombre,n.nit_id,n.nits_num_documento,n.nits_apellidos,n.nits_nombres,c.cre_id,c.cre_valor,SUM(dc.des_cre_capital) capital,
	c.cre_num_cuotas,ROUND(c.cre_valor/c.cre_num_cuotas,0) valor_cuota,c.cre_dtf,c.cre_dtf/12 tasa_mensual,
	c.cre_fec_desembolso
	FROM creditos c INNER JOIN des_credito dc ON c.cre_id=dc.des_cre_credito
	INNER JOIN nits n ON n.nit_id=dc.des_cre_nit
	INNER JOIN conceptos con ON c.cue_id=con.con_id
	WHERE n.nits_num_documento BETWEEN '$nit_num_documento' AND '$nit_num_documento'
	AND c.cre_id='$cre_id'
	GROUP BY con.con_nombre,c.cre_id,n.nit_id,n.nits_num_documento,n.nits_apellidos,n.nits_nombres,c.cre_valor,c.cre_num_cuotas,
	c.cre_dtf,c.cre_fec_desembolso
	ORDER BY con_nombre,nits_apellidos ASC";
  	$query=mssql_query($sql);
  	if($query)
	{
		$res_dat_ext_credito=mssql_fetch_array($query);
		return $res_dat_ext_credito;
	}
  	else
  		return false;
  }
  
   public function ConsultarExtractoDeCredito($cre_id,$nit_id)
  {
  	$sql="SELECT DISTINCT dc.des_cre_capital,dc.des_cre_interes,dc.des_cre_total,dc.des_cre_factura,
  	CONVERT(DATETIME,dc.des_cre_fecha,103) des_cre_fecha,dc.des_cre_fecha AS fec_sin_formato,
	dc.des_cre_pagCompensacion
	FROM des_credito dc
	INNER JOIN creditos c ON dc.des_cre_credito=c.cre_id
	WHERE dc.des_cre_credito='$cre_id' AND des_cre_nit='$nit_id'
	ORDER BY CONVERT(DATETIME,dc.des_cre_fecha,103)";
	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
		return $query;
  	else
  		return false;
  }
  
  
  public function ConsultarEncabezadoTablaAmortizacionCredito($nit_num_documento,$cre_id)
  {
  	$sql="SELECT con.con_nombre,n.nit_id,n.nits_num_documento,n.nits_apellidos,n.nits_nombres,c.cre_id,c.cre_valor,
	c.cre_num_cuotas,ROUND(c.cre_valor/c.cre_num_cuotas,0) valor_cuota,
	c.cre_dtf,c.cre_dtf/12 tasa_mensual, c.cre_fec_desembolso
	FROM creditos c
	INNER JOIN nits n ON n.nit_id=c.nit_id
	INNER JOIN conceptos con ON c.cue_id=con.con_id
	WHERE n.nits_num_documento BETWEEN '$nit_num_documento' AND '$nit_num_documento' AND c.cre_id='$cre_id'
	GROUP BY con.con_nombre,c.cre_id,n.nit_id,n.nits_num_documento,n.nits_apellidos,n.nits_nombres,c.cre_valor,
	c.cre_num_cuotas, c.cre_dtf,c.cre_fec_desembolso
	ORDER BY con_nombre,nits_apellidos ASC";
	
  	$query=mssql_query($sql);
  	if($query)
	{
		$res_dat_ext_credito=mssql_fetch_array($query);
		return $res_dat_ext_credito;
	}
  	else
  		return false;
  }
  
  public function CantidadCuotasDescontadasCredito($cre_id,$est_descuento)
  {
  	$sql="SELECT ISNULL(COUNT(des_cre_id),0) cuo_descontadas
	FROM des_credito
	WHERE des_cre_credito='$cre_id' AND des_cre_estado='$est_descuento'";
	
  	$query=mssql_query($sql);
  	if($query)
	{
		$res_dat_ext_credito=mssql_fetch_array($query);
		return $res_dat_ext_credito['cuo_descontadas'];
	}
  	else
  		return false;
  }
  
  public function ConsultarCiudadYCentroCredito($cre_id)
  {
  	$sql="SELECT *
	FROM centros_costo cc
	INNER JOIN ciudades c ON cc.ciud_ciu_id=c.ciu_id
	WHERE cc.cen_cos_id=(SELECT cen_cos_id FROM creditos cre WHERE cre_id='$cre_id')";
  	$query=mssql_query($sql);
  	if($query)
	{
		$res_dat_ext_credito=mssql_fetch_array($query);
		return $res_dat_ext_credito;
	}
  	else
  		return false;
  }
  
  public function ConsultarCreditosConSaldo($cedula)
  {
  	$sql="SELECT DISTINCT mc.mov_mes_contable,con.con_nombre,n.nit_id,nits_num_documento,tb.cre_id,tb.tab_amo_cap_abonado as capDescuento,
	n.nits_nombres,n.nits_apellidos,c.cre_valor-(SELECT CAST(ISNULL(SUM(des_cre_capital),0) AS INT) FROM des_credito WHERE des_cre_credito=tb.cre_id AND des_cre_estado=3) as saldo
	FROM tabla_amortizacion tb INNER JOIN creditos c ON tb.cre_id=c.cre_id inner join nits n on n.nit_id=c.nit_id
	INNER JOIN movimientos_contables mc ON mc.mov_compro='CRE_'+CAST(c.cre_id AS VARCHAR(100))
	INNER JOIN conceptos con ON c.cue_id=con.con_id
	WHERE c.cre_valor>(
	SELECT ISNULL(SUM(des_cre_capital),0) FROM des_credito WHERE des_cre_credito=c.cre_id AND des_cre_estado=3) 
	AND n.nits_num_documento='$cedula'
	AND cre_fec_desembolso IS NOT NULL ORDER BY cre_id";
	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
		return $query;
  	else
  		return false;
  }
  
  
  public function GuardarUnSoloRegistroUnificacionCreditos($des_cre_credito,
  $des_cre_nit,$des_cre_factura,$des_cre_estado,$des_cre_fecha,$des_cre_pagCompensacion,$des_cre_mes_contable,$des_cre_ano_contable)
  {
  	$sql="SELECT * FROM des_credito WHERE des_cre_credito='$des_cre_credito'
	AND des_cre_nit='$des_cre_nit' AND des_cre_factura='$des_cre_factura' AND des_cre_estado='$des_cre_estado'
	AND des_cre_fecha='$des_cre_fecha' AND des_cre_pagCompensacion='$des_cre_pagCompensacion'
	AND des_cre_mes_contable='$des_cre_mes_contable' AND des_cre_ano_contable='$des_cre_ano_contable'";
	//echo $sql;
  	$query=mssql_query($sql);
  	if($query)
		return $query;
  	else
  		return false;
  }
  
  
  
  public function ActualizarInteresCredito($interes,$credito,$factura,$fecha,$nit,$estado,$sigla,$mes,$anio)
  {
  	//$fecha=date('d-m-Y');
  	$sql="UPDATE des_credito SET des_cre_interes='$interes' WHERE des_cre_credito='$credito' AND des_cre_factura='$factura'
  	AND des_cre_fecha='$fecha' AND des_cre_nit='$nit' AND des_cre_estado='$estado' AND des_cre_mes_contable='$mes'
  	AND des_cre_ano_contable='$anio'";
  	//echo $sql;
  	$query=mssql_query($sql);
	if($query)
	   return $query;
	else
	   return false;
  }
  
  public function ActualizarCuotaTotalCredito($credito,$factura,$fecha,$nit,$estado,$sigla,$mes,$anio)
  {
  	//$fecha=date('d-m-Y');
  	$sql="UPDATE des_credito SET des_cre_total=des_cre_capital+des_cre_interes WHERE des_cre_credito='$credito' AND des_cre_factura='$factura'
  	AND des_cre_fecha='$fecha' AND des_cre_nit='$nit' AND des_cre_estado='$estado' AND des_cre_mes_contable='$mes'
  	AND des_cre_ano_contable='$anio'";
  	//echo $sql;
  	$query=mssql_query($sql);
	if($query)
	   return $query;
	else
	   return false;
  }
  
  
  public function ConsultarCreditosConSaldoYContabilizados($cre_id,$cedula)
  {
  	$query="SELECT cre.cre_id,nit.nit_id,nit.nits_nombres,nit.nits_apellidos,cue.con_id,cue.con_nombre,cc.cen_cos_id,cc.cen_cos_nombre,
	cre.cre_observacion,cre.cre_valor,cre.cre_interes,cre.cre_dtf,cre.cre_num_cuotas,cre.cre_codeudor,cre.cre_ciu_sec_transito,cre.cre_num_pla_carro,
	tdc.tip_des_cre_id,tdc.tip_des_cre_nombre,cre.cre_fec_solicitud,cre.cre_fec_desembolso,cre.cre_fec_pri_pago,cre.cre_fec_vencimiento,flc.for_liq_cre_id,flc.for_liq_cre_nombre,
	cre.cre_garantia,tg.tip_gar_id,tg.tip_gar_nombres,ciu.ciu_id,ciu.ciu_nombre,cre.cre_num_pla_carro,cre.cre_num_esc_casa,cre.cre_num_not_casa,cre.cre_fec_con_casa,cre.cre_nota
	FROM dbo.creditos cre INNER JOIN dbo.nits nit ON nit.nit_id = cre.nit_id INNER JOIN dbo.conceptos cue ON cue.con_id = cre.cue_id INNER JOIN dbo.centros_costo cc
	ON cc.cen_cos_id = cre.cen_cos_id INNER JOIN dbo.tipo_descuento_credito tdc ON tdc.tip_des_cre_id = cre.tip_des_cre_id INNER JOIN dbo.forma_liquidacion_credito flc
	ON flc.for_liq_cre_id = cre.for_liq_cre_id LEFT JOIN dbo.tipos_garantia tg ON tg.tip_gar_id = cre.tip_gar_id LEFT JOIN ciudades ciu ON ciu.ciu_id = cre.cre_ciu_sec_transito
	WHERE cre.cre_id = $cre_id AND cre.cre_id IN(
	SELECT DISTINCT c.cre_id
	FROM tabla_amortizacion tb INNER JOIN creditos c ON tb.cre_id=c.cre_id inner join nits n on n.nit_id=c.nit_id
	INNER JOIN movimientos_contables mc ON mc.mov_compro='CRE_'+CAST(c.cre_id AS VARCHAR(100))
	INNER JOIN conceptos con ON c.cue_id=con.con_id
	WHERE c.cre_valor>(
	SELECT ISNULL(SUM(des_cre_capital),0) FROM des_credito WHERE des_cre_credito=c.cre_id AND des_cre_estado=3) 
	AND n.nits_num_documento='$cedula'
	AND cre_fec_desembolso IS NOT NULL)";
	//echo $query."<br>";
  	$ejecutar=mssql_query($query);
  	if($ejecutar)
  		return $ejecutar;
  	else
		return false;
  }
  
  public function ConsultarDatosCreditoPorId($credito)
  {
  	//$fecha=date('d-m-Y');
  	$sql="SELECT cre.cen_cos_id,cc.cen_cos_nombre
  	FROM creditos cre
	INNER JOIN centros_costo cc
	ON cc.cen_cos_id = cre.cen_cos_id
	WHERE cre.cre_id = '$credito'";
  	//echo $sql;
  	$query=mssql_query($sql);
	if($query)
	   return $query;
	else
	   return false;
  }
  
  public function ConsultarNucleoYCiudadEstadoCuenta($nit_id)
  {
  	//$fecha=date('d-m-Y');
  	$sql="SELECT TOP 1 c.*,cc.cen_cos_nombre,ciu.ciu_nombre
	FROM creditos c
	INNER JOIN centros_costo cc ON c.cen_cos_id=cc.cen_cos_id
	INNER JOIN ciudades ciu ON cc.ciud_ciu_id=ciu.ciu_id
	WHERE nit_id='$nit_id'
	ORDER BY cre_id DESC";
  	//echo $sql;
  	$query=mssql_query($sql);
	if($query)
	   return $query;
	else
	   return false;
  }
}
?>