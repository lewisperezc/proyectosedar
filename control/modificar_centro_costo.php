<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('clases/centro_de_costos.class.php');
@include_once('../clases/centro_de_costos.class.php');
$centro = new centro_de_costos();
$nom_centro = strtoupper($_POST['nom_cen']);
$cod_centro = strtoupper($_POST['cod_cen']);
$ciudad = strtoupper($_POST['ciudad']);
$cen_seleccionado = strtoupper($_SESSION['centros_costo']);
$modificar = $centro->modificar_centro($nom_centro,$ciudad,$cod_centro,$cen_seleccionado);
if($modificar)
 {
    echo "<script type=\"text/javascript\">alert(\"Centro de costo modificado con exito.\");</script>"; 
	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=6'>";
	unset($_SESSION['centros_costo']);
 }
else
 {
  echo "<script type=\"text/javascript\">alert(\"No se pudo modificar el centro de costo, intente mas tarde.\");</script>";  echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=6'>";
  unset($_SESSION['centros_costo']);
 }
?>