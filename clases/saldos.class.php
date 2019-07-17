<?php
@include_once('../conexion/conexion.php');//MODIFICADO 7 DE FEBRERO
include_once('nits.class.php');
include_once('cuenta.class.php');

class saldos
{
	 private $cod_nit;
	 private $cuenta;
	 private $nit;
	 private $i;
	 
	 public function __construct()
  		{
			$this->nits = new nits();
			$this->cuenta = new cuenta();	
  		}	

////////////////////////////hace la consulta de las cuentas  auxiliares  en su naturaleza///////////////////////
	public function con_sal_cuen_debito($mov_cuenta)
    {
	 $sql="select dbo.debito($mov_cuenta) debito";
	 $sal_concepto=mssql_query($sql);
	return $sal_concepto;	 	 
    }
	
	public function con_sal_cuen_credito($mov_cuenta)
    {
	 $sql="select dbo.credito($mov_cuenta) credito";
	 $sal_concepto=mssql_query($sql);
	 return $sal_concepto;
    }
	
	public function saldo_cuenta($cuenta,$tipo,$mes,$ano)
	{
		$sal_ini=$this->salInicial($mes,$ano,$cuenta);
		$sql_deb="SELECT SUM(mov_valor) valor FROM movimientos_contables
		WHERE mov_cuent=$cuenta AND mov_mes_contable = $mes AND mov_tipo=1 AND mov_ano_contable=$ano";
		$que_deb=mssql_query($sql_deb);
		$dat_deb=mssql_fetch_array($que_deb);
		
		$sql_cre="SELECT SUM(mov_valor) valor FROM movimientos_contables
		WHERE mov_cuent=$cuenta AND mov_mes_contable = $mes AND mov_tipo=2 AND mov_ano_contable=$ano";
		$que_cre = mssql_query($sql_cre);
		$dat_cre = mssql_fetch_array($que_cre);
		
		if($tipo==1)
		  return ($sal_ini+$dat_deb['valor'])-$dat_cre['valor'];
		elseif($tipo==2)
		  return ($sal_ini+$dat_cre['valor'])-$dat_deb['valor'];
	}
	
