<?php
@include_once('../conexion/conexion.php');
include_once('tipo_comprobante.class.php');

class caja_menor
{
  public function guardar_caja($centro,$monto)
   {
	$sql="INSERT INTO caja_menor (caj_men_mon_asig,caj_men_centro) VALUES ($monto,$centro)";
    $query = mssql_query($sql);
    if($query)
	  return $query;
	else
	  return false; 
   }
   
   public function ult_caja()
   {
	 $sql = "SELECT MAX(caj_men_id) caj_men FROM caja_menor";
	 $query = mssql_query($sql);
	 if($query)
	 {
	   $max = mssql_fetch_array($query);
	   return $max['caj_men'];
	 }
	 else
	   return false;
   }
   
   public function actCaja_gastos($valor,$caja)
   {
	   $sql = "UPDATE caja_menor SET caj_men_monto = $valor WHERE caj_men_id = $caja";
	   $query = mssql_query($sql);
	   if($query)
	      return true;
	   else
	      return false;	   
   }
   
   public function buscar_caja($centro)
   {
	   $sql = "SELECT * FROM caja_menor WHERE caj_men_centro = $centro";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
   
   public function buscar_datos_caja($caja)
   {
	   $sql = "SELECT * FROM caja_menor WHERE caj_men_id = $caja";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
   
   public function centros_cajas()
   {
	   $sql = "SELECT cm.caj_men_id caj_id, cc.cen_cos_id cen_id, cc.cen_cos_nombre cen_nombre FROM caja_menor cm 
	           INNER JOIN centros_costo cc ON cm.caj_men_centro = cc.cen_cos_id";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
}
?>