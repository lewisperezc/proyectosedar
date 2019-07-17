<?php
@include_once('../conexion/conexion.php');
include_once('nits.class.php');
include_once('centro_de_costos.class.php');
include_once('producto.class.php');
include_once('recibo_caja.class.php');

class orden_compra
 { 
   private $orden_compra;
   private $fecha;
   private $nit;
   private $cen_cos;

   public function __construct()
   {
     $this->cen_cos = new centro_de_costos();
     $this->nit = new nits();
   }
   
   public function get_cenCos()
   {
     $getOrdCom = "SELECT MAX(ord_com_id) ord_com FROM ordenes_compra";
	 $guaOrdCom = mssql_query($getOrdCom);
	 $this->orden_compra = mssql_fetch_array($guaOrdCom);
	 return $this->orden_compra['ord_com']; 
   }
   
   public function guardar_ordCompra($nit,$cen_cos,$total,$iva,$rete,$ica,$sigla,$mes,$ano)
   {
	 $fecha = date('d-m-Y');
	 if($ica=="")
	    $ica = 0;
	 if($iva=="")
	 	$iva=0;
	 if($rete=="")
	 	$rete=0;

	$queOrdCom = "INSERT INTO ordenes_compra (est_ord_com_id,nit_id,cen_cos_id,ord_com_val_total,ord_com_fecha,ord_com_iva,ord_com_rete,ord_com_ica,ord_com_conse,ord_com_mes,ord_com_ano) 
				  VALUES (1,'$nit','$cen_cos',$total,'$fecha',$iva,$rete,$ica,'$sigla',$mes,$ano)";
	 $guaOrdCom = mssql_query($queOrdCom);
	 if($guaOrdCom)
	   return true;
	 else
	   return false;
   }
   
   public function bus_ProOrd($ord_com)
   {
     $queOrdCom = "SELECT * FROM prod_por_ord_compra ppoc INNER JOIN productos pro ON pro.pro_id = ppoc.pro_id
	  			   WHERE ord_com_id = $ord_com";
	 $guaOrdCom = mssql_query($queOrdCom);
	 if($guaOrdCom)
	   return $guaOrdCom;
	 else
	   return false;
   }
   
   public function bus_Ord($orden)
   {
     $queOrdCom = "SELECT * FROM ordenes_compra WHERE ord_com_id = $orden";
	 $guaOrdCom = mssql_query($queOrdCom);
	 if($guaOrdCom)
	   return $guaOrdCom;
	 else
	   return false;
   }
   
   public function bus_ordCen($centro)
   {
     $queOrdCom = "SELECT oc.ord_com_id orden_compra,oc.est_ord_com_id,ord_com_val_total,cc.cen_cos_nombre nombre,eoc.est_ord_com_nombre estado FROM ordenes_compra oc INNER JOIN centros_costo cc on cc.cen_cos_id = oc.cen_cos_id INNER JOIN estados_ord_compra eoc on eoc.est_ord_com_id = oc.est_ord_com_id WHERE oc.cen_cos_id = $centro";
	 $guaOrdCom = mssql_query($queOrdCom);
	 if($guaOrdCom)
	   return $guaOrdCom;
	 else
	   return false;
   }
   
   public function bus_ordPro($pro)
   {
     $queOrdCom = "SELECT oc.ord_com_id orden_compra,oc.est_ord_com_id,ord_com_val_total,cc.cen_cos_nombre nombre, eoc.est_ord_com_nombre estado FROM ordenes_compra oc INNER JOIN centros_costo cc on cc.cen_cos_id = oc.cen_cos_id
			   INNER JOIN estados_ord_compra eoc on eoc.est_ord_com_id = oc.est_ord_com_id WHERE oc.nit_id = $pro";
	 $guaOrdCom = mssql_query($queOrdCom);
	 if($guaOrdCom)
	   return $guaOrdCom;
	 else
	   return false;
   }
   
   public function act_ordCom($ord_com,$estado)
    {
	  $query = "UPDATE ordenes_compra SET est_ord_com_id = $estado WHERE ord_com_id = $ord_com";
	  $eje = mssql_query($query);
	  if($eje)
	    return true;
	  else
	   return false;
	}
	
   public function obt_consecutivo()
     {
	   $sql = "SELECT MAX(ord_com_id) max FROM ordenes_compra";
	   $query = mssql_query($sql);
	   if($query)
	   {
		   $datMax = mssql_fetch_array($query);
		   return $datMax['max'];
	   }
     }
}
?>