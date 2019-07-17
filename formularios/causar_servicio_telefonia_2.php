<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  //Este Es El Tipo De Nit Que Seleccionan Arriba(Asociado ó Empleado)
  $_SESSION['tipo_nit'] = $_POST['tipo_nit'];
  
  if($_SESSION['tipo_nit']==1)
    include_once('../prueba/linea.php');
  else
    include_once('../prueba/archivo.php');
?>