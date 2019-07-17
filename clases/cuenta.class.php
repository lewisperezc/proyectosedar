<?php
@include_once('../conexion/conexion.php');//MODIFICADO 7 DE FEBRERO
@include_once('concepto.class.php');
class cuenta
{
		private $cue_id;
		private $cue_nombre;
		private $cue_esmayor;
		private $cue_subdivision;
		private $cue_pide_nit;
		private $cue_pide_doc;
		private $cue_pide_cent;
		private $cue_porcentage;
		private $cue_ciudad;
		private $cuentabalance;
		private $concepto;
	
	function __construct()
	{
		$this->concepto = new concepto();
	}
	
	public function setCue_numero($cue_num)
	{
	$this-> cue_numero=$cue_num;
	}
	
	public function getCue_numero()
	{
	return $this->cue_numero ;
	}
	
	public function setCue_nombre($cue_nom)
	{
	$this->cue_nombre =$cue_nom;
	}
	public function getCue_nombre()
	{
	return $this->cue_nombre;
	}
	
	public function setcue_id ($cue_id )
	{
	$this->cue_id =$cue_id;
	}
	public function getcue_id ()
	{
	return $this->cue_id;
	}
	public function setcue_subdivision ($cue_subdivision )
	{
	$this->cue_subdivision =$cue_subdivision;
	}
	public function getcue_subdivision ()
	{
	return $this->cue_subdivision;
	}

	public function busqueda($cue_subdivision)
	{
		$sqlcue="SELECT * FROM dbo.cuentas WHERE cue_subdivision ='$cue_subdivision'";
		$cue =mssql_query($sqlcue);
		return $cue;
	}
		
	public function busqueda_T($cue_subdivision)
	 {
		 $sqlcuen="SELECT * FROM cuentas WHERE cue_subdivision ='$cue_subdivision'";
		 $cue =mssql_query($sqlcuen);
		 return $cue;
	 }
	 	
	public function insert_cuenta($cue_id,$cue_nombre,$cue_esmayor,$cue_subdivision,$cue_porcentaje,$cue_ciudad,$cuentabalancem,$cue_nomina)
		{
		 $sqlcuet="INSERT INTO cuentas(cue_id,cue_nombre,cue_esmayor,cue_subdivision,cue_porcentage,cue_ciudad,cue_balance,cue_nomina)
				   VALUES($cue_id,'$cue_nombre','$cue_esmayor','$cue_subdivision',$cue_porcentaje,$cue_ciudad,$cuentabalancem,'$cue_nomina')";
		 $cuet =mssql_query($sqlcuet);
		 if($cuet)
		 	return $cuet;
	  	 else
	   	 	return false;
		}
	
	public function verificar_existe($id_cuenta)
		{
		  $sqlcuet="select cue_id,cue_nombre,cue_porcentage from cuentas WHERE cue_id = $id_cuenta";
		  $cuet = mssql_query($sqlcuet);
		  return $cuet;
		}

	public function actualizar_cuenta ($cue_id,$nombre)
	  {
		$sqlcuen ="UPDATE cuentas set cue_nombre = '$nombre' where cue_id = $cue_id";
		$cuen =mssql_query($sqlcuen);
		return $cuen;
	  }
				
	public function busPorCuenta($cuenta)
	 {
	   $sql = "SELECT cue_porcentage,cue_nombre FROM cuentas WHERE cue_id =$cuenta";
	   $exe = mssql_query($sql);
	   if($exe)
	     return $exe;
	   else
	     return false;
	 }
	 
	 public function variables()
	 {
		 $sql = "SELECT * FROM cuentas WHERE cue_variable = 1";
		 $query = mssql_query($sql);
		 if($query)
		   return $query;
		 else
		   return false;  
	 }
	 
	 public function cuentas_pagar()
	 {
		 $bor_vista = "DROP VIEW cue_pagar";
		 $query_vista = mssql_query($bor_vista);
		 if($bor_vista)
		 {
			$cre_vista=" CREATE VIEW cue_pagar AS select * from cuentas where cue_id LIKE ('23%') OR cue_id LIKE ('25%')";
			$query = mssql_query($cre_vista);
		    if($query)
		    {
		     $sql_cuen = "SELECT * FROM cue_pagar cp INNER JOIN saldos_cuentas_por_nits scn 
						  ON cp.cue_id = scn.sald_mov_cuent 
						  WHERE sald_mov_valor >0 AND sald_mov_tipo = 2"; 
		     $query_cue = mssql_query($sql_cuen);
			 if($query_cue)
			   return $query_cue;
			 else
			   return false;  
		    }
		 }
	 }
	 
