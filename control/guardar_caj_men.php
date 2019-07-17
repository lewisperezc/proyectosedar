<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');
include_once('../clases/caja_menor.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/centro_de_costos.class.php');
$movimiento = new movimientos_contables();
$tran = new transacciones();
$caja = new caja_menor();
$centro = new centro_de_costos();
$cen_cos = $_POST['cen_cos'];
$monto = $_POST['mon_caj'];
$concepto = 305;
$fecha = date('d-m-Y');
$mes=date('m');
$ano = $_SESSION['elaniocontable'];
$cen_costo = $centro->buscar_centros($cen_cos);
$centro_cos = mssql_fetch_array($cen_costo);
$nit = $centro_cos['cen_cos_responsable'];
if($cen_cos && $monto)
 {
   $asig = $caja->guardar_caja($cen_cos,$monto);
   $conse = $caja->ult_caja();
   if($asig)
    { 
       echo "<script type=\"text/javascript\">alert(\"Se guardo la caja menor satisfactoriamente.\");location.href = '../index.php?c=56';</script>";
	   $nueTran = $tran->guaTransaccion(strtoupper("caj_men-".$conse),$fecha,$nit,$cen_cos,$monto,0,$fecha,$conse,$_SESSION['k_nit_id'],$fecha,$mes,$ano);
	   $tran = $tran->obtener_concecutivo();
       $num_tran = mssql_fetch_array($tran);
	   $mov = $movimiento->guarCam_movimiento($cen_cos,$conse,strtoupper("caj_men-".$conse),$nit,$fecha,$conse,$fecha,0,$monto,$concepto,0,0,$ano);
	   if($mov)
	    {
	      echo "<script type=\"text/javascript\">alert(\"Recibo de caja guardado correctamente.\");</script>"; 
	      echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=52'>"; 
        }
    }
 }
 else
 {
	 echo "<script type=\"text/javascript\">alert(\"No se pudo actualizar, Intentelo de nuevo.\");</script>"; 
	 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=52'>"; 
 }
?>