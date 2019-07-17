<?php
@include_once('conexion/conexion.php');
@include_once('../conexion/conexion.php');
include_once('ciudades.class.php');
@include_once('../inicializar_session.php');
@include_once('inicializar_session.php');

class centro_de_costos
{
  private $cen_cos_id;
  private $cen_cos_nombre;
  private $cen_ciu_codigo;
  private $cen_dep_codigo;

  public function __construct()
    {
      $this->cen_ciu_codigo = new ciudades();
    }
	
  public function buscar_ciudades()
   {
	return $this->cen_ciu_codigo->consultar_ciudades();
   }

   public function cons_centro_costos()
    {
	 $sql="SELECT * FROM centros_costo WHERE cen_cos_nit IS NOT NULL";
	 $cen_cos=mssql_query($sql);
	 if($cen_cos)
	 	return $cen_cos;
	 else
	 	return false;
    }
	
	public function cons_centro_costos_ciudad()
    {
	 $sql="SELECT * FROM centros_costo WHERE cen_cos_nit IS NULL";
	 $cen_cos=mssql_query($sql);
	 if($cen_cos)
	 	return $cen_cos;
	 else
	 	return false;
    }
	
	public function con_cen_cos_es_nit()
	{
            $query = "SELECT *
                      FROM dbo.centros_costo
                      WHERE cen_cos_nit IS NOT NULL
                      ORDER BY cen_cos_nombre ASC";
            $ejecutar=mssql_query($query);
            return $ejecutar;
	}
	
