<?php session_start();

 if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
 $ano = $_SESSION['elaniocontable'];
 include_once('../clases/recibo_caja.class.php');
 include_once('../clases/transacciones.class.php');
 include_once('../clases/factura.class.php');
 include_once('../clases/moviminetos_contables.class.php');
 include_once('../clases/cuenta.class.php');
 include_once('../clases/contrato.class.php');
 include_once('../clases/reporte_jornadas.class.php');
 include_once('../clases/varios.class.php');
 include_once('../clases/nits.class.php');
 
 $bas_retencion=0;
 
 $ins_varios=new varios();
 $ins_rec_caja = new rec_caja();
 $inst_transaccion = new transacciones();
 $ins_factura = new factura();
 $inst_mov_contable = new movimientos_contables();
 $ins_cuenta = new cuenta();
 $ins_contrato = new contrato();
 $ins_repJornadas = new reporte_jornadas();
 $ins_nit=new nits();
 $val_impu = 0;
 $impuestos = $_POST['impu_id'];
 $impu_id = split("-",$impuestos);
 
 
 
 
$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE RECIBO DE CAJA';
 
 
 
 
 
 for($i=1;$i<$_SESSION['des_impuestos'];$i++)
 {
	 $val_impu += $_POST['val'.$i];
	 $val_impuesto[$i] = $_POST['val'.$i];
	 $cue_impuesto[$i] = $_POST['cuenta'.$i];
 }
 
$abono = $_POST['abono'];
if(!$abono)
 { 
   for($i=0;$i<sizeof($impu_id)-1;$i++)
	 $ins_contrato->act_impuesto($impu_id[$i]);
 }

//Datos De La Factura
$fac_consecutivo = $_POST['fac_id'];
$fac_nit = $_POST['nit'];
$fac_cen_costo = $_POST['cen_costo'];
$fac_val_total=$_POST['val_tot_factura'];
$val_abo_rec_caja = $_POST['val_abo_rec_caja'];
$consecutivo_rec_caja = $_POST['conse_rec_caja'];
$fec_rec_caja = $_POST['fec_rec_caja'];
$not_rec_caja = strtoupper($_POST['not_rec_caja']);
$concep_rec_caja = $_POST['concep_rec_caja'];
$adm_rec_caja = $_POST['adm_rec_caja'];
$glo_ace_rec_caja = $_POST['glo_ace_rec_caja'];

if($glo_ace_rec_caja==0)
	$glo_ace_rec_caja = $_POST['glo_ace_rec_caja1'];

$glo_pen_ace_rec_caja = $_POST['glo_pen_ace_rec_caja'];

if($glo_pen_ace_rec_caja==0)
	$glo_pen_ace_rec_caja = $_POST['glo_pen_ace_rec_caja1'];

$des_rec_caja = $_POST['des_rec_caja'];
$imp_tim_rec_caja = $_POST['imp_tim_rec_caja'];
$ret_en_la_fue_rec_caja = $_POST['ret_en_la_fue_rec_caja'];
$pro_hos_rec_caja = $_POST['pro_hos_rec_caja'];
$ica_rec_caja = $_POST['ica_rec_caja'];
$pro_des_rec_caja = $_POST['pro_des_rec_caja'];
$otr_des_rec_caja = $_POST['otr_des_rec_caja'];
$val_net_rec_caja = $_POST['val_net_rec_caja'];


