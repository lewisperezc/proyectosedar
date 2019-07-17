<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../conexion/conexion.php');
@include_once('../clases/reporte_jornadas.class.php');
@include_once('../clases/hospital.class.php');
@include_once('../clases/factura.class.php');
@include_once('../clases/moviminetos_contables.class.php');
@include_once('../clases/transacciones.class.php');  
$movimiento = new movimientos_contables();
$tran = new transacciones(); 
$fac = new factura();
$cons_fac = $fac->obt_consecutivo(7);
$consecutivo =  $cons_fac+1;
$tot_jornadas=0;
$_SESSION["conse"] = $consecutivo;
$tipo = $_SESSION["tipo"];
$nota = strtoupper($_SESSION["nota"]);
$fecha = date('d-m-Y');
$_SESSION["centro"] = $_SESSION["hospital"];
$tama = $_SESSION['i'];
for($i=0;$i<$tama;$i++)
   $jornadas[$i] = $_POST['num_jornadas'.$i];
$_SESSION['jornadas'] = $jornadas;
$num_aso = $_SESSION["num_aso"];
$rep_jornadas = new reporte_jornadas();
$tip_reporte = $_POST['tip_rep'];
/*Ciclo para guardar los reportes de jornadas*/
$i = 0;
while($i < sizeof($jornadas))
 {
   $aso = $num_aso[$i];
   $jor = $jornadas[$i];
   $tot_jornadas = $tot_jornadas+$jor;
   $rep_jornadas->registrarReporte_jornadas($jor,$aso,$tipo,$nota,$consecutivo,$tip_reporte);
   $i++;
 } 
$_SESSION['tot_jornadas'] =  $tot_jornadas;
if($i==sizeof($jornadas))
  {
   echo "<script type=\"text/javascript\">alert(\"Reporte de jornadas registrado con Exito.\");  
   var a = confirm('Desea Imprimir la factura para el reporte de jornadas?');
	   if(a)
		 	location.href = '../reportes/factura.php';</script>";
  }
?>