	 public function saldo_cuePagar($concepto)
	 {
		 $formula = $this->concepto->busca_concepto($concepto);
		 $dat_form = mssql_fetch_array($formula);
		 $cont = 1;
		 while($cont<=21)
		 {
	       $arre = split(",",$dat_form["for_cue_afecta".$cont]);
		   $b = $arre[1];
		   $c = $arre[2]; 
		   $cuenta = trim($b);
		   $naturaleza = trim($c); 
		   if($cuenta != "" && $naturaleza != "")
		 	 {
			   $sql = "SELECT sald_mov_valor FROM saldos_cuentas_por_nits WHERE sald_mov_cuent = $b AND sald_mov_tipo = $c
			           AND sald_mov_cuent LIKE ('25%')";
			   $query = mssql_query($sql);
			   if($query)
			   {
				   $dat_cuentas = mssql_fetch_array($query);
				   $sal_cuenta = $dat_cuentas["sald_mov_valor"];
			   }
			 }
		   $cont++;
		 }
	 }
	 
	 public function val_iva($documento,$nombre)
	 {
		 $sql = "
		     BEGIN 
			   DECLARE @retorno int EXECUTE bus_iva '$documento','$nombre',@movimiento = @retorno OUTPUT
			   SELECT @retorno as retor 
			 END";
		 $query = mssql_query($sql);
		 if($query)
		   {
		    $prueba = mssql_fetch_array($query);
			return $prueba['retor'];
		   }
		 else
		   return false;   
	 }
	 
	 public function val_rete($documento,$nombre)
	 {
		  $sql = "
		     BEGIN
  			  DECLARE @retorno int EXECUTE bus_rete '$documento','$nombre',@movimiento = @retorno OUTPUT 
			  SELECT @retorno as retor
			 END";
		 $query = mssql_query($sql);
		 if($query)
		   {
		    $prueba = mssql_fetch_array($query);
			return $prueba['retor'];
		   }
		 else
		   return false;   
	 }
	 
	 public function cuentas_bancarias()
	 {
		 $sql = "SELECT cue_id, cue_nombre FROM cuentas WHERE (cue_id LIKE('1110%') OR cue_id LIKE('23803004')) AND cue_esmayor = 'no'";
		 $query = mssql_query($sql);
		 if($query)
		   return $query;
		 else
		   return false;  
	 }
	 
	 public function cuentas_chequera()
	 {
		 $sql = "SELECT cue_id,cue_nombre FROM cuentas WHERE cue_id LIKE('1110%') AND cue_esmayor = 'no' 
		         AND cue_id NOT IN (SELECT che_cue_pertenece FROM chequera WHERE che_estado = 1)";
		 $query = mssql_query($sql);
		 if($query)
		   return $query;
		 else
		   return false;  
	 }
	 
	 public function con_cue_pabs($cue_id)
	 {
		 $query = "SELECT cue_id,cue_nombre FROM cuentas WHERE cue_id like('$cue_id%')";
		 $ejecutar = mssql_query($query);
		 if($ejecutar)
		   return $ejecutar;
		 else
		   return false;
	 }
	 
	 public function con_cue_menores($cue_id)
	 {
		 $query = "SELECT cue_id,cue_nombre FROM cuentas WHERE cue_id like('$cue_id%') AND cue_esmayor = 'no' ORDER BY cue_nombre ASC";
		 $ejecutar = mssql_query($query);
		 if($ejecutar)
		   return $ejecutar;
		 else
		   return false;
	 }
	 
	 public function cuentasPabs($cuenta1,$cuenta2)
	 {
		 $sql = "select * from cuentas where cue_id between '$cuenta1' and '$cuenta2'";
		 $query = mssql_query($sql);
		 if($query)
		   return $query;
		 else
		   return false;  
	 }
	 
	 public function cuentaContrato()
	 {
		 $sql = "SELECT MAX(cue_id) cuenta FROM cuentas WHERE cue_id LIKE('41150%')";
		 $query = mssql_query($sql);
		 if($query)
		 {
			 $dat_cuenta = mssql_fetch_array($query);
			 return $dat_cuenta['cuenta']+1;
		 }
		 else
		   return false;
	 }
	 
	 public function cue_centro($centro)
	 {
		 $sql = "SELECT cen_cos_cuenta FROM centros_costo WHERE cen_cos_id = $centro";
		 $query = mssql_query($sql);
		 if($query)
		 {
			 $dat_cue = mssql_fetch_array($query);
			 return $dat_cue['cen_cos_cuenta'];
		 }
	 }
	 
