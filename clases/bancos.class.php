<?php
class bancos{
private $codigo;
private $banco;

  public function cons_bancos()
   {
	$sql="SELECT * FROM bancos ORDER BY banco ASC";
	$ejecutar=mssql_query($sql);
	if($ejecutar)
		return $ejecutar;
	else
		return false;
  }
  
  public function datBancos($banco)
  {
   	$sql = "SELECT * FROM bancos WHERE cod_banco = $banco";
	$ejecutar=mssql_query($sql);
	return $ejecutar;
  }
}
?>