$_SESSION['consecutivo'] = $fac_consecutivo;
$_SESSION['nit'] = $fac_nit;
$_SESSION['nit_imp'] = $fac_nit;
$_SESSION['cen_costo'] = $fac_cen_costo;
$_SESSION['total_fac'] = $fac_val_total;
$_SESSION['abono'] = $abono;
$_SESSION['valor_recibo'] = $val_abo_rec_caja;
$_SESSION['consecutivo'] = $consecutivo_rec_caja;
$_SESSION['fecha_recibo'] = $fec_rec_caja;
$_SESSION['nota_recibo'] = $not_rec_caja;
$_SESSION['concepto'] = $concep_rec_caja;
$_SESSION['nota_recibo'] = $not_rec_caja;
$_SESSION['concepto'] = $concep_rec_caja;
$_SESSION['glosa_aceptada'] = $glo_ace_rec_caja;
$_SESSION['administracion'] = $adm_rec_caja;
$_SESSION['glosa_pendi'] = $glo_pen_ace_rec_caja;
$_SESSION['descuento'] = $des_rec_caja;
$_SESSION['impuesto'] = $imp_tim_rec_caja;
$_SESSION['retencion'] = $ret_en_la_fue_rec_caja;
$_SESSION['pro_hospital'] = $pro_hos_rec_caja;
$_SESSION['ica'] = $ica_rec_caja;
$_SESSION['pro_desarrollo'] = $pro_des_rec_caja;
$_SESSION['otros_descuentos'] = $otr_des_rec_caja;
$_SESSION['neto'] = $val_net_rec_caja;
$cue_centro = $ins_cuenta->cue_centro($_SESSION['cen_costo']);
//
$sal_fecha = $_POST['sal_fecha'];
$_SESSION['sal_fecha'] = $sal_fecha;
//
$mes = split("-",$_POST['mes_sele'],2);
if($val_abo_rec_caja && $val_net_rec_caja)
  {
	$val_con_descuentos = $val_abo_rec_caja - $val_net_rec_caja;
	$val_recibo = $val_abo_rec_caja;
	$_SESSION['valor'] = $val_recibo;
  }
elseif($val_abo_rec_caja)
  {
	$val_con_descuentos = $val_abo_rec_caja - $val_net_rec_caja;
	$val_recibo = $val_abo_rec_caja;
	$_SESSION['valor'] = $val_recibo;
  }
    else
    {
	  $val_con_descuentos = $fac_val_total - $val_net_rec_caja;
	  $val_recibo = $fac_val_total;
	  $_SESSION['valor'] = $val_recibo;
    }
	
$val_con_descuentos = $val_con_descuentos;	
//////////////////////////////////////////////////////////////////////////////////////////////////////
//INICIO OBTENGO EL ID DE LA TRANSACCION DE LA FACTURA
$obt_num_transaccion = $inst_transaccion->num_tran($fac_consecutivo);
//FIN OBTENGO EL ID DE LA TRANSACCION DE LA FACTURA
//Guardar los descuentos de la factura
//Estado 2 es NO PAGADA
if($abono && $val_abo_rec_caja)
{
	if($_SESSION['sal_fecha']==$_SESSION['valor_recibo'])
	{
		$act_fac_a_abono = $ins_factura->act_est_factura(1,$fac_consecutivo);
		//DEBO CALCULAR
	}
	else
		$act_fac_a_abono = $ins_factura->act_est_factura(2,$fac_consecutivo);
}
else
	$act_fac_a_abono = $ins_factura->act_est_factura(1,$fac_consecutivo);

$conse_reccaja = $ins_rec_caja->obt_consecutivo(15);
$act_rec_caja = $ins_rec_caja->act_consecutivo(15);

$gua_recibo=$ins_rec_caja->guardar_recibos($fac_consecutivo,$fec_rec_caja,$val_con_descuentos,$not_rec_caja,$conse_reccaja);

$con_ult_rec_caja=$ins_rec_caja->sel_max_rec_caja();
$res_ult_rec_caja=mssql_fetch_array($con_ult_rec_caja);