	public function cen_cos_sec()
	{
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$sql = "SELECT * FROM centros_costo cc WHERE cc.per_cen_cos IS NOT NULL AND (cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.")) ORDER BY cen_cos_nombre ASC";
		}
		else
		{
			$sql = "SELECT * FROM centros_costo WHERE per_cen_cos IS NOT NULL ORDER BY cen_cos_nombre ASC";
		}
		$query = mssql_query($sql);
		if($query)
			return $query;
		else
			return false;
	}
	//**
	public function con_cen_por_con_estado($estado)
	{
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			//NO TIENE PRINCIPAL
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$sql = "SELECT DISTINCT cc.cen_cos_id,cen_cos_codigo,cen_cos_nombre
                                FROM contrato c
				INNER JOIN nits n ON c.nit_id=n.nit_id
				INNER JOIN centros_costo cc ON n.nit_id=cc.cen_cos_nit
				WHERE(cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.")) AND per_cen_cos IS NOT NULL AND c.est_con_id=$estado ORDER BY cen_cos_nombre ASC";
                        //echo $sql;
		}
		else
	        {
                    //echo "entra por el else";
                    $sql="SELECT DISTINCT cc.cen_cos_id,cen_cos_codigo,cen_cos_nombre
                            FROM contrato c
                            INNER JOIN nits n ON c.nit_id=n.nit_id
                            INNER JOIN centros_costo cc ON n.nit_id=cc.cen_cos_nit
                            WHERE per_cen_cos IS NOT NULL AND c.est_con_id=$estado ORDER BY cen_cos_nombre ASC";
		}
		$query = mssql_query($sql);
		if($query)
                    return $query;
		else
                    return false;
	}

	public function cen_cos_contrato()
	{
		$sql = "SELECT DISTINCT cc.cen_cos_nit,cc.cen_cos_id cc_id, cc.cen_cos_nombre cc_nombre
                FROM centros_costo cc
                INNER JOIN contrato con on con.nit_id = cc.cen_cos_nit
                ORDER BY cen_cos_nombre ASC";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
	///////
	public function cen_cos_con($nit_id)
	{
		$sql = "SELECT DISTINCT cc.cen_cos_id cc_id, cc.cen_cos_nombre cc_nombre FROM centros_costo cc
                INNER JOIN contrato con on con.nit_id = cc.cen_cos_nit
                WHERE cc.cen_cos_id NOT IN(SELECT cen_cos_id FROM dbo.nits_por_cen_costo WHERE nit_id = $nit_id)
				ORDER BY cc.cen_cos_nombre ASC";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	////////////
	
	public function cen_cos_prin()//CIUDADES
	{
		$sql = "SELECT * FROM centros_costo WHERE per_cen_cos IS NULL";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
		
	public function buscar_cen_costo($centro_costo)
	{
	 $sqlBusCen = "SELECT COUNT(*) FROM centros_costo where cen_cos_id = $centro_costo";
	 $conBusCen = mssql_query($sqlBusCen);
	 $row = mssql_num_rows($conBusCen);
	 if($row > 0)
	    return true;
	 else
	   return false;
	}
	
	public function buscar_centros($centro)
	{
	 $sqlBusCiu = "SELECT * FROM centros_costo inner join ciudades on ciu_id=ciud_ciu_id where cen_cos_id = $centro";
	 $conBusCiu = mssql_query($sqlBusCiu);
	 return $conBusCiu;
	}
     
	/*Guarda el centro de costo principal*/	
	public function guardarCentroCiudad($nom,$ciu,$cod,$usu)
	{
	 $sqlGuaCen = "INSERT INTO centros_costo(ciud_ciu_id,cen_cos_nombre,cen_cos_codigo,cen_cos_responsable)
	               VALUES ('$ciu','$nom','$cod','$usu')";
	 $conGuaCen = mssql_query($sqlGuaCen);
	 if($conGuaCen)
	    return true;
	 else
	   return false;
	}
	
	public function guardar_cenSecun($nom,$cod,$ppal,$usu)
	{
		$sql = "INSERT INTO centros_costo(cen_cos_nombre,cen_cos_codigo,per_cen_cos,cen_cos_responsable)
		        VALUES ('$nom',$cod,$ppal,$usu)";
		$conGuaCen = mssql_query($sql);
	    if($conGuaCen)
	      return true;
	    else
	      return false;
	}
	
	public function buscar_centroCostoPrin($ciu,$cod)
	{
	 $sqlBusCiu = "SELECT COUNT(*) FROM centros_costo WHERE ciud_ciu_id = $ciu AND cen_cos_codigo = '$cod'";
	 $conGuaCen = mssql_query($sqlBusCiu);
	 if(sizeof($row) > 0 )
	    return true;
	 else
	   return false;
	}
	
	public function buscar_centroCostoSec($cod)
	{
	 $sqlBusCiu = "SELECT COUNT(*) FROM centros_costo WHERE cen_cos_codigo = $cod";
	 $conGuaCen = mssql_query($sqlBusCiu);
	 if(sizeof($row) > 0 )
	    return true;
	 else
	   return false;
	}
	
	public function modificar_centro($nom,$ciu,$cod,$centro)
	{
	   $sqlBusCiu = "UPDATE centros_costo SET ciud_ciu_id='$ciu',cen_cos_nombre='$nom',cen_cos_codigo=$cod WHERE cen_cos_id = $centro";
	   $conGuaCen = mssql_query($sqlBusCiu);
	   if($conGuaCen)
	      return true;
	   else
		  return false;
	}
	
	public function conultar_centro_costos()
    {
	  $query = "SELECT cen_cos_id,cen_cos_nombre FROM centros_costo";
	  $ejecutar = mssql_query($query);
	  return $ejecutar;
    }
	
	public function buscar_asociados($cen_cos)
	{
		$sql = "SELECT npcc.nit_id nit_id,npcc.id_nit_por_cen cen_nit_id,nit.nits_nombres nombres,
		        nit.nits_apellidos apellidos,nit.nits_num_documento doc,nit.nit_est_id estado FROM nits_por_cen_costo npcc INNER JOIN nits nit on nit.nit_id=npcc.nit_id WHERE cen_cos_id = $cen_cos AND nit.tip_nit_id = 1 AND nit_est_id IN(1,3) ORDER BY nit.nits_apellidos";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		   return false;
	}
	
	public function bus_cenCosto($nit_cen)
	{
		$sql = "SELECT cen_cos_id FROM nits_por_cen_costo WHERE id_nit_por_cen = $nit_cen";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		 return false;
	}
	
	public function nit_cenCosto($cen_cos)
	{
		$sql = "SELECT cen_cos_nit FROM centros_costo WHERE cen_cos_id = $cen_cos";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		 return false;
	}
	
	public function datos_nitCen($cen_cos_id,$tipo_nit)
	{
		$query = "SELECT nit.nit_id,nit.nits_nombres,nit.nits_apellidos, nit.nits_num_documento, nit.nits_tel_residencia,
		                 nit.nits_dir_residencia FROM dbo.nits nit
                  INNER JOIN dbo.nits_por_cen_costo npcc ON nit.nit_id = npcc.nit_id INNER JOIN dbo.centros_costo cc
                  ON cc.cen_cos_id = npcc.cen_cos_id WHERE cc.cen_cos_id = $cen_cos_id
                  AND tip_nit_id IN($tipo_nit)
                  order by nits_apellidos";
       //echo $query."<br>";
	   $ejecutar = mssql_query($query);
	   return $ejecutar;
	}
	
	
	public function datos_centro_por_id($cen_cos_id)
	{
		$query = "SELECT * FROM dbo.centros_costo cc WHERE cc.cen_cos_id = $cen_cos_id";
       //echo $query."<br>";
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   {
	   		$res_datos=mssql_fetch_array($ejecutar);
	   		return $res_datos;
	   }
	   else
	   		return false;
	}
	//INICIO TRAER LOS ASOCIADOS QUE PERTENECEN A EL CENTRO DE COSTOS QUE SELECCIONEN
	
	public function cenCos_nit($cenCos_nit)
	{
		 $sql = "SELECT nit_id, cen_cos_id FROM nits_por_cen_costo WHERE id_nit_por_cen = $cenCos_nit";
		 $query = mssql_query($sql);
		 if($query)
		 {
		   $row = mssql_fetch_array($query);
		   $cen_nit[0] = $row['nit_id'];
		   $cen_nit[1] = $row['cen_cos_id'];
		   return $cen_nit;
		 }
		 else
		   return false;
	}
	
    public function nucleo()
	{
		$sql = "SELECT * FROM centros_costo WHERE cen_cos_nucleo = 1";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
	public function centro_costo_ciudad($ciudad)
	{
		$sql="SELECT * FROM centros_costo WHERE ciud_ciu_id=$ciudad";
		$ejecutar=mssql_query($sql);
		return $ejecutar;
	}
	
	public function con_cen_cos_credito($id_asociado,$id_empleado)
	{
		$query = "EXECUTE ConCenCosPriAsociado $id_asociado,$id_empleado";
                //echo $query;
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_cen_cos_credito2($per_cen_cos)
	{
		$query = "EXECUTE ConCenCosPriAsociado2 $per_cen_cos";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
    public function hos_sin_centro()
    {
	   $sql = "SELECT nit.nit_id id,nit.nits_nombres nombre FROM nits nit
			   WHERE nit.tip_nit_id = 8 AND nit.nit_id not in(select cen_cos_nit from centros_costo)";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return $false;	
    }
	
	public function cue_cobrar_cc($centro)
	{
		$sql = "SELECT th.tip_hos_cue_pagar cue_pagar FROM tipo_hospital th
		INNER JOIN nits nit ON th.tip_hos_id = nit.tip_hospital
		INNER JOIN centros_costo cc ON cc.cen_cos_nit = nit.nit_id WHERE cc.cen_cos_id = $centro";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		  {
			  $cuenta = mssql_fetch_array($query);
			  return $cuenta["cue_pagar"];
		  }
		else
		  return false;  
	}
	
	public function con_cen_cos_nit($nit_id)
	{
		$query = "SELECT cc.cen_cos_id,cc.cen_cos_nombre FROM dbo.centros_costo cc INNER JOIN dbo.nits_por_cen_costo npcc
				  ON cc.cen_cos_id = npcc.cen_cos_id WHERE npcc.nit_id = $nit_id";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		  return $ejecutar;
		else
		  return false;
	}
	
	public function con_cen_cos_pabs($cen_cos_id)
	{
		$query = "SELECT cen_cos_id,cen_cos_nombre,ciud_ciu_id FROM centros_costo WHERE cen_cos_id = $cen_cos_id";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	public function con_responsable_cen_cos($cen_cos_id)
	{
		$query = "SELECT cen_cos_responsable FROM centros_costo WHERE cen_cos_id = $cen_cos_id";
		$ejecutar = mssql_query($query);
		if($ejecutar){
			$res_cen_cos = mssql_fetch_array($ejecutar);
			return $res_cen_cos['cen_cos_responsable'];
		}
		else
		return false;
	}
	
	public function con_cen_cos_ord_por_hospital()
	{
		$query="SELECT * FROM centros_costo ORDER BY cen_cos_nit DESC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
		
	}
	
	public function con_cen_cos_por_id($nit_id)
	{
		$query="SELECT cen_cos_id
				FROM nits_por_cen_costo
				WHERE nit_id=$nit_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_ciu_por_cen_cos_id($cen_cos_id)
	{
		$query="SELECT ciud_ciu_id
				FROM centros_costo
				WHERE cen_cos_id=$cen_cos_id";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$res_ciudad=mssql_fetch_array($ejecutar);
			return $res_ciudad['ciud_ciu_id'];
		}
		else
			return false;
	}
	
	public function con_cen_por_usuario()
	{
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			//NO TIENE PRINCIPAL
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$sql = "SELECT DISTINCT cc.cen_cos_id,cc.cen_cos_codigo,cc.cen_cos_nombre
                                FROM centros_costo cc
				WHERE(cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.")) AND cen_cos_resolucion IS NOT NULL ORDER BY cen_cos_nombre ASC";
		}
		else
	    {
			$sql = "SELECT DISTINCT cc.cen_cos_id,cc.cen_cos_codigo,cc.cen_cos_nombre
                                FROM centros_costo cc
                                WHERE cen_cos_resolucion IS NOT NULL
                                ORDER BY cen_cos_nombre ASC";
		}
			$query = mssql_query($sql);
			if($query)
		  		return $query;
			else
		  		return false;
	}
	
	public function ConsultarDatosCentroCostoPorId($cen_cos_id)
	{
		$query="SELECT *
				FROM centros_costo
				WHERE cen_cos_id=$cen_cos_id";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
}
?>