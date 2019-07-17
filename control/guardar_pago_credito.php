<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/cuenta.class.php');


$bas_retencion=0;

$credito = new credito();
$ins_cuenta = new cuenta();
$cuenta_credito = $_POST['cue_cre'];
$inst_transaccion = new transacciones();
$mov_con = new movimientos_contables();
$cre_seleccionado = $_POST['pagar'];
$fecha = date('d-m-Y');
$act = $credito->act_credito($cre_seleccionado,$fecha);
$valor = $_POST['valor'];
$_SESSION['val_pagar'] = $valor;
$nit = $_POST['nit'];
$centro = $_POST['centro'];
$cuenta = $_POST['cue_bancaria'];
$_SESSION['conc'] = 199;
  $consecutivo = $inst_transaccion->obtener_concecutivo();
  $cue = mssql_fetch_array($consecutivo);
  $transacciones = $cue['max_id'];
  $conse = $cue['max_id'] + 1;
  echo "<script>alert('Credito desembolsado satisfactoriamente.')</script>";
  $dat_credito = $credito->con_dat_credito($cre_seleccionado);
  $datos_credito = mssql_fetch_array($dat_credito);
  $sigla = "CRE_".$conse;
  $ano = $_SESSION['elaniocontable'];
  //$cue_centro = $ins_cuenta->cue_centro($centro[$cre_seleccionado]);
  $nueTran = $inst_transaccion->guaTransaccion($sigla,$fecha,$nit[$cre_seleccionado],$centro[$cre_seleccionado],$valor[$cre_seleccionado],0,$fecha,$cre_seleccionado,$_SESSION['k_nit_id'],$fecha,199,$ano);
  if($nueTran)
       { 
         echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion.\");</script>";
		 $fecha = date('d-m-Y');
		 $cuenta2 = $cuenta_credito;
	     $cantidad_cuentas = 2;
         $sql="EXECUTE insMovimiento '$sigla','$conse','$cuenta2','199','$nit[$cre_seleccionado]','$centro[$cre_seleccionado]',
		 '$valor[$cre_seleccionado]','1','$conse','$cre_seleccionado','0','0','$fecha','$ano','$bas_retencion'";
		$ejecutar = mssql_query($sql);
		$sql2="EXECUTE insMovimiento '$sigla','$conse','$cuenta[$cre_seleccionado]','199','$nit[$cre_seleccionado]','$centro[$cre_seleccionado]',
		 '$valor[$cre_seleccionado]','2','$conse','$cre_seleccionado','0','0','$fecha','$ano','$bas_retencion'";
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
			   $sql= "INSERT INTO paso_saldo_cuentas SELECT uno,ocho,nueve,cuatro,seis,siete,tres 
			          FROM dbo.mov_contable";	  
			   $ejecutar = mssql_query($sql);
			   if($ejecutar)
			    {
			      $sql_2 = "SELECT * FROM paso_saldo_cuentas";
	              $ejecutar_2 = mssql_query($sql_2);
			      while($row = mssql_fetch_array($ejecutar_2))
			       {	
				     $sql3 = "EXECUTE ConCuenPorCenCos $row[3],$row[6],$row[4],'$row[7]',$row[5],'$row[1]'";
				     $exec = mssql_query($sql3);
                   }
				  
				  $sql_2 = "EXECUTE TrunPasSaldos";
	              $ejecutar_2 = mssql_query($sql_2);
				}
				$mov = "EXECUTE movContable $cantidad_cuentas";
		        $ins_mov = mssql_query($mov);
		        if($ins_mov)
		          return true;
		        else
		         return false;
			}
		 }
	   }
  else
	  {
		 echo "<script type=\"text/javascript\">alert(\" No Se actualizo el movimiento.\");</script>";
		 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=73'>";
	  }	
?>