	 public function todCuentas()
	 {
		 $sql = "SELECT * FROM cuentas";
		 $query = mssql_query($sql);
		 if($query)
		   return $query;
		 else
		   return false;  
	 }
	 
	 public function cue_Pagar($cue)
	 {
		$sql = "SELECT * FROM cuentas WHERE cue_id LIKE('$cue%') AND cue_esmayor = 'no'";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	 }
	 
	 public function con_tod_cuentas(){
		$query="SELECT CAST(cue_id AS varchar) cue_id ,cue_nombre FROM cuentas ORDER BY cue_id asc";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	 }
	 
	 public function con_cue_nomina($valor)
	 {
		 $query="SELECT * FROM cuentas WHERE cue_nomina='$valor' ORDER BY cue_id";
		 //echo $query;
		 $ejecutar=mssql_query($query);
		 if($ejecutar)
		 	return $ejecutar;
		 else
		 	return false;
	 }
	  public function getnomCuenta($cuenta)
	 {
		 $sql = "SELECT cue_nombre FROM cuentas WHERE cue_id=$cuenta";
		 $query = mssql_query($sql);
		 if($query)
		  {
			  $dat_cuenta = mssql_fetch_array($query);
			  return $dat_cuenta['cue_nombre'];
		  }
		 else
		   return false; 
	 }
	 
	 public function cue_mes($mes,$ano)
	 {
		$sql = "SELECT DISTINCT CAST(mov_cuent AS VARCHAR) cuenta FROM movimientos_contables WHERE mov_mes_contable=$mes AND mov_ano_contable=$ano ORDER BY cuenta";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	 }
	 
	 public function exis_columna($columna,$tabla)
	 {
		$sql = "SELECT COUNT(*) cantidad FROM sysobjects INNER JOIN syscolumns ON sysobjects.id = syscolumns.id INNER JOIN
		systypes ON syscolumns.xtype = systypes.xtype WHERE sysobjects.name = '$tabla' AND syscolumns.name = '$columna'";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_columna = mssql_fetch_array($query);
			if($dat_columna['cantidad']==0)
			{
				$sql = "ALTER TABLE $tabla ADD $columna FLOAT DEFAULT 1";
				$query = mssql_query($sql);
				if($query)
                                {
                                  $query="UPDATE $tabla SET $columna=1";
                                  $ejecutar=mssql_query($query);
                                  if($ejecutar)
                                  { return true;}
                                  else
                                  { return false; }
                                }
				else
                                { return false; }
			}
			return false;
		}
	 }
	 
	 public function val_cierre($columna,$cuenta,$valor)
	 {
		 $sql="UPDATE cuentas SET $columna=$valor WHERE cue_id = $cuenta";
		 $query = mssql_query($sql);
		 if($query)
		 {
		   $cont = strlen(trim($cuenta))-2;
		   while($cont>0)
			{
			 $part_cuenta = substr($cuenta,0,$cont);
			 $sql = "UPDATE cuentas SET $columna = $columna+$valor WHERE cue_id = $part_cuenta";
			 $query = mssql_query($sql);
			 if($cont>2)
			  $cont-=2;
			 else
			  $cont--;
			}
		   return true;
		 }
		 else
		   return false;
	 }
	 
	 public function ConCueEsMayPorCueId($cuenta)
	 {
		 $query="SELECT cue_esmayor FROM cuentas WHERE cue_id='$cuenta'";
		 $ejecutar=mssql_query($query);
		 if($ejecutar)
		 {
			 $resultado=mssql_fetch_array($ejecutar);
			 return $resultado['cue_esmayor'];
		 }
		 else
		 	return false;
	 }
	 
	 public function cue_gasto()
	 {
		 $sql = "SELECT cue_id,cue_nombre FROM cuentas WHERE (cue_id LIKE ('5110%') OR cue_id LIKE ('5120%') OR cue_id LIKE ('5125%') OR cue_id LIKE ('5135%') OR cue_id LIKE ('5140%') OR cue_id LIKE ('5145%') OR cue_id LIKE ('5150%') OR cue_id LIKE ('5155%') OR cue_id LIKE ('5160%') OR cue_id LIKE ('5165%') OR cue_id LIKE ('5195%') OR cue_id LIKE ('5199%')) and cue_esmayor = 'no'";
		 $query = mssql_query($sql);
		 if($query)
		   return $query;
		 else
		   return false;
	 }
         
         public function ConsultarCuentasCredito($valor)
	 {
		 $sql="SELECT * FROM cuentas WHERE cue_variable=$valor";
		 $query=mssql_query($sql);
		 if($query)
                    return $query;
		 else
                    return false;
	 }
} 
?>