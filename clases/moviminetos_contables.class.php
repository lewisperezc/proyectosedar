<?php
//session_start();
@include_once('../conexion/conexion.php');//MODIFICADO 7 DE FEBRERO
include_once('cuenta.class.php');
include_once('concepto.class.php');
include_once('centro_de_costos.class.php');
include_once('credito.class.php');
include_once('varios.class.php');
include_once('pabs.class.php');
include_once('compensacion_nomina.class.php');
@include_once('../inicializar_session.php');
@include_once('inicializar_session.php');

class movimientos_contables
{
	private $idformula;
	private $concepto;
	private $compensacion_nomina;
	private $varios;
	
	public function __construct()
	{
		$this->concepto = new concepto();
        $this->compensacion_nomina=new compensacion_nomina();
		$this->varios=new varios();
	}
	//consulta los dos id_delas tablas conceptos formulas pra poder hacer la consulta espefica de las formulas
	public function cons_formulas()
	{
	 $query ="SELECT con_id,form_for_id,con_nombre FROM dbo.conceptos cop INNER JOIN dbo.formulas fom on 
	         cop.form_for_id = fom.for_id";
	 $result = mssql_query ($query);
	 return  $result;
	}
		//consulta las formulas con respecto al un concepto especifico
	public function consul_formulas($idformula)
	{
	 $que ="SELECT con_id,for_cue_afecta1,for_cue_afecta2,for_cue_afecta3,for_cue_afecta4,for_cue_afecta5,for_cue_afecta6,
for_cue_afecta7,for_cue_afecta8,for_cue_afecta9,for_cue_afecta10,for_cue_afecta11,for_cue_afecta12,
for_cue_afecta13,for_cue_afecta14,for_cue_afecta15,for_cue_afecta16,for_cue_afecta17,for_cue_afecta18,
for_cue_afecta19,for_cue_afecta20 FROM dbo.conceptos co INNER JOIN dbo.formulas fo on co.form_for_id = fo.for_id
where con_id = $idformula";
	//echo $que."<br>";
	 $qua = mssql_query ($que);
	 if($qua)
	  return  $qua;
	 else
	  return false;
	}
	
    public function guarCam_movimiento($centro,$numero,$sigla,$nit,$fecha1,$num_doc,$fecha2,$cantidad,$total_mov,$concepto,$producto,$ica,$mes,$ano)
	 {
	  $cuenta = new cuenta();
	  $reteica = $this->concepto->tiene_ica($concepto);
	  $val_total=0;$cant_cuentas=0;
      $form = $this->consul_formulas($concepto);
	  
	  $bas_retencion=0;
	  
      $i=1;$matriz;
      if($form)
       {
        $row = mssql_fetch_array($form);	
        while($i<=21)
        {
		 $palabras=split(",",$sp);
	     $arre = split(",",$row["for_cue_afecta".$i]);
		 $a = $arre[0];
		 $b = $arre[1];
		 $c = $arre[2]; 
		 $d = $arre[3];
		 if($a != "" && $b != "" && $c != "")
		 	{
			  $matriz[$i][0]= $a;
			  $matriz[$i][1]= $b;
			  $matriz[$i][2]= $c;
			  $matriz[$i][3]= $d;
			}
		 $i++;	
		}
		if($reteica!=0)
	    {
		 $por_cuenta = $cuenta->busPorCuenta($ica);
		 $porcentaje = mssql_fetch_array($por_cuenta);
		 $rete = $total_mov * ($porcentaje['cue_porcentage']);
		 $cant_cuentas = sizeof($matriz)+1;
		 
		 $mov_con = $this->guaMovimiento($sigla,$num_doc,$ica,$concepto,$nit,$centro,$rete,2,$num_doc,3,$cant_cuentas,$producto,$cant_cuentas,$mes,$ano,$bas_retencion);
		}
		if($cant_cuentas==0)
		   $cant_cuentas = sizeof($matriz);
		for($i=1;$i<sizeof($matriz);$i++)
		 {
			if(trim($matriz[$i][3]) == "" && $i==1)
	          {
				$mov_con = $this->guaMovimiento($sigla,$num_doc,$matriz[trim($i)][1],$concepto,$nit,$centro,$total_mov,$matriz[trim($i)][2],$num_doc,3,$cant_cuentas,$producto,$cant_cuentas,$mes,$ano,$bas_retencion);
		        $matriz[trim($i)][4] = $total_mov;
	          }
			 else
	         {
	          $cuenta = new cuenta();
		      /*Fila de la matriz*/$cue_afecta=$matriz[$i][3];
			  if($matriz[$i][1] == " ")
			   {
				 $cen_cos = new centro_de_costos();
				 $matriz[$i][1] = $cen_cos->cue_cobrar_cc($centro);
			   }
		      /*Porcentaje de mi mismo*/$espor = $cuenta->busPorCuenta($matriz[$i][1]);
		      $porcen = mssql_fetch_array($espor);
		      if($porcen['cue_porcentage'] == 0)
		       {
		        $val_total = $total_mov;
    	        $mov_con = $this->guaMovimiento($sigla,$num_doc,$matriz[trim($i)][1],$concepto,$nit,$centro,
		        $val_total,$matriz[trim($i)][2],$num_doc,3,$cant_cuentas,$producto,$cant_cuentas,$mes,$ano,$bas_retencion);
		        $total_mov = $matriz[$i][4];
		       }
		      else
		       { 
		        $val_cue = $matriz[trim($cue_afecta)][4];
		        $total_mov = $val_cue * ($porcen['cue_porcentage']/100);
		        $mov_con = $this->guaMovimiento($sigla,$num_doc,$matriz[trim($i)][1],$concepto,$nit,$centro,
				$total_mov,$matriz[trim($i)][2],$num_doc,3,$cant_cuentas,$producto,sizeof($matriz),$mes,$ano,$bas_retencion);
		       } 
	         }//cierra el else	  
		   }//cierra el for
		   $ultima_cuenta = $matriz[sizeof($matriz)][1];
		   $ultima_naturaleza = $matriz[sizeof($matriz)][2];
		   $bal_concep = $this->balance($num_doc,$concepto);
		   $mov_con = $this->guaMovimiento($sigla,$num_doc,$ultima_cuenta,$concepto,$nit,$centro,$bal_concep[0],$ultima_naturaleza,$num_doc,3,$cant_cuentas,$producto,$cant_cuentas,$mes,$ano,$bas_retencion);
	   }//cierra el if*/
	  }//cierra la funcion 
	
	public function guaMovimiento($comp,$num_comp,$cuenta,$concepto,$nit,$cen_cos,$val,$nat,$num_doc,$user,$cant_cuentas,$producto,$cant,$mes,$ano,$bas_retencion)
	{
		$fecha = date('d-m-Y');
		$cuenta2 = trim($cuenta);
        $sql="EXECUTE insMovimiento '$comp','$num_comp','$cuenta2','$concepto','$nit','$cen_cos','$val','$nat','$num_doc','$user','$producto','$cant','$fecha','$mes','$ano','$bas_retencion'";
		//echo $sql;
		$ejecutar = mssql_query($sql);
		if($ejecutar)
	     {
			$cantidad_cuentas = 0;
			$query = "SELECT COUNT(*) cant FROM mov_contable";
		    $cant_mov = mssql_query($query);
		    $cantidad = mssql_fetch_array($cant_mov);
		    $can_cue = "SELECT can_cuenta FROM mov_contable GROUP BY can_cuenta";
		    $cant_cue = mssql_query($can_cue);
			while($row = mssql_fetch_array($cant_cue))
		         $cantidad_cuentas = $cantidad_cuentas+$row['can_cuenta'];
			if($cantidad['cant'] == $cantidad_cuentas)
		     {
				$mov = "EXECUTE movContable $cantidad_cuentas";
		        $ins_mov = mssql_query($mov);
		        if($ins_mov)
		          return true;
		        else
		         return false;
			}
		 }
	}
	
public function consul_nat()
     {
      $que ="SELECT * FROM dbo.tipo_cuenta";
	  $qua = mssql_query ($que);
	  if($qua)
	    return $qua;
	  else
	    return false;	
     }

    public function consul_cuenta($no)
     {
      $que ="select cue_id,cue_nombre from dbo.cuentas where cue_subdivision ='no'";
      $qua = mssql_query ($que);
      if($qua)
	    return $qua;
      else
	    return false;	
     }

    public function consultar_nat($c)
     {
	  $que ="select * from dbo.tipo_cuenta where tip_cue_id = $c";
	  $qua = mssql_query($que);
	  if($qua)
	    return  $qua;
	  else
	    return false;
     }
	 
	public function balance($compro,$concepto)
	{
		$sql = "SELECT * FROM mov_contable WHERE dos = '$compro' AND cinco = '$concepto'";
		$query = mssql_query($sql);
		$debito=0;$credito=0;$list;
		while($row = mssql_fetch_array($query))
		  {
			 if($row['nueve']==1)
			   $debito = $debito + $row['ocho'];
			 elseif($row['nueve']==2)
			   $credito = $credito + $row['ocho'];
		  }
		  
		 if($debito>$credito)
		   {
			  $list[0] = $debito-$credito;
			  $list[1] = 2;
		   }
		 else
		   {
			  $list[0] = $credito-$debito;
			  $list[1] = 1;
		   }
		   return $list;
	 }
	 