/*GUARDAR LAS GLOSAS*/
$sel_glosa = $_POST['glo_ace'];
if($sel_glosa==2)
{
    if($_SESSION['jor_abono']==1)//CONSULTAR EL ID DE LAS JORNADAS POR ABONO(rep_jor_con_recibo)
    {
        $con_jor_abono=$ins_repJornadas->buscarReporteJornadas_Factura_Abono($fac_consecutivo);
        $z=0;
        while($res_jor_abono=mssql_fetch_array($con_jor_abono))
        {
            $ins_repJornadas->distGlosa($res_jor_abono['rep_jor_con_rec_id'],$_POST['jorna'.$z],$res_ult_rec_caja['rec_caj_id']);
            $z++;
        }
    }
    else
    {
		for($p=0;$p<$_SESSION['tot_asociados'];$p++)
		{
			if($_POST['jorna'.$p]!="" && $_POST['jorna'.$p]>0)
	                    $ins_repJornadas->distGlosa($_POST['repJor'.$p],$_POST['jorna'.$p],$res_ult_rec_caja['rec_caj_id']);
		}
    }
}

$sel_pen = $_POST['glo_pen'];
if($sel_pen==2)
{
    if($_SESSION['jor_abono']==1)//CONSULTAR EL ID DE LAS JORNADAS POR ABONO(rep_jor_con_recibo)
    {
        $con_jor_abono_pendiente=$ins_repJornadas->buscarReporteJornadas_Factura_Abono($fac_consecutivo);
        $z=0;
        while($res_jor_abono_pendiente=mssql_fetch_array($con_jor_abono_pendiente))
        {
            $ins_repJornadas->distGlosa($res_jor_abono_pendiente['rep_jor_con_rec_id'],$_POST['jorna_pen'.$z],$res_ult_rec_caja['rec_caj_id']);
            $z++;
        }
    }
    else
    {
	for($p=0;$p<$_SESSION['tot_asociados'];$p++)
	{
		if($_POST['jorna_pen'.$p]!="" && $_POST['jorna_pen'.$p]>0)
                    $ins_repJornadas->distGlosa($_POST['repJor_pen'.$p],$_POST['jorna_pen'.$p],$res_ult_rec_caja['rec_caj_id']);
	}
    }
}
///////////////////////////////////////////////////////



