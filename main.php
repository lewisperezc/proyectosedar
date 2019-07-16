<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
?>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />

