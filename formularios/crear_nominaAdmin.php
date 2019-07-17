<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!--<head><link rel="stylesheet" type="text/css" href="../estilos/menu.css"/></head>-->
<!--<a href="registrar_novedad.php" target="nomina">Novedades administrativa||</a>-->
<a href="causacion_nomina_administrativa.php" target="nomina">Causar nomina administrativa||</a><!--form_pag_nomina_anterior.php-->
<a href="form_con_nomina.php" target="nomina">Consultar causaci&oacute;n nomina administrativa||</a>