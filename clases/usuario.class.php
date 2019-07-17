<?php
@include_once('../conexion/conexion.php');
@include_once('conexion/conexion.php');

class usuario
{
	public function get_casosUso()
	{
		$sql = "SELECT cas_uso_id,cas_uso_nombre FROM casos_uso";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
	public function get_perfiles()
	{
		$sql = "SELECT * FROM perfiles";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	
	public function crear_usuario($tipo,$perfil,$usu,$pass,$nit)
	{
		$sql = "UPDATE nits SET nit_perfil = $perfil,nit_nom_usuario = '$usu',nit_password='$pass' WHERE nit_id = $nit";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query){
		  if($tipo==1){
		  echo "<script>
		  			alert('Usuario Creado Correctamente');
					location.href='../index.php?c=106';
				</script>";
		  }
		  else{
			  if($tipo==2){
			  echo "<script>
		  			alert('Usuario Actualizado Correctamente');
					location.href='../index.php?c=113';
				</script>";
			  }
		  }
		  return $query;
		}
		else
		  return false;
	}
	
	public function crear_perfil($nombre)
	{
		$sql = "INSERT INTO perfiles(per_nombre) VALUES ('$nombre')";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function maxPerfil()
	{
		$sql = "SELECT MAX(per_id) perfil FROM perfiles";
		$query = mssql_query($sql);
		if($query)
		 {
		   $dat_max = mssql_fetch_array($query);
		   return $dat_max['perfil'];
		 }
		else
		 return false; 
	}
	
	public function casosPerfil($perfil,$caso)
	{
		if($perfil!=''&&$caso!='')
		{
		  $sql = "INSERT INTO perfiles_por_casosUso(per_por_cas_per_id,per_por_cas_cas_id) VALUES ($perfil,$caso)";
		  $query = mssql_query($sql);
		  if($query)
		    return true;
		  else
		    return false;  
		}
		else
		 return false;
	}
	
	public function perfiles()
	{
		$sql = "SELECT * FROM perfiles";
		$query = mssql_query($sql);
		if($query) 
		  return $query;
		else
		 return false;  
	}
	
	public function casos_perfil($perfil)
	{
		$sql = "SELECT per_por_cas_id,per_por_cas_cas_id FROM perfiles_por_casosUso WHERE per_por_cas_per_id = $perfil"; 
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function actPerfil($perfil,$nombre)
	{
		$sql = "UPDATE perfiles SET per_nombre='$nombre' WHERE per_id=$perfil";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;
	}
	
	public function borrarCasos($perfil)
	{
	   $sql = "DELETE FROM perfiles_por_casosUso WHERE per_por_cas_per_id=$perfil";
	   $query = mssql_query($sql);
	   if($query)
		  return true;
		else
		  return false;
	}
	
	public function con_usu_por_id($nit_id)
	{
		$query = "SELECT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos as nombres,per.per_id,
per.per_nombre,nit.nit_nom_usuario,nit.nit_password FROM nits nit INNER JOIN perfiles per
                  ON nit.nit_perfil = per.per_id WHERE nit.nit_id = $nit_id";
		$ejecutar = mssql_query($query);
		if($ejecutar){
		$res_dat_usu_seleccionado = mssql_fetch_array($ejecutar);
		return $res_dat_usu_seleccionado;
		}
		else
		return false;
	}
	
   public function ver_usu_existe($existe,$tip_nit_id,$nit_est_id,$nit_nom_usuario)
   {
	   $query = "SELECT nit_nom_usuario FROM nits
                 WHERE nit_perfil $existe AND tip_nit_id = $tip_nit_id AND nit_est_id = $nit_est_id AND nit_nom_usuario = '$nit_nom_usuario'";
	   $ejecutar = mssql_query($query);
	   if($ejecutar){
	   $res_nom_usuario = mssql_num_rows($ejecutar);
	   return $res_nom_usuario;
	   }
	   else
	   return false;
   }
	
   public function encrypt($string, $key){
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
   }
   
   public function decrypt($string, $key) {
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
   }
   
   public function validarUsuario($usuario,$pass,$nits_estados)
   {
	   $sql = "SELECT nit_id,nits_nombres,nits_apellidos,nit_perfil,nit_nom_usuario,nit_password
               FROM nits WHERE nit_nom_usuario='$usuario' AND nit_password = '$pass' AND nit_est_id IN($nits_estados)";
			  //echo $sql;
	   $query = mssql_query($sql);
	   if($query){
	   		$res_usu_sis = mssql_fetch_array($query);
	     	return $res_usu_sis;
	   }
	   else
	     return false;	 
   }
   
   public function cerrar_sesion()
   {
	   $_SESSION = array();
	
	   if(ini_get("session.use_cookies")){
	   $params = session_get_cookie_params();
	   setcookie(session_name(), '', time() - 42000,
	   $params["path"], $params["domain"],
	   $params["secure"], $params["httponly"]);
	   }
	   session_unset(); 
	   session_destroy();
   }
   
   public function cam_est_sesion($estado,$id_usuario)
   {
	   $query = "UPDATE nits SET nit_est_sesion = $estado where nit_id = $id_usuario";
	   $ejecutar = mssql_query($query);
	   if($ejecutar)
	   return $ejecutar;
	   else
	   return false;
   }
   
   public function con_est_sesion($id_usuario){
	   $query = "SELECT nit_est_sesion FROM nits WHERE nit_id = $id_usuario";
	   $ejecutar = mssql_query($query);
	   if($ejecutar){
		   $res_est_sesion = mssql_fetch_array($ejecutar);
	   return $res_est_sesion['nit_est_sesion'];
	   }
	   else
	   return false;
   }
   
   public function modificar_usuario($nit,$pass)
   {
	   $pasword = $this->encrypt($pass,"g5@anestecoop.com");
	   $modi = "UPDATE nits SET nit_password = '$password' WHERE nit_id = $nit";
	   $query = mssql_query($modi);
	   if($query)
	     return true;
	   else
	   	 return false;
   }
   
   public function con_men_por_modulo($modulo)
   {
	   $query="SELECT * FROM menus WHERE mod_id=$modulo";
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   	return $ejecutar;
	   else
	   	return false;
   }
   
   public function ConDatPerPorId($per_id)
   {
	   $query="SELECT * FROM perfiles WHERE per_id='$per_id'";
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   {
	   	$res_dat_perfil=mssql_fetch_array($ejecutar);
	   	return $res_dat_perfil;
	   }
	   else
	   	return false;
   }
   
}
?>