<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
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
  $fac = new factura();
  $conse = $fac->obt_consecutivo(13);
  $act = $fac->act_consecutivo(13);
  $_SESSION['num_rows'];
  $pagar = $_POST['pagar'];
  $val_pagar = $_POST['val_pagar'];
  $concepto = $_POST['concep'];
  $tran_sele = $_SESSION['tran'];
  $num_fac = $_POST['num_fac'];
  $descrip = $_POST['descr'];
  $fec = date('d-m-Y');
  $mes = split("-",$_SESSION['mes'],2);
  $ano = $_SESSION['elaniocontable'];
  $i = 0;
  while($i < $_SESSION['num_rows'])
   {
	  $act_transac = $transaccion->act_transaccion($pagar[$i],$concepto[$i]);
  	  $cen = $transaccion->obtener_concecutivo();
  	  $cue = mssql_fetch_array($cen);
  	  $transacciones = $cue['max_id'];
  	  $conse = $cue['max_id'] + 1;//tre el ultimo numro insertado en los consecutivos y suma 1
  	  $tran = $transaccion->dat_transaccion($pagar[$i]);
  	  $dat_tran = mssql_fetch_array($tran);
	  if($act_transac)
     {
	  echo "<script>alert('Transaccion actualizada satisfactoriamente.')</script>";
      $sigla = "PAG-FAC_".$conse;
      $nueTran = $transaccion->guaPagTransaccion($sigla,$fecha,$dat_tran['trans_nit'],$dat_tran['trans_centro'],
	  $val_pagar[$i],0,$fecha,$dat_tran['trans_fac_num'],$_SESSION['k_nit_id'],$fecha,$concepto[$i],$tran_sele[$i],$mes[1],$ano);
      if($nueTran)
       { 
         echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion.\");</script>";   
		 $ins_camMov = $mov_con->guarCam_movimiento($dat_tran['trans_centro'],$conse,$sigla,$dat_tran['trans_nit'],$fecha,$conse,$fecha,1,$val_pagar[$i],$concepto[$i],0,0,$mes[1],$ano);
		  if(!$ins_camMov)
		    {
			  $nom_prov = $nits->consul_nits($dat_tran['trans_nit']);
			  $dat_pro = mssql_fetch_array($nom_prov);
			  $nombre_prov[$i] =  $dat_pro['nits_nombres']." ".$dat_pro['nits_apellidos'];
			  $iden_pro[$i] = $dat_pro['nits_num_documento'];
			  $num_factura[$i] = $num_fac[$i];
			  $fecha[$i] = $fec;
			  $descripcion[$i] = $descrip[$i];
			  $iva[$i] = $cuen = $cuenta->val_iva($sigla,'iva');//iva
			  $retefuente[$i] = $cuenta->val_rete($sigla,'fuente');//retefuente
			  $reteiva[$i] = $cuenta->val_rete($sigla,'iva');//reteiva
			  $ica = $cuenta->val_iva($sigla,'ica');//ica
			  $reteica = $cuenta->val_rete($sigla,'ica');//reteica
			}
		  else
		   {
		     echo "<script type=\"text/javascript\">alert(\" No se actualizo el movimiento.\");</script>";
			 echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=73'>";
		   }
	    }
     }
     else
        echo "<script type=\"text/javascript\">alert(\"No se pudo guardar el encabezado, intente nuevamente.\");</script>";
	$i++;
   }
 
 $_SESSION['nom_pro'] = $nombre_prov;
 $_SESSION['iden_pro'] = $iden_pro;
 $_SESSION['num_fac'] = $num_factura;
 $_SESSION['fecha'] = $fecha;
 $_SESSION['descripcion'] = $descripcion;
 $_SESSION['val_neto'] = $val_pagar;
 $_SESSION['conse'] = $conse;
 $_SESSION['iva'] = $iva;
 $_SESSION['retefuente'] = $retefuente;
 $_SESSION['reteiva'] = $reteiva;
 $_SESSION['ica'] = $ica;
 $_SESSION['reteica'] = $reteica;
 echo "<script type=\"text/javascript\">alert(\"Se actualizo el movimiento.\");";
 echo "window.location.assign('../reportes_PDF/desembolso.php');</script>";
 ?>