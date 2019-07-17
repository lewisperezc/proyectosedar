<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
//echo "valor: ".$ano."<br>";
include_once('../clases/moviminetos_contables.class.php');include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');include_once('../clases/centro_de_costos.class.php');
include_once('../clases/concepto.class.php');include_once('../clases/recibo_caja.class.php');
include_once('../clases/transacciones.class.php');include_once('../clases/comprobante.class.php');

$concepto = new concepto();$mov_contable = new movimientos_contables();
$cen_costo = new centro_de_costos();$rec_caja = new rec_caja();
$nit = new nits();$transaccion = new transacciones();
$comprobante= new comprobante();


$bas_retencion=0;

$mes = $_POST['seg_social']-2;
$mes_pago = $_POST['seg_social'];
$ano = $_SESSION['elaniocontable'];

$conse_pagSeg = $comprobante->cons_comprobante($ano,$mes_pago,23);
$sig = $comprobante->sig_comprobante(23);
$comprobante->act_comprobante($ano,$mes_pago,23);
$sigla = $sig.$conse_pagSeg;

if($mes<=0)
{
	$mes = 12+$mes;
	$ano--;
}
$dias = cal_days_in_month(1,$mes,$_SESSION['elaniocontable']);
$cantidad = $_POST['cantidad'];
$minimo = $nit->sal_minimo();

$form = $mov_contable->consul_formulas(801);
$i=1;$matriz;
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
   }//cierra el while
 }
$cue_fondo = $matriz[2][1];
$nat_fondo = $matriz[2][2];
$cuenta = $matriz[1][1];
$naturaleza = $matriz[1][2];

