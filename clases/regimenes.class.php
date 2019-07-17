<?php
@include_once('../conexion/conexion.php');
class regimenes
{
	private $regimen;
//METODO QUE PERMITE CONSULTAR LOS REGIMENES	
 public function cons_regimen()
  {
	$sql= "SELECT * FROM regimenes";
	$ejecutar= mssql_query($sql);
	return $ejecutar;
  }
  
  public function afec_impuesto($tipo)
  {
	  $sql = "SELECT impuestos FROM regimenes WHERE reg_id=$tipo";
	  $query = mssql_query($sql);
	  if($query)
	   {
		   $dat_regimenes = mssql_fetch_array($query);
		   return $dat_regimenes['impuestos'];
	   }
	  else
	    return false; 
  }
}
?>