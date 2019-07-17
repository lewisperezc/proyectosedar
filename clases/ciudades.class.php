<?php
@include_once('../conexion/conexion.php');
@include_once('conexion/conexion.php');
include_once('departamento.class.php');
class ciudades
{
  private $ciu_codigo;
  private $ciu_nombre;
  private $depto_dep_codigo;
  
  public function __construct()
  {
    $this->depto_dep_codigo = new departamento();
  }
  
  public function consultar_ciudades()
   {
	$sql="select * from ciudades ORDER BY ciu_nombre ASC";
    $execut= mssql_query($sql);
    return $execut;
   }  
   public function con_ciu_por_departamento($dep_id)
   {
       $query="SELECT * FROM ciudades WHERE depa_dep_id=$dep_id ORDER BY ciu_nombre ASC";
       //echo $query;
       $ejecutar=mssql_query($query);
       if($ejecutar)
        return $ejecutar;
       else
        return false;
   }
}
?>