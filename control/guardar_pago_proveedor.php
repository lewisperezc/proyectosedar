<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); } 
$ano = $_SESSION['elaniocontable'];?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript">
 function ordenPago(URL)
 {
    day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
 }
</script>
<?php
include_once('../clases/transacciones.class.php');	
include_once('../clases/factura.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/comprobante.class.php');
include_once('../clases/varios.class.php');
include_once('../clases/orden_compra.class.php');
$ins_varios=new varios();
$transacciones = new transacciones();
$fact = new factura();
$nits = new nits();
$comprobante= new comprobante();
$orden_compra = new orden_compra();


$mes_sele=$_POST['mes_sele'];
$mes_contable=explode('-',$mes_sele);


$prove = $_POST['prove'];
$nit=$nits->busNit($prove);
$cantidad = $_POST['cantidad'];
$observacion = $_POST['observacion'];
$conce = $comprobante->cons_comprobante($ano,$mes_contable[1],13);
$sig = $comprobante->sig_comprobante(13);
$comprobante->act_comprobante($ano,$mes_contable[1],13);

$j=0;
$sigla = $sig.$conce;
for($i=0;$i<$cantidad;$i++)
{
	if($_POST['parcial'.$i]!=""||$_POST['total'.$i]=="on")
	{
		$pag_parcial[$i] = $_POST['parcial'.$i];
		$total[$i] = $_POST['total'.$i];
		$transaccion[$i] = $_POST['tran_id'.$i];
		$sig_pago[$i] = $_POST['sigla'.$i];
		$num_factura[$i] = $_POST['num_factura'.$i];
		$val_factura[$i] = $_POST['val_fac'.$i];
		$cuenta[$i] = $_POST['cue_pagar'.$i];
        $elmes[$i]=$_POST['elmes'.$i];
        $iva=0;$rete=0;$ica=0;
		if($pag_parcial[$i]!="")
		{
		  $val_pagar = $pag_parcial[$i];
		  $opcion=1;
		}
		else
		{
		  $val_pagar = $val_factura[$i];
		  $opcion=2;
		}
        $res_num_mes=$ins_varios->ConvertirLetrasANumeros($elmes[$i]);
        $guardar_tran = $transacciones->guaPagTransaccion($sigla,date('d-m-Y'),$nit,1169,$val_pagar,0,date('d-m-Y'),$num_factura[$i],$_SESSION["k_nit_id"],date('d-m-Y'),4,$transaccion[$i],$res_num_mes,$ano);
		$observacion = $transacciones->guaObservacion($observacion,$opcion,$valor,$transaccion[$i]);
		$dat_causacion = $transacciones->consulTransaccion($sig_pago[$i],$mes_contable[1],$ano);
		
		$dat_causacion_2 = $transacciones->consulTransaccionPorNit($sig_pago[$i],$mes_contable[1],$ano,$nit);
		
		while($dat_query=mssql_fetch_array($dat_causacion_2))
    	{
    		if(substr($dat_query['mov_cuent'],0,4)==2408)
    			$iva=$dat_query['mov_valor'];
    		if(substr($dat_query['mov_cuent'],0,4)==2368)
    			$ica=$dat_query['mov_valor'];
    		if(substr($dat_query['mov_cuent'],0,4)==2365)
    			$rete=$dat_query['mov_valor'];
    	}
		//echo "el iva: ".$iva."___".$rete."<br>";
		$orden = $orden_compra->guardar_ordCompra($nit,1169,($val_factura[$i]+$iva+$rete),$iva,$rete,$ica,$sigla,$mes_contable[1],$ano);
	}
}
//echo "En este momento no se pueden guardar ordenes de desembolso, intentelo mas tarde";
echo "<script>ordenPago('../reportes_PDF/desembolso.php?sigla=".$sigla."&tercero=".$nit."&mes_cont=".$res_num_mes."&fecha=".date('d-m-Y')."');</script>";
echo "<script>history.back(-1);</script>";
?>