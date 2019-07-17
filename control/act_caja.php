<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/caja_menor.class.php');
$guar= new caja_menor();

$caso = strtoupper($_SESSION['caso']);
$centro = strtoupper($_SESSION["cen_cos"]);
$fecha1 = strtoupper($_SESSION["fecha_fact"]);
$total = strtoupper($_SESSION["val_total"]);
$iva = strtoupper($_SESSION["iva"]);
$fecha = date('d-m-y');
$caja = $guar->buscar_caja($centro);
$dat_caj = mssql_fetch_array($caja);
$monto_asignado = $dat_caj['caj_men_mon_asig'];
$caja_men = $dat_caj['caj_men_id'];
if($total && $iva && $caja_men)
  {
    $actualiza = $guar->actCaja_gastos(($total+$iva),$caja_men);
	
	//LIMPIAR SESSIONES//
	unset($_SESSION['caso']);
	unset($_SESSION["cen_cos"]);
	unset($_SESSION["fecha_fact"]);
	unset($_SESSION["val_total"]);
	unset($_SESSION["iva"]);
	/////////////////////
	
    if($actualiza)
       echo "<script type=\"text/javascript\">alert(\"Caja menor actualizada satisfactoriamente.\");</script>"; 
    else
       echo "<script type=\"text/javascript\">alert(\"No se pudo actualizar la caja menor, intentelo de nuevo.\");</script>";
    echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=$caso'>"; 
  }
else
  {
	  echo "<script type=\"text/javascript\">alert(\"No se pudo actualizar la caja menor, intentelo de nuevo.\");</script>";
	  echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=$caso'>";
  }
  
?>