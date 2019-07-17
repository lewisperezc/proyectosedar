<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  
  include_once('../clases/concepto.class.php');
  include_once('../clases/credito.class.php');
  include_once('../clases/transacciones.class.php');
  include_once('../clases/moviminetos_contables.class.php');
  include_once('../clases/nits.class.php');
  include_once('../clases/cuenta.class.php');
  include_once('../clases/factura.class.php');
  
  $cuenta = new cuenta();
  $transaccion = new transacciones();
  $mov_con = new movimientos_contables();
  $nits = new nits();
  $cuenta = new cuenta();
  $transaccion = new transacciones();
  $mov_con = new movimientos_contables();
  $nits = new nits();
  $concep = new concepto();
  
  
  $bas_retencion=0;
  
   function calc_interes($credito)
   {
	 $cred = new credito();
	 $dias = $cred->ult_pago($credito);
 	 $num_dias = mssql_fetch_array($dias);
 	 $resultado = $cred->int_diario($credito);
 	 $res = mssql_fetch_array($resultado);
 	 $interes = $res['cre_interes'];
 	 $int_total = ($interes/365)*$num_dias['dias'];
	 return $int_total;
   } 
  
  $saldo = strtoupper($_POST['val_saldo']);
  $mon_pagar = strtoupper($_POST['val_pagar']);
  $descripcion = strtoupper($_POST['descr']);
  $concepto = strtoupper($_POST['concep']);

  $pagar = strtoupper($_POST['pagar']);
  $credito = strtoupper($_SESSION['credito']);
  $cuen_credito = strtoupper($_SESSION['cuen_credito']);
  $mes = strtoupper($_SESSION['mes']);
 
  $fecha = date('d-m-Y');
  $act_transac = $transaccion->act_transaccion($pagar,$concepto[$pagar]);
  $cen = $transaccion->obtener_concecutivo();
  $cue = mssql_fetch_array($cen);
  $transacciones = $cue['max_id'];
      
  $_SESSION['conc'] = $concepto;
  $_SESSION['val_pagar'] = $pagar;
  $_SESSION['monto'] = $mon_pagar;
  
  $conse = $cue['max_id'] + 1;//tre el ultimo numro insertado en los consecutivos y suma 1
  $tran = $transaccion->dat_transaccion($pagar);
  $dat_tran = mssql_fetch_array($tran);
  if($act_transac)
     {
	  echo "<script>alert('Transaccion actualizada satisfactoriamente!')</script>";
      $sigla = "PAG-CRE_".$conse;
	  $cue_centro = $cuenta->cue_centro($dat_tran['trans_centro']);
      $nueTran = $transaccion->guaPagTransaccion($sigla,$fecha,$dat_tran['trans_nit'],$dat_tran['trans_centro'],$mon_pagar[$pagar],0,$fecha,$dat_tran['trans_fac_num'],$_SESSION["k_nit_id"],$fecha,$concepto[$pagar],$pagar,$ano);
	  if($nueTran)
       {
         echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion!!!\");</script>";
	     $form = $mov_con->consul_formulas($concepto[$pagar]);//buscar la formula del concepto
         $i=1;$matriz;
         if($form)
          {
           $row = mssql_fetch_array($form);
           while($i<=21)
            {
	         $arre = split(",",$row["for_cue_afecta".$i]);
		     $a = $arre[0];
		     $b = $arre[1];
		     $c = $arre[2];
		     $d = $arre[3];
		     if($a != "" && $b != "" && $c != "")
		 	  {
			    $matriz[$i][0]= $a;
			    $matriz[$i][1]= $b;
			    $matriz[$i][2]= $c;
			    $matriz[$i][3]= $d;
			  }
		     $i++;	
		    }//cierra el while
			$movimiento = $mov_con->guaMovimiento($sigla,$credito[$pagar],$matriz[1][1],$concepto[$pagar],$dat_tran['trans_nit'],$$dat_tran['trans_centro'],$mon_pagar[$pagar],$matriz[1][2],$credito[$pagar],3,sizeof($matriz),0,(sizeof($matriz)-1),$bas_retencion);
		 $val_total = calc_interes($credito[$pagar]); 				  
    	    $movimiento = $mov_con->guaMovimiento($sigla,$credito[$pagar],$cuen_credito[$pagar],$concepto[$pagar],
			$dat_tran['trans_nit'],$$dat_tran['trans_centro'],$val_total,$matriz[2][2],$credito[$pagar],3,sizeof($matriz),0,(sizeof($matriz)-1),$bas_retencion);
		    $val_movimiento = $mon_pagar[$pagar]-$val_total;
		    $movimiento = $mov_con->guaMovimiento($sigla,$credito[$pagar],$matriz[3][1],$concepto[$pagar],$dat_tran['trans_nit'],$$dat_tran['trans_centro'],$val_movimiento,$matriz[3][2],$credito[$pagar],3,sizeof($matriz),0,(sizeof($matriz)-1),$bas_retencion);
		  if(!$ins_camMov)
		    {
			  $nom_prov = $nits->consul_nits($dat_tran['trans_nit']);
			  $dat_pro = mssql_fetch_array($nom_prov);
			  $nombre_prov[$i] =  $dat_pro['nits_nombres']."".$dat_pro['nits_apellidos'];
			  $iden_pro[$i] = $dat_pro['nits_num_documento'];
			  $num_factura[$i] = $num_fac[$i];
			  $fecha[$i] = $fecha;
			  $descripcion[$i] = $descripcion[$i];
			  $neto[$i] = $val_pagar[$i];
			  $iva[$i] = $cuen = $cuenta->val_iva($sigla,'iva');//iva
			  $retefuente[$i] = $cuenta->val_rete($sigla,'fuente');//retefuente
			  $reteiva[$i] = $cuenta->val_rete($sigla,'iva');//reteiva
			  $ica = $cuen = $cuenta->val_iva($sigla,'ica');//ica
			  $reteica = $cuenta->val_rete($sigla,'ica');//reteica
			}
	    }				
	echo "<script type=\"text/javascript\">location.href = '../reportes_PDF/recibo_caja.php'</script>";
     }
     else{
      echo "<script type=\"text/javascript\">alert(\"No se pudo guardar el encabezado, intente nuevamente!!!\");</script>";
	  //LIMPIAR SESSIONES//
		unset($_SESSION['persona_id']);
		unset($_SESSION['cre_consecutivo']);
		unset($_SESSION['nombres']);
		unset($_SESSION['direccion']);
		unset($_SESSION['credito']);
		unset($_SESSION['cuen_credito']);
		unset($_SESSION['mes']);
		unset($_SESSION['conc']);
		unset($_SESSION['val_pagar']);
		unset($_SESSION['monto']);
		/////////////////////
	 }
   }
 ?>