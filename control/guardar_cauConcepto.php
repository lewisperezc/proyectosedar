<?PHP
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }

function array_column($array, $column)
{
    $ret = array();
    foreach ($array as $row) $ret[] = $row[$column];
    return $ret;
}

$ano = $_SESSION['elaniocontable'];
include_once('../clases/transacciones.class.php');
include_once('../clases/recibo_caja.class.php');
include_once('../clases/comprobante.class.php');
include_once('../clases/credito.class.php');
include_once('../clases/orden_desembolso.class.php');
$ins_ord_desembolso=new orden_desembolso();
$ins_rec_caja = new rec_caja();
$comprobante= new comprobante();
$ins_credito=new credito();
$des_credito=array();


$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE DOCUMENTO DE CONTABILIDAD';
 


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
if(!empty($_GET['mod_credito']))//UNIFICACION DE CREDITOS
{
	$aud_mov_con_descripcion='CREACION DE UNIFICACION DE CREDITO';
	$cre_unificar=$_POST['cre_conta'];
	$fec_causacion = $_POST['fec_con_credito'];
	$fec_creacion = $_POST['fec_con_credito'];
	$cen_costo = 1169;
	$descrip_causacion = "UNIFICACION DE CREDITOS";
	$total = $_POST['tot_deb'];
	if(empty($total))
		$total=$_POST['tot_deb'];

	$mes = $_POST['mes_sele'];
	$ano=$_SESSION['elaniocontable'];
	if($mes=="")
		$mes = $_SESSION['me'];
	$mes_con = split("-",$mes,2);
	
	
	$conce = $comprobante->cons_comprobante($ano,$mes_con[1],47);
	$sig = $comprobante->sig_comprobante(47);
	$comprobante->act_comprobante($ano,$mes_con[1],47);
	$sigla = $sig.$conce;
	
	//$sigla = "CRE_".$_POST['credito_contabilizar'];
	//echo $sigla;
	
	$fec_des_credito=$_POST['fec_des_credito'];//FECHA DESEMBOLSO
	
	//ACTUALIZA LA FECHA DE DESEMBOLSO DEL CREDITO
	$act_fec_des_credito=$ins_credito->act_credito($_POST['credito_contabilizar'],$fec_des_credito);
}
else
{
	//echo "entra por el else";
	$fec_causacion = $_POST['cau_fecha'];
	$fec_creacion = $_POST['cau_fecCreacion'];
	$cen_costo = $_POST['centro'];
	$descrip_causacion = $_POST['desc'];
	$total = $_POST['tot_deb'];
	if(empty($total))
		$total=$_POST['tot_deb'];

	$mes = $_POST['mes_sele'];
	$ano=$_SESSION['elaniocontable'];
	if($mes=="")
		$mes = $_SESSION['me'];	
	$mes_con = split("-",$mes,2);

	$conce = $comprobante->cons_comprobante($ano,$mes_con[1],$_POST['conce']);
	$sig = $comprobante->sig_comprobante($_POST['conce']);
	$comprobante->act_comprobante($ano,$mes_con[1],$_POST['conce']);
	$sigla = $sig.$conce;

	$diferido = $_POST['can_diferido']."-".$_POST['cuenta_gasto'];
	if($diferido)
	{
		$ult_transaccion = $transaccion->obtener_concecutivo();
		$obt_consecu = mssql_fetch_array($ult_transaccion);
		$sql="UPDATE transacciones SET trans_diferido='$diferido' WHERE trans_id=".$obt_consecu['max_id'];
		$query = mssql_query($sql);
	}
}

$can_registros = $_POST['cant_gasto'];
$num_factura = $_POST['num_doc'];

