<?php
class tipo_comprobantes
{
  private $com_tipo;
  public function crear_tipComprobante($tipo,$codigo)
   {
	  $sql = "INSERT INTO tipo_comprobante(tip_com_nombre,tip_com_codigo) VALUES ('$tipo',$codigo)";
    $execut = mssql_query($sql);
    if($execut)
	   return true;
	else
	   return false;   
   }
   public function ConTipComprobante($notrae)
   {
	  $query="SELECT * FROM tipo_comprobante WHERE tip_com_id NOT IN($notrae) AND tip_com_sigla IS NOT NULL ORDER BY tip_com_nombre";
    $ejecutar = mssql_query($query);
    if($ejecutar)
	   return $ejecutar;
	else
	   return false;   
   }

  public function ConsultarConsecutivoDigital($tip_com_id)
  {
    $query="SELECT * FROM tipo_comprobante WHERE tip_com_id='$tip_com_id'";
    $ejecutar = mssql_query($query);
    if($ejecutar)
    {
      $res_dat_tip_comprobante=mssql_fetch_array($ejecutar);
      return $res_dat_tip_comprobante;
    }
    else
      return false;   
  }

  public function ActualizarConsecutivoDigital($tip_com_id)
  {
    $query="UPDATE tipo_comprobante SET tip_com_con_digital=(tip_com_con_digital+1) WHERE tip_com_id='$tip_com_id'";
    $ejecutar = mssql_query($query);
    if($ejecutar)
      return $ejecutar;
    else
      return false;   
  }

}
?>