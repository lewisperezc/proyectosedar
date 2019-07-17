<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/transacciones.class.php');//para funcionamiento local
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/orden_compra.class.php');
include_once('../clases/notas.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/comprobante.class.php');
include_once('../clases/nits.class.php');

$ins_nit=new nits();
$nota = new nota();
$mov_con = new movimientos_contables();
$comprobante= new comprobante();
$transaccion = new transacciones();
$fac = new factura();

$grupo_contable = strtoupper($_POST['select1']);
$concepto =  strtoupper($_POST['select2']);
$fecha = strtoupper($_POST['fecha']);
$desc = strtoupper($_POST['desc']);
$factura = strtoupper($_SESSION["factura"]);
$monto = strtoupper($_POST['monto_fin']);
$mes = split("-",strtoupper($_POST['mes_sele']),2);


if($concepto==108)
{
	$conce = $comprobante->cons_comprobante($ano,$mes[1],4);
    $sig = $comprobante->sig_comprobante(4);
    $comprobante->act_comprobante($ano,$mes[1],4);
}
elseif($concepto==109)
{
	$conce = $comprobante->cons_comprobante($ano,$mes[1],40);
    $sig = $comprobante->sig_comprobante(40);
    $comprobante->act_comprobante($ano,$mes[1],40);
}

$tem = $fac->bus_cenNit($factura);
$cenNit = mssql_fetch_array($tem);
$centro = $cenNit['cen_id'];
$nit = $cenNit['cen_nit'];
$sigla = $sig.$conce;

$nue_nota = $nota->guardar_nota($desc,$factura,$grupo_contable,$monto,$sigla,$mes[1],$ano);

if($nue_nota)
 {
   echo "<script type=\"text/javascript\">alert(\"Se guardo la nota satisfactoriamente.\");</script>"; 
   
   $nueTran = $transaccion->guaTransaccion($sigla,$fecha,$nit,$centro,$monto,0,'0',$factura,$_SESSION["k_nit_id"],$fecha,$mes[1],$ano);
   if($nueTran)
   { 
     echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion.\");</script>";
	   $tran = $transaccion->obtener_concecutivo();
     $num_tran = mssql_fetch_array($tran);
  	 $num = $transaccion->guaDetallePro($num_tran[0],$fecha,0,0,$monto,11,$centro,sizeof($productos),$sigla);
		
	//GUARDAR FACTURA ANTES
	//$ins_camMov = $mov_con->guarCam_movimiento($centro,$num_tran[0],$sigla,$nit,$fecha,$factura,$fecha,0,$monto,$concepto,0,0,$mes[1],$ano);
	
	if($concepto==108)//NOTA CREDITO
	{
		//GUARDAR NOTA AHORA
		$que_cue_1="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('$sigla','$factura','$fecha','41751501','108','$nit','$centro','$monto','1','$factura','3','$mes[1]','$ano')";
		$eje_cue_1=mssql_query($que_cue_1);
		//echo $que_cue_1."<br>";
		
		$con_uni_funcional=$ins_nit->ConsultarUnidadFuncionalPorId($nit);//CUENTA QUE MuEVE SEGUN LA UNIDAD FUNCIONAL
		$tie_uni_funcional=mssql_fetch_array($con_uni_funcional);
		
		$que_cue_2="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('$sigla','$factura','$fecha','$tie_uni_funcional[nit_uni_funcional]','108','$nit','$centro','$monto','2','$factura','3','$mes[1]','$ano')";
		$eje_cue_2=mssql_query($que_cue_2);
		//echo $que_cue_2."<br>";
	}
	elseif($concepto==109)//NOTA DEBITO
	{
		//GUARDAR NOTA AHORA
		$que_cue_1="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('$sigla','$factura','$fecha','41150105','109','$nit','$centro','$monto','2','$factura','3','$mes[1]','$ano')";
		$eje_cue_1=mssql_query($que_cue_1);
		//echo $que_cue_1."<br>";
		
		$con_uni_funcional=$ins_nit->ConsultarUnidadFuncionalPorId($nit);//CUENTA QUE MuEVE SEGUN LA UNIDAD FUNCIONAL
		$tie_uni_funcional=mssql_fetch_array($con_uni_funcional);
		
		$que_cue_2="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
		mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('$sigla','$factura','$fecha','$tie_uni_funcional[nit_uni_funcional]','109','$nit','$centro','$monto','1','$factura','3','$mes[1]','$ano')";
		$eje_cue_2=mssql_query($que_cue_2);
		//echo $que_cue_2."<br>";
	}
	 
  	 if($eje_cue_1 && $eje_cue_2)
  	    echo "<script type=\"text/javascript\">alert(\"Se actualizo el movimiento.\");</script>";
  	 else
  		echo "<script type=\"text/javascript\">alert(\" No Se actualizo el movimiento.\");</script>";		
    }
   unset($_SESSION["factura"]);
   unset($_SESSION["consecu"]);
   unset($_SESSION["nucleo"]);
 }
else
   echo "<script type=\"text/javascript\">alert(\"No se pudo guardo la nota, intente nuevamente.\");</script>";
    
echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=52'>";
?>