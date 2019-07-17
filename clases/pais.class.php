<?php
class pais
{
  private $pai_codigo;
  private $pai_nombre;
  
  public function getPai_codigo()
  {
    return $this->pai_codigo;
  }
  
  public function getPai_nombre()
  {
    return $this->pai_nombre;
  } 
  
  public function paises()
  {
	  $sql = "SELECT * FROM paises";
	  $query = mssql_query($sql);
	  if($query)
	    return $query;
	  else
	    return false;	
  }
} 
?>