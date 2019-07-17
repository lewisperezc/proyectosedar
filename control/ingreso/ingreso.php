<?php session_start(); 
include_once('../../clases/usuario.class.php');
include_once('../../clases/centro_de_costos.class.php');
include_once('../../conexion/conexion.php'); ?>
<script type="text/javascript" src="./librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript">
function contratos()
{
	day = new Date();
	id = day.getTime();
	eval("page" + (id) + " = window.open('../../reportes/estado_contratos.php', '" + (id) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
}
function AnioContable(eltipo)
{
	day=new Date();
	id=day.getTime();
	eval("page" + (id) + " = window.open('../../formularios/anio_activo.php?elaniocont="+eltipo+"', '" + (id) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
}
</script>

<?php
$ins_usuario = new usuario();

      function quitar($mensaje)
      {
          $nopermitidos = array("'",'\\','<','>',"\"");
          $mensaje = str_replace($nopermitidos, "", $mensaje);
          return $mensaje;
      }
      if(trim($_POST["usu"]) != "" && trim($_POST["pass"]) != "")
      {  
          $usuario = strtoupper($_POST["usu"]); 
		  $password =  strtoupper($_POST["pass"]);
		  $estados='1,3';
		  $enc_pass = $ins_usuario->encrypt($password,'g5@anestecoop.com');
          $res_usu_sis = $ins_usuario->validarUsuario($usuario,$enc_pass,$estados);
		  
		  $res_dat_perfil=$ins_usuario->ConDatPerPorId($res_usu_sis['nit_perfil']);

		  if($res_usu_sis['nit_nom_usuario'] != $usuario || $res_usu_sis['nit_password'] != $enc_pass)
		  {
			   echo "<script>
				  		alert('Usuario y/o contraseña incorrectos');
						location.href = '../../ingreso/index.php';
					</script>";
		  }
		 else
		  {
			   $_SESSION['k_username'] = $res_usu_sis['nit_nom_usuario'];
			   $_SESSION['k_password'] = $res_usu_sis['nit_password'];
			   $_SESSION['k_perfil'] = $res_usu_sis['nit_perfil'];
			   $_SESSION['k_nit_id'] = $res_usu_sis['nit_id'];
			   $_SESSION['elaniocontable']=$_POST['anio_contable'];



			   $_SESSION['per_sigla']=$res_dat_perfil['per_sigla'];
			   $_SESSION['per_rut_digital']=$res_dat_perfil['per_rut_digital'];
			   $_SESSION['per_nombre']=$res_usu_sis['nits_nombres'];
			   $_SESSION['per_apellido']=$res_usu_sis['nits_apellidos'];



			   //echo "el anio es: ".$_SESSION['elaniocontable'];
			   $ins_cen_costo=new centro_de_costos();
			   $con_cen_cos_por_id=$ins_cen_costo->con_cen_cos_por_id($_SESSION['k_nit_id']);
			   while($res_cen_cos_por_id=mssql_fetch_array($con_cen_cos_por_id))
			   {
			    	$resultado=$resultado.$res_cen_cos_por_id['cen_cos_id'].",";
					$arreglo[]=$res_cen_cos_por_id['cen_cos_id'];
			   }
			   $_SESSION['k_cen_costo']=$resultado;
			   
			   $i=0;
			   while($i<sizeof($arreglo))
			   {
					$res_ciu_por_centros=$ins_cen_costo->con_ciu_por_cen_cos_id($arreglo[$i]);
			    	$ciudades=$ciudades.$res_ciu_por_centros.",";
			    	$i++;
			   }
			   $_SESSION['k_ciudades']=$ciudades;
			   
			   
			   
			   //$act_estado_sesion = $ins_usuario->cam_est_sesion(2,$_SESSION['k_nit_id']);
			   if($_SESSION['k_perfil']==13)
			   	 echo "<script>contratos();</script>";
				
			   /*echo "<script>AnioContable(1);</script>";*/
			   echo "<script>alert('Bienvenido ".$res_usu_sis['nits_nombres']."');</script>";
			   echo "<script>
			   			location.href='../../index.php';
			   		</script>";
			  //}
		  }  
	  }
?>