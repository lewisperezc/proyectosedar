<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
include_once('../clases/usuario.class.php');
$nit = new nits();
$usuario = new usuario();
$nom_perfil = strtoupper($_POST['nom_perfil']);
$casos_uso = $_POST['cas_uso'];
$dat = $_GET['modificar'];
$i=0;
if($_GET['modificar'])
{
	$borrar = $usuario->borrarCasos($_SESSION['perfil'][0]);
  	for($i=0;$i<$_SESSION['cant'];$i++)
   	{
    	$peruso = $usuario->casosPerfil($_SESSION['perfil'][0],$casos_uso[$i]);
    	if(!$peruso)
	    	$j=1;
   	}
   	echo "<script>alert('Se modifico el perfil con exito.');location.href='../index.php?c=105';</script>";
   	unset($_SESSION['perfil']);unset($_SESSION['cant']);
   	//echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=73'>";
}
else
{
	$perfil = $usuario->crear_perfil($nom_perfil);
  	if($perfil)
   	{
		$maxPerfil = $usuario->maxPerfil();
		if($maxPerfil!="")
		{
	  		$j=0;
	  		for($i=0;$i<$_SESSION['cant'];$i++)
       		{
         		$peruso = $usuario->casosPerfil($maxPerfil,$casos_uso[$i]);
		 		if(!$peruso)
		    		$j=1;
 	   		}
	   		unset($_SESSION['cant']);
	   		echo "<script>alert('Se modifico el perfil con exito.');location.href='../index.php?c=105';</script>";
		}
		else
	  		echo "<script>alert('No se logro crear el perfil, intente nuevamente');location.href='../index.php?c=105';</script>";  
	}
	else
   		echo "<script>alert('No se logro crear el perfil, intente nuevamente');location.href='../index.php?c=105';</script>";
  		unset($_SESSION['cant']);
}
?>