for($i=0;$i<$cantidad;$i++)
{
   $_SESSION['asociado'][$i] = $_POST['id_nit'.$i];
   $_SESSION['ced_aso'][$i] = $_POST['ced_aso'.$i];
   $_SESSION['nombre_aso'][$i] = $_POST['nom_aso'.$i];
   $_SESSION['pag_sedar'][$i] = $_POST['pag_sedar'.$i];
   $_SESSION['pag_anes'][$i] = $_POST['pag_anes'.$i];
   $_SESSION['des_sedar'][$i] = $_POST['des_sedar'.$i];
   $_SESSION['des_anes'][$i] = $_POST['des_anes'.$i];
   $temp = 0;
   if($_SESSION['des_sedar'][$i]!=0)
	{
	 $centro = $cen_costo->con_cen_cos_nit($_SESSION['asociado'][$i]);
	 $centros = mssql_num_rows($centro);
	 $valor_centro;
	 $centro_contable;
	 
	 $tip_seguridad = $nit->tip_seguridad($_SESSION['asociado'][$i]);
	 $centro = 1169;
	 if($tip_seguridad==1)
	 {
		$porcentajes = $nit->porcentajes();
		$dat_porcentajes = mssql_fetch_array($porcentajes); 
		$pension = $nit->con_pension_empleado($_SESSION['asociado'][$i]);
		$dat_pension = mssql_fetch_array($pension);
		$nit_conca = $dat_pension['nit_id'];
		$din_pen=0;
		$resultado = ROUND($_SESSION['pag_sedar'][$i]/$minimo,2);
		if($resultado>4)
		 {
			$din_pen = ROUND($_SESSION['pag_sedar'][$i]*($dat_porcentajes['adic_solidaridad']/100),-2);
			$din_pen = $din_pen*2;
			//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_pen,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social pension
			//$query5 = mssql_query($sql5);
			$resul_fondo = ROUND($_SESSION['pag_sedar'][$i],-2);
			$_SESSION['pag_sedar'][$i]=$resul_fondo-$din_pen;
		 }
		$subsistencia = 0;
		
		
		if((int)$_SESSION['pag_sedar'][$i]/$minimo >= 4 && (int)$_SESSION['pag_sedar'][$i]/$minimo<16)
				$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1/100);
		
			elseif((int)$_SESSION['pag_sedar'][$i]/$minimo >=16 && (int)$_SESSION['pag_sedar'][$i]/$minimo <17)
		   		$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.2/100);
			
			elseif((int)$_SESSION['pag_sedar'][$i]/$minimo >=17 && (int)$_SESSION['pag_sedar'][$i]/$minimo <18)
				$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.4/100);
			
			elseif((int)$$_SESSION['pag_sedar'][$i]/$minimo >=18 && (int)$_SESSION['pag_sedar'][$i]/$minimo <19)
			   $res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.6/100);
			
			elseif((int)$res[$i]["pag_sedar"]/$minimo >=19 && (int)$res[$i]["pag_sedar"]/$minimo <20)
			   $res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.8/100);
			
			elseif((int)$res[$i]["pag_sedar"]/$minimo >=20)
			   $res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(2/100);
			
		if($_SESSION['pag_sedar'][$i]/$minimo >=4 && $_SESSION['pag_sedar'][$i]/$minimo <16)
			$_SESSION['pag_sedar'][$i] = ROUND($_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		
		elseif($_SESSION['pag_sedar'][$i]/$minimo >=16 && $_SESSION['pag_sedar'][$i]/$minimo <17)
		  {
			$subsistencia = ROUND($seguridad_social*(0.005+($dat_porcentajes['adic_subsis17']/100)),-2);
			$_SESSION['pag_sedar'][$i] = ROUND($_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >=17 && $_SESSION['pag_sedar'][$i]/$minimo <18)
		  { 
		    $subsistencia = ROUND($seguridad_social*(0.005+($dat_porcentajes['adic_subsis18']/100)),-2);
			$_SESSION['pag_sedar'][$i] = ROUND($_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >=18 && $_SESSION['pag_sedar'][$i]/$minimo <19)
		  { 
		    $subsistencia = ROUND($seguridad_social*(0.005+($dat_porcentajes['adic_subsis19']/100)),-2);
			$_SESSION['pag_sedar'][$i] = ROUND($_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >=19 && $_SESSION['pag_sedar'][$i]/$minimo <20)
		  {
			$subsistencia = ROUND($seguridad_social*(0.005+($dat_porcentajes['adic_subsis20']/100)),-2);
			$_SESSION['pag_sedar'][$i] = ROUND($_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >20)
		  {
			$subsistencia = ROUND($seguridad_social*(0.005+($dat_porcentajes['adic_subsismayor']/100)),-2);
			$_SESSION['pag_sedar'][$i] = ROUND($_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		 
    	$eps = $nit->con_eps_asociado($_SESSION['asociado'][$i]);
		$dat_eps = mssql_fetch_array($eps);
		$din_eps = ROUND(($_SESSION['des_sedar'][$i]-$din_pen)*(40.41/100),-2);
		$nit_conca = $dat_eps['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_eps,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social EPS
		//$query5 = mssql_query($sql5);
					
		$pension = $nit->con_pension_empleado($_SESSION['asociado'][$i]);
		$din_pension = ROUND(($_SESSION['des_sedar'][$i]-$din_pen)*(51.72/100),-2);
		$dat_pension = mssql_fetch_array($pension);
		$nit_conca = $dat_pension['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_pension,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social EPS
		//$query5 = mssql_query($sql5);
				
		$arp = $nit->con_arp_asociado($_SESSION['asociado'][$i]);
		$din_arp = ROUND(($_SESSION['des_sedar'][$i]-$din_pen)*(7.87/100),-2);
		$dat_arp = mssql_fetch_array($arp);
				
		$nit_conca = $dat_arp['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_arp,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social ARP
		//$query5 = mssql_query($sql5);
					
		$caja = $nit->con_caja_empleado($_SESSION['asociado'][$i]);
		$din_caja = $_SESSION['des_sedar'][$i]*(11.444/100);
		$dat_caja = mssql_fetch_array($caja);

		$nit_conca = $dat_caja['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_caja,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social CAJA
		//$query5 = mssql_query($sql5);
	  }
	  elseif($tip_seguridad==2)
	  {
		$porcentajes = $nit->porcentajes();
		$dat_porcentajes = mssql_fetch_array($porcentajes); 
		$pension = $nit->con_pension_empleado($_SESSION['asociado'][$i]);
		$dat_pension = mssql_fetch_array($pension);
		$nit_conca = $dat_pension['nit_id'];
		$din_pen=0;
		$resultado = $_SESSION['pag_sedar'][$i]/$minimo;
		if($resultado>4)
		 {
			$din_pen = ROUND($_SESSION['pag_sedar'][$i]*($dat_porcentajes['adic_solidaridad']/100),-2);
			$din_pen = $din_pen*2;
			//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_pen,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social pension
			//$query5 = mssql_query($sql5);
			$resul_fondo = ROUND($_SESSION['pag_sedar'][$i],-2);
			$_SESSION['pag_sedar'][$i]=$resul_fondo-$din_pen;
		 }
		$subsistencia = 0;
		if($_SESSION['pag_sedar'][$i]/$minimo >=16 && $_SESSION['pag_sedar'][$i]/$minimo <17)
		  {
			ROUND($subsistencia = $seguridad_social*($dat_porcentajes['adic_subsis17']/100)-2);
			ROUND($_SESSION['pag_sedar'][$i] = $_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >=17 && $_SESSION['pag_sedar'][$i]/$minimo <18)
		  { 
		    ROUND($subsistencia = $seguridad_social*($dat_porcentajes['adic_subsis18']/100)-2);
			ROUND($_SESSION['pag_sedar'][$i] = $_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >=18 && $_SESSION['pag_sedar'][$i]/$minimo <19)
		  { 
		    ROUND($subsistencia = $seguridad_social*($dat_porcentajes['adic_subsis19']/100)-2);
			ROUND($_SESSION['pag_sedar'][$i] = $_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >=19 && $_SESSION['pag_sedar'][$i]/$minimo <20)
		  {
			ROUND($subsistencia = $seguridad_social*($dat_porcentajes['adic_subsis20']/100)-2);
			ROUND($_SESSION['pag_sedar'][$i] = $_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		elseif($_SESSION['pag_sedar'][$i]/$minimo >20)
		  {
			ROUND($subsistencia = $seguridad_social*($dat_porcentajes['adic_subsismayor']/100)-2); 
			ROUND($_SESSION['pag_sedar'][$i] = $_SESSION['pag_sedar'][$i]-$subsistencia,-2);
		  }
		
		$eps = $nit->con_eps_asociado($_SESSION['asociado'][$i]);
		$dat_eps = mssql_fetch_array($eps);
		
		$din_eps = ROUND(($_SESSION['des_sedar'][$i]-$din_pen)*(35.25/100),-2);
		$nit_conca = $dat_eps['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_eps,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social EPS
		//$query5 = mssql_query($sql5);
					
		$din_pension = ROUND(($_SESSION['des_sedar'][$i]-$din_pen)*(45.12/100),-2);
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_pension,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social EPS
		//$query5 = mssql_query($sql5);
							
		$arp = $nit->con_arp_asociado($_SESSION['asociado'][$i]);
		$din_arp = ROUND(($_SESSION['des_sedar'][$i]-$din_pen)*(19.63/100)-2);
		$dat_arp = mssql_fetch_array($arp);
				
		$nit_conca = $dat_arp['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_arp,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social ARP
		//$query5 = mssql_query($sql5);
					
		$caja = $nit->con_caja_empleado($_SESSION['asociado'][$i]);
		$din_caja = $_SESSION['des_sedar'][$i]*(10.1/100);
		$dat_caja = mssql_fetch_array($caja);

		$nit_conca = $dat_caja['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_caja,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social CAJA
		//$query5 = mssql_query($sql5);
	  }
	  elseif($tip_seguridad==3)
	  {
		$eps = $nit->con_eps_asociado($_SESSION['asociado'][$i]);
		$dat_eps = mssql_fetch_array($eps);
		$din_eps = ROUND($_SESSION['des_sedar'][$i]*(83.69/100),-2);
		$nit_conca = $dat_eps['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_eps,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social EPS
		//$query5 = mssql_query($sql5);
				
		$arp = $nit->con_arp_asociado($_SESSION['asociado'][$i]);
		$din_arp = ROUND($_SESSION['des_sedar'][$i]*(16.31/100),-2);
		$dat_arp = mssql_fetch_array($arp);
				
		$nit_conca = $dat_arp['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_arp,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social ARP
		//$query5 = mssql_query($sql5);
					
		$caja = $nit->con_caja_empleado($_SESSION['asociado'][$i]);
		$din_caja = $_SESSION['des_sedar'][$i]*(21.1/100);
		$dat_caja = mssql_fetch_array($caja);

		$nit_conca = $dat_caja['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_caja,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social CAJA
		//$query5 = mssql_query($sql5);
	  }
	  elseif($tip_seguridad==4)
	  {
		$eps = $nit->con_eps_asociado($_SESSION['asociado'][$i]);
		$dat_eps = mssql_fetch_array($eps);
		$din_eps = ROUND($_SESSION['des_sedar'][$i]*(64.23/100),-2);
		$nit_conca = $dat_eps['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_eps,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social EPS
		//$query5 = mssql_query($sql5);
				
		$arp = $nit->con_arp_asociado($_SESSION['asociado'][$i]);
		$din_arp = ROUND($_SESSION['des_sedar'][$i]*(35.77/100),-2);
		$dat_arp = mssql_fetch_array($arp);
				
		$nit_conca = $dat_arp['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_arp,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social ARP
		//$query5 = mssql_query($sql5);
					
		$caja = $nit->con_caja_empleado($_SESSION['asociado'][$i]);
		$din_caja = $_SESSION['des_sedar'][$i]*(17.052/100);
		$dat_caja = mssql_fetch_array($caja);

		$nit_conca = $dat_caja['nit_id'];
		//$sql5 ="EXECUTE insMovimiento '$sigla',$conse_pagSeg,'$cue_fondo',2,'$nit_conca',$centro,$din_caja,$nat_fondo,'$conse_pagSeg','3',0,$cantidad,'".date('d-m-Y')."',$mes_pago,".$_SESSION['elaniocontable'],'$bas_retencion';//Seguridad Social CAJA
		//$query5 = mssql_query($sql5);
	  }
	 //$query = "SELECT COUNT(*) cant FROM mov_contable";
	 //$cant_mov = mssql_query($query);
	 //$cant_ingresados = mssql_fetch_array($cant_mov);
	 //$mov = "EXECUTE movContable ".$cant_ingresados['cant'];
	 //$ins_mov = mssql_query($mov);
	}
}
//$sql="DELETE FROM movimientos_contables WHERE mov_valor=0 AND mov_compro='$sigla' AND mov_mes_contable=$mes_pago AND mov_ano_contable=".$_SESSION['elaniocontable'],'$bas_retencion';
//$query=mssql_query($sql);
$_SESSION['cen_costo']=$centro_costo;
echo "<script>location.href='../reportes_EXCEL/seg_social.php';</script>";
echo "<script>location.href='../formularios/pag_segSocial.php'</script>";
/////////////////////////////////////////////////////////////////////////
?>