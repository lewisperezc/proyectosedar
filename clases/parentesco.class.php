<?php
class parentesco{
  
  public function con_tod_parentescos()
   {
	$query="SELECT * FROM parentescos ORDER BY par_nombres ASC";
	$ejecutar=mssql_query($query);
	if($ejecutar)
	return $ejecutar;
	else
	return false;
  }
}
?>