	public function comprobantes()
	{
	$sql= "SELECT DISTINCT mov_compro FROM movimientos_contables WHERE mov_compro LIKE('CAU_FAC%') OR mov_compro LIKE('TRA%')";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
	public function doc_prov($documento, $proveedor)
	{
		$sql = "SELECT * FROM transacciones WHERE trans_sigla LIKE('CAU_FAC%') OR trans_sigla LIKE('TRA%') 
		        AND trans_nit = $proveedor AND trans_sigla LIKE ('$documento')";	
		$query = mssql_query($sql);
		if($query)
		{
		  if(mssql_num_rows($query)>0)
		     return $query;
		  else
		     return false;
		}
		else
		  return false;
	}
	
	public function con_doc($documento)
	{
		$sql = "SELECT * FROM transacciones WHERE trans_sigla LIKE ('$documento') OR trans_sigla LIKE('CAU-FAC%') 
		        AND trans_sigla LIKE('TRA%')";	
		$query = mssql_query($sql);
		if($query)
		{
		  if(mssql_num_rows($query)>0)
		     return $query;
		  else
		     return false;	 
		}
		else
		  return false;
	}
	
	public function con_prov($proveedor)
	{
		$sql = "SELECT * FROM transacciones WHERE trans_sigla LIKE('CAU_FAC%') OR trans_sigla LIKE('TRA%') 
		        AND trans_nit = $proveedor";
		$query = mssql_query($sql);
		if($query)
		{
		  if(mssql_num_rows($query)>0)
		     return $query;
		  else
		     return false;	 
		}
		else
		  return false;
	}
	
	public function bus_sal_documento($num_tran)
	{
		$sql = "SELECT trans_sigla FROM transacciones WHERE tran_tran_id = $num_tran OR trans_id = $num_tran";
		$query = mssql_query($sql);
		if($query)
		   {
			   $i = 1;
			   while($dat_tran = mssql_fetch_array($query))
			   {
				 $sal_doc = $dat_tran['trans_sigla'];  
				 if($i==1)
				 {	 
				   $sql_sal = "SELECT sal_doc_valor FROM saldos_documentos WHERE sal_doc_id = '$sal_doc' 
				               AND sal_doc_tipo = 1";		   
				   $query_sal = mssql_query($sql_sal);
				   $dat_valor = mssql_fetch_array($query_sal);
				   $resta = $dat_valor['sal_doc_valor']; 
				   $i++;
				 }
				 else
				 {
				  $sql_sal = "SELECT sal_doc_valor FROM saldos_documentos WHERE sal_doc_id = '$sal_doc' 
				              AND sal_doc_tipo = 2";
				  $query_sal = mssql_query($sql_sal);
				  $dat_valor = mssql_fetch_array($query_sal);
				  $suma = $suma + $dat_valor['sal_doc_valor'];
				 }
			   }
			   return $resta-$suma;
		   }
		else
		  return false;
	}
	public function consultar_movimiento_contable($compro,$mes,$anio)
      {	
	    $sql= "SELECT * FROM dbo.movimientos_contables WHERE mov_compro = '$compro' AND mov_mes_contable=$mes AND mov_ano_contable=$anio ORDER BY id_mov";
		$ejecutar = mssql_query($sql);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
     }
	 
	 public function con_mov_con_por_sig_mes_anio($compro,$mes,$anio)
      {	
	    $sql="SELECT mc.*,cue_id,cue_nombre,nits_num_documento,nits_apellidos,nits_nombres,cen_cos_id,cen_cos_nombre FROM dbo.movimientos_contables mc
				LEFT JOIN cuentas ON mc.mov_cuent=cue_id
				LEFT JOIN nits ON mc.mov_nit_tercero=CAST(nit_id AS VARCHAR(100))
				INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
				WHERE mc.mov_compro = '$compro' AND mc.mov_mes_contable=$mes AND mc.mov_ano_contable=$anio ORDER BY mc.id_mov";
		$ejecutar=mssql_query($sql);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
     }
	 
	public function con_sal_cue_seg_soc_asociado($mes,$ano,$cuenta,$dias,$nit)
	 {
		/* $query="SELECT * FROM movimientos_contables WHERE mov_fec_elabo BETWEEN CAST('01/".$mes."/".$ano."' AS datetime)
		 		 AND CAST('".$dias."/".$mes."/".$ano."' AS datetime) AND mov_cuent = '$cuenta' AND 
				 (mov_compro like('Cau-nom-%') OR mov_compro like('Ajus-nom-%')) AND mov_nit_tercero like ('".$nit."_%') ORDER BY mov_cent_costo,mov_nit_tercero";*/
		  
		  $query = "SELECT mov_compro,mov_nume,mov_fec_elabo,mov_concepto,mov_cent_costo,((SUM(mov_valor)*40)/100) valor,mov_tipo,mov_documento,mov_doc_numer,mov_mes_contable,mov_ano_contable,mov_nit_tercero
                    FROM movimientos_contables
                    INNER JOIN factura ON mov_nume=fac_id
                    WHERE mov_mes_contable = $mes and mov_ano_contable = $ano
                    AND (mov_compro like('CAU-NOM-%')) AND mov_cuent IN($cuenta)
                    AND mov_nit_tercero like ('".$nit."%') AND fac_estado!=5
                    GROUP BY mov_compro,mov_nume,mov_fec_elabo,mov_concepto,mov_cent_costo,mov_tipo,mov_documento,mov_doc_numer,mov_mes_contable,mov_ano_contable,mov_nit_tercero";
		 //echo $query;
		 $ejecutar = mssql_query($query);
		 if($ejecutar)
		   return $ejecutar;
		 else
		   return false;
	 }
	 
	 
	 //INICIO FACTURAS BASE PARA SEGURIDAD SOCIAL//
    public function FacturasBaseSeguridadSocial($mes,$ano,$cuenta,$dias,$nit)
     {
          $query = "SELECT fac_id FROM movimientos_contables
                    INNER JOIN factura ON mov_nume=fac_id
                    WHERE mov_mes_contable = $mes and mov_ano_contable = $ano
                    AND (mov_compro like('CAU-NOM-%')) AND mov_cuent IN($cuenta)
                    AND mov_nit_tercero like ('".$nit."%') AND fac_estado!=5";
         //echo $query;
         $ejecutar = mssql_query($query);
         if($ejecutar)
           return $ejecutar;
         else
           return false;
     }
     //FIN FACTURAS BASE PARA SEGURIDAD SOCIAL//
	 
	 public function porCentro($mes,$ano,$cuenta,$dias,$nit,$centro)
	 {
		 $query="SELECT * FROM movimientos_contables WHERE mov_fec_elabo BETWEEN CAST('01/".$mes."/".$ano."' AS datetime)
		 		 AND CAST('".$dias."/".$mes."/".$ano."' AS datetime) AND mov_cuent = '$cuenta' AND 
				 (mov_compro like('Cau-nom-%') OR mov_compro like('Ajus-nom-%')) AND mov_nit_tercero like ('".$nit."_%') AND mov_cent_costo=$centro ORDER BY mov_cent_costo,mov_nit_tercero";	 
		 $ejecutar = mssql_query($query);
		 if($ejecutar)
		   return $ejecutar;
		 else
		   return false;
	 }
	 
	 public function rest_nits_seg_social($nits)
	 {
		 if(empty($nits))
		    $nits=0;
		 $query = "SELECT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos nombres FROM nits nit WHERE nit.tip_nit_id = 1 AND nit_est_id IN (1,2) AND nit.nit_id NOT IN($nits)";
		 $ejecutar = mssql_query($query);
		 if($ejecutar)
		    return $ejecutar;
		 else
		    return false;
	 }
	 
	 //INICIO DEVOLUCIÓN DE APORTES ASOCIADO
	 //Consulto Todos Los Movimientos Que Tiene Un Nit Para La Devolucion De Aportes O Liquidacion
	 public function con_cue_por_nit($nit_id,$cue_1,$cue_2,$cue_3)
	 {
		 $query = "SELECT DISTINCT mc.mov_cuent,c.cue_nombre
				   FROM movimientos_contables mc
				   INNER JOIN cuentas c ON mc.mov_cuent=c.cue_id
				   WHERE mov_nit_tercero LIKE('$nit_id%') AND mov_valor>0 AND mc.mov_cuent
				   LIKE('$cue_1%') OR mc.mov_cuent LIKE('$cue_2%') OR mc.mov_cuent LIKE('$cue_3%') ";
		  $ejecutar = mssql_query($query);
		  if($ejecutar)
		  	return $ejecutar;
		  else
		  	return false;
	 }
	 //TENEMOS QUE VERIFICAR QUE NOS TRAIGA EL NIT CORRECTO POR EL %
	 public function sum_mov_por_nit_cuenta($nit_id,$mov_tipo,$cuenta)
	 {
		 $query = "SELECT SUM(mc.mov_valor) suma_cuenta
				   FROM movimientos_contables mc
				   INNER JOIN cuentas c ON mc.mov_cuent=c.cue_id
				   WHERE mov_nit_tercero LIKE('$nit_id%') AND mov_tipo=$mov_tipo AND mov_cuent='$cuenta'";
		  $ejecutar = mssql_query($query);
		  if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
		  	return $resultado['suma_cuenta'];
		  }
		  else
		  	return false;
	 }

	 //Realizo La Operacíon Para Saber Cual Es El Saldo Total De Las Cuentas Para El NIT 
	 public function obt_sum_cue_nit($nit_id)
	 {
	  	$query = "SELECT Dbo.DevAportes($nit_id) as suma";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		{
			$res_sum = mssql_fetch_array($ejecutar);
			return $res_sum['suma'];
		}
		else
			return false;
	 }
	 //FIN DEVOLUCIÓN DE APORTES ASOCIADO
	 public function consulMovimiento($movimiento,$nit)
	 {
		 $sql = "SELECT * FROM movimientos_contables WHERE mov_compro = '$movimiento' AND mov_nit_tercero = $nit";
		 $query = mssql_query($sql);
		 if($query)
		    return $query;
		 else
		   return false;
	 }
	 
