<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('../clases/cuenta.class.php');
  include_once('../clases/nits.class.php');
  include_once('../clases/moviminetos_contables.class.php');
  $opcion = $_GET['opt'];
  $cuenta = new cuenta();
  $nit = new nits();
  $cue_pagar = $cuenta->cuentas_pagar();
  $nits = $nit->cons_nits();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
 <?php
  if($opcion==1)
    include_once('form_ent_creditos.php');
  /*elseif($opcion==2)
	include_once('form_sal_creditos.php');*/
  elseif($opcion==3)
    include_once('pag_compeBanco.php');
  /*elseif($opcion==4)
  	include_once('form_con_nomina.php');*/
  elseif($opcion==5)
    include_once('form_pag_facturas.php');
  elseif($opcion==6)
    include_once('form_pag_proveedores.php');
  elseif($opcion==7)
   {
	  $_SESSION['trans']=1;
    include_once('crear_transacciones.php');
   }
 ?>
</body>
</html>