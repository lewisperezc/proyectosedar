<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/factura.class.php');
include_once('../clases/recibo_caja.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/contrato.class.php');
include_once('../clases/nits.class.php');

$factura = new factura();
$rec_caja = new rec_caja();
$transacciones = new transacciones();
$centro_costo = new centro_de_costos();
$movimiento = new movimientos_contables();
$con = new contrato();
$ins_nit = new nits();
$ano = $_SESSION['elaniocontable'];
$radicado = $_GET['radicado'];



$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE FACTURA';

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
if($radicado==1)
{
	$fecha=$_POST['fec_radicado'];
	$fec_radicado = $factura->mod_fec_radicado(strtoupper($_SESSION["consecu"]),$fecha);
	if($fec_radicado)
	{
		echo "<script>alert('Se agrego la fecha de radicado con exito.');</script>";
		echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=52'>";
	}
	else
	{
		echo "<script>alert('No se pudo agregar la fecha de radicado, intentelo de nuevo.');</script>";
		echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=52'>";
	}
}
else
{
		
	$fec_factura = $_POST['fec_fac'];
	$des_factura = addslashes($_POST['desc_factura']);
	$val_factura = $_POST['val_fac'];
    $per_facturado=$_POST['per_facturado'];
	$mes_servi = $_POST['mes_ser'];
	$_SESSION['fecha'] = $fec_factura;
	$_SESSION['descripcion'] = $des_factura;
	$_SESSION['val_unitario'] = $val_factura;
	$_SESSION['val_total'] = $val_factura;
	if(isset($_GET['centro_cos']))
		$centro=$_GET['centro_cos'];
	else
		$centro=$_POST['centro_cos'];
	
	$tipo_nit='8';
	
	$datos_centro = $centro_costo->datos_nitCen($centro,$tipo_nit);
	$dat_centro = mssql_fetch_array($datos_centro);
	$contra=$con->contratoServicio($dat_centro['nit_id'],$mes_servi,$ano,$_SESSION['fecha']);
	$dat_contrato =  mssql_fetch_array($contra);
	$_SESSION['dias'] = $dat_contrato['con_fac_vencimiento'];
	$contrato = $dat_contrato['con_id'];
	
	//echo "<br> el contrato es: ".$contrato."<br>";
	
	if($contrato=="")
		$contrato=0;
	/*****************Mes de la factura**********************/
	$mes_factura = explode("-",$fec_factura,3);
	if(sizeof($mes_factura)>1)
	{
		  $cons_fac = $factura->buscar_consecutivo($centro);
		  $act_resolu=$factura->actConse_resolucion($centro);
	}
	else
	{
		$mes_factura = explode("/",$fec_factura,3);
		$cons_fac = $factura->buscar_consecutivo($centro);
		$act_resolu=$factura->actConse_resolucion($centro);
	}
	
	$_SESSION['consecutivo']=$cons_fac;
	$centro_cos = $centro_costo->buscar_centros($centro);
	$centros = mssql_fetch_array($centro_cos);
	$_SESSION['ciudad']=$centros['ciu_nombre'];
	$nit=$centros['cen_cos_nit'];
	
	$doc_dig_usu_propietario=explode('.',$_POST['doc_dig_usu_propietario']);

	$per_id=$doc_dig_usu_propietario[0];
	
	$mes_estado=explode("-",$_POST['mes']);
	
	$mes_contable=$mes_estado[1];
	
	$fec_sistema=date('d-m-Y');
	
	
	
	$con_uni_funcional=$ins_nit->ConsultarUnidadFuncionalPorId($nit);//CUENTA QUE MuEVE SEGUN LA UNIDAD FUNCIONAL
	$tie_uni_funcional=mssql_fetch_array($con_uni_funcional);
	//echo "la unidad es: ".$tie_uni_funcional['nit_uni_funcional'];
	
	
	if(trim($tie_uni_funcional['nit_uni_funcional'])!='' && trim($tie_uni_funcional['nit_uni_funcional'])!=0 && trim($tie_uni_funcional['nit_uni_funcional']!=NULL))
	{
	
	
	
	$sql="INSERT INTO factura(fac_cen_cos,fac_fecha,fac_descripcion,fac_val_unitario,fac_val_total,fac_consecutivo,
		  fac_nit,fac_estado,fac_rep_reconfirmado,fac_contrato,fac_fec_creacion,fac_mes_servicio,fac_ano_servicio,fac_perFacturacion)
		  VALUES ('$centro','$fec_factura','$des_factura',$val_factura,$val_factura,'$cons_fac',$nit,2,0,$contrato,'$fec_sistema', $mes_servi,'$ano','$per_facturado')";
    //echo $sql;
	$query = mssql_query($sql);
	if($query)
	{
		//$act_consecutivo = $factura->act_consecutivo(2);
		$ult_factura = $factura->ult_factura();
		
		
		
		$nueTran = $transacciones->guaTransaccion("FAC-".$ult_factura,$fec_factura,$nit,$centro,$val_factura,0,
		$fec_factura,$consecutivo,$_SESSION['k_nit_id'],$fec_factura,$mes_contable,$ano);
		$transacc = $transacciones->obtener_concecutivo();
		$num_tran = mssql_fetch_array($transacc);
		
		//GUARDAR FACTURA ANTES
		//$mov = $movimiento->guarCam_movimiento($centro,$num_tran[0],"FAC-".$ult_factura,$nit,$fec_factura,$num_tran[0],$fec_factura,0,$val_factura,101,0,0,$mes_contable,$ano);
		
		//GUARDAR FACTURA AHORA
		$que_cue_1="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('FAC-$ult_factura','$num_tran[0]','$fec_factura','41150105','101','$nit','$centro','$val_factura','2','$num_tran[0]','3','$mes_contable','$ano')";
		$eje_cue_1=mssql_query($que_cue_1);
		//echo $que_cue_1."<br>";
		
		$que_cue_2="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('FAC-$ult_factura','$num_tran[0]','$fec_factura','$tie_uni_funcional[nit_uni_funcional]','101','$nit','$centro','$val_factura','1','$num_tran[0]','3','$mes_contable','$ano')";
		$eje_cue_2=mssql_query($que_cue_2);
		//echo $que_cue_2."<br>";
		
		
		//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
		$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
		aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
		aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
		aud_mov_con_descripcion='$aud_mov_con_descripcion'
		WHERE mov_compro='FAC-$ult_factura' AND mov_mes_contable='$mes_contable' AND mov_ano_contable='$ano'
		AND tip_mov_aud_id IS NULL";
		//echo $que_aud_mov_contable;
		$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	}

	$dat_hospital = $ins_nit->consul_nits($nit);
	$datos_hos = mssql_fetch_array($dat_hospital);
	echo "<script>abreFactura('../reportes_PDF/factura_febrero.php?opcion=1');</script>";
	}
	else
		echo "<script>alert('NO se pudo registar la factura, Debe asignar una Unidad funcional al nit seleccionado, intentelo de nuevo.');</script>";

	
	
	$_SESSION['nits_nombres']=$datos_hos['nits_nombres'];
	$_SESSION['nits_num_documento']= $datos_hos['nits_num_documento'];
	$_SESSION['nits_dir_residencia']=$datos_hos['nits_dir_residencia'];
	$_SESSION['nits_tel_residencia']=$datos_hos['nits_tel_residencia'];
	
	
	echo "<script>history.back(1);</script>";
	//LIMPIAR SESSIONES//
	/////////////////////*/
}
?>