	 public function saldo_cuenta($cuenta,$nit/*,$mes_ini,$mes_fin,$ano_contable*/)
	 {
		 //,$mes_ini,$mes_fin,$ano_contable
		 $sql = "EXECUTE saldo_cuenta $cuenta,$nit";
		 //echo $sql;
		 $query = mssql_query($sql);
		 if($query)
		  {
			  $saldo = mssql_fetch_array($query);
			  return $saldo['resultado'];
		  }
	 }
	 
	 public function saldo_cuenta_nit($cuenta,$ano,$mes,$nit)
	 {
		 $sql = "SELECT [dbo].[SaldoCuentaPorMesNit] ('$cuenta','$ano','$mes','$nit') saldo_cuenta";
		 $query = mssql_query($sql);
		 if($query)
		  {
			  $saldo = mssql_fetch_array($query);
			  return $saldo['saldo_cuenta'];
		  }
	 }
	 
	 public function saldo_cuenDocumento($doc,$nit,$centro,$cuenta)
	 {
		 $sql = "SELECT * FROM movimientos_contables WHERE mov_compro = '$doc' AND mov_nit_tercero = $nit AND mov_cent_costo=$centro AND mov_cuent = $cuenta";
		 $query = mssql_query($sql);
		 if($query)
		 {
		    $ingreso = mssql_fetch_array($query);
			return $ingreso['mov_valor'];
		 }
		 else
		   return false;
	 }
	 
	 public function cal_ret_fuente($dinero)
	 {
	    //($dinero*8/100)SEGURIDAD SOCIAL
	    $exe_renta=$this->varios->ConsultarDatosVariablesPorId(11);
		$base_1 = ($dinero)*$exe_renta['var_valor']/100;//SE HACE SOBRE EL 75% DE LA BASE
		//echo "datos: ".$base_1."<br>";
		//$base_1 = $dinero;
		
		$uvt = $this->con_uvt(2);
		$veces_imas = $base_1/$uvt;
        $veces_iman=$dinero/$uvt;
        //echo "<br>".$veces_imas."<br>";
        //echo $veces_iman."<br>";
		//echo $uvt."<br>";
		/************************************IMAS******************************************/
		if($veces_imas>=0 && $veces_imas<95)
		{
			$imas=0;
		}
		elseif($veces_imas>95 && $veces_imas<=150)
		{
			$operacion=$veces_imas-95;//$resultado=numero de veces que está el uvt en el ingreso base menos 95 uvt
			$res_uvt=($operacion*19)/100;
			$imas=$res_uvt*$uvt;
		}
		elseif($veces_imas>150 && $veces_imas<=360)
		{
			$operacion=$veces_imas-150;
			//echo $operacion."<br>";
			$tot_uvt = $operacion*(28/100)+10;
			$imas = $tot_uvt*$uvt;
		}
		elseif($veces_imas>360)
		{
			$operacion=$veces_imas-360;
			$res_uvt=$operacion*(33/100)+69;
			$imas=$res_uvt*$uvt;
		}

		/**************************************IMAN***************************************/
		if($veces_iman<=177)
			$sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN $veces_iman-3 AND $veces_iman+3";
		elseif($veces_iman>177 && $veces_iman<=340)
			$sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN $veces_iman-7 AND $veces_iman+7";
		elseif($veces_iman>340)
			$sql = "SELECT ima_uvt,ima_ret_pesos FROM iman WHERE ima_uvt BETWEEN $veces_iman-17 AND $veces_iman+17";
		$query = mssql_query($sql);
        //echo $sql."<br>";
        
		$i=0;
		while($row=mssql_fetch_array($query))
		{
			$resul['uvt'][$i]=$row['ima_uvt'];
			$resul['pesos'][$i]=$row['ima_ret_pesos'];
			$menor = $veces_iman-$row['ima_uvt'];
			if($menor<0)
			  $resul['menor'][$i]=($menor*-1);
			else
			   $resul['menor'][$i]=$menor;
			$i++;
		}

		$mayor=0;
		$pos=0;
		$temp=0;
		for($i=0;$i<sizeof($resul);$i++)
		 {
		 	$temp = $resul['menor'][$i];
		 	if($mayor<$temp)
		 	{
		 		$mayor=$temp;
		 		$pos = $i;
		 	}
		 }
		 //echo $imas."___".$resul['pesos'][$pos]."<br>";
		return round(max($imas,$resul['pesos'][$pos]));
	 }
	 
	 public function con_uvt($var_id){
		 $query = "SELECT * FROM variables WHERE var_id = $var_id";
		 $ejecutar = mssql_query($query);
		 if($ejecutar){
			 $res_uvt = mssql_fetch_array($ejecutar);
			 return $res_uvt['var_valor'];
		 }
		 else
		 return false;
	 }
	 public function con_sal_cuentas($cuenta,$tipo){
	 	$query="SELECT SUM(mov_valor) total FROM movimientos_contables WHERE mov_cuent like('$cuenta') 
		AND mov_tipo = $tipo";
		$ejecutar = mssql_query($query);
		if($ejecutar){
			$res_valor = mssql_fetch_array($ejecutar);
			return $res_valor['total'];
		}
		else
		return false;
	 }
	 
	 public function con_sal_cueCentro($cuenta,$tipo)
	   {
	 		$query="SELECT SUM(mov_valor) total,mov_nit_tercero FROM movimientos_contables WHERE mov_tipo = $tipo AND mov_cuent = $cuenta GROUP BY mov_nit_tercero";
			$ejecutar = mssql_query($query);
			if($ejecutar)
			  return $ejecutar;
			else
			  return false;  
		}
	 
	 public function con_nom_cuenta($cuenta){
		 $query = "SELECT * FROM cuentas WHERE cue_id=$cuenta";
		 $ejecutar = mssql_query($query);
		 if($ejecutar)
		 return $ejecutar;
		 else
		 return false;
	 }
	 
	 public function cuen_movimi(){
		 $query="SELECT DISTINCT mov_cuent FROM movimientos_contables ORDER BY mov_cuent ASC";
		 $ejecutar=mssql_query($query);
		 if($ejecutar)
		 	return $ejecutar;
		 else
		 	return false;
	 }
	 
	  public function cuen_movimiMes($mes){
		 $query="SELECT DISTINCT mov_cuent FROM movimientos_contables WHERE mov_mes_contable = $mes ORDER BY mov_cuent ASC";
		 $ejecutar=mssql_query($query);
		 if($ejecutar)
		 	return $ejecutar;
		 else
		 	return false;
	 }
	 
	 public function con_sal_cue_credito($mes,$ano){
		 $query="SELECT * FROM movimientos_contables WHERE mov_fec_elabo LIKE('%-$mes-$ano')";
		 $ejecutar=mssql_query($query);
		 if($ejecutar)
		 return $ejecutar;
		 else
		 return false;
	 }
	 
	 public function con_dat_aso_fabs($nit_id,$desde,$hasta){
		 $query="SELECT p.pab_id,p.pab_nit_id,p.pab_fecha,p.cue_id,p.pab_beneficiario,p.pab_producto,p.pab_valor,p.pab_cantidad,prov.nits_nombres,prod.pro_nombre
				FROM pabs p
				INNER JOIN nits prov ON p.pab_beneficiario=prov.nit_id
				INNER JOIN productos prod ON p.pab_producto=prod.pro_id 
				WHERE pab_nit_id=$nit_id AND pab_fecha BETWEEN CAST('$desde' AS DATETIME) AND CAST('$hasta' AS DATETIME)";
		 $ejecutar=mssql_query($query);
		 if($ejecutar)
		 return $ejecutar;
		 else
		 return false;
	 }
	 