   public function salInicial($mes,$ano,$cuenta)//SALDO INICIAL PARA EL BALANCE, NO MODIFICAR
   {
   	  $temp=0;
   	  $ano_anterior=$ano-1;
	  $dos_anos_anteriores=$ano-2;
	  $mes_antes=$mes-1;
	  $entra=0;
	  if($mes_antes==0)
	  {
	  	$temp=1;
	    $mes=12;
		$ano=$ano_anterior;
		$entra=1;
	  }
	  else
	  {
		  $temp=2;
	  }
	  
	  $dat_debito=0;$dat_credito=0;
	  if($entra==1)
	  {
	  	if($mes==12&&$temp==1)//ES EL BALANCE DE ENERO
		{
			//echo "entra if 1<br>";
			$sql_debito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
			mov_cuent like ('$cuenta%') AND len(mov_cuent)=8
			and (mov_ano_contable=$ano AND mov_mes_contable<=$mes) and mov_tipo=1 AND mov_mes_contable<13)
			+ 
	  		(select ISNULL(sum(mov_valor),0)
	  		from movimientos_contables where mov_cuent like ('$cuenta%') AND len(mov_cuent)=8 and
	  		mov_ano_contable=$ano_anterior and mov_tipo=1
	  		and mov_mes_contable=13 AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100)))
	  		+
			(select ISNULL(sum(mov_valor),0) from movimientos_contables
			where mov_cuent like ('$cuenta%') AND len(mov_cuent)=8 and mov_ano_contable=$dos_anos_anteriores
			and mov_tipo=1
			and mov_mes_contable=13 AND mov_compro='CIE-'+CAST($dos_anos_anteriores AS VARCHAR(100)))
	  		AS debito";
			//echo $sql_debito."<br>";
		}
		else
		{
			//echo "entra por aqui 1 <br>";
			$sql_debito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
			mov_cuent like ('$cuenta%') AND len(mov_cuent)=8
			and (mov_ano_contable=$ano AND mov_mes_contable<$mes) and mov_tipo=1  AND mov_mes_contable<13)
			+
	  		(select ISNULL(sum(mov_valor),0) from movimientos_contables where 
	  		mov_cuent like ('$cuenta%') AND len(mov_cuent)=8 and mov_ano_contable=$ano_anterior and mov_tipo=1
	  		and mov_mes_contable=13 AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100))) AS debito";
		}
	  	
	  }
      else
      {
      	if($mes==12&&$temp==1)//ES EL BALANCE DE ENERO
      	{
      		//echo "ESTÁ ENTRANDO POR AQUI <br>";
      		//echo "entra else 1<br>";
			$sql_debito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
			mov_cuent like ('$cuenta%') AND len(mov_cuent)=8
			and (mov_ano_contable=$ano AND mov_mes_contable<=$mes) and mov_tipo=1 AND mov_mes_contable<13)
      		+ 
      		(select ISNULL(sum(mov_valor),0)
      		from movimientos_contables where mov_cuent like ('$cuenta%')
      		AND len(mov_cuent)=8 and mov_ano_contable=$ano_anterior and mov_tipo=1 and mov_mes_contable=13
      		AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100)))
      		+
			(select ISNULL(sum(mov_valor),0)
      		from movimientos_contables where mov_cuent like ('$cuenta%')
      		AND len(mov_cuent)=8 and mov_ano_contable=$dos_anos_anteriores and mov_tipo=1 and mov_mes_contable=13
      		AND mov_compro='CIE-'+CAST($dos_anos_anteriores AS VARCHAR(100)))      		
      		AS debito";
      		
      		//echo "el del if: ".$sql_debito."<br>"; 		
      	}
		else
		{
			//echo "entra por aqui 2 <br>";
			$sql_debito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
			mov_cuent like ('$cuenta%') AND len(mov_cuent)=8
      		and (mov_ano_contable=$ano AND mov_mes_contable<$mes) and mov_tipo=1 AND mov_mes_contable<13)
      		+
      		(select ISNULL(sum(mov_valor),0)
      		from movimientos_contables where mov_cuent like ('$cuenta%')
      		AND len(mov_cuent)=8 and mov_ano_contable=$ano_anterior and mov_tipo=1 and mov_mes_contable=13
      		AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100))) AS debito";
		}

      }
		/*if($cuenta==1)
		  echo "Debito=".$sql."<br>";
		*/
		
	   	$query=mssql_query($sql_debito);
	   	if($query)
	   	{
	   	  $debito=mssql_fetch_array($query);
		  $dat_debito=$debito['debito'];
		}
		
		if($entra==1)
		{
			if($mes==12&&$temp==1)//ES EL BALANCE DE ENERO
			{
				//echo "entra if 2<br>";
				$sql_credito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
				mov_cuent like ('$cuenta%') AND len(mov_cuent)=8
      			and (mov_ano_contable=$ano AND mov_mes_contable<=$mes) and mov_tipo=2 AND mov_mes_contable<13)
      			+
      			(select ISNULL(sum(mov_valor),0)
      			from movimientos_contables where mov_cuent like ('$cuenta%')
      			AND len(mov_cuent)=8 and mov_ano_contable=$ano_anterior and mov_tipo=2 and mov_mes_contable=13
      			AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100)))
      			+
      			(select ISNULL(sum(mov_valor),0)
      			from movimientos_contables where mov_cuent like ('$cuenta%')
      			AND len(mov_cuent)=8 and mov_ano_contable=$dos_anos_anteriores and mov_tipo=2 and mov_mes_contable=13
      			AND mov_compro='CIE-'+CAST($dos_anos_anteriores AS VARCHAR(100)))
      			AS credito";
      			//echo $sql_credito."<br>";
      							
			}
			else
			{
				//echo "entra por aqui 3 <br>";
				$sql_credito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
			 	like ('$cuenta%') AND len(mov_cuent)=8
      			and (mov_ano_contable=$ano AND mov_mes_contable<$mes) and mov_tipo=2 AND mov_mes_contable<13)
      			+
      			(select ISNULL(sum(mov_valor),0)
      			from movimientos_contables mov_cuent like ('$cuenta%')
      			AND len(mov_cuent)=8 and mov_ano_contable=$ano_anterior and mov_tipo=2 and mov_mes_contable=13
      			AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100))) AS credito";
			}
				
		}
		else
		{
			if($mes==12&&$temp==1)//ES EL BALANCE DE ENERO
			{
				//echo "ESTÁ ENTRANDO POR AQUI TAMBIEN <br>";
				//echo "entra else 2<br>";
				$sql_credito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
				mov_cuent like ('$cuenta%') AND len(mov_cuent)=8
				and (mov_ano_contable=$ano AND mov_mes_contable<=$mes) and mov_tipo=2 AND mov_mes_contable<13)
				+
				(select ISNULL(sum(mov_valor),0)
				from movimientos_contables where mov_cuent like ('$cuenta%')
				AND len(mov_cuent)=8 and mov_ano_contable=$ano_anterior and mov_tipo=2 and mov_mes_contable=13
				AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100)))
				+
				(select ISNULL(sum(mov_valor),0)
      			from movimientos_contables where mov_cuent like ('$cuenta%')
      			AND len(mov_cuent)=8 and mov_ano_contable=$dos_anos_anteriores and mov_tipo=2 and mov_mes_contable=13
      			AND mov_compro='CIE-'+CAST($dos_anos_anteriores AS VARCHAR(100)))
				AS credito";
				//echo "el del else: ".$sql_credito."<br>";
			}
			else
			{
				//echo "entra por aqui 4 <br>";
				$sql_credito="SELECT (select ISNULL(sum(mov_valor),0) from movimientos_contables where
				mov_cuent like ('$cuenta%') AND len(mov_cuent)=8
				and (mov_ano_contable=$ano AND mov_mes_contable<$mes) and mov_tipo=2 AND mov_mes_contable<13)
				+
				(select ISNULL(sum(mov_valor),0)
				from movimientos_contables where mov_cuent like ('$cuenta%')
				AND len(mov_cuent)=8 and mov_ano_contable=$ano_anterior and mov_tipo=2 and mov_mes_contable=13
				AND mov_compro='CIE-'+CAST($ano_anterior AS VARCHAR(100))) AS credito";			
			}
		}
		/*if($cuenta==1)
		  echo "Credito=".$sql."<br>";*/
	   	$query=mssql_query($sql_credito);
	   	if($query)
	   	{
	   	  $credito=mssql_fetch_array($query);
		  $dat_credito=$credito['credito'];
		}
	
		/*if($cuenta==1)
			echo $dat_debito."-".$dat_credito;*/
		
		if($cuenta[0]==1 || $cuenta[0]==5 || $cuenta[0]==6)
		   $saldo=$dat_debito-$dat_credito;
		elseif($cuenta[0]==2 || $cuenta[0]==3 || $cuenta[0]==4)
		{
			$saldo=$dat_credito-$dat_debito;
			//echo "datos: ".$saldo."___".$dat_credito."___".$dat_debito."<br>";
		}
		   
		
		//if($cuenta[0]==2)
			//echo $saldo."<br>";
		
		//echo "el inicial: ".$saldo."<br>";
		return $saldo;
   }

