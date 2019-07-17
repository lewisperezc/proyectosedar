<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../conexion/conexion.php');//para funcionamiento local
include_once('../clases/transacciones.class.php');//para funcionamiento local
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/orden_compra.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/factura.class.php');

$ord_com = new orden_compra();
$transaccion = new transacciones();
$mov_con = new movimientos_contables();
$ins_cen_costo = new centro_de_costos();
$ins_factura = new factura();

$fecha = date('d-m-Y');
/*INICIO DATOS DEL FORMULARIO*/
$rec_caja = strtoupper($_SESSION['res_caja_id']);
$cen_costo = strtoupper($_SESSION['cen_cos']);
$caj_men_gas = strtoupper($_POST['caj_gas']);
$mes = strtoupper($_SESSION['mes_sele']);
$mes_contable = split("-",$mes);
$ano = $_SESSION['elaniocontable'];
$concepto_id = strtoupper($_POST['concepto_id']);
/*FIN DATOS DEL FORMULARIO*/

$res_cen_costo = $ins_cen_costo->con_responsable_cen_cos($cen_costo);
$obt_consecutivo = $ins_factura->obt_consecutivo(22);
$sigla = "REE-CAJ_".$obt_consecutivo;
$nueTran = $transaccion->guaTransaccion($sigla,$fecha,$res_cen_costo,$cen_costo,$caj_men_gas,0,$fecha,$obt_consecutivo,4,$fecha,$mes_contable[1],$ano);
  if($nueTran)
   { 
     echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion.\");</script>";
	 $ins_camMov = $mov_con->guarCam_movimiento($cen_costo,$obt_consecutivom,$sigla,$res_cen_costo,$fecha,$obt_consecutivo,$fecha,1,$caj_men_gas,$concepto_id,0,0,$mes_contable[1],$ano);
	 $act_consecutivo = $ins_factura->act_consecutivo(22);
		  if($ins_camMov)
		  {
			  echo "<script type=\"text/javascript\">alert(\"Se actualizo el movimiento.\");</script>";
		  }
		  else
		  {
		     echo "<script type=\"text/javascript\">alert(\" No Se actualizo el movimiento.\");</script>";
		  }												
    }
   else{
      echo "<script type=\"text/javascript\">alert(\"No se pudo guardar el encabezado, intente nuevamente.\");</script>";
   }
//LIMPIAR SESSIONES//
unset($_SESSION['res_caja_id']);
unset($_SESSION['cen_cos']);
unset($_SESSION['mes_sele']);
unset($_POST['concepto_id']);
/////////////////////
?>