	 public function consulDocumentos($mes,$anio)
   	{
	   $sql = "SELECT DISTINCT mc.mov_compro,cc.cen_cos_nombre
			   FROM movimientos_contables mc
			   INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
			   WHERE mov_mes_contable = $mes AND mov_ano_contable=$anio
			   AND mov_compro not like('AJUS_NOM%') ORDER BY mov_compro";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;	 
   }
   
   
   	public function borrarDocumento($doc,$est_mes,$mes)
	{
		$usuario_actualizador=$_SESSION['k_nit_id'];
		$fecha_actualizacion=date('d-m-Y');
				
		$hora=localtime(time(),true);
		if($hora[tm_hour]==1)
			$hora_dia=23;
		else
			$hora_dia=$hora[tm_hour]-1;
		
		$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
		
		$tip_mov_aud_id=3;
		$aud_mov_con_descripcion='ELIMINACION DE DOCUMENTO DE CONTABILIDAD';							
							
		
	   	//REC-CAJ_0,Cau_seg_0,PAG-COM-0
		$anio_contable=$_SESSION['elaniocontable'];
	   	$fecha=date('d-m-Y');
	   	$dat_comprobante = split("_",$doc,3);
		/*echo $dat_comprobante[0]."<br>";
		echo $dat_comprobante[1]."<br>";
		echo $dat_comprobante[2]."<br>";*/
		//echo $dat_comprobante[0]."___".$dat_comprobante[1]."___".$dat_comprobante[2]."___".$dat_comprobante[3];
		//$doc=strtoupper($doc);
        //$dat_comprobante=strtoupper($dat_comprobante);
		
		if($dat_comprobante[0]=="REC-CAJ")
		{
			$sql="EXECUTE ModificarReciboCaja $dat_comprobante[1],$est_mes,'$fecha',$anio_contable,$mes";
			//echo $sql;
			$query = mssql_query($sql);
			
		
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
			
			
			if($query)
			{
				return true;
			}
			else
				return false;
		}
		elseif($dat_comprobante[0]=="CAU" && $dat_comprobante[0]=="SEG")
		{
			$sql = "EXECUTE segSocial $doc,$anio_contable,$mes";
			$query = mssql_query($sql);
			
			
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
			
			
			if($query)
				return true;
			else
				return false;  
		}
		///////////INICIO NOMINA ADMINISTRATIVA Y RECAUDO NOMINA ADMINISTRATIVA///////////
		elseif(($dat_comprobante[0]=="CAU-NOM"&&$dat_comprobante[1]=="ADM")||($dat_comprobante[0]=="PRO-NOM"&&$dat_comprobante[1]=="ADM"))
		{
			//echo "Entra por aqui.";
			//PARA SABER CUAL ES LA PROVISIÓN DE ESTA CAUSACIÓN Y ELIMINARLA TAMBIÉN//
			/*$sql="SELECT trans_observacion FROM transacciones WHERE trans_sigla='$doc'";
			$query=mssql_query($sql);
			if($query)
			{
				$res_sigla=mssql_fetch_array($query);*/
				$sql="DELETE FROM movimientos_contables WHERE mov_compro='$doc' AND mov_ano_contable=$anio_contable AND mov_mes_contable=$mes";
				$query=mssql_query($sql);
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				if($query)
				{
					$sql="DELETE FROM transacciones WHERE trans_sigla='$doc' AND tran_mes_contable=$mes AND trans_ano_contable=$anio_contable";
					$query=mssql_query($sql);
					
					//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
					$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
					aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
					aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
					aud_mov_con_descripcion='$aud_mov_con_descripcion'
					WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
					AND tip_mov_aud_id IS NULL";
					//echo $que_aud_mov_contable;
					$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
					
					if($query)
					{
						//ELIMINAR LA PROVISION//
						/*$sql="DELETE FROM movimientos_contables WHERE mov_compro='$res_sigla[trans_observacion]' AND mov_ano_contable=$anio_contable AND mov_mes_contable=$mes";
						//echo $sql."<br>";
						$query=mssql_query($sql);
						if($query)
						{*/
							/*$sql="DELETE FROM transacciones WHERE trans_sigla='$res_sigla[trans_observacion]' AND tran_mes_contable=$mes AND trans_ano_contable=$anio_contable";
							//echo $sql."<br>";
							$query=mssql_query($sql);
							if($query)
							{*/
							    //CAMBIAR ESTADO CREDITOS
								$sql="UPDATE des_credito SET des_cre_pagCompensacion=NULL,des_cre_estado=2,des_cre_mes_contable=NULL,des_cre_ano_contable=NULL
								WHERE des_cre_pagCompensacion='$doc' AND des_cre_mes_contable='$mes' AND des_cre_ano_contable='$anio_contable'";
								$query=mssql_query($sql);
								if($query)
                                {
                                    //CAMBIAR ESTADO OTROS DESCUENTOS
                                    $des_nom_administrativa=$this->compensacion_nomina->EliminarOtroDescuentoDescontabilizar($doc,0,2);
                                    if($des_nom_administrativa)
                                        return $des_nom_administrativa;
                                    else
                                        return false;
                                }
                                else
                                    return false;
							/*}
							else
								return false;*/
						/*}
						else
							return false;*/
					}
					
					else
						return false;
				}
				else
					return false;
			/*}
			else
				return false;*/
		}
		///////////FIN NOMINA ADMINISTRATIVA Y RECAUDO NOMINA ADMINISTRATIVA///////////
		
		elseif($dat_comprobante[0]=="PAG-NOM"&&$dat_comprobante[1]=="ADM")
		{
			//echo "Entra por aqui.";
			//PARA SABER CUAL ES LA PROVISIÓN DE ESTA CAUSACIÓN Y ELIMINARLA TAMBIÉN//
			/*$sql="SELECT trans_observacion FROM transacciones WHERE trans_sigla='$doc'";
			$query=mssql_query($sql);
			if($query)
			{*/
				//$res_sigla=mssql_fetch_array($query);
				$sql="DELETE FROM movimientos_contables WHERE mov_compro='$doc' AND mov_ano_contable=$anio_contable AND mov_mes_contable=$mes";
				$query=mssql_query($sql);
				
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				if($query)
				{
					$sql="DELETE FROM transacciones WHERE trans_sigla='$doc' AND tran_mes_contable=$mes AND trans_ano_contable=$anio_contable";
					$query=mssql_query($sql);
					
					//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
					$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
					aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
					aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
					aud_mov_con_descripcion='$aud_mov_con_descripcion'
					WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
					AND tip_mov_aud_id IS NULL";
					//echo $que_aud_mov_contable;
					$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
					
					/*if($query)
					{
						//CAMBIAR ESTADO DE LA CAUSACIÓN DE PAGADA A CAUSADA//
						$query="UPDATE transacciones SET estado_nomina_admin=1 WHERE trans_sigla='$res_sigla[trans_observacion]' AND trans_ano_contable=$anio_contable AND tran_mes_contable=$mes";
						$ejecutar=mssql_query($query);
					}
					else
						return false;*/
				}
				else
					return false;
			/*}
			else
				return false;*/
		}
		elseif($dat_comprobante[0]=="CRE") 
		{
				//echo "entra por aqui";
				$query="EXECUTE EliTransaccion '$doc',$anio_contable,$mes";
				$ejecutar=mssql_query($query);
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				
				if($ejecutar)
				{
					$sql = "UPDATE creditos SET cre_fec_desembolso = NULL WHERE cre_id = $dat_comprobante[1]";
					$ejecutar = mssql_query($sql);
					if($ejecutar)
					   return true;
					else
					   return false;
				}
				else
					return false;
		}
		elseif($dat_comprobante[0]=="NOT-DEB" || $dat_comprobante[0]=="NOT-CRE")
		{
			//echo "entra por aqui...<br>";
			$sql_1="EliminarNotasFacturas '$doc','$mes','$anio_contable'";
			//echo $sql_1."<br>";
			$query_1=mssql_query($sql_1);
			
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
			
			if($query_1)
				return $query_1;
			else
				return false;
		}
		else
		{
			
			$dat_comprobante = split("-",$doc,3);
			$dat_comprobante_2 = split("_",$dat_comprobante[1],2);
			
			//echo "DATOS: ".$dat_comprobante[0]."___".$dat_comprobante_2[0];
			
			if($dat_comprobante[0]=="FAC")
			{
				//echo "entra por aqui";
				$sql = "EXECUTE des_factura '$dat_comprobante[1]','$est_mes','$fecha','$anio_contable','$mes'";
				//echo $sql;
				$query = mssql_query($sql);
				
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				
				if($query)
					return true;
				else
					return false;
			}
			
			
			elseif($dat_comprobante[0]=="PAG"&&$dat_comprobante[1]=="COM")//PAGO NOMINA AFILIADOS
			{
				$sql = "EXECUTE pagCompensacion $dat_comprobante[2],$anio_contable,$mes";
				//echo "Entra por aca".$sql;
				$query = mssql_query($sql);
				
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				if($query)
				  return true;
				else
				  return false;
			}
			
			elseif($dat_comprobante[0]=="CAU" && $dat_comprobante[1]=="NOM")//CAUSACION NOMINA AFILIADOS
			{
				echo "entra por este";
				$sql="DELETE FROM movimientos_contables WHERE mov_compro='$doc' AND mov_ano_contable=$anio_contable AND mov_mes_contable=$mes";
				$query=mssql_query($sql);
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				
				if($query)
				{
					$sql="DELETE FROM transacciones WHERE trans_sigla='$doc' AND tran_mes_contable=$mes AND trans_ano_contable=$anio_contable";
					$query=mssql_query($sql);
					
					
					//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
					$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
					aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
					aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
					aud_mov_con_descripcion='$aud_mov_con_descripcion'
					WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
					AND tip_mov_aud_id IS NULL";
					//echo $que_aud_mov_contable;
					$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
					
					if($query)
						return true;
					else
						return false;
				}
				else
					return false;
			}			
			elseif($dat_comprobante[0]=="FICH")
			{
				$query="EXECUTE EliTransaccion '$doc',$anio_contable,$mes";
				$ejecutar=mssql_query($query);
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				if($ejecutar)
					return $ejecutar;
				else
					return false;
			}
			
			elseif(($dat_comprobante[0]=="DEV" && $dat_comprobante_2[0]=="FABS") || ($dat_comprobante[0]=="CAU" && $dat_comprobante_2[0]=="FABS"))//DEVOLUCION DE FABS Y CAUSACION DE FABS
			{
				//echo "DATOS: ".$dat_comprobante[0]."___".$dat_comprobante_2[0];
				//echo "entra por la devolucion y causacion";
				$sql="DELETE FROM movimientos_contables WHERE mov_compro='$doc' AND mov_ano_contable=$anio_contable AND mov_mes_contable=$mes";
				$query=mssql_query($sql);
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
				
				if($query)
				{
					$sql="DELETE FROM transacciones WHERE trans_sigla='$doc' AND tran_mes_contable=$mes AND trans_ano_contable=$anio_contable";
					$query=mssql_query($sql);
					if($query)
					{
						$que_eli_dev_fabs_modulo="DELETE reg_com_pabs WHERE reg_com_sigla='$doc' AND reg_com_mes='$mes' AND reg_com_ano='$anio_contable'";
						$eje_eli_dev_fabs_modulo=mssql_query($que_eli_dev_fabs_modulo);
						if($eje_eli_dev_fabs_modulo)
							return true;
						else
							return false;
					}
					else
						return false;
				}
				else
					return false;
			}
			
			else
			{
				$dat_comprobante=split(" ",$doc,3);
				if($dat_comprobante[0]=="TRA")
				{
					$query="EXECUTE EliTransaccion '$doc',$anio_contable,$mes";
					$ejecutar=mssql_query($query);
					
					
					//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
					$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
					aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
					aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
					aud_mov_con_descripcion='$aud_mov_con_descripcion'
					WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
					AND tip_mov_aud_id IS NULL";
					//echo $que_aud_mov_contable;
					$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
					
					if($ejecutar)
						return $ejecutar;
					else
						return false;
				 }
				 else
				 {
						$dat_comprobante = split("-",$doc,3);
						
						if($dat_comprobante[0]=="PAG"&&$dat_comprobante[1]=="NOM")
						{
							$query="EXECUTE EliTransaccion '$doc',$anio_contable,$mes";
							//$act_est_nomina=$this->transacciones->act_est_nom_adm_pagada(1,;
							$ejecutar=mssql_query($query);
							
							//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
							$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
							aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
							aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
							aud_mov_con_descripcion='$aud_mov_con_descripcion'
							WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
							AND tip_mov_aud_id IS NULL";
							//echo $que_aud_mov_contable;
							$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
							
							
							if($ejecutar)
								return $ejecutar;
							else
								return false;
						}
						else//NOTAS PRESTAMOS(ELIMINAR DE DES_CREDITO PARA QUE AFECTE EL ESTADO DE CUENTA)
						{
							
							if($dat_comprobante[0]=="NOT")
							{
								$pos=strpos($dat_comprobante[1],'PRE');
								
								if($pos!==false)//LA CADENA FUE ENCONTRADA(ES NOTA PESTAMO NOT-PRE)
								{
									$query="EXECUTE EliTransaccion '$doc',$anio_contable,$mes";
									$ejecutar=mssql_query($query);
									
									//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
									$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
									aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
									aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
									aud_mov_con_descripcion='$aud_mov_con_descripcion'
									WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
									AND tip_mov_aud_id IS NULL";
									//echo $que_aud_mov_contable;
									$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
					
									if($ejecutar)
									{
										//ELIMINAR DE DES_CREDITO
										$que_eli_des_credito="DELETE FROM des_credito WHERE des_cre_pagCompensacion='$doc' AND des_cre_mes_contable='$mes' AND des_cre_ano_contable='$anio_contable'";
										$eje_eli_des_credito=mssql_query($que_eli_des_credito);
										if($eje_eli_des_credito)
											return $eje_eli_des_credito;
										else
											return false;
									}
									else
										return false;
								}
								else
								{
									$query="EXECUTE EliTransaccion '$doc',$anio_contable,$mes";
									//echo "Entra por aca: ".$query;
									$ejecutar=mssql_query($query);
									
									//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
									$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
									aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
									aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
									aud_mov_con_descripcion='$aud_mov_con_descripcion'
									WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
									AND tip_mov_aud_id IS NULL";
									//echo $que_aud_mov_contable;
									$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
									
									
									if($ejecutar)
										return $ejecutar;
									else
										return false;	
								}
								
							}
							else
							{
								
								$query="EXECUTE EliTransaccion '$doc',$anio_contable,$mes";
								//echo "Entra por aca: ".$query;
								$ejecutar=mssql_query($query);
								
								//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
								$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
								aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
								aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
								aud_mov_con_descripcion='$aud_mov_con_descripcion'
								WHERE mov_compro='$doc' AND mov_mes_contable='$mes' AND mov_ano_contable='$anio_contable'
								AND tip_mov_aud_id IS NULL";
								//echo $que_aud_mov_contable;
								$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
								
								if($ejecutar)
									return $ejecutar;
								else
									return false;	
							}
						}
					}
				}
		   }
	}
   
