<?php
@include_once('../conexion/conexion.php');
include_once('tipo_comprobante.class.php');

class nota
{
  public function guardar_nota($desc,$fac,$tip_comp,$monto,$sigla,$mes_contable,$ano_contable)
   {
	 $fecha = date('d-m-Y');
	 $sql="INSERT INTO notas VALUES ('$fecha','$desc','$fac','$tip_comp','$monto','$sigla','$mes_contable','$ano_contable')";
	 //echo $sql;
	 $execut = mssql_query($sql);
	 if($execut)
	   return true;
	 else  
       return false;
   }
   
  public function ultNota()
  {
	  $sql = "SELECT MAX(not_id) max FROM notas";
	  $query = mssql_query($sql);
	  if($query)
	    {
			$ult = mssql_fetch_array($query);
			return $ult['max'];
		}
	  else
	    return false;	
  }
}
?>