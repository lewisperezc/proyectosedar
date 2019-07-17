<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?><head><link rel="stylesheet" type="text/css" href="../estilos/menu.css"/></head>
<?php
  $opc = $_GET['opc'];
?>
<a href="pago_tesoreria.php?opc=1">Creditos||</a>
 <?php 
  if($opc == 1)
   { ?>
   <a href="form_contenido.php?opt=1" target="contenido">Consignacion||</a>
   <!--<a href="form_contenido.php?opt=2" target="contenido">Contabilizaci�n cr�dito</a>-->
  <?php
   }
  ?>
<a href="pago_tesoreria.php?opc=2">Nominas||</a>
  <?php
  if($opc == 2)
   { ?>
   <a href="form_contenido.php?opt=3" target="contenido">Compensacion Afiliados||</a>
   <!--<a href="form_contenido.php?opt=4" target="contenido">Nomina Administrativa||</a>-->
   <a href="form_contenido.php?opt=5" target="contenido">Pago hosptitales||</a>
  <?php
   }
  ?>
<a href="pago_tesoreria.php?opc=3">Otros||</a>
  <?php
  if($opc == 3)
   { ?>
   <a href="form_contenido.php?opt=6" target="contenido">Otros</a>
  <?php
   }
  ?>

<!--<a href="pago_tesoreria.php?opc=4">Traslados</a>  
 <?php
  if($opc == 4)
   { ?>
   <a href="form_contenido.php?opt=7" target="contenido">Traslado</a>
  <?php
   }
   ?>
-->