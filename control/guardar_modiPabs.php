<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
   include_once('../conexion/conexion.php');
   include_once('../clases/pabs.class.php');
   include_once('../clases/moviminetos_contables.class.php');
   $pabs = new pabs();
   $mov_contable = new movimientos_contables();
   $mes=split("-",$_POST['mes_sele'],2);
   $conse = $_SESSION['conse'];
   $fecha_pabs = $_POST['fec_pab'];
   $asociado = $_POST['aso'];
   $beneficiario = $_POST['bene'];
   $producto = $_POST['prod'];
   $cantidad = $_POST['cant'];
   $valor = $_POST['val'];
   $temp = 0;
   for($i=0;$i<sizeof($asociado);$i++)
   {
      $act_pabs = $pabs->mod_pabs($conse,$asociado[$i],$beneficiario[$i],$fecha[$i],68);
	  if(!$act_pabs)
	     $temp = 0;
   }
?>