<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../conexion/conexion.php');
include_once('../clases/centro_de_costos.class.php');
$cent= new centro_de_costos();
$cod=strtoupper($_POST['cod_txt']);
$nom=strtoupper($_POST['nom_txt']);
$ciu=strtoupper($_POST['select2']);
$cen_existe = $cent->buscar_centroCosto($ciu,$cod);
if(!$cen_existe)
 {
  $gua_centro = $cent->guardarCentroCiudad($nom,$ciu,$cod);
  if($gua_centro)
    {
	 echo "<script type=\"text/javascript\">alert(\"Centro de costo creado con exito.\");</script>";
	 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=3'>";
	}
 }
else
 {
    echo "<script type=\"text/javascript\">alert(\"No se pudo crear el centro de costo, Intente de nuevo.\");</script>";
	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=3'>";
 }
?>