<?php
include_once('pais.class.php');

class departamento
{
  private $dep_codigo;
  private $dep_nombre;
  private $pai_cod_pais;
  
  public function __construct()
  {
    $this->pai_cod_pais = new pais();
  }
  
  public function getDep_codigo()
  {
    return $this->dep_codigo;
  }
  
  public function getDep_nombre()
  {
    return $this->dep_nombre;
  }
  
  public function getPai_cod_pais()
  {
    return $this->pai_cod_pais;
  }
  
  public function buscar_departamentos()
  {
	$sqldepartamentos="SELECT * FROM departamentos";
	$condepartamentos= mssql_query($sqldepartamentos);
	return $condepartamentos;
  } 
}
?>