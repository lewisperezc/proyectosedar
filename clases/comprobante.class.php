<?php
@include_once('../conexion/conexion.php');
include_once('tipo_comprobante.class.php');

class comprobante
{
  private $com_tipo;
  private $com_consecutivo;
  
   public function consultar_ciudades()
   {
    $sql="select * from ciudades";
    $execut= mssql_query($sql);
    return $execut;
   }

   public function cons_comprobante($ano,$mes,$compro)
   {
      $columna="a".$ano."a".$mes;
      $sql="SELECT $columna col FROM tipo_comprobante WHERE tip_com_id=$compro";
	  //echo $sql;
      $query=mssql_query($sql);
      if($query)
      {
        $dat_query=mssql_fetch_array($query);
        return $dat_query['col'];
      }
   }

   public function act_comprobante($ano,$mes,$compro)
   {
      $columna="a".$ano."a".$mes;
      $sql="UPDATE tipo_comprobante SET $columna=$columna+1 WHERE tip_com_id=$compro";
      //echo $sql;
      $query=mssql_query($sql);
      if($query)
        return true;
   }

   public function sig_comprobante($compro)
   {
      $sql="SELECT tip_com_sigla FROM tipo_comprobante WHERE tip_com_id=$compro";
      $query=mssql_query($sql);
      if($query)
      {
        $dat_query=mssql_fetch_array($query);
        return $dat_query['tip_com_sigla'];
      }
      else
        return false;
   }

   public function doc_comprobante($user)
   {
      $sql="SELECT DISTINCT cc.cen_cos_resolucion FROM nits n INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id WHERE n.nit_id=$user";
      $query=mssql_query($sql);
      if($query)
      {
        $dat_query=mssql_fetch_array($query);
        $sql="SELECT res_prefijo,res_comEgre_consecutivo FROM resoluciones WHERE res_id=".$dat_query['cen_cos_resolucion'];
        $query=mssql_query($sql);
        if($query)
        {
          $dat_compro=mssql_fetch_array($query);
          return $dat_compro['res_prefijo']."-".$dat_compro['res_comEgre_consecutivo']."--".$dat_query['cen_cos_resolucion'];
        }
        else
          return false;
      }
      else
        return false;
   }

   public function doc_act_comprobante($compro)
   {
      $sql="UPDATE resoluciones SET res_comEgre_consecutivo=res_comEgre_consecutivo+1";
      $query=mssql_query($sql);
      if($query)
        return true;
      else
        return false;
   }
}
?>