/**********************************Empezamos desde aqui****************************************/
   public function saldos_seguridad($cuenta,$nit,$natu,$ano)
   {
	   $sql = "SELECT SUM(mov_valor) valor FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_nit_tercero LIKE ('$nit') AND mov_tipo = $natu AND mov_ano_contable=$ano";
	   $query = mssql_query($sql);
	   if($query)
	     {
			 $sal_cuenta = mssql_fetch_array($query);
			 return $sal_cuenta['valor'];
		 }
	   else
	    return false;	 
   }
  
  
   public function saldos_cuenta($cuenta,$nit,$natu)
   {
	   $sql = "SELECT SUM(mov_valor) valor FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_nit_tercero LIKE ('$nit%') AND mov_tipo = $natu";
	   $query = mssql_query($sql);
	   if($query)
	     {
			 $sal_cuenta = mssql_fetch_array($query);
			 return $sal_cuenta['valor'];
		 }
	   else
	    return false;	 
   }
   
   public function sal_cue_cencos($nit,$cencos,$cuenta,$natu)
    {
	 $sql="SELECT mov_valor valor FROM movimientos_contables WHERE mov_cuent='$cuenta' AND mov_nit_tercero LIKE ('$nit%') AND mov_cent_costo = '$centro' AND mov_tipo = $natu";
	 $query = mssql_query($sql);
	   if($query)
	     {
			 $sal_cuenta = mssql_fetch_array($query);
			 return $sal_cuenta['valor'];
		 }
	   else
	    return false;	
	}
	
	public function saldos_cuenta_centro($cuenta,$nit,$natu,$centro)
   {
	   $sql = "SELECT SUM(mov_valor) valor, mov_nume FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_nit_tercero LIKE ('$nit%') AND mov_tipo = $natu AND mov_cent_costo =
	   $centro GROUP BY mov_nume";
	   $query = mssql_query($sql);
	   if($query)
	     {
			 $sal_cuenta = mssql_fetch_array($query);
			 return $sal_cuenta['valor']."_".$sal_cuenta['mov_nume'];
		 }
	   else
	    return false;	 
   }
   
   public function sal_seg_social($nit,$centro)
   {
	   $sql = "SELECT SUM(mov_valor) valor FROM movimientos_contables WHERE mov_cuent=13250594 AND mov_nit_tercero LIKE ('$nit%') AND mov_tipo = 1 AND mov_cent_costo =
	   $centro AND mov_compro LIKE ('Cau_seg_%')";
	   $query = mssql_query($sql);
	   $deb_ss = mssql_fetch_array($query);
	   $debe = $deb_ss['valor'];
 	   $sql = "SELECT SUM(mov_valor) valor FROM movimientos_contables WHERE mov_cuent=13250594 AND mov_nit_tercero LIKE ('$nit%') AND mov_tipo = 2 AND mov_cent_costo = $centro
 	   AND mov_compro LIKE ('PAG-COM-%')";
	   $query = mssql_query($sql);
	   $pago_ss = mssql_fetch_array($query);
	   $pago = $pago_ss['valor'];
	   return ($debe-$pago);
   }
   
   public function con_cuo_pagadas($cuenta,$nit,$centro,$credito,$naturaleza){
	   $query="SELECT * FROM movimientos_contables WHERE mov_cuent=$cuenta AND mov_nit_tercero = $nit AND mov_cent_costo = $centro AND mov_doc_numer = $credito AND mov_tipo=$naturaleza";
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   	return $ejecutar;
	   else
	   	return false;
   }
   
  public function saldos_cuenta_documento($cuenta,$nit,$natu,$centro,$documento)
  {
    $sql="SELECT SUM(mov_valor) valor FROM movimientos_contables
    WHERE mov_cuent='$cuenta' AND mov_nit_tercero LIKE ('$nit%') AND mov_tipo = '$natu' AND mov_cent_costo = '$centro'
    AND mov_compro = '$documento'";
    //SELECT SUM(mov_valor) valor
    //FROM movimientos_contables
    //WHERE mov_cuent=13451001 AND mov_nit_tercero LIKE ('1320%') AND mov_tipo = 2 AND mov_cent_costo = 1188 AND mov_documento = 
    //echo "Los datos: ".$sql;
    $query = mssql_query($sql);
    if($query)
    {
    $sal_cuenta = mssql_fetch_array($query);
    return $sal_cuenta['valor'];
  }
    else
        return false;	 
    }
  
  public function sal_cue_tercero($mes,$ano)
  {
	 $sql="EXECUTE saldoCuen_tercero $mes,$ano"; 
	 echo $sql; 
	 $query=mssql_query($sql);
	 if($query)
	   return true;
	 else
	   return false;
  }
  
  public function conSal_cue_tercero($cuenta,$tercero,$mes,$ano)
  {
	  $sql="SELECT SUM(sal_cue_ter_valor) valor FROM saldo_cuentas_tercero WHERE sal_cue_ter_tercero LIKE($tercero) AND sal_cue_ter_mes < $mes AND sal_cue_ter_ano <=$ano AND sal_cue_ter_cuenta=$cuenta";
	  //echo $sql;
	  $query=mssql_query($sql);
	  if($query)
	  {
		  
		  $resultado=mssql_fetch_array($query);
		  return $resultado['valor'];
	  }
	  else
	  	return false;
  }

  public function sal_cueMes($cuenta,$ano,$mes_ini,$mes_fin)
  {
  	$sql="SELECT mov_nit_tercero,sum(valor) valor,mov_tipo FROM
			(
			SELECT mov_nit_tercero,sum(mov_valor) as valor,mov_tipo FROM movimientos_contables WHERE mov_cuent='$cuenta'
			and mov_ano_contable < $ano group by mov_nit_tercero,mov_tipo
			UNION
			SELECT mov_nit_tercero,sum(mov_valor) as valor,mov_tipo FROM movimientos_contables WHERE mov_cuent='$cuenta' and mov_mes_contable between $mes_ini and $mes_fin 
			and mov_ano_contable=$ano group by mov_nit_tercero,mov_tipo
			) as temp
			group by mov_nit_tercero,mov_tipo";
	$query=mssql_query($sql);
	if($query)
		return $query;
	else
		return false;
  }
}
  ?>