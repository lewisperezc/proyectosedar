<?php
@include_once('../conexion/conexion.php');
class beneficiario
{
	public function ins_beneficiario($aso_num_doc_beneficiario,$aso_ape_beneficiario,$aso_nom_beneficiario,$aso_tip_doc_beneficiario,$aso_por_ben_beneficiario)
	{
		$query = "INSERT INTO beneficiarios(ben_num_identificacion,ben_apellidos, ben_nombres, tip_identificacion,
		          ben_por_beneficios) VALUES($aso_num_doc_beneficiario,'$aso_ape_beneficiario','$aso_nom_beneficiario',
				  $aso_tip_doc_beneficiario,$aso_por_ben_beneficiario)";
	    $ejecutar = mssql_query($query);
	    if($ejecutar)
		   return true;
		else
			return false;
	}
	
	public function sel_max_id_beneficiario()
	{
		$query = "SELECT MAX(ben_id) max_ben_id FROM beneficiarios";
		$ejecutar = mssql_query($query);
	    return $ejecutar;
	}
	
	public function ins_ben_por_asociado_1($aso_id,$ben_id)
    {
	  $query = "INSERT INTO nits_por_beneficiarios(nit_id,ben_num_identificacion) VALUES($aso_id,$ben_id)";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return true;
	  else 
		  return false;
    }
}
?>