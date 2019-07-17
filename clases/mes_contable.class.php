<?php
@include_once("../conexion/conexion.php");
@include_once("conexion/conexion.php");

class mes_contable
 {
   public function mes()
   {
	 $sql = "SELECT * FROM mes_contable";
	 $query = mssql_query($sql);
	 if($query)
	   return $query;
	 else
	   return false;  
   }
   
   public function actMes($estado,$id)
   {
	   $sql = "UPDATE mes_por_ano_contable SET est_mes_por_ano_con_id = $estado WHERE mes_por_ano_con_id = $id";
	   $query = mssql_query($sql);
	   if($query)
	     return true;
	   else
	     return false;	 
   }
   
    public function nomMes($mes)
    {
    	//echo "entra a la funcion";
        $sql="SELECT mes_nombre FROM mes_contable WHERE mes_id='$mes'";
        //echo $sql."<br>";
		$query = mssql_query($sql);
		if($query)
		{
	            $dat_mes = mssql_fetch_array($query);
	            return $dat_mes['mes_nombre'];
		}
    }
   
    public function periodo($factura)
    {
        $sql = "SELECT mes_nombre FROM factura INNER JOIN mes_contable ON fac_mes_servicio=mes_id WHERE fac_consecutivo = '$factura'";
		$query = mssql_query($sql);
		if($query)
		{
	            $dat_query = mssql_fetch_array($query);
	            return $dat_query['mes_nombre'];
		}
    }

    public function get_anos()
    {
    	$sql = "SELECT ano_con_id,ano_con_estado,CASE ano_con_estado
				WHEN 1 THEN 'Cerrado' 
				WHEN 2 THEN 'Abierto' END AS estado
				FROM ano_contable ORDER BY ano_con_id DESC";
    	$query = mssql_query($sql);
    	if($query)
    		return $query;
    	else 
    		return false;
    }
	
	public function ObtenerAniosPorEstado($estado)
    {
    	$sql = "SELECT * FROM ano_contable WHERE ano_con_estado IN($estado)";
    	$query = mssql_query($sql);
    	if($query)
    		return $query;
    	else 
    		return false;
    }

    public function actAno($estado,$ano)
    {
    	$sql = "UPDATE ano_contable SET ano_con_estado=$estado WHERE ano_con_id=$ano";
    	$query = mssql_query($sql);
    	if($query)
    		return true;
    	else
    		return false;
    }

    public function cre_ano($estado)
    {
    	$query_1="SELECT MAX(ano_con_id) ano_con_id FROM ano_contable";
		$ejecutar_1=mssql_query($query_1);
		$res_anio=mssql_fetch_array($ejecutar_1);
		$nuevo_anio=$res_anio['ano_con_id']+1;
    	$sql="INSERT INTO ano_contable(ano_con_id,ano_con_estado) VALUES($nuevo_anio,$estado)";
    	$query=mssql_query($sql);
    	if($query)
    	{
    		$meses=1;
			while($meses<=13)
			{
				$query_4="INSERT INTO mes_por_ano_contable(mes_id,ano_con_id,est_mes_por_ano_con_id)
				VALUES($meses,$nuevo_anio,1)";
				$ejecutar_4=mssql_query($query_4);
				$meses++;
			}
    	}
    	else
    		return false;
    }

    public function conAno($ano)
    {
        $sql="SELECT ano_con_estado FROM ano_contable WHERE ano_con_id=$ano";
        $query=mssql_query($sql);
        if($query)
        {
            $dat_query = mssql_fetch_array($query);
            return $dat_query['ano_con_estado'];
        }
        else
            return false;
    }

    public function validarPeriodo($mes,$ano)
    {
    	
    	$sql="SELECT a".$ano."a".$mes." AS periodo FROM tipo_comprobante where tip_com_id=61 ";
		//echo $sql; 
        $query=mssql_query($sql);
        if($query)
        {
            $dat_query = mssql_fetch_array($query);
            return $dat_query['periodo'];
        }
        else
            return false;
    }
	
	public function DatosMesesAniosContables($anio_trabajo)
    {
    	
    	$sql="SELECT mpac.mes_por_ano_con_id,mpac.ano_con_id,mpac.est_mes_por_ano_con_id AS mes_estado,
		mc.mes_id,mc.mes_nombre FROM mes_por_ano_contable mpac
    	INNER JOIN mes_contable mc ON mpac.mes_id=mc.mes_id WHERE ano_con_id=$anio_trabajo";
        $query=mssql_query($sql);
        if($query)
            return $query;
        else
            return false;
    }
	
	public function DatosMesesAniosContablesPorAnioMes($anio_trabajo,$mes_trabajo)
    {
    	
    	$sql="SELECT mpac.mes_por_ano_con_id,mpac.ano_con_id,mpac.est_mes_por_ano_con_id AS mes_estado,
		mc.mes_id,mc.mes_nombre FROM mes_por_ano_contable mpac
    	INNER JOIN mes_contable mc ON mpac.mes_id=mc.mes_id
    	WHERE mpac.ano_con_id=$anio_trabajo AND mpac.mes_id=$mes_trabajo";
		//echo $sql."<br>";
        $query=mssql_query($sql);
        if($query)
            return $query;
        else
            return false;
    }
	
	public function ConsultarEstadoPorMesAno($mes_trabajo,$anio_trabajo)
    {
    	
    	$query="select * from mes_por_ano_contable WHERE mes_id='$mes_trabajo' AND ano_con_id='$anio_trabajo'";
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
	
	
	
}
?>