$dat_factura=$ins_factura->datFactura($fac_consecutivo);
$tod_fac = mssql_fetch_array($dat_factura);

	if($gua_recibo && $act_rec_caja)
	{
		echo "<script>alert('Recibo de caja registrado correctamente.');</script>";
        $consecutivo = $inst_transaccion->obtener_concecutivo();
        if($val_abo_rec_caja!="")
        { $val_abo_rec_caja = $fac_val_total; }
        $cue = mssql_fetch_array($consecutivo);
        $transacciones = $cue['max_id'];
        $conse = $cue['max_id'] + 1;
        $sigla = "REC-CAJ_".$conse_reccaja;
        $ult_rec_caja = $ins_rec_caja->sel_max_rec_caja();
        $resultado = mssql_fetch_array($ult_rec_caja);
        $nueTran = $inst_transaccion->guaPagTransaccion(strtoupper($sigla),$fec_rec_caja,$fac_nit,$fac_cen_costo,$val_con_descuentos,0,$fec_rec_caja,$resultado['rec_caj_id'],$_SESSION['k_nit_id'],$fec_rec_caja,102,$obt_num_transaccion,$mes[1],$ano);
		$form = $inst_mov_contable->consul_formulas($concep_rec_caja);
    	$i=1;$matriz;
		$cantidad_cuentas = 2;
    	if($form)
     	{
      		$dat_matriz = mssql_fetch_array($form);
      		while($i<=21)
      		{
			   $arre = split(",",$dat_matriz["for_cue_afecta".$i]);
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
	  		}
	$fecha = date('d-m-Y');
	$cuenta = $matriz[1][1];
//	if($matriz[1][2]==1)
  	$naturaleza = $matriz[1][2];
	/*else
	   $naturaleza = 1;	*/
	
	
	//GUARDAR RECIBO CAJA ANTES
	//$sql1="EXECUTE insMovimiento '$sigla','$fac_consecutivo','$cuenta','3','".$_SESSION["nit"]."','".$_SESSION['cen_costo']."','$val_recibo','$naturaleza','$conse','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";//recibo_caja
	//$sql2="EXECUTE insMovimiento '$sigla','$fac_consecutivo','$cuenta','3','".$_SESSION["nit"]."','".$_SESSION['cen_costo']."',' $val_con_descuentos','$naturaleza','$conse','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";//Banco
	
	//GUARDAR RECIBO CAJA AHORA
	$transacc = $inst_transaccion->obtener_concecutivo();
	$num_tran = mssql_fetch_array($transacc);
	
	
	$cuenta='11100524';
	$naturaleza=1;
	$sql1="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,mov_doc_numer,mov_mes_contable,mov_ano_contable)
	VALUES('$sigla','$fac_consecutivo','$fecha','$cuenta','102','".$_SESSION["nit"]."','".$_SESSION['cen_costo']."','$val_con_descuentos','$naturaleza','$num_tran[0]','3','$mes[1]','$ano')";//Banco
	//echo $sql1."<br>";
	$query1 = mssql_query($sql1);
	$_SESSION['cue_concep'][0] = $cuenta."-".$naturaleza."-".$val_recibo;
	
	
	$con_uni_funcional=$ins_nit->ConsultarUnidadFuncionalPorId($_SESSION["nit"]);//CUENTA QUE MuEVE SEGUN LA UNIDAD FUNCIONAL
	$tie_uni_funcional=mssql_fetch_array($con_uni_funcional);
	
	$cuenta = $tie_uni_funcional['nit_uni_funcional'];
  	$naturaleza = 2;
	$sql2="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
	mov_doc_numer,mov_mes_contable,mov_ano_contable)
	VALUES('$sigla','$fac_consecutivo','$fecha','$cuenta','102','".$_SESSION["nit"]."','".$_SESSION['cen_costo']."','$val_recibo','$naturaleza','$num_tran[0]','3','$mes[1]','$ano')";//Unidad funcional
	//echo $sql2."<br>";
  	$query2 = mssql_query($sql2);
	
	$_SESSION['cue_concep'][1] = $cuenta."-".$naturaleza."-".$val_con_descuentos;
	
    echo "<script type=\"text/javascript\">alert(\"Se guardo el encabezado de la transaccion.\");</script>";
	
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['glosa_aceptada'],1,$sel_glosa);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['glosa_pendi'],2,$sel_pen);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['impuesto'],3,2);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['pro_hospital'],4,2);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['pro_desarrollo'],5,2);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['administracion'],6,2);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$des_rec_caja,7,2);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['retencion'],8,2);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['ica'],9,2);
    $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$_SESSION['otros_descuentos'],10,2);
    /*if($tod_fac['fac_mes_servicio']<9 && $tod_fac['fac_ano_servicio']<=2013)
		$uno=($val_recibo*0.5)/100;//1% DE LA FACTURA
	else
		$uno=0;
	$descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$uno,12,2);*/
	//INSERT INTO descuentos(des_factura,des_monto,des_tipo) VALUES($factura,$monto,$tipo

	$fecha = date('d-m-Y');
	
	
	
	$que_nit_factura="SELECT * FROM factura f INNER JOIN recibo_caja rc ON f.fac_id=rc.rec_caj_factura WHERE rc.rec_caj_id='$resultado[rec_caj_id]'";
	$eje_nit_factura=mssql_query($que_nit_factura);
	$res_nit_factura=mssql_fetch_array($eje_nit_factura);
	
	$nit_san_jorge=1323;
	if($res_nit_factura['fac_nit']==$nit_san_jorge)
	{
		
		$res_por_legalizacion=$ins_varios->ConsultarDatosVariablesPorId(13);
		//echo "el % de leg es: ".$res_por_legalizacion['var_valor']."<br>";
		$val_legalizacion=round($val_recibo*$res_por_legalizacion['var_valor']/100,0);
		
		$descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$val_legalizacion,11,2);
	}
	
	else
	{
		for($i=1;$i<=sizeof($val_impuesto);$i++)
	 	{
		 $descuento = $ins_rec_caja->guardar_descuentos($resultado['rec_caj_id'],$val_impuesto[$i],11,2);	
		 $dat_cueImpuesto = split(",",$cue_impuesto[$i],4);
		 $cue_impuFormula = trim($dat_cueImpuesto[1]);
		 if($dat_cueImpuesto[2]==1)
		 {
		    $nat_impuFormula = 2;
			$nat_cosFormula = 1;
		 }
	     else
		 { 
		   	$nat_impuFormula = 1;	
			$nat_cosFormula = 2;
		 }
		 /*$sql="EXECUTE insMovimiento '$sigla',$conse,'".$cue_impuFormula."',102,$fac_nit,".$_SESSION['cen_costo'].",".$val_impuesto[$i].",".$nat_impuFormula.",'$consecutivo_rec_caja','3',0,$cantidad_cuentas,'$fecha',$mes[1],$ano,'$bas_retencion'";
		 $query = mssql_query($sql);
		 $sql="EXECUTE insMovimiento '$sigla',$conse,'51955001',102,$fac_nit,".$_SESSION['cen_costo'].",".$val_impuesto[$i].",".$nat_cosFormula.",'$consecutivo_rec_caja','3',0,$cantidad_cuentas,'$fecha',$mes[1],$ano,'$bas_retencion'";
		 $query = mssql_query($sql);
		 $cantidad_cuentas+=2;*/
	 	}
	}
	
	if($val_con_descuentos)
	{
	  	if($_SESSION['glosa_aceptada']!="" && $_SESSION['glosa_aceptada']!=0 )
		{
			$sql="EXECUTE insMovimiento '$sigla','$fac_consecutivo','41751501','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['glosa_aceptada']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
		 	$query = mssql_query($sql);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "41751501-1-".$_SESSION['glosa_aceptada'];
		}
		if($_SESSION['glosa_pendi']!="" && $_SESSION['glosa_pendi']!=0)
		{
			$sql1="EXECUTE insMovimiento '$sigla','$fac_consecutivo','13805604','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['glosa_pendi']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
			$query1 = mssql_query($sql1);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "13805604-1-".$_SESSION['glosa_pendi'];
		}
		if($_SESSION['impuesto']!=""&&$_SESSION['impuesto']!=0)
		{
			$sql2="EXECUTE insMovimiento '$sigla','$fac_consecutivo','61151506','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['impuesto']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
		 	$query2 = mssql_query($sql2);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "61151506-1-".$_SESSION['impuesto'];
		}
		if($_SESSION['pro_hospital']!=""&&$_SESSION['pro_hospital']!=0)
		{
			$sql3="EXECUTE insMovimiento '$sigla','$fac_consecutivo','61151505','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['pro_hospital']."','1','$consecutivo_rec_caja','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
		 	$query3 = mssql_query($sql3);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "61151505-1-".$_SESSION['pro_hospital'];
		}
		if($_SESSION['pro_desarrollo']!=""&&$_SESSION['pro_desarrollo']!=0)
		{
			$sql3="EXECUTE insMovimiento '$sigla','$fac_consecutivo','61151505','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['pro_desarrollo']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
		 	$query3 = mssql_query($sql3);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "61151505-1-".$_SESSION['pro_desarrollo'];
		}
		if($_SESSION['administracion']!=""&&$_SESSION['administracion']!=0)
		{
			$sql3="EXECUTE insMovimiento '$sigla','$fac_consecutivo','61151505','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['administracion']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
		 	$query3 = mssql_query($sql3);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "61151505-1-".$_SESSION['administracion'];
		}
		if($des_rec_caja!=""&&$des_rec_caja!=0)
		{
			$sql3="EXECUTE insMovimiento '$sigla','$fac_consecutivo','61151506','102','$fac_nit','".$_SESSION['cen_costo']."','$des_rec_caja','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";$query3 = mssql_query($sql3);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "61151506-1-".$des_rec_caja;
		}
		if($_SESSION['retencion']!=""&&$_SESSION['retencion']!=0)
		{
			$sql3="EXECUTE insMovimiento '$sigla','$fac_consecutivo','13805603','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['retencion']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
		 	$query3 = mssql_query($sql3);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "13805603-1-".$_SESSION['retencion'];
		}
		if($_SESSION['ica']!=""&&$_SESSION['ica']!=0)
		{
			$sql3="EXECUTE insMovimiento '$sigla','$fac_consecutivo','53050501','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['ica']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
			$query3 = mssql_query($sql3);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "53050501-1-".$_SESSION['ica'];
		}
		if($_SESSION['otros_descuentos']!=""&&$_SESSION['otros_descuentos']!=0)
		{
			$sql3="EXECUTE insMovimiento '$sigla','$fac_consecutivo','53050501','102','$fac_nit','".$_SESSION['cen_costo']."','".$_SESSION['otros_descuentos']."','1','$num_tran[0]','3','0','$cantidad_cuentas','$fecha','$mes[1]','$ano','$bas_retencion'";
		 	$query3 = mssql_query($sql3);
			$cantidad_cuentas++;
			$_SESSION['cue_descuentos'][$cantidad_cuentas] = "53050501-1-".$_SESSION['otros_descuentos'];
		}
		
			   
		echo "<script>alert('Recibo de caja agregado satisfactoriamente');</script>";
		$query = "SELECT COUNT(*) cant FROM mov_contable";
		$cant_mov = mssql_query($query);
		$cantidad = mssql_fetch_array($cant_mov);
			
			
			
		$mov = "EXECUTE movContable $cantidad_cuentas";
		$ins_mov = mssql_query($mov);
			
			
		$_SESSION["cuentas"] = $cantidad_cuentas;
			
			
			
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
			$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
			aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
			aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_mov_con_descripcion='$aud_mov_con_descripcion'
			WHERE mov_compro='$sigla' AND mov_mes_contable='$mes[1]' AND mov_ano_contable='$ano'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_mov_contable;
			$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
			
			echo "<script type=\"text/javascript\">location.href = '../reportes_PDF/recibo_caja.php?recibo=".$conse_reccaja."'</script>";
		 }
	    else
		{
			echo "<script type=\"text/javascript\">alert(\" No Se actualizo el movimiento.\");</script>";
			unset($_SESSION['des_impuestos']);unset($_SESSION['consecutivo']);unset($_SESSION['nit']);unset($_SESSION['nit_imp']);unset($_SESSION['cen_costo']);unset($_SESSION['total_fac']);unset($_SESSION['abono']);unset($_SESSION['valor_recibo']);unset($_SESSION['consecutivo']);unset($_SESSION['fecha_recibo']);unset($_SESSION['nota_recibo']);
			unset($_SESSION['concepto']);unset($_SESSION['nota_recibo']);unset($_SESSION['concepto']);
			unset($_SESSION['glosa_aceptada']);unset($_SESSION['administracion']);unset($_SESSION['glosa_pendi']);
			unset($_SESSION['descuento']);unset($_SESSION['impuesto']);unset($_SESSION['retencion']);unset($_SESSION['pro_hospital']);unset($_SESSION['ica']);unset($_SESSION['pro_desarrollo']);unset($_SESSION['otros_descuentos']);
			unset($_SESSION['neto']);unset($_SESSION['cen_costo']);unset($_SESSION['tot_asociados']);unset($_SESSION['jor_abono']);
		 	echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=73'>";
		}
	  }
   }
?>