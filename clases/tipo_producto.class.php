<?php
include_once('cuenta.class.php');

class tipo_producto
{
  private $tip_pro_nombre;
  
  private $cue_id;
  public function __construct()
  {
	$this->cue_id = new cuenta();
  }
  
  public function con_cue_menores($cue_id){
	  return $this->cue_id->con_cue_menores($cue_id);
  }
  
  public function setTip_pro_nombre($tip_pro_nombre)
  {
     $this->tip_pro_nombre=$tip_pro_nombre;
  }
  public function geTip_pro_nombre()
  {
     return $this->tip_pro_nombre;
  }
  public function cons_tipo_producto()
  {
	$query = "SELECT * FROM tipo_producto";	
	$ejecutar = mssql_query($query);
	return $ejecutar;
  }
 
  public function crear_tipo_producto($tipo,$concep,$cuenta)
  {
	  $sql="INSERT INTO TIPO_PRODUCTO VALUES('$tipo',$cuenta,$concep)";
	  $ejecutar = mssql_query($sql);
	  return $ejecutar;
	  
  }
  public function cons_descripcion($descripcion)
  {
	 $sql = "SELECT *
			 FROM tipo_producto tp
			 INNER JOIN cuentas c ON tp.cue_id=c.cue_id
			 INNER JOIN conceptos co ON tp.concepto=co.con_id
			 WHERE tip_pro_id=$descripcion"; 
	 $ejecutar =mssql_query($sql);
	 return $ejecutar;
  }
  public function editarTipo($descripcion,$cuenta,$concepto,$id)
  {
	  $sql="UPDATE tipo_producto SET tip_pro_nombre='$descripcion',cue_id='$cuenta',concepto='$concepto' WHERE tip_pro_id=$id";
	  $ejecutar = mssql_query($sql);
	  if($ejecutar)
	  	return $ejecutar;
	  else
	  	return false;
  }
  public function cueTipo($tipo)
  {
	  $sql = "SELECT cue_id FROM tipo_producto WHERE tip_pro_id = $tipo";
	  $query = mssql_query($sql);
	  if($query)
	   {
		   $cuenta = mssql_fetch_array($query);
		   return $cuenta['cue_id'];
	   }
	  else
	   return false;	
  }
}
?>