<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/transacciones.class.php');
include_once('../clases/recibo_caja.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/comprobante.class.php');
include_once('../clases/factura.class.php');

$ins_nits=new nits();
$ins_rec_caja = new rec_caja();
$comprobante= new comprobante();
$fac = new factura();


?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript">

function abreFactura(URL)
{
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
}
</script>
<?php
$transaccion = new transacciones();
$fec_causacion = $_POST['cau_fecha'];
$fec_creacion = $_POST['cau_fecCreacion'];
$cen_costo = $_POST['centro'];
$num_factura = $_POST['num_doc'];
$can_registros = $_POST['cant_gasto'];
$descrip_causacion = $_POST['desc'];
$total = $_POST['tot_deb'];


$bas_retencion=0;


$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE DEVOLUCION DE APORTES';

$conse = $fac->obt_consecutivo(41);
$ac_conse = $fac->act_consecutivo(41);
$sigla = "DEV-APO_".$conse;

//$sigla = $_POST['conce'];
$mes = $_POST['mes_sele'];
if($mes=="")
	$mes = $_SESSION['me'];	
$mes_con = split("-",$mes,2); 
$diferido = $_POST['can_diferido']."-".$_POST['cuenta_gasto'];


$nueTran = $transaccion->guaTransaccionCau(strtoupper($sigla),$fec_causacion,380,$cen_costo,$total,0,$fec_creacion,$num_factura,$_SESSION['k_nit_id'],$fec_creacion,$mes_con[1],$descrip_causacion,$ano);
if($diferido)
{
	$ult_transaccion = $transaccion->obtener_concecutivo();
	$obt_consecu = mssql_fetch_array($ult_transaccion);
	$sql="UPDATE transacciones SET trans_diferido='$diferido' WHERE trans_id=".$obt_consecu['max_id'];
	$query = mssql_query($sql);
}

for($i=0;$i<=$can_registros;$i++)
{
	$cuenta = $_POST['cuenta'.$i];
	$descr = $_POST['desc'.$i];
	$prove = $_POST['prove'.$i];
	$ica = $_POST['ica'.$i];
	$debito = $_POST['debito'.$i];
	$credito = $_POST['credito'.$i];
	if($debito>0)
	  $sql ="EXECUTE insMovimiento '$sigla','$num_factura','$cuenta','2','$prove','$cen_costo','$debito','1','$num_factura','3','0','$can_registros','$fec_creacion','$mes_con[1]','$ano','$bas_retencion'";
	elseif($credito>0)
	  $sql ="EXECUTE insMovimiento '$sigla','$num_factura','$cuenta','2','$prove','$cen_costo','$credito','2','$num_factura','3','0','$can_registros','$fec_creacion','$mes_con[1]','$ano','$bas_retencion'";
	$query = mssql_query($sql);
	
	$cambiar_estado_asociado=$ins_nits->act_est_nit(5,$prove);
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
WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_con[1]' AND mov_ano_contable='$ano'
AND tip_mov_aud_id IS NULL";
//echo $que_aud_mov_contable;
$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);


$comprobante->act_comprobante($ano,$mes_con[1],42);

echo "<script>alert('Se guardo la causacion con exito.');</script>";
echo "<script language='javascript'>abreFactura('../reportes_PDF/causacion_pago.php?sigla=".strtoupper($sigla)."');</script>";
echo "<script>location.href='../index.php?c=97'</script>";
?>