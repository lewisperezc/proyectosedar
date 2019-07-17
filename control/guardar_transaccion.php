<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../conexion/conexion.php');//para funcionamiento local
include_once('../clases/transacciones.class.php');//para funcionamiento local
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/orden_compra.class.php');
$caso = $_SESSION['caso'];
$ord_com = new orden_compra();
$transaccion = new transacciones();
$mov_con = new movimientos_contables();
$suma = 0;
$centro = $_SESSION["cen_cos"];
$numero = $_SESSION["num_tra"];
$sigla = strtoupper($_SESSION["sigla"]);
$nit = $_SESSION["prov"];
/**/
$fecha1 = $_SESSION["fecha_fact"];
$num_doc = $_SESSION["num_oc_fa"];
$fecha2 = $_SESSION["fec_ven"];
$mes_contable = split("-",$_SESSION['mes'],2);
$ano = $_SESSION['elaniocontable'];
$cantidad = $_POST['cantidad'];
$fecha = date('d-m-y');
$ejem = strtoupper($_SESSION['tra']);
$concepto = $_POST['concepto'];
$_SESSION["cantidad"] = $cantidad;
$tran_num = $_POST['ref'];
$productos = $_POST['select2'];
$valor = $_POST['valor'];
for($t=0;$t<sizeof($concepto);$t++)
 {
    if($concepto[$t]==$concepto[$t+1])
	   $bandera = 0;
	else
	   $bandera = 1;  
 }
$sigla = "CAU-FAC_".$ejem;
$nueTran = $transaccion->guaTransaccion($sigla,$fecha1,$nit,$centro,$suma,round($iva,0),$fecha2,$num_doc,$_SESSION["k_nit_id"],$fecha,$mes_contable[1],$ano);
  if($nueTran)
   { 
     echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion.\");</script>";   
     $tran_num = $_POST['ref'];
     $productos = $_POST['select2'];
     $valor = $_POST['valor'];
     $tran = $transaccion->obtener_concecutivo();
     $num_tran = mssql_fetch_array($tran);
     for($i=0;$i<sizeof($productos);$i++)
     {
	  $num = $transaccion->guaDetallePro(strtoupper($tran_num[$i]),$fecha,$productos[$i],$cantidad[$i],$valor[$i],11,$centro,sizeof($productos),$sigla);	
	  if($bandera == 1)
		{
		    $ins_camMov = $mov_con->guarCam_movimiento($centro,strtoupper($tran_num[$i]),$sigla,$nit,$fecha1,$ejem,$fecha2,
			$cantidad[$i],$cantidad[$i]*$valor[$i],$concepto[$i],$productos[$i],0,$mes_contable[1],$ano);
		  if($ins_camMov)
		  {
			 echo "<script type=\"text/javascript\">alert(\"Se actualizo el movimiento.\");</script>";
			 //aqui debemos hacer la orden de desembolso con los conceptos de movimiento cuenta por pagar
		  }
		  else
		     echo "<script type=\"text/javascript\">alert(\" No Se actualizo el movimiento.\");</script>";
		 }
	  }
	  if($bandera!=1)
	  {
		$ins_camMov = $mov_con->guarCam_movimiento($centro,strtoupper($tran_num[1]),$sigla,$nit,$fecha1,$ejem,$fecha2,0,$total,$concepto[1],$productos[$i],0,$mes_contable[1],$ano);
		if($ins_camMov)
		{
    		echo "<script type=\"text/javascript\">alert(\"Se actualizo el movimiento.\");</script>";
			//aqui debemos hacer la orden de desembolso con los conceptos de movimiento cuenta por pagar
		}
	  }
    }
   else
      echo "<script type=\"text/javascript\">alert(\"No se pudo guardar el encabezado, intente nuevamente.\");</script>";
   if($caso==57)
	    echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../control/act_caja.php'>";
   else
   {
	   //LIMPIAR SESSIONES//
	   unset($_SESSION['caso']);
	   unset($_SESSION["cen_cos"]);
	   unset($_SESSION["num_tra"]);
       unset($_SESSION["sigla"]);
       unset($_SESSION["prov"]);
       unset($_SESSION["fecha_fact"]);
       unset($_SESSION["num_oc_fa"]);
       unset($_SESSION["fec_ven"]);
       unset($_SESSION['mes']);
       unset($_SESSION['tra']);

	   /////////////////////
   }
?>