	public function modificarRecibo($doc,$est_mes,$mes)
	{
		$anio_contable=$_SESSION['elaniocontable'];
	   	$fecha=date('d-m-Y');
	   	$dat_comprobante = split("_",$doc,3);
		if($dat_comprobante[0]=="REC-CAJ")
		{
			$sql="EXECUTE ModificarRecibo $dat_comprobante[1],$est_mes,'$fecha',$anio_contable,$mes";
			//echo $sql;
			$query = mssql_query($sql);
			if($query)
			{
				return true;
			}
			else
				return false;
		} 
	}

   
   public function con_pag_seg_social($comprobante,$fecha)
   {
	   $query="SELECT DISTINCT mc.mov_compro,mc.mov_fec_elabo,mc.mov_nit_tercero,mc.mov_cent_costo,mc.mov_valor,
nits_apellidos+' '+nits_nombres nombres,n.nits_num_documento,cc.cen_cos_nombre
				FROM movimientos_contables mc
				INNER JOIN nits n ON mc.mov_nit_tercero=CAST(n.nit_id as VARCHAR(20))
				INNER JOIN centros_costo cc ON mc.mov_cent_costo=cc.cen_cos_id
				WHERE mov_compro LIKE('$comprobante%') AND mov_fec_elabo LIKE('$fecha')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
   }
   
   //INICIO CONSULTAR VALOR INICIAL VALANCE
   public function con_sal_inicial($fecha){
	   $query="SELECT *
			   FROM movimientos_contables
			   WHERE CAST(mov_fec_elabo AS DATETIME) < '$fecha'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
   }
   //FIN CONSULTAR VALOR INICIAL VALANCE
   