$c=0;
$array_cuentas=array();
while($c<=$can_registros)
{
	$array_cuentas[]=$_POST['cuenta'.$c];
	$c++;
}
$cre='';
for($k=0;$k<=$can_registros;$k++)
{
	$cuenta = $_POST['cuenta'.$k];
	$descr = $_POST['desc'.$k];
	$prove = $_POST['prove'.$k];
	$pagare = $_POST['pagare'.$k];
	$debito = $_POST['debito'.$k];
	$credito = $_POST['credito'.$k];
	$bas_retencion=$_POST['bas_retencion'.$k];
	
	if(!empty($_GET['mod_credito']))
	{
		if($pagare!=0)
		{
			if($debito>0)
				$valor=$debito;
			else
				$valor=$credito;
			array_push($des_credito, array('credito' => $pagare,
	                      	 			   'cuenta'  => $cuenta,
	                      	 			   'nit'     => $prove,
	                      	 			   'valor'   => $valor));
		}
	}
	if($k==$can_registros)
		$nueTran = $transaccion->guaTransaccionCau(strtoupper($sigla),$fec_causacion,$prove,$cen_costo,$total,0,$fec_creacion,$num_factura,$_SESSION['k_nit_id'],$fec_creacion,$mes_con[1],strtoupper($descrip_causacion),$ano);
	if($debito>0)
		$sql ="EXECUTE insMovimiento '$sigla','$num_factura','$cuenta','2','$prove','$cen_costo','$debito','1','$num_factura','3','0','$can_registros','$fec_creacion','$mes_con[1]','$ano','$bas_retencion'";
	elseif($credito>0)
		$sql ="EXECUTE insMovimiento '$sigla','$num_factura','$cuenta','2','$prove','$cen_costo','$credito','2','$num_factura','3','0','$can_registros','$fec_creacion','$mes_con[1]','$ano','$bas_retencion'";
	$query = mssql_query($sql);

	if(!empty($_GET['mod_credito']))
	{
		$creditos = array_column($des_credito, 'credito');
		//echo "el tam es: ".sizeof($creditos)."<br>";
		for($p=0;$p<sizeof($creditos);$p++)
		{
			//echo $p."<br>";
			if($k==0)
			{
				$con_fil_guardadas=$ins_credito->GuardarUnSoloRegistroUnificacionCreditos($des_credito[$p]['credito'],
  $des_credito[$p]['nit'],$cre_unificar,3,$fec_creacion,$sigla,$mes_con[1],$ano);
  				
				$num_fil_guardadas=mssql_num_rows($con_fil_guardadas);
  				
				
				if($num_fil_guardadas==0)
				{
				
					if(substr($des_credito[$p]['cuenta'],0,2)==13&&$_POST['credito_contabilizar']!=$des_credito[$p]['credito'])//$des_credito[$p]['nit'],3,$sigla
						$ins_credito->des_cuoCredito($des_credito[$p]['valor'],0,$des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
					
					elseif($des_credito[$p]['credito']!=0)
						$ins_credito->des_cuoCredito(0,$des_credito[$p]['valor'],$des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
					$cre=$des_credito[$p]['credito'];
				
				}
				
				if(substr($des_credito[$p]['cuenta'],0,2)==42)//INTERESES
				{
					$con_fil_guardadas=$ins_credito->GuardarUnSoloRegistroUnificacionCreditos($des_credito[$p]['credito'],
  $des_credito[$p]['nit'],$cre_unificar,3,$fec_creacion,$sigla,$mes_con[1],$ano);
  				
					$can_registro=mssql_num_rows($con_fil_guardadas);
  
  					if($can_registro>0)
					{
						$act_int_credito=$ins_credito->ActualizarInteresCredito($des_credito[$p]['valor'],$des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
						$act_cuo_tot_credito=$ins_credito->ActualizarCuotaTotalCredito($des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
					}				
				}
			}
			else
			{
				//echo $des_credito[$p]['credito']."-*-".$cre."<br>";
				if($des_credito[$p]['credito']!=$cre)
				{
					$con_fil_guardadas=$ins_credito->GuardarUnSoloRegistroUnificacionCreditos($des_credito[$p]['credito'],
  $des_credito[$p]['nit'],$cre_unificar,3,$fec_creacion,$sigla,$mes_con[1],$ano);
  				
					$num_fil_guardadas=mssql_num_rows($con_fil_guardadas);
					
					if($num_fil_guardadas==0)
					{
						if(substr($des_credito[$p]['cuenta'],0,2)==13&&$_POST['credito_contabilizar']!=$des_credito[$p]['credito'])
						$ins_credito->des_cuoCredito($des_credito[$p]['valor'],0,$des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
						
						elseif($des_credito[$p]['credito']!=0)
						$ins_credito->des_cuoCredito(0,$des_credito[$p]['valor'],$des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
						$cre=$des_credito[$p]['credito'];
					}
				}
				
				if(substr($des_credito[$p]['cuenta'],0,2)==42)//INTERESES
				{
					$con_fil_guardadas=$ins_credito->GuardarUnSoloRegistroUnificacionCreditos($des_credito[$p]['credito'],
  $des_credito[$p]['nit'],$cre_unificar,3,$fec_creacion,$sigla,$mes_con[1],$ano);
  				
					$can_registro=mssql_num_rows($con_fil_guardadas);
  
  					if($can_registro>0)
					{
						$act_int_credito=$ins_credito->ActualizarInteresCredito($des_credito[$p]['valor'],$des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
						$act_cuo_tot_credito=$ins_credito->ActualizarCuotaTotalCredito($des_credito[$p]['credito'],$cre_unificar,$fec_creacion,$des_credito[$p]['nit'],3,$sigla,$mes_con[1],$ano);
					}				
				}
			}	
		}
		//$ins_credito->act_credito($_POST['credito_contabilizar'],$fec_creacion);
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
WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_con[1]' AND mov_ano_contable='$ano'
AND tip_mov_aud_id IS NULL";
//echo $que_aud_mov_contable;
$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);


$consecutivo=$_POST['acutaliza'];
if(isset($_POST['conce']))
	$act_rec_caja = $ins_rec_caja->act_consecutivo($_POST['conce']);
echo "<script>alert('Se guardo la causacion con exito.');</script>";
echo "<script language='javascript'>abreFactura('../reportes_PDF/causacion_pago.php?sigla=".strtoupper($sigla)."&mes=".$mes_con[1]."');history.back(-1);</script>";
//history.back(-1);
?>