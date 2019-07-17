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
   
$cen_costo = $_POST['centro'];
$fec_factura = $_POST['fecha'];
$des_factura = $_POST['descripcion'];
$val_factura = $_POST['val_factura'];
$tercero = $_POST['nit'];
$concepto = $_POST['concep'];


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


if(isset($_POST['mes_sele']))
{
	$m=explode('-',$_POST['mes_sele']);
	$mes=$m[1];
}
else
	$mes=date('m');
	
$ano=$_SESSION['elaniocontable'];
$mes_servicio=$_POST['mes_servicio'];

$dat_nit = $ins_nit->consultar($tercero);
$dat_tercero = mssql_fetch_array($dat_nit);

$_SESSION['fecha'] = $fec_factura;
$_SESSION['descripcion'] = strtoupper($des_factura);
$_SESSION['val_unitario'] = $val_factura;
$_SESSION['val_total'] = $val_factura;
$_SESSION['consecutivo']=$consecutivo;
$_SESSION['nits_num_documento']=$dat_tercero['nits_num_documento'];
$_SESSION['nits_nombres']=$dat_tercero['nits_nombres'];
$_SESSION['nits_dir_residencia']=$dat_tercero['nits_dir_residencia'];
$_SESSION['nits_tel_residencia']=$dat_tercero['nits_tel_residencia'];


$con_uni_funcional=$ins_nit->ConsultarUnidadFuncionalPorId($tercero);//CUENTA QUE MuEVE SEGUN LA UNIDAD FUNCIONAL
$tie_uni_funcional=mssql_fetch_array($con_uni_funcional);
			
			
if(trim($tie_uni_funcional['nit_uni_funcional'])!='' && trim($tie_uni_funcional['nit_uni_funcional'])!=0 && trim($tie_uni_funcional['nit_uni_funcional']!=NULL))
{


if($concepto==110)
{
   $conse_factura=$factura->buscar_consecutivo($cen_costo);
}
else
{
    $cons_fac=$rec_caja->obt_consecutivo(2);
    $consec=$cons_fac+1;
}

$guaFactura=$factura->facConcepto($cen_costo,$fec_factura,strtoupper($des_factura),$val_factura,$conse_factura,$tercero,$ano,$mes_servicio);
if($concepto==110)
{
    $act_resolu=$factura->actConse_resolucion($centro);
    $ult_facura=$factura->ult_factura();
    $consecutivo="FAC-".$ult_facura;
}
else
{
    $consecutivo="FAC-".$consec;
    $act_consecutivo=$factura->act_consecutivo(2);
	$ult_factura=$consec;
}
//$ult_factura = $factura->ult_factura();
	if($guaFactura)
	{
		$nueTran=$transacciones->guaTransaccion($consecutivo,$fec_factura,$tercero,$cen_costo,$val_factura,0,
		$fec_factura,$consecutivo,$_SESSION['k_nit_id'],$fec_factura,$mes,$ano);
	        if($nueTran)
	        {
	            $transacc = $transacciones->obtener_concecutivo();
	            $num_tran = mssql_fetch_array($transacc);
				
	            //GUARDAR FACTURA ANTES
	            //$mov=$movimiento->guarCam_movimiento($cen_costo,$num_tran[0],$consecutivo,$tercero,$fec_factura,$num_tran[0],
	            //$fec_factura,0,$val_factura,$concepto,0,0,$mes,$ano);
				
				//GUARDAR FACTURA AHORA
				$que_cue_1="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
				mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('$consecutivo','$num_tran[0]','$fec_factura','42350505','110','$tercero','$cen_costo','$val_factura','2','$num_tran[0]','3','$mes','$ano')";
				$eje_cue_1=mssql_query($que_cue_1);
				//echo $que_cue_1."<br>";
			
				$que_cue_2="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
				mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('$consecutivo','$num_tran[0]','$fec_factura','$tie_uni_funcional[nit_uni_funcional]','110','$tercero','$cen_costo','$val_factura','1','$num_tran[0]','3','$mes','$ano')";
				$eje_cue_2=mssql_query($que_cue_2);
				//echo $que_cue_2."<br>";
				
				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
				aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
				aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='$consecutivo' AND mov_mes_contable='$mes' AND mov_ano_contable='$ano'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	            
	            /*if($mov)
	            {*/
	                echo "<script>alert('Factura registrada correctamente.');</script>";
	                echo "<script>abreFactura('../reportes_PDF/factura_pdf.php');</script>";
	            /*}*/
	        }
	}
}
else
	echo "<script>alert('NO se pudo registar la factura, Debe asignar una Unidad funcional al nit seleccionado, intentelo de nuevo.');</script>";
	
echo "<script>history.back(-1);</script>";
?>