   public function con_mov_por_nit_empleado($nit){
   $query="SELECT SUM(mov_valor) suma
		   FROM movimientos_contables
		   WHERE mov_nit_tercero='$nit'";
	$ejecutar=mssql_query($query);
	if($ejecutar){
		$resultado=mssql_fetch_array($ejecutar);
		return $resultado['suma'];
	}
	else
		return false;
   }
   
   
   
   
   //INICIO CONSULTAR PAGADO O GASTADO EN PABS POR ANESTESIOLOGO
   public function con_mov_por_nit($nit,$cuenta,$tipo,$fecha){
   $query="SELECT SUM(mov_valor) suma
		   FROM movimientos_contables
		   WHERE mov_nit_tercero='$nit' AND mov_cuent='$cuenta' AND mov_tipo=$tipo AND mov_fec_elabo LIKE('%$fecha')";
	$ejecutar=mssql_query($query);
	if($ejecutar){
		$resultado=mssql_fetch_array($ejecutar);
		return $resultado['suma'];
	}
	else
		return false;
   }
   //FIN CONSULTAR PAGADO O GASTADO EN PABS POR ANESTESIOLOGO
   public function con_sal_fabs_asociado($nit,$cuenta,$tipo){
	   $nit=$nit."_1";
	   $query="SELECT SUM(mov_valor) suma_valores
			   FROM movimientos_contables
			   WHERE mov_nit_tercero='$nit' AND mov_cuent='$cuenta' AND mov_tipo=$tipo";
		//echo $query;	   
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['suma_valores'];
		}
		else
			return false;
   }
   
   public function balance_tercero($mes)
   {
	   $sql = "SELECT CAST(cue_id AS varchar)cuenta,cue_nombre,mov_nit_tercero,SUM(mov_valor) suma FROM cuentas
	   		   LEFT JOIN movimientos_contables ON cue_id=mov_cuent WHERE mov_mes_contable = $mes
			   GROUP BY cue_id,cue_nombre,mov_nit_tercero ORDER BY cuenta asc";
	   $query = mssql_query($sql);
	   if($query)
	      return $query;
	   else
	      return false;
   }
   
   public function gua_des_compensacion($asociado,$valor,$factura,$cuenta,$fecha,$des_nom_rec_caja,$estado)
   {
		$query="INSERT INTO descuentos_compensacion(des_nom_nit,des_nom_valor,des_nom_factura,des_nom_cuenta,des_nom_fecha,des_nom_rec_caja,des_nom_estado)
				VALUES($asociado,$valor,$factura,'$cuenta','$fecha',$des_nom_rec_caja,$estado)";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;

   }
   
   
   public function GuardarDescuentoLegalizacionAdicional($rec_caja,$des_monto,$des_tipo,$des_tip_adicion,$des_descripcion)
   {
   		$des_fecha=date('d-m-Y');
	
		$query="INSERT INTO descuentos(des_factura,des_monto,des_tipo,des_tip_adicion,des_descripcion,des_fecha)
				VALUES('$rec_caja','$des_monto','$des_tipo','$des_tip_adicion','$des_descripcion','$des_fecha')";
		//echo "los datos son: ".$query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;

   }
   
   public function EliminarDescuentoLegalizacionAdicional($des_id)
   {
		$query="DELETE FROM descuentos WHERE des_id='$des_id'";
		//echo "los datos son: ".$query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;

   }
   

   public function GuardarOtroDescuentoCompensacion($recibo_caja,$valor,$tipo,$des_distribucion)
   {
		$query="INSERT INTO descuentos(des_factura,des_monto,des_tipo,des_distribucion) VALUES('$recibo_caja','$valor','$tipo','$des_distribucion')";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;

   }

   public function GuardarOtroDescuentoCompensacion2($recibo_caja,$valor,$tipo)
   {
		$query="INSERT INTO descuentos(des_factura,des_monto,des_tipo) VALUES('$recibo_caja','$valor','$tipo')";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;

   }
   
   public function TotalDescuentosCompensacion($asociado,$factura,$recibo)
   {
	   $sql="SELECT ISNULL(SUM(des_nom_valor),0) des_nom_valor FROM descuentos_compensacion WHERE des_nom_nit=$asociado AND des_nom_factura=$factura AND des_nom_rec_caja=$recibo";
	   $query = mssql_query($sql);
	   if($query)
	   {
	   	$res_datos=mssql_fetch_array($query);
		return $res_datos['des_nom_valor'];
	   }
	   else
	     return false;
   }
   
   
    public function bus_des_compensacion($asociado,$factura,$recibo)
   {
	   $sql="SELECT * FROM descuentos_compensacion WHERE des_nom_nit=$asociado AND des_nom_factura=$factura AND des_nom_rec_caja=$recibo";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
   
   public function borr_Movimiento($con,$mes,$ano)
	{
		$sql = "DELETE FROM movimientos_contables WHERE mov_compro='$con' AND mov_mes_contable=$mes AND mov_ano_contable=$ano";
		$query = mssql_query($sql);
		if($query)
		  return false;
		else
		  return true;  
	}
   
   public function ConFecNomAdmCausada($elmes,$laquincena)
   {
	   $query="SELECT * FROM movimientos_contables WHERE mov_fec_elabo LIKE('%-$elmes-%') AND mov_concepto=$laquincena";
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   {
		   $resultado=mssql_num_rows($ejecutar);
	   	   return $resultado;
	   }
	   else
	   	return false;
   }
   
	///////////////////////////INICIO AJUSTE DE NOMINA AFILIADOS///////////////////////////
	
	public function ajuste_causacion($factura,$mes,$ano,$recibo_id,$sig_pago,$nit,$fec_elabo,$centro)
   	{
   		$base_retencion=0;
   		
   		
   		$sig_causacion='CAU-NOM-'.$factura;
		
   		$sigla_ajuste='AJUS-NOM-'.$factura;
   		
		$query_1="SELECT * FROM distribucion_porcentajes_conceptos_fabs ORDER BY dis_por_con_fab_id ASC";
        $ejecutar_1=mssql_query($query_1);
		
		$nit_tercero=$nit.'_1';
		$nit_conta=$nit;
		$nit_tercero_sedar=$nit.'_380';
		
   		/////////INICIO FABS/////////
   		
   		$contador_1=1;
   		while($res_dat_lin_fabs=mssql_fetch_array($ejecutar_1))
		{
			$cue_uno=$res_dat_lin_fabs['dis_por_con_fab_cue_uno'];
			
			$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_tercero,2,$contador_1);
			$res_datos_causada=mssql_fetch_array($con_causacion);
			
			if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
				$res_datos_causada['mov_valor']=0;
			
			$cue_dos=$res_dat_lin_fabs['dis_por_con_fab_cue_uno']."1";
			
   			$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_tercero,1,$contador_1);
			$res_datos_pagada=mssql_fetch_array($con_pago);
			
			if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
				$res_datos_pagada['mov_valor']=0;
			
			if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
			{
				//echo "entra por aqui!";
				if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
				{
					$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
					$nue_naturaleza=1;
					$nue_cuenta=$res_datos_causada['mov_cuent']."1";
					
					if($nue_cuenta!=0 || $nue_cuenta!='')
					{
						$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
						'$fec_elabo','$nue_cuenta','$contador_1','$nit_tercero','$centro','$valor_ajuste',
						'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
						//echo $que_ajuste."<br>";
						$eje_ajuste=mssql_query($que_ajuste);
					}
				}
				else
				{
					$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
					$nue_naturaleza=2;
					$nue_cuenta=$res_datos_causada['mov_cuent'];
					
					if($nue_cuenta!=0 || $nue_cuenta!='')
					{
						$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
						'$fec_elabo','$nue_cuenta','$contador_1','$nit_tercero','$centro','$valor_ajuste',
						'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
						//echo $que_ajuste."<br>";
						$eje_ajuste=mssql_query($que_ajuste);
					}
				}
			}
			
			$contador_1++;
		}
		
		
		$query_2="SELECT * FROM distribucion_porcentajes_conceptos_fabs ORDER BY dis_por_con_fab_id ASC";
        $ejecutar_2=mssql_query($query_2);
		
		$contador_2=1;
		while($res_dat_lin_fabs=mssql_fetch_array($ejecutar_2))
		{
			$cue_uno=$res_dat_lin_fabs['dis_por_con_fab_cue_dos'];
			$cue_dos=$res_dat_lin_fabs['dis_por_con_fab_cue_dos']."1";
			
			$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_tercero,1,$contador_2);
			$res_datos_causada=mssql_fetch_array($con_causacion);
			
			if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
				$res_datos_causada['mov_valor']=0;
			
			
   			$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_tercero,2,$contador_2);
			$res_datos_pagada=mssql_fetch_array($con_pago);
			
			if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
				$res_datos_pagada['mov_valor']=0;
			
			
			if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
			{
				if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
				{
					$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
					$nue_naturaleza=2;
					$nue_cuenta=$res_datos_causada['mov_cuent']."1";
					
					if($nue_cuenta!=0 || $nue_cuenta!='')
					{
						$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
						'$fec_elabo','$nue_cuenta','$contador_2','$nit_tercero','$centro','$valor_ajuste',
						'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
						$eje_ajuste=mssql_query($que_ajuste);
					}
				}
				else
				{
					$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
					$nue_naturaleza=1;
					$nue_cuenta=$res_datos_causada['mov_cuent'];
					
					if($nue_cuenta!=0 || $nue_cuenta!='')
					{
						$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
						'$fec_elabo','$nue_cuenta','$contador_2','$nit_tercero','$centro','$valor_ajuste',
						'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
					
						$eje_ajuste=mssql_query($que_ajuste);
					}
				}
			}
			$contador_2++;
		}
		
		
		/////////FIN FABS/////////
		
		/////////INICIO FONDO DE RETIRO SINDICAL/////////
		$cue_uno='25051004';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_conta,2,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='25051004';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_conta,1,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}

		$cue_uno='61150515';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_conta,1,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='23803009';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_conta,2,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}
		
		/////////FIN FONDO DE RETIRO SINDICAL/////////
		
		
		/////////INICIO FONDO DE RECREACION/////////
		$cue_uno='25051006';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_conta,2,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='25051006';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_conta,1,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}

		$cue_uno='61150520';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_conta,1,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='25051005';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_conta,2,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}
		
		/////////FIN FONDO DE RECREACION/////////
		
		
		/////////INICIO EDUCACION/////////
		$cue_uno='250510121';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_tercero_sedar,2,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='250510121';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_tercero_sedar,1,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}

		$cue_uno='510595961';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_tercero_sedar,1,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='510595961';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_tercero_sedar,2,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}
		
		/////////FIN EDUCACION/////////
		
		
		
		
		
		/////////INICIO ADMINISTRACION/////////
		$cue_uno='250510131';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_tercero_sedar,2,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='250510131';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_tercero_sedar,1,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}

		$cue_uno='510595981';
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_tercero_sedar,1,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='510595981';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_tercero_sedar,2,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_tercero_sedar','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
		}
		
		/////////FIN ADMINISTRACION/////////
		
		
		
		/////////INICIO HONORARIOS NO AFILIADOS/////////
		$cue_uno='23352501';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_conta,2,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='23352501';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_conta,1,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			$bandera=0;
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				$bandera=1;
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				$bandera=2;
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
			$val_aju_honorarios=$valor_ajuste;
			if($nue_naturaleza==1)
				$nat_aju_honorarios=2;
			elseif($nue_naturaleza==2)
				$nat_aju_honorarios=1;
		}

		$cue_honorarios='61151005';
		$nue_cuenta=$cue_honorarios;
				
		$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
		'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$val_aju_honorarios',
		'$nat_aju_honorarios','$factura','$recibo_id','$mes','$ano','$base_retencion')";
		$eje_ajuste=mssql_query($que_ajuste);
		
		
		/////////FIN HONORARIOS NO AFILIADOS/////////
		
		
		/////////INICIO COMPENSACIONES /NOM CAUSADAS/////////
		
		
		$cue_uno='25051002';
		
		
		$con_causacion=$this->DatCauAjuste($sig_causacion,$cue_uno,$nit_conta,2,2);
		$res_datos_causada=mssql_fetch_array($con_causacion);
		
		if($res_datos_causada['mov_valor']=="" || $res_datos_causada['mov_valor']==NULL)
			$res_datos_causada['mov_valor']=0;
		
		$cue_dos='25051002';
		
   		$con_pago=$this->DatPagAjuste($sig_pago,$factura,$cue_dos,$nit_conta,1,3);
		$res_datos_pagada=mssql_fetch_array($con_pago);
		
		
		if($res_datos_pagada['mov_valor']=="" || $res_datos_pagada['mov_valor']==NULL)
			$res_datos_pagada['mov_valor']=0;
		
		if($res_datos_causada['mov_valor']!=$res_datos_pagada['mov_valor'])//SI NO ENTRA ES PORQUE NO SE DEBE HACER AJUSTE, YA QUE EL FABS NO CAMBIÓ
		{
			if($res_datos_causada['mov_valor']>$res_datos_pagada['mov_valor'])
			{
				$valor_ajuste=round($res_datos_causada['mov_valor']-$res_datos_pagada['mov_valor'],0);
				$nue_naturaleza=1;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				$eje_ajuste=mssql_query($que_ajuste);
			}
			else
			{
				$valor_ajuste=round($res_datos_pagada['mov_valor']-$res_datos_causada['mov_valor'],0);
				$nue_naturaleza=2;
				$nue_cuenta=$res_datos_causada['mov_cuent'];
				
				$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
				'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$valor_ajuste',
				'$nue_naturaleza','$factura','$recibo_id','$mes','$ano','$base_retencion')";
				
				$eje_ajuste=mssql_query($que_ajuste);
			}
			$val_aju_com_afiliados=$valor_ajuste;
			if($nue_naturaleza==1)
				$nat_aju_com_afiliados=2;
			elseif($nue_naturaleza==2)
				$nat_aju_com_afiliados=1;
		}
		
		$cue_com_afiliados='61150510';
		$nue_cuenta=$cue_com_afiliados;
		
		$que_ajuste="INSERT INTO movimientos_contables VALUES('$sigla_ajuste','$factura',
		'$fec_elabo','$nue_cuenta','2','$nit_conta','$centro','$val_aju_com_afiliados',
		'$nat_aju_com_afiliados','$factura','$recibo_id','$mes','$ano','$base_retencion')";
			
		$eje_ajuste=mssql_query($que_ajuste);
		/////////FIN COMPENSACIONES /NOM CAUSADAS/////////
		
	
	  /* $fecha=date('d-m-Y');
	   $sql = "EXECUTE ajuste_nomina $factura,'$fecha',$mes,$ano,'$recibo_id'";
	   $query = mssql_query($sql);
	   if($query)
	     return true;
	   else
	     return false;*/
   	}


	 //////////////////////////FIN AJUSTE DE NOMINA AFILIADOS///////////////////////////
   	
   	public function DatCauAjuste($causacion,$cuenta,$nit_tercero,$mov_tipo,$mov_concepto)
    {
        $query="SELECT id_mov,mov_compro,mov_cuent,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_cent_costo
		FROM movimientos_contables 
		WHERE mov_compro = '$causacion' AND mov_cuent='$cuenta' AND mov_nit_tercero LIKE('$nit_tercero%')
		AND mov_tipo='$mov_tipo' AND mov_concepto='$mov_concepto'
		ORDER BY id_mov";
		//echo $query."<br>";
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			return $ejecutar;
		}
        else
            return false;
    }
	
	public function DatPagAjuste($pago,$num_factura,$cuenta,$nit_tercero,$mov_tipo,$mov_concepto)
    {
        $query="SELECT id_mov,mov_compro,mov_cuent,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_cent_costo 
		FROM movimientos_contables 
		WHERE mov_compro='$pago' and mov_nume='$num_factura' AND mov_cuent='$cuenta'
		AND mov_nit_tercero LIKE('$nit_tercero%') AND mov_tipo='$mov_tipo' AND mov_concepto='$mov_concepto'
		ORDER BY id_mov";
		//echo $query."<br>";
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			return $ejecutar;
		}
        else
            return false;
    }
   	
   	///////////////////////////FIN AJUSTE///////////////////////////
   
   /**************************DIFERIDOS************************************/
   public function diferidos_contratos($mes)
   {
	   $sql = "
SELECT id_mov,mov_compro,mov_nit_tercero,mov_cuent,mov_cent_costo,mov_valor,nit_id,con_vigencia,con_fec_inicio,
con_fec_fin FROM movimientos_contables INNER JOIN contrato ON con_id=mov_documento
WHERE mov_cuent LIKE ('17%') and mov_mes_contable = $mes AND mov_doc_numer = 3";
	   $query = mssql_query($sql);
	   if($query)
	      return $query;
	   else
	     return false;
   }
   
   public function diferidos_causacion($mes)
   {
	   $sql = "SELECT id_mov,mov_compro,mov_cuent,mov_nit_tercero,mov_cent_costo,mov_valor FROM movimientos_contables
WHERE mov_cuent LIKE ('17%') and mov_mes_contable = $mes AND mov_doc_numer = 3 and mov_compro like('TRA%')";
	   $query = mssql_query($sql);
	   if($query)
	      return $query;
	   else
	     return false;
   }
   
   public function guar_diferidos($comprobante,$cue_dife,$cue_gasto,$val_diferido,$val_diferir,$coutas,$fec_ini,$fec_fin,$mov,$centro,$tercero)
   {   
	   $sql="INSERT INTO diferidos (dif_documento,dif_cueDiferido,dif_cueGasto,dif_valor,dif_valDiferido,dif_cuotas,dif_fecInicial,dif_fecFinal,dif_cantidad,dif_movimiento,dif_centro,dif_nit) VALUES('$comprobante',$cue_dife,$cue_gasto,$val_diferido,$val_diferir,$coutas,'$fec_ini','$fec_fin',0,$mov,$centro,$tercero)";
	   $query=mssql_query($sql);
	   if($query)
	   {
		 //$sql1="UPDATE movimientos_contables SET mov_doc_numer=10 WHERE id_mov=$mov";
		 //$query1=mssql_query($sql1);
		 //if($query1)
		   return true;
		 //else
		   //return false;
	   }
	   else
	     return false;
   }
   
  public function eje_diferidos($mes,$ano,$sigla,$conse_pagSeg)
   {
	   $diferir = "EXECUTE diferido $mes,'$sigla',$conse_pagSeg,$ano"; 
	   $dif_query = mssql_query($diferir);
	   if($dif_query)
		 return true;
	   else
		return false; 
   }
   
    public function GuaArcPlano1($data0,$data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12)
   {
	   $query="INSERT INTO movimientos_contables (mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,
			mov_valor,mov_tipo,mov_documento,mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('$data0','$data1','$data2','$data3','$data4','$data5','$data6','$data7','$data8','$data9','$data10','$data11','$data12')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
   }
   
   public function GuaArcPlano2($data0,$data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12)
   {
	   $iva=0;
	   $query="INSERT INTO transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,
trans_fec_vencimiento,trans_fac_num,trans_user,trans_fec_grabado,tran_tip_com,pag_che_id,tran_mes_contable,trans_observacion,trans_ano_contable)
		VALUES('$data0','$data1','$data2','$data3','$data4','$iva','$data5','$data6','$data7','$data8','$data9','$data10','$data11','$data12','$_SESSION[elaniocontable]')";
		//echo $query;
	   $ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
   }
   
	public function cierre_ano($tipo,$ano)
	{
		
		
		$sigla='CIE-'.$ano;
		$mes_contable=13;
		
		$usuario_actualizador=$_SESSION['k_nit_id'];
		$fecha_actualizacion=date('d-m-Y');
	
		$hora=localtime(time(),true);
		if($hora[tm_hour]==1)
			$hora_dia=23;
		else
			$hora_dia=$hora[tm_hour]-1;
		
		$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
			
		$tip_mov_aud_id=1;
		$aud_mov_con_descripcion='CREACION DE CIERRE DE AÑO';
		
		//$que_eliminar="DELETE FROM movimientos_contables WHERE mov_compro='CIE-$ano' AND mov_ano_contable='$ano' AND mov_mes_contable=13";
		//echo $que_eliminar;
		//$eje_eliminar=mssql_query($que_eliminar);
		
   		$fecha = date('d-m-Y');
   		//$fecha = '30-12-2016';
		if($tipo==1)//CUENTAS 1-2-3
   		{
   			$uno_cue_ret_fue_por_pagar_1='2365';
			$uno_cue_ret_fue_por_pagar_2='23659801';
			$uno_per_y_ganancias='36050501';
			
   			//@fecha VARCHAR(50),@ano INT,@cuenta BIGINT
   			$sql_1 = "EXECUTE cierre_anual_123 '$fecha','$ano','1',$tipo,'$uno_cue_ret_fue_por_pagar_1','$uno_cue_ret_fue_por_pagar_2','$uno_per_y_ganancias'";
	   		$query_1=mssql_query($sql_1);
			
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_contable' AND mov_ano_contable='$ano'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
			
			$sql_2 = "EXECUTE cierre_anual_123 '$fecha','$ano','2',$tipo,'$uno_cue_ret_fue_por_pagar_1','$uno_cue_ret_fue_por_pagar_2','$uno_per_y_ganancias'";
	   		$query_2=mssql_query($sql_2);
			
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_contable' AND mov_ano_contable='$ano'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
			
			$sql_3 = "EXECUTE cierre_anual_123 '$fecha','$ano','3',$tipo,'$uno_cue_ret_fue_por_pagar_1','$uno_cue_ret_fue_por_pagar_2','$uno_per_y_ganancias'";
	   		$query_3=mssql_query($sql_3);
	   		
	   		
	   		//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_contable' AND mov_ano_contable='$ano'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
			
	   		
	   		if($query_1&&$query_2&&$query_3)
	   			return true;
	   		else
	   			return false;
   		}

		if($tipo==2)//CUENTAS 4-5-6
   		{
   			//@fecha VARCHAR(50),@ano INT,@cuenta BIGINT
   			$sql_1 = "EXECUTE cierre_anual_456 '$fecha','$ano',$tipo";
	   		$query_1=mssql_query($sql_1);
			
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_contable' AND mov_ano_contable='$ano'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	   		
	   		if($query_1)
	   			return true;
	   		else
	   			return false;
   		}
		
		
		
		if($tipo==3)//RETENCION EN LA FUENTE(CUENTA: RETENCION POR SALARIOS - 23651001)
   		{
   			
			
			$tres_cue_ret_fue_por_pagar_1='2365';
			$tres_cue_ret_fue_por_pagar_2='23659801';
   			//@fecha VARCHAR(50),@ano INT,@cuenta BIGINT
   			$sql_1 = "EXECUTE cierre_anual_retencion_por_salarios '$fecha','$ano',$tipo,'$tres_cue_ret_fue_por_pagar_1','$tres_cue_ret_fue_por_pagar_2'";
			//echo $sql_1."<br>";
	   		$query_1=mssql_query($sql_1);
	   		
	   		//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_contable' AND mov_ano_contable='$ano'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	   		
	   		if($query_1)
	   			return true;
	   		else
	   			return false;
   		}
   		
   		
	   /*$fecha = date('d-m-Y');
	   $sql = "EXECUTE cierre_ano $tipo,'$fecha',$ano";
	   $query = mssql_query($sql);
	   if($query)
		 return true;
	   else
	   	 return false;*/
   }

   public function ConsultarDocumentosDescuadrados($mes,$anio)
   {
       $query="SELECT ROUND(SUM(mov_valor),0) valor,mov_compro,mov_fec_elabo,mov_cuent,mov_nit_tercero,mov_tipo,mov_mes_contable,mov_ano_contable FROM movimientos_contables
WHERE mov_mes_contable=$mes AND mov_ano_contable=$anio AND mov_valor>0
GROUP BY mov_compro,mov_fec_elabo,mov_cuent,mov_nit_tercero,mov_tipo,mov_mes_contable,mov_ano_contable
ORDER BY mov_compro";
       $ejecutar=mssql_query($query);
       if($query)
       { return $ejecutar; }
       else
       { return false; }
   }
   	//CONSULTAR DATOS NOMINA ADMINISTRATIVA
	public function ConsultarDatosNominaAdministrativa($sigla,$num_quincena,$mes_contable,$ano_contable,$empleado_id)
   	{
		$query="SELECT mc.*,cue.cue_nombre
				FROM movimientos_contables mc
				INNER JOIN cuentas cue ON cue.cue_id=mc.mov_cuent
				WHERE mc.mov_compro='$sigla' AND mov_concepto='$num_quincena' AND mc.mov_mes_contable='$mes_contable' AND mc.mov_ano_contable='$ano_contable' AND mov_doc_numer='$empleado_id'";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	} 
	
	public function ConsultarDiasTrabajados($sigla,$num_quincena,$mes_contable,$ano_contable,$empleado_id)
   	{
		$query="SELECT DISTINCT mov_nume
				FROM movimientos_contables mc
				WHERE mc.mov_compro='$sigla' AND mov_concepto='$num_quincena' AND mc.mov_mes_contable='$mes_contable' AND mc.mov_ano_contable='$ano_contable' AND mov_doc_numer='$empleado_id'";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	} 
	
	public function ConsultarDiasTrabajadosPrimaServicios($sigla,$nit,$mes_inicial,$mes_final,$ano_contable)
   	{
		$query="SELECT mov_nume,mov_compro,mov_nit_tercero,mov_mes_contable,mov_ano_contable
				FROM movimientos_contables mc
				WHERE mc.mov_compro LIKE('$sigla%') AND mov_doc_numer='$nit' AND (mov_mes_contable BETWEEN '$mes_inicial' AND '$mes_final')
				AND mov_ano_contable='$ano_contable'
				GROUP BY mov_nume,mov_compro,mov_nit_tercero,mov_mes_contable,mov_ano_contable";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
    
    public function GuardarSeguridadSocial($mes_servicio,$anio_servicio,$estado_nit,$tipo_nit,$pag_seg_soc_fecha,$pag_seg_soc_hora,$pag_seg_soc_mes_pago,$pag_seg_soc_anio_pago,$pag_seg_soc_usuario)
    {
    	$hon_no_afiliados='61151005';
        $cue_fon_recreacion='25051005';
        $cue_dietas_uno='51559505';
		$cue_dietas_dos='51059597';
		$cue_ret_sindical_uno='23803009';
		$cue_ret_sindical_dos='31400101';
		//$cue_dietas_2='51059597';
        
		
        $query="EXECUTE seguridad_social '$mes_servicio','$anio_servicio','$estado_nit','$tipo_nit','$pag_seg_soc_fecha','$pag_seg_soc_hora','$pag_seg_soc_mes_pago','$pag_seg_soc_anio_pago','$pag_seg_soc_usuario','$hon_no_afiliados','$cue_fon_recreacion','$cue_dietas_uno','$cue_dietas_dos','$cue_ret_sindical_uno','$cue_ret_sindical_dos'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
       		return $ejecutar;
        else
            return false;
    }
    
    public function GuardarSeguridadSocialDesde2018($mes_pag_compensaciones,$anio_pag_compensaciones,$estado_nit,$tipo_nit,$pag_seg_soc_fecha,$pag_seg_soc_hora,$pag_seg_soc_mes_pago,$pag_seg_soc_anio_pago,$pag_seg_soc_usuario)
    {
        
        $query="execute seguridad_social_desde_2018 '$mes_pag_compensaciones','$anio_pag_compensaciones','$estado_nit','$tipo_nit','$pag_seg_soc_fecha','$pag_seg_soc_hora','$pag_seg_soc_mes_pago','$pag_seg_soc_anio_pago','$pag_seg_soc_usuario'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
       		return $ejecutar;
        else
            return false;                
    }

    public function ObtenerPagoSeguridadSocial($pag_seg_soc_mes_pago,$pag_seg_soc_anio_pago)
    {
    	$query="SELECT pss.*,n.nits_num_documento,n.nits_apellidos,n.nits_nombres
				FROM pago_seguridad_social pss
				INNER JOIN nits n ON pss.pag_seg_soc_nit=n.nit_id
				WHERE pag_seg_soc_mes_pago='$pag_seg_soc_mes_pago' AND pag_seg_soc_anio_pago='$pag_seg_soc_anio_pago'
				ORDER BY nits_apellidos ASC";
    	//echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        	return $ejecutar;
        else
        	return false;
    }
	
	
	public function ObtenerPagoSeguridadSocialDesde2018($pag_seg_soc_mes_pag_fondos,$pag_seg_soc_anio_pag_fondos)
    {
    	$query="SELECT pss.*,n.nits_num_documento,n.nits_apellidos,n.nits_nombres
		FROM pago_seguridad_social_desde_2018 pss
		INNER JOIN nits n ON pss.pag_seg_soc_nit=n.nit_id
		WHERE pag_seg_soc_mes_pag_fondos='$pag_seg_soc_mes_pag_fondos' AND pag_seg_soc_anio_pag_fondos='$pag_seg_soc_anio_pag_fondos'
		ORDER BY nits_apellidos ASC";
    	//echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        	return $ejecutar;
        else
        	return false;
    }
    
    public function ConsultarSeguridadSocialPagada($mes,$ano)
    {
        $query="SELECT TOP 1 * FROM pago_seguridad_social WHERE pag_seg_soc_mes_pago='$mes' AND pag_seg_soc_anio_pago='$ano'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
            return $ejecutar;
        else
            return false;
    }
    
    public function EliminarSeguridadSocialPagada($mes,$ano)
    {
        $query="DELETE FROM pago_seguridad_social WHERE pag_seg_soc_mes_pago='$mes' AND pag_seg_soc_anio_pago='$ano'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
            return $ejecutar;
        else
            return false;
    }
    
	public function SaldoAnteriorCuenta($cuenta,$ano)
    {
        $query="SELECT dbo.saldo_cuenta_anual('$cuenta','$ano') AS saldo_anterior";
		//echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			$res_saldo_anterior=mssql_fetch_array($ejecutar);
			//echo "el saldo es: ".$res_saldo_anterior['saldo_anterior']."<br>";
            return $res_saldo_anterior['saldo_anterior'];
		}
        else
            return false;
    }
    
	public function SaldoAnteriorCuentaTercero($cuenta,$nit,$ano)
    {
    	//echo "entra <br>";
        $query="SELECT dbo.saldo_cuenta_tercero_anual('$cuenta','$nit','$ano') AS saldo_anterior";
		//echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			$res_saldo_anterior=mssql_fetch_array($ejecutar);
			$valor_saldo=0;
			$valor_saldo=$res_saldo_anterior['saldo_anterior'];
            return $valor_saldo;
		}
        else
		{
			$valor_saldo=0;
			return $valor_saldo;
		}
            
    }

	public function ConsultarMesAnoNomCausada($mov_compro,$mov_nume,$mov_documento)
    {
        $query="SELECT TOP 1 * FROM movimientos_contables WHERE mov_compro='$mov_compro' AND mov_nume='$mov_nume' AND mov_documento='$mov_documento'";
		//echo $query."<br>";
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			$res_datos=mssql_fetch_array($ejecutar);
			return $res_datos;
		}
        else
            return false;
    }
	
	public function ConValCauFabsPorSiglaFacturaNit($sigla,$factura,$nit_id)
    {
        $query="SELECT SUM(mov_valor) mov_valor
		FROM movimientos_contables
		WHERE mov_compro='$sigla' AND mov_nume='$factura' AND mov_nit_tercero='$nit_id'
		AND mov_cuent IN(SELECT dis_por_con_fab_cue_uno FROM distribucion_porcentajes_conceptos_fabs)";
		//echo $query."<br>";
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			$res_datos=mssql_fetch_array($ejecutar);
			return $res_datos['mov_valor'];
		}
        else
            return false;
    }
	
	public function ConValCauPorCuentaSiglaFacturaNit($cuenta,$sigla,$factura,$nit_id,$naturaleza)
    {
    	
        $query="SELECT SUM(mov_valor) mov_valor
		FROM movimientos_contables
		WHERE mov_compro='$sigla' AND mov_nume='$factura' AND mov_nit_tercero='$nit_id'
		AND mov_cuent IN($cuenta) AND mov_tipo='$naturaleza'";
		
		//if($cuenta=='250510131' || $cuenta=='250510121')
			//echo $query."<br>";
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			$res_datos=mssql_fetch_array($ejecutar);
			return $res_datos['mov_valor'];
		}
        else
            return false;
    }
	
	
	
	
}
?>