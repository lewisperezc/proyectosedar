<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/centro_de_costos.class.php');

$cent= new centro_de_costos();

$cod=strtoupper($_POST['cod_txt']);
$nom=strtoupper($_POST['nom_txt']);
$ciu=$_POST['select2'];
$cen_ppal = strtoupper($_POST['ppal']);
$nit_res = strtoupper($_POST['emp']);
  if($_SESSION["cen_prin"])
   {
    if($_SESSION["cen_prin"] == 1)	 
      { 
	   $cen_existe = $cent->buscar_centroCostoPrin($ciu,$cod);
	   if(!$cen_existe)
		   $gua_centro = $cent->guardarCentroCiudad($nom,$ciu,$cod,$nit_res);
       else
        {
          echo "<script type=\"text/javascript\">alert(\"No se pudo crear el centro de costo, Intente de nuevo.\");</script>"; 
	      echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=3'>";
        }
	  }
    elseif($_SESSION["cen_prin"] == 2)
      {
	   $cen_existe = $cent->buscar_centroCostoSec($cod);
	   if(!$cen_existe)
		   $gua_centro = $cent->guardar_cenSecun($nom,$cod,$cen_ppal,4); 	 
	  }
  
  if($gua_centro)
    {
	 	echo "<script type=\"text/javascript\">alert(\"Centro de costo creado con exito.\");</script>"; 
	 	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=3'>";
  	}	   
   }
?>