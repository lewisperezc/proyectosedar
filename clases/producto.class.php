<?php
@include_once('../conexion/conexion.php');
include_once('tipo_producto.class.php');

class producto
{
  private $pro_nombre;
  private $pro_descripcion;
  private $pro_tip_producto;
  
  public function __construct()
  {
     $this->pro_tip_producto = new tipo_producto();
  }   
  public function setPro_nombre($pro_nombre)
   {
	$this->pro_nombre = $pro_nombre;
   }
   
  public function getPro_nombre()
   {
	return $this->pro_nombre;
   }
   
  public function setPro_descripcion($pro_descripcion)
   {
	$this->pro_descripcion = $pro_descripcion;
   }
   
  public function getPro_descripcion()
   {
	return $this->pro_descripcion;
   }
 //consulta todos los productos
 public function todosProductos()
	{
	 $sql="SELECT pro_id,pro_nombre FROM productos ORDER BY pro_nombre ASC";
	 $ejecutar = mssql_query($sql);
	 return $ejecutar;
	}
 //cosulta todos los productos segun el tipo recibe como parametro el tipo de producto 
 public function productos($tipo)
	{
	 $sql="SELECT pro_id,pro_nombre FROM productos WHERE tip_pro_id = $tipo";
	 $ejecutar = mssql_query($sql);
	 return $ejecutar;
	}

  public function guaProducto($producto,$ord_com,$cant,$val_uni,$ref,$descrip,$iva,$retencion)
  {
    if($retencion=="")
	   $retencion = 0;
	$sql="INSERT INTO prod_por_ord_compra(pro_id,ord_com_id,cantidad_producto,valor_unitario,referencia,descripcion,iva,retencion)
	VALUES ($producto,$ord_com,$cant,$val_uni,'$ref','$descrip',$iva,$retencion)";
	$ejecutar = mssql_query($sql);
	if($ejecutar)
	  return true;
	else
	  return false;
  }	
  
  public function tipo_producto()
  {  
    return $this->pro_tip_producto->cons_tipo_producto();
  }
  //crear un producto en la base de datos, recibe como parametro(el tipo,descripcion del producto,iva)
  public function crearProducto($tipo,$producto,$iva,$retencion)
  {
	$sql="INSERT INTO productos VALUES($tipo,'$producto',$iva,$retencion)";
	$ejecutar=mssql_query($sql);
	if($ejecutar)
	  return $ejecutar;
	else
	  return false;
  }

  public function cons_desc_articulo($id_prod)
  {
	  $sql="SELECT * FROM PRODUCTOS WHERE PRO_ID = $id_prod";
	  $ejecutar=mssql_query($sql);
	  return $ejecutar;  
  }
  
  public function editarProducto($nombre,$iva,$retencion,$tip_producto,$pro_id)
  { 
	$sql="UPDATE productos SET pro_nombre='$nombre',pro_iva='$iva',pro_retencion='$retencion',tip_pro_id=$tip_producto WHERE pro_id=$pro_id";
	//echo $sql;
	$ejecutar=mssql_query($sql);
	if($ejecutar)
		return $ejecutar;
	else
		return false;
  }
  
  public function busConcepto($tip_pro)
  {
	  $sql = "SELECT con_id FROM tipo_producto WHERE tip_pro_id = $tip_pro";
	  $query = mssql_query($sql);
	  if($query)
	     {
			 $ret = mssql_fetch_array($query);
			 return $ret['con_id'];
		 }
	  else
	    return false;	 
  }
  public function ivaProducto($producto)
  {
	  $sql = "SELECT pro_iva FROM productos WHERE pro_id = $producto";
	  $query = mssql_query($sql);
	  if($query)
	  {
	     $dat_iva = mssql_fetch_array($query);
		 return $dat_iva['pro_iva'];
	  }
	  else
	    return false;	 
  }
  
  public function reteProducto($producto,$nit)
  {
	  $sql = "SELECT reg_id FROM nits WHERE nit_id=$nit";
	  $query = mssql_query($sql);
	  if($query)
	   {
		  $datos = mssql_fetch_array($query);
		  if($datos['reg_id']!=3)
		  {
			  $query = "SELECT cue_porcentage,cue_id
			  FROM cuentas cue INNER JOIN productos pro ON cue.cue_id = pro.pro_retencion
	          WHERE pro.pro_id = $producto";
			  $ejecutar = mssql_query($query);
			  if($ejecutar)
			  {
				 $dat_iva = mssql_fetch_array($ejecutar);
				 return $dat_iva['cue_porcentage']."-".$dat_iva['cue_id'];
			  }
			  else
				return false;	
		  }
		  else
		    return 0;
	   } 
  }
  
  public function ConDatProPorId($elid)
  {
	  $query="SELECT *
			  FROM productos p
			  INNER JOIN tipo_producto tp ON p.tip_pro_id=tp.tip_pro_id
			  WHERE pro_id=$elid";
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   {
		$resultado=mssql_fetch_array($ejecutar);
	   	return $resultado;
	   }
	   else
	   	return false;
  }
}
?>