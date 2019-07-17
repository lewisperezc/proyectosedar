<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/cuenta.class.php');
include_once('../clases/recibo_caja.class.php');  
include_once('../clases/moviminetos_contables.class.php');
@include_once('../clases/transacciones.class.php');
@include_once('clases/transacciones.class.php');
  $recibo = new rec_caja();
  $movimiento = new movimientos_contables();
  $tran = new transacciones();
  $consecutivo = $recibo->obt_consecutivo(8);
  $conse = $consecutivo+1;
  $act_conse = $recibo->act_consecutivo(8);
  $fecha = $_SESSION['fecha'];
  $mon_factura = $_SESSION['mon_factura'];
  $factura = $_SESSION["factura"];
  $nit = $_SESSION["nit"];
  $centro = $_SESSION["centro"];
  $cuenta = $_POST['cuenta'];
  $valor = $_POST['valor'];
  $descripcion = strtoupper($_SESSION['des']);
  $concepto = $_SESSION['concepto'];
  $ano = $_SESSION['elaniocontable'];
  $mes=date('m');
  
  $bas_retencion=0;
  
  $i=0;$tot_descuento=0;
  while($i<=sizeof($valor))
    {
		$tot_descuento = $tot_descuento+$valor[$i];
		$i++;
    }
  if($tot_descuento>0)
    {
		$guaRecibo = $recibo->guardar_recibos($factura,$fecha,$mon_factura-$tot_descuento,$descripcion,$conse);
		$nueTran = $tran->guaTransaccion("REC_CAJ".$conse,$fecha,$nit,$centro,$mon_factura-$tot_descuento,0,$fecha,$conse,$_SESSION['k_nit_id'],$fecha,$mes,$ano);
		$tran = $tran->obtener_concecutivo();
        $num_tran = mssql_fetch_array($tran);
		$i=0;
		while($i<=sizeof($valor))
    	{
			if($valor[$i])
			{
			  $cue_mov = split("-",$cuenta[$i]);
			  /*ceunta*/$cue_mov[0];
			  /*nombre*/$cue_mov[1];
			  $mov = $movimiento->guaMovimiento($num_tran['max_id'],$conse,$cue_mov[0],$concepto,$nit,$centro,$valor[$i],1,8,$_SESSION['k_nit_id'],1,"0",1,$mes,$ano,$bas_retencion);
			}
			$i++;
			 echo "<script type=\"text/javascript\">alert(\"Recibo de caja guardado.\");</script>"; 
	 		 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=52'>";
        }
	}
  else
    {
		$nueTran = $tran->guaTransaccion("REC_CAJ".$conse,$fecha,$nit,$centro,$mon_factura-$tot_descuento,0,$fecha,$conse,$_SESSION['k_nit_id'],$fecha,$mes,$ano);
		$tran = $tran->obtener_concecutivo();
        $num_tran = mssql_fetch_array($tran);
		$mov = $movimiento->guarCam_movimiento($centro,$numero,$sigla,$nit,$fecha1,$num_doc,$fecha2,$cantidad,$total_mov,$concepto,$producto,$ica,$ano);
		echo "<script type=\"text/javascript\">alert(\"Recibo de caja guardado.\");</script>"; 
	 	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=52'>";
	}
?>