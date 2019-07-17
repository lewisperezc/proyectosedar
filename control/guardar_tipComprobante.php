<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/tipo_comprobante.class.php');
include_once('../conexion/conexion.php');
$tip_com = new tipo_comprobantes();
$nom_com = strtoupper($_POST['nomCom']);
$con_com = strtoupper($_POST['codCom']);
$cre = $tip_com->crear_tipComprobante($nom_com,$con_com);
if($cre)
  {
     echo "<script type=\"text/javascript\">alert(\"Se inserto el tipo de comprobante.\");</script>";
	 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?e=6'>";
  }
?>