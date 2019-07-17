<?php
class estudios
{
	public function ins_estudios($aso_id,$aso_uni_pregrado,$aso_fec_pregrado,$aso_tit_gra_obtenido,$aso_ciu_pregrado,
                                 $aso_uni_posgrado,$aso_fec_posgrado,$aso_tit_pos_obtenido,$aso_ciu_posgrado,
                                 $aso_uni_otros,$aso_fec_otros,$aso_tit_otr_obtenido,$aso_ciu_otr_obtenido)
	{
		$query = "insert into estudios(nit_id,est_nom_uni_pregrado,est_fec_pregrado,est_tit_obt_pregrado,est_ciu_pregrado,
                  est_nom_uni_posgrado,est_fec_posgrado,est_tit_obt_posgrado,est_ciu_posgrado,est_nom_uni_otros,
                  est_fec_otros,est_tit_obt_otros,est_ciu_otros) values($aso_id,'$aso_uni_pregrado','$aso_fec_pregrado','$aso_tit_gra_obtenido',$aso_ciu_pregrado,'$aso_uni_posgrado','$aso_fec_posgrado','$aso_tit_pos_obtenido',$aso_ciu_posgrado,'$aso_uni_otros','$aso_fec_otros','$aso_tit_otr_obtenido',$aso_ciu_otr_obtenido)";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  	return true;
	  else
		  return false;
	}
}
?>