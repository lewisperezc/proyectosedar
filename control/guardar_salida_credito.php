<?php session_start();

if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/chequera.class.php');
include_once('../clases/cuenta.class.php');

$bas_retencion=0;

$credito = new credito();
$chequera = new chequera();
$inst_transaccion = new transacciones();
$mov_con = new movimientos_contables();
$cuen_centro = new cuenta();
$cant_registros=$_POST['cant'];
$can_guardados=0;


$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE CONTABILIZACION DE CREDITO';



for($i=0;$i<$_POST['cant'];$i++)
{
	if(!empty($_POST['credito_id'.$i]))
	{
		$cre_sel=$_POST['credito_id'.$i];
		$valor =$_POST['valor'.$cre_sel];
		$centro = $_POST['centro'.$cre_sel];
		$nit = $_POST['nit'.$cre_sel];
		$cheque = $_POST['cheque'.$cre_sel];
		$valor = $_POST['valor'.$cre_sel];
		$tercero = $_POST['asoc'.$i];
		$centro = $_POST['centro'.$cre_sel];
		$cuenta_credito = $_POST['cue_cre'.$cre_sel];
		
		//$cuenta_banco = $_POST['cue_bancaria'.$i];
		$cuenta_banco = '23803001';
		$fec_des_credito=$_POST['fec_des_credito'.$i];
		$fec_con_credito=$_POST['fec_con_credito'.$i];
		
		if(trim($fec_des_credito)=="")
			$fec_des_credito=date('d-m-Y');
		if(trim($fec_con_credito)=="")
			$fec_con_credito=date('d-m-Y');
		
		
		//echo $fec_des_credito."___".$fec_con_credito."<br>";
		
		$cuenta = $credito->buscar_cuenta($cuenta_credito);	

		if($tercero!=""&&$cuenta_banco!="")
		{
			$can_guardados++;
			/****************obtener consecutivo de la transaccion*////////////////////////////////////////////
			//$fec_con_credito = date('d-m-Y');
			
			$conse = $cre_sel;
			///////////////////////////////////////////////////////////////////////////////////////////////////
			/*echo "<script>alert('Credito desembolsado satisfactoriamente')</script>";*/
			/***************obtener los datos del credito desembolsado para el registro*///////////////////////
			$dat_credito = $credito->con_dat_credito($cre_sel);
			$datos_credito = mssql_fetch_array($dat_credito);
			$sigla = "CRE_".$conse;
			/***********************************************************************************************/	

			$mes_sele = $_POST['mes_sele'];
			$mes_contable = split("-",$mes_sele);
			$nueTran = $inst_transaccion->guaTransaccion($sigla,$fec_con_credito,$tercero,$centro,$valor,0,$fec_con_credito,$cre_sel,$_SESSION['k_nit_id'],$fec_con_credito,$mes_contable[1],$ano);

			if($nueTran)
			{ 
			    $act = $credito->act_credito($cre_sel,$fec_des_credito);
			    /*echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion!!!\");</script>";*/
				//$fec_con_credito = date('d-m-Y');
				$cantidad_cuentas = 2;
				
			    $sql="EXECUTE insMovimiento '$sigla','$conse','".$cuenta."','".$cuenta_credito."',
				'".$nit."','".$centro."','".$valor."','1','$conse','".$cre_sel."','0','0','$fec_con_credito','$mes_contable[1]','$ano','$bas_retencion'";
				$ejecutar = mssql_query($sql);
				$sql2="EXECUTE insMovimiento '$sigla','$conse','".$cuenta_banco."','".$centro."','".$tercero."','".$centro."','".$valor."','2','$conse','".$cre_sel."','0','0','$fec_con_credito','$mes_contable[1]','$ano','$bas_retencion'";		
				$ejecutar2 = mssql_query($sql2);
				if($ejecutar && $ejecutar2)
				{
					$query = "SELECT COUNT(*) cant FROM mov_contable";
					$cant_mov = mssql_query($query);
					$cantidad = mssql_fetch_array($cant_mov);
					$can_cue = "SELECT can_cuenta FROM mov_contable GROUP BY can_cuenta";
					$cant_cue = mssql_query($can_cue);
					while($row = mssql_fetch_array($cant_cue))
					      $cantidad_cuentas = $cantidad_cuentas+$row['can_cuenta'];
					if($cantidad['cant'] == $cantidad_cuentas)
					   {
						 $mov = "EXECUTE movContable $cantidad_cuentas";
					     $ins_mov = mssql_query($mov);
					     
					     
						 //SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
						$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
						aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
						aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
						aud_mov_con_descripcion='$aud_mov_con_descripcion'
						WHERE mov_compro='$sigla' AND mov_mes_contable='$mes_contable[1]' AND mov_ano_contable='$ano'
						AND tip_mov_aud_id IS NULL";
						//echo $que_aud_mov_contable;
						$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
						 
						 
					     if($ins_mov)
							echo "<script>alert('Se contabilizo el credito exitosamente.');location.href='../index.php?c=146';</script>";
						}
				}
			}

		}
	}
}
if($can_guardados==0)
	echo "<script>alert('No se contabilizo ningun credito debido a que ninguno fue seleccionado.');location.href='../index.php?c=146';</script>";

?>