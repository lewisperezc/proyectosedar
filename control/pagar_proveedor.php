<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once("../clases/transacciones.class.php");
include_once("../clases/factura.class.php"); 
include_once('../clases/comprobante.class.php');

?>

<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript" >
 function abreFactura(URL)
 {
    day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
 }
</script>

<?php

$factura = new factura();
$transacciones = new transacciones();
$comprobante= new comprobante();

$fecha = date('d-m-Y');
$cantidad = $_POST['cantidad'];
$doc_sigla = $_POST['doc_sigla'];
$mes = $_POST['mes_contable'];
$mes = split("-",$mes);
$conce=0;

$bas_retencion=0;

if($doc_sigla==0)
{
	$conce = $comprobante->cons_comprobante($ano,$mes[1],25);
    $sig = $comprobante->sig_comprobante(25);
    $comprobante->act_comprobante($ano,$mes[1],25);
    $sig_mov=$sig.$conce;
}
else
{
	$sig_comprobante = explode("--",$comprobante->doc_comprobante($k_nit_id));
	$comprobante->doc_act_comprobante($sig_comprobante[1]);
	$sig_mov=$sig_comprobante[0];
}


$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION PAGO A PROVEEDORES';


for($i=0;$i<$cantidad;$i++)
{
	if($_POST['pagar'.$i]=="on")
	{
		
		//echo "antes: ".$centro[$i]."<br>";
			
		$sigla[$i] = $_POST['sigla'.$i];
		$nit[$i] = $_POST['nit'.$i];
		if(trim($_POST['cen_cos'.$i])==''||$_POST['cen_cos'.$i]==0||$_POST['cen_cos'.$i]==1)
			$centro=1169;
		else
			$centro = $_POST['cen_cos'.$i];
			
		//echo "despues: ".$centro[$i]."<br>";
		
		
		
		$fec_factura[$i] = $_POST['fec_fac'.$i];
		$fec_vencimiento[$i] = $_POST['fec_vencimiento'.$i];
		$num_factura[$i] = $_POST['num_fact'.$i];
		$causacion[$i] = $_POST['causacion'.$i];
		$cheque[$i] = $_POST['cheque'.$i];
		if(empty($cheque[$i]))
		   $cheque[$i]=0;
		$act_tran = $transacciones->actu_transaccion($causacion[$i],$cheque[$i]);
		$cuenta[$i] = $_POST['cuenta'.$i];
		$val_pagar[$i] = $_POST['val_pagar'.$i];
		$transaccion[$i] = $_POST['tran'.$i];
		$act_tran = $transacciones->actu_transaccion($transaccion[$i],$cheque[$i]);
		$banco[$i] = $_POST['banco'.$i];
		///Movimiento
		if($centro==0 || trim($centro)=="")
			$centro=1169;
		
		$pagar="EXECUTE insMovimiento '$sig_mov','$conce','".$_POST['cuenta'.$i]."','3','".$nit[$i]."','".$centro."','".$val_pagar[$i]."','1','$conce','$transaccion[$i]','0','2','$fecha','".$mes[1]."','$ano','$bas_retencion'";
		$que_pagar = mssql_query($pagar);
		
		
		$banco="EXECUTE insMovimiento '$sig_mov','$conce','".$_POST['banco'.$i]."','3','".$nit[$i]."','".$centro."','".$val_pagar[$i]."','2','$conce','$transaccion[$i]','0','2','$fecha','".$mes[1]."','$ano','$bas_retencion'";
		$que_pagar = mssql_query($banco);
		 
		
		// hacer el movimiento contable e imprimir el fichero*/
	}
}

$query = "SELECT COUNT(*) cant FROM mov_contable";
$cant_mov = mssql_query($query);
$cantidad = mssql_fetch_array($cant_mov);
$mov = "EXECUTE movContable ".$cantidad['cant'];
$ins_mov = mssql_query($mov);


//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
aud_mov_con_descripcion='$aud_mov_con_descripcion'
WHERE mov_compro='$sig_mov' AND mov_mes_contable='$mes[1]' AND mov_ano_contable='$ano'
AND tip_mov_aud_id IS NULL";
//echo $que_aud_mov_contable;
$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);


echo "<script>abreFactura('../reportes_PDF/causacion_pago.php?sigla=".$sig_mov."&mes=".$mes[1]."')</script>";

?>