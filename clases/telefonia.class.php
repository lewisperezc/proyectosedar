<?php
@include_once('../conexion/conexion.php');
@include_once('recibo_caja.class.php');
@include_once('estado_registro_telefonia.class.php');

class telefonia
{
	private $recibo_caja;
	private $est_reg_telefonia;
	
    public function __construct()
    {
	   $this->recibo = new rec_caja();
	   $this->est_reg_telefonia = new estado_registro_telefonia();
    }
	
	public function con_tod_est_reg_telefonia(){
		return $this->est_reg_telefonia->con_tod_est_reg_telefonia();
	}
	
	public function obt_consecutivo()
    {
	  return $this->recibo->obt_consecutivo(19);
    }
   
   public function act_consecutivo()
   {
	 return $this->recibo->act_consecutivo(19);
   }
   
   public function gua_lin_telefonia($lin_tel_nombres,$nit_id,$est_lin_tel_id,$tip_lin_tel)
	{
		$fecha = date("d-m-Y");
		$query = "INSERT INTO lineas_telefonia(lin_tel_nombres,nit_id,lin_tel_fec_creacion,est_lin_tel_id,tip_lin_tel)
		          VALUES('$lin_tel_nombres',$nit_id,'$fecha',$est_lin_tel_id,$tip_lin_tel)";
		$ejecutar = mssql_query($query);
		if($ejecutar){
			$query2 = "SELECT MAX(lin_tel_id) ultimo FROM lineas_telefonia";
			$ejecutar2 = mssql_query($query2);
			$result = mssql_fetch_array($ejecutar2);
			return $result['ultimo'];
		}
		else
		return false;
	}
	
	public function gua_reg_telefonia($pla_tel_id,$lin_tel_id,$est_reg_tel_id){
		$query = "INSERT INTO lineas_telefonia_por_planes(pla_tel_id,lin_tel_id,est_reg_tel_id) VALUES($pla_tel_id,$lin_tel_id,$est_reg_tel_id)";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	//Consultar Los Nits Que Tienen Lineas Telefónicas Registradas
	public function con_nit_con_reg_telefonia($tip_nit,$est_nit)
	{
		$query = "SELECT DISTINCT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos nombres FROM nits nit
INNER JOIN dbo.lineas_telefonia lt ON nit.nit_id = lt.nit_id INNER JOIN dbo.lineas_telefonia_por_planes ltpp ON ltpp.lin_tel_id = lt.lin_tel_id WHERE nit.tip_nit_id = $tip_nit AND nit.nit_est_id = $est_nit";
	    $ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	/////////////////////////////////////////////////
	
	//Consultar Las Lineas Telefonicas Que Tiene Un Nit Seleccionado
	public function con_tod_lin_por_nit($nit_id){
		$query = "SELECT lin_tel_id,lin_tel_nombres FROM dbo.lineas_telefonia 
		WHERE nit_id = $nit_id";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	public function activas($nit_id,$tipo)
	{
	  $query = "SELECT lt.lin_tel_id,lin_tel_nombres FROM dbo.lineas_telefonia lt INNER JOIN lineas_telefonia_por_planes ltpp
	  ON lt.lin_tel_id = ltpp.lin_tel_id WHERE nit_id = $nit_id AND est_lin_tel_id = 1 AND lt.tip_lin_tel = $tipo" ;
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	/////////////////////////////////////////////////////////
	
	//Consultar Los Planes Que Tiene Registrados Una Liena Telefonica Seleccionada
	public function con_tod_pla_por_lin_telefonica($lin_tel_id){
		$query = "SELECT ltpp.lin_tel_por_pla_id,lt.lin_tel_fec_creacion,pt.pla_tel_nombre,pt.pla_tel_valor,
ert.est_reg_tel_id,ert.est_reg_tel_nombres,lt.tip_lin_tel
FROM lineas_telefonia lt
INNER JOIN lineas_telefonia_por_planes ltpp ON ltpp.lin_tel_id = lt.lin_tel_id
INNER JOIN planes_telefonia pt ON pt.pla_tel_id = ltpp.pla_tel_id
INNER JOIN estados_registros_telefonia ert ON ert.est_reg_tel_id = ltpp.est_reg_tel_id
WHERE ltpp.lin_tel_id = $lin_tel_id";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	/////////////////////////////////////////////////
	
	
	public function con_est_lin_tel_seleccionada($lin_tel_id){
		$query = "SELECT elt.est_lin_tel_id,elt.est_lin_tel_nombres
				  FROM estados_lineas_telefonicas elt
				  INNER JOIN lineas_telefonia lt ON lt.est_lin_tel_id = elt.est_lin_tel_id
				  WHERE lt.lin_tel_id = $lin_tel_id";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	//Cambiar El Estado De Un Plan De Telefonia Que Tenga Asignado Una Linea
	public function cam_est_reg_telefonia($nue_estado,$id)
	{
		$query = "UPDATE lineas_telefonia_por_planes SET est_reg_tel_id = $nue_estado WHERE lin_tel_por_pla_id = $id";
		$ejecutar = mssql_query($query);
		if($ejecutar){
		echo "<script>
				alert('El Estado Del Plan De Telefonia Se Modifico Correctamente!!!');
				//location.href = '../formularios/consultar_registro_servicio_telefonia_3.php';
	     </script>";
		return $ejecutar;
		}
		else{
			echo "<script>
				alert('Error Al Cambiar El Estado Del Plan De Telefonia, Intentelo De Nuevo!!!');
				//location.href = '../formularios/consultar_registro_servicio_telefonia_3.php';
	     </script>";
		return false;
		}
	}
	///////////////////////////////////////////////////////////
	
	//Cambiar El Estado De La Linea Telefonica
	public function cam_est_lin_telefonica($nue_estado,$linea_id)
	{
		$query = "UPDATE lineas_telefonia SET est_lin_tel_id = $nue_estado WHERE lin_tel_id = $linea_id";
		$ejecutar = mssql_query($query);
		if($ejecutar){
		echo "<script>
				alert('El Estado De La Linea Telefonica Se Modifico Correctamente!!!');
				//location.href = '../formularios/consultar_registro_servicio_telefonia_3.php';
	     </script>";
		return $ejecutar;
		}
		else{
			echo "<script>
				alert('Error Al Cambiar El Estado De La Linea Telefonica, Intentelo De Nuevo!!!');
				//location.href = '../formularios/consultar_registro_servicio_telefonia_3.php';
	     </script>";
		return false;
		}
	}
	////////////////////////////////////////////////////////
	
	
	//////////////////////////////FUNCIONES PARA CAUSAR EL SERVICIO DE TELEFONIA///////////////////////////
	
	public function con_nit_con_reg_tel_activos($tip_nit,$est_nit,$est_lin_telefonia)
	{
		$query = "SELECT DISTINCT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos nombres FROM nits nit
INNER JOIN dbo.lineas_telefonia lt ON nit.nit_id = lt.nit_id INNER JOIN dbo.lineas_telefonia_por_planes ltpp ON ltpp.lin_tel_id = lt.lin_tel_id WHERE nit.tip_nit_id = $tip_nit AND nit.nit_est_id = $est_nit AND lt.est_lin_tel_id = $est_lin_telefonia";
	    $ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	
	
	public function con_reg_tel_por_nit_id($nit_id,$est_linea){
		$query = "SELECT lin_tel_id,lin_tel_nombres FROM dbo.lineas_telefonia WHERE nit_id = $nit_id AND est_lin_tel_id = $est_linea";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	//Consultar El Proveedor Al Que Pertenece La Linea Telefónica
	public function con_pla_tel_proveedor($lin_tel_id){
		$query = "SELECT nit_id FROM planes_telefonia pt INNER JOIN lineas_telefonia_por_planes ltpp ON ltpp.pla_tel_id = pt.pla_tel_id
				  WHERE ltpp.lin_tel_id = $lin_tel_id";
		$ejecutar = mssql_query($query);
		if($ejecutar){
			$res_proveedor = mssql_fetch_array($ejecutar);
			return $res_proveedor['nit_id'];
		}
		else
		return false;
	}
	
	public function con_ult_lin_telefonia(){
		$query="SELECT MAX(lin_tel_id) ultima FROM lineas_telefonia";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['ultima'];
		}
		else
			return false;
	}
}
?>