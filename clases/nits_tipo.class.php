<?php
@include_once('../inicializar_session.php');
@include_once('inicializar_session.php');
@include_once('../conexion/conexion.php');
class tipo_nit
{
	public function con_tod_tip_nits()
	{
 		$query = "SELECT * FROM nits_tipos";
 		$ejecutar = mssql_query($query);
		if($ejecutar)
 			return $ejecutar;
		else
			return false;
	}
	public function con_tip_nit($id_tip_nit)
        {
            $principal="1169,";
            $lacadena=$_SESSION['k_cen_costo'];
            $comparacion=strpos($lacadena,$principal);
            if($comparacion===false)
            {
                $loscentros=substr($_SESSION['k_cen_costo'],0,-1);
  	     		$query = "SELECT DISTINCT n.nit_id,n.nits_nombres,n.nits_apellidos,n.nits_num_documento
                          FROM nits n
                          INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
			  INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
         		  WHERE tip_nit_id IN($id_tip_nit) AND (cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.")) ORDER BY nits_nombres ASC";
            }
            else
            {
                $query="SELECT n.nit_id,n.nits_nombres,n.nits_apellidos,n.nits_num_documento
                          FROM nits n
                          WHERE tip_nit_id IN($id_tip_nit) ORDER BY nits_nombres ASC";
            }
			//echo $query;
            $ejecutar=mssql_query($query);
            if($ejecutar)
                return $ejecutar;
            else
                return false;
         }
	
	public function con_tip_nit_eps($id_tip_nit,$nit_id)
        {
		 $principal="1169,";
		 $lacadena=$_SESSION['k_cen_costo'];
		 $comparacion=strpos($lacadena,$principal);
		 if($comparacion===false)
		 {
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
  	     	$query="SELECT DISTINCT n.nit_id,n.nits_nombres,n.nits_apellidos,n.nits_num_documento
         	   		  FROM nits n
					  INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
				      INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
         		      WHERE (tip_nit_id IN($id_tip_nit) OR n.nit_id IN($nit_id)) AND (cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros."))";
         }
		 else
		 {
			$query="SELECT n.nit_id,n.nits_nombres,n.nits_apellidos,n.nits_num_documento
         	   		  FROM nits n
         		      WHERE tip_nit_id IN($id_tip_nit) OR n.nit_id IN($nit_id)";
		 }
		 //echo $query;
		 $ejecutar = mssql_query($query);
	     return $ejecutar;
	}
}
?>