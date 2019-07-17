<?php
@include_once('../conexion/conexion.php');

class linea_pabs
{
  public function gua_lin_pabs($lin_pab_nombres,$lin_pab_porcentaje)
   {
	$query="INSERT INTO lineas_pabs VALUES('$lin_pab_nombres',$lin_pab_porcentaje)";
    $ejecutar = mssql_query($query);
    if($ejecutar){
		echo "<script>alert('Linea(s) De PABS Creada(s) Correctamente!!!');location.href = '../index.php?c=111';</script>";
	  return $ejecutar;
	}
	else
	  return false; 
   }
}
?>