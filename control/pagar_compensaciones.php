<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
//echo "en año contable es: ".$ano;

  include_once('../clases/transacciones.class.php');
  include_once('../clases/moviminetos_contables.class.php');
  include_once('../clases/factura.class.php');
  include_once('../clases/reporte_jornadas.class.php');
  include_once('../clases/nomina.class.php');
  include_once('../clases/nits.class.php');
  include_once('../clases/recibo_caja.class.php');
  include_once('../clases/saldos.class.php');
  include_once('../clases/contrato.class.php');
  include_once('../clases/centro_de_costos.class.php');
  include_once('../clases/cuenta.class.php'); 
  include_once('../clases/credito.class.php');
  include_once('../clases/compensacion_nomina.class.php');
  include_once('../clases/varios.class.php');
  include_once('../clases/pabs.class.php');
  
  //echo "entra <br>";

?>
  
  <script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
  <script language="javascript" type="text/javascript">
   
   function abreFactura(URL,num)
    {
     day = new Date();
     id = day.getTime();
     eval("page" + (id+num) + " = window.open(URL, '" + (id+num) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");  
    }
    
    function sumaCuota(pos)
    {
        var interes = parseInt($("#interes"+pos).val());
        var cuota = parseInt($("#capital"+pos).val());
        var total = parseInt(interes+cuota);
        $("#total"+pos).val(total);
    }
    
    function preguntar(SigNomina,Numventana)
    {
        var mensaje = confirm("Desea enviar un correo electronico informandole a los afiliados que se registro el pago de una compensacion?");
        if(mensaje)
            abreFactura('../reportes/envio_correo_pago_nomina.php?nomina=PAG-COM-'+SigNomina,Numventana);
    }
  </script>
  <?php
  $saldos = new saldos();
  $nomina = new nomina();
  $reporte = new reporte_jornadas();
  $transaccion = new transacciones();
  $fac = new factura();
  $com_nomina = new compensacion_nomina();
  $movimiento = new movimientos_contables();
  $nit = new nits();
  $recibo_caja = new rec_caja();
  $con = new contrato();
  $cen_cos = new centro_de_costos();
  $cuen_centro = new cuenta();
  $ins_credito = new credito();
  $ins_varios=new varios();
  
  $ins_fabs=new pabs();
  
  
  $mes_sele = split("-",strtoupper($_POST['mes_sele']),2);
  $mes = $mes_sele[1];
  
  
  $novedad = $_POST['novedad'];
  $empresa = $nit->con_dat_nit(13);
  $dat_empresa = mssql_fetch_array($empresa);
  $nit_empresa = $dat_empresa['nit_id'];
  $minimo = $nit->sal_minimo();
  
  $rep_recibo_caja=split("-",strtoupper($_SESSION['recibo']),4);
  $factura_id=$_SESSION['factura'];
  
  $res_dat_factura=$fac->ConsultarTodosDatosFacturaPorId($factura_id);
  
  $bas_retencion=0;

	$sigla_causacion='CAU-NOM-'.$factura_id;

$usuario_actualizador=$_SESSION['k_nit_id'];
$fecha_actualizacion=date('d-m-Y');
	
$hora=localtime(time(),true);
if($hora[tm_hour]==1)
	$hora_dia=23;
else
	$hora_dia=$hora[tm_hour]-1;

$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];

$tip_mov_aud_id=1;

$aud_mov_con_descripcion='CREACION DE NOMINA AFILIADOS';


$cue_uno_retiro='23803009';
$cue_dos_retiro='31400101';

//////////////////////////////////DESDE AQUI SI VIENE UN VALOR NEGATIVO/////////////////////
    
  
  $rep_con_afiliados=0;
  $rep_can_negativos=0;
  
  $datos = $reporte->bus_datCompensacion();
  $dat_compe = mssql_fetch_array($datos);
  
  while($rep_con_afiliados<$_POST['can_jornadas'])
  {
  	$rep_tot_pagar=0;
	$rep_val_fabs=0;
	$rep_val_aportes=0;
	$rep_val_vacaciones=0;
	$rep_val_administracion=0;
	$rep_val_educacion=0;
	$rep_val_des_legalizacion=0;
	$rep_val_seg_social=0;
	$rep_val_des_glosa=0;
	$rep_val_creditos=0;
	$rep_val_des_compensacion=0;
	$rep_base_retencion=0;
	$rep_val_rete=0;
	$rep_tot_pagar=0;
	
  	if($_POST['estado'.$rep_con_afiliados]==1)//AFILIADO
  	{
  		if($_POST['nove'.$rep_con_afiliados]>0)
		{
			$rep_con_fabs=$nit->pabs_asociado($_POST['nit'.$rep_con_afiliados]);
			$rep_res_fabs=mssql_fetch_array($rep_con_fabs);
			
			$rep_val_fabs=($_POST['nove'.$rep_con_afiliados]*$rep_res_fabs['pabs'])/100;
			
			
			//INICIO PONER AQUI EL BLOQUE 2 DE FONDO DE RETIRO Y COMENTAREAR LO ANTERIOR
			
			
			//ANTERIOR
			//$rep_val_aportes=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_nom_aportes'])/100;
			
			$cue1=$movimiento->saldo_cuenta_nit($cue_uno_retiro,$ano,$mes,$_POST['nit'.$rep_con_afiliados]);
 			$cue2=$movimiento->saldo_cuenta_nit($cue_dos_retiro,$ano,$mes,$_POST['nit'.$rep_con_afiliados]);
			
			$tot_sal_cuenta=$cue1+$cue2;
			
			$tot_sal_creditos=$fac->TotalSaldoCreditosPorNit($_POST['nit'.$rep_con_afiliados]);
			
			
			
			$saldo_final=$tot_sal_cuenta-$tot_sal_creditos;//LO QUE TIENE EN EL FONDO - LO QUE DEBE DE CREDITOS
			$can_sal_minimos=40;
			$val_tot_sal_minimos=$minimo*$can_sal_minimos;
			
			if($res_dat_factura['fac_ano_servicio']==2018)
			{
				if($res_dat_factura['fac_mes_servicio']<=9)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
				{
					$rep_val_aportes=$_POST['nove'.$rep_con_afiliados]*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
				}
				else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
				{
					//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
					//if(($tot_sal_cuenta > $val_tot_sal_minimos) && ($tot_sal_creditos < $val_tot_sal_minimos))//SI TIENE MÁS DE 40SMMLV
					if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))
					{
						$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id=".$_POST['nit'.$rep_con_afiliados];
						$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
						$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
				
						$rep_val_aportes=$_POST['nove'.$rep_con_afiliados]*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
					}
					else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
					{
						$rep_val_aportes=$_POST['nove'.$rep_con_afiliados]*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
					}
				}
			}
			else
			{
				if($res_dat_factura['fac_ano_servicio']<2018)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
				{
					$rep_val_aportes=$_POST['nove'.$rep_con_afiliados]*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
				}
				else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
				{
					//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
					//if(($tot_sal_cuenta > $val_tot_sal_minimos) && ($tot_sal_creditos < $val_tot_sal_minimos))//SI TIENE MÁS DE 40SMMLV
					if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))
					{
						$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id=".$_POST['nit'.$rep_con_afiliados];
						$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
						$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
				
						$rep_val_aportes=$_POST['nove'.$rep_con_afiliados]*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
					}
					else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
					{
						$rep_val_aportes=$_POST['nove'.$rep_con_afiliados]*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
					}
				}
			}
			
			//FIN PONER AQUI EL BLOQUE 2 DE FONDO DE RETIRO Y COMENTAREAR LO ANTERIOR
			
			
			$rep_val_vacaciones=($_POST['nove'.$rep_con_afiliados]*$rep_res_fabs['porce'])/100;
			
			
			
			if($res_dat_factura['fac_ano_servicio']==2017)
			{
				if($res_dat_factura['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
				{
					$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*5)/100;//ADMINISTRACION BASICA
				}
				else//CALCULA LA ADMON NUEVA 5.5%
				{
					$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_nom_gastos'])/100;//ADMINISTRACION BASICA
				}
			}
			else//CALCULA LA ADMON NUEVA 5.5%
			{
				if($res_dat_factura['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
				{
					$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*5)/100;//ADMINISTRACION BASICA
				}
				else//CALCULA LA ADMON NUEVA 5.5%		
				{
					$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_nom_gastos'])/100;//ADMINISTRACION BASICA
				}
			}
			
			//$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_nom_gastos'])/100;
			
			
			$rep_val_educacion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_nom_educacion'])/100;
			$rep_val_des_legalizacion=$_POST['descu'.$rep_con_afiliados];
			$rep_val_seg_social=$_POST['des_segSocial'.$rep_con_afiliados];
			$rep_val_des_glosa=$_POST['glosa'.$rep_con_afiliados];
			$rep_val_creditos=$ins_credito->TotalDescuentoCreditos($_SESSION['factura'],$_POST['nit'.$rep_con_afiliados],1);
			$rep_val_des_compensacion=$movimiento->TotalDescuentosCompensacion($_POST['nit'.$rep_con_afiliados],$_SESSION['factura'],$rep_recibo_caja[0]);
			
			
			$rep_base_retencion=$_POST['nove'.$rep_con_afiliados]-$rep_val_fabs-$rep_val_aportes-$rep_val_vacaciones-$rep_val_administracion-$rep_val_educacion;
			
			/*if($_POST['nit'.$rep_con_afiliados]=='1019')
				echo $rep_val_fabs."___".$rep_val_aportes."___".$rep_val_vacaciones."___".$rep_val_administracion."___".$rep_val_educacion."___".$rep_base_retencion."<br>";
			*/
			
			$rep_que_tip_retencion="SELECT nit_tip_procedimiento,nit_por_ret_fuente FROM nits WHERE nit_id=".$_POST['nit'.$rep_con_afiliados];
        	//echo $que_tip_retencion."<br>";
        	$rep_eje_tip_retencion=mssql_query($rep_que_tip_retencion);
	        $rep_res_tip_retencion=mssql_fetch_array($rep_eje_tip_retencion);
			
	        if($rep_res_tip_retencion['nit_tip_procedimiento']==2)
	        {
	            $rep_val_rete=round(($rep_base_retencion*$rep_res_tip_retencion['nit_por_ret_fuente'])/100,0);
	        }
	        else
	        {
	            $rep_val_rete=$movimiento->cal_ret_fuente($rep_base_retencion);
	        }
			
			$rep_tot_pagar=$rep_base_retencion-$rep_val_des_legalizacion-$rep_val_seg_social-$rep_val_des_glosa-$rep_val_creditos-$rep_val_des_compensacion-$rep_val_rete;
		}
  	}
	
	elseif($_POST['estado'.$rep_con_afiliados]==3)//NO AFILIADO ACTIVO
	{
		
		if($res_dat_factura['fac_ano_servicio']==2017)
		{
			if($res_dat_factura['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
			{
				$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*5)/100;//ADMINISTRACION BASICA
			}
			else//CALCULA LA ADMON NUEVA 5.5%
			{
				$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_admonNoAfi'])/100;//ADMINISTRACION BASICA
			}
		}
		else//CALCULA LA ADMON NUEVA 5.5%
		{
			if($res_dat_factura['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
			{
				$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*12)/100;//ADMINISTRACION BASICA
			}
			else//CALCULA LA ADMON NUEVA 5.5%		
			{
				$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_admonNoAfi'])/100;//ADMINISTRACION BASICA
			}
		}
		
		//$rep_val_administracion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_admonNoAfi'])/100;
		
		
		
		$rep_val_educacion=($_POST['nove'.$rep_con_afiliados]*$dat_compe['dat_admonAdministacion'])/100;
		$rep_val_des_legalizacion=$_POST['descu'.$rep_con_afiliados];
		$rep_val_des_glosa=$_POST['glosa'.$rep_con_afiliados];
		$rep_val_creditos=$ins_credito->TotalDescuentoCreditos($_SESSION['factura'],$_POST['nit'.$rep_con_afiliados],1);
		$rep_val_des_compensacion=$movimiento->TotalDescuentosCompensacion($_POST['nit'.$rep_con_afiliados],$_SESSION['factura'],$rep_recibo_caja[0]);
			
		$rep_base_retencion=$_POST['nove'.$rep_con_afiliados]-$rep_val_administracion-$rep_val_educacion;
		
		$rep_val_rete=round(($rep_base_retencion*10)/100,0);
		
		$rep_tot_pagar=$rep_base_retencion-$rep_val_des_legalizacion-$rep_val_des_glosa-$rep_val_creditos-$rep_val_des_compensacion-$rep_val_rete;
	}
	
	if($rep_tot_pagar<0)
	{
		$rep_nits_negativos[]=$_POST['nit'.$rep_con_afiliados];
		$rep_valores_negativos[]=$rep_tot_pagar;
		$rep_can_negativos++;
	}
	
	
  	$rep_con_afiliados++;
  }

  if($rep_can_negativos>0)
  {
  	//$act_est_credito=$ins_credito->ActEstDesNomNegativa(2,$rep_recibo_caja[0],$factura_id);
  	$enviar_nits_array=$ins_varios->envia_array_url($rep_nits_negativos);
	$enviar_valores_array=$ins_varios->envia_array_url($rep_valores_negativos);
  	echo "<script>alert('La compensacion contiene valores negativos, por favor verifique los afiliados que se encuentran en el reporte para realizar el proceso correspondiente.')</script>";
	
	//echo "<script>location.href='../reportes/listado_negativos_compensacion_afiliados.php?nits=$enviar_nits_array&valores=$enviar_valores_array&cantidad=$rep_can_negativos&reci_caj=$rep_recibo_caja[0]&factu=$factura_id'</script>";
	
	echo "<script>abreFactura('../reportes_PDF/listado_negativos_compensacion_afiliados.php?nits=$enviar_nits_array&valores=$enviar_valores_array&cantidad=$rep_can_negativos&reci_caj=$rep_recibo_caja[0]&factu=$factura_id');</script>";
	
  }
  //////////////////////HASTA AQUI//////////////////
  
  /*
  
  else//////////////////////////////SE HACE TODA LA NOMINA NORMALMENTE/////////////////////////////////////////////////////
  {
  
  */
  
	$tot_glosa = $_POST['desc1'];
  
  if($_SESSION['conse_recibo']!="")
  {
      $rec_cantidad = $recibo_caja->rec_factura($_SESSION['conse_recibo']);
      if($rec_cantidad)
        $factura=$rec_cantidad;
      else
        $factura = $reporte->bus_factura(strtoupper($_SESSION["rep_jornadas"][0]));
      $saldo_factura = $fac->val_factura($_SESSION['conse_recibo']);
      $mes_factura = $fac->mesFactura($_SESSION['conse_recibo']);
  }
  else
  {
    $factura = $reporte->bus_factura(strtoupper($_SESSION["rep_jornadas"][0]));
    $saldo_factura = $fac->val_factura($factura);
    $mes_factura = $fac->mesFactura($factura);
    $adelanto=1;
  }

  $nits = $_SESSION["aso"];
  $_SESSION['factura'] = $factura;
  /***********Obtenemos el mes de servicio de la factura***********************/
  $dat_factura=$fac->datFactura($factura);
  $tod_fac = mssql_fetch_array($dat_factura);
  /****************************************************************************/
  $cant_jornadas = $reporte->canJorFac($factura);
  $fecha = date('d-m-Y');
  
  $recibo_act = split("-",strtoupper($_SESSION['recibo']),3);
  $tipo = $_POST['tip_rep'];
  $rec_cajaNum = $recibo_act[0];
  $des_contrato = $recibo_caja->des_recibo($rec_cajaNum);
  if($_SESSION['conse_recibo']!="")
  {
      $a = split("-",$saldo_factura,2);
      $val_recibo = $a[0];
      $val_factura = $a[1];
      $_SESSION['val_recibo1'] = $_SESSION['val_recibo1']-$des_contrato;
      $val_jornada = strtoupper($_SESSION['val_recibo'])/$cant_jornadas;
  }
 
  //if($val_recibo<=$val_factura)
  //{
      $conse = $fac->obt_consecutivo(18);
      $ac_conse = $fac->act_consecutivo(18);
      $recibo = $_SESSION['conse_recibo'];
      $numRec = $transaccion->num_tranRecibo($recibo);
      $sigla = "PAG-COM-".$conse;
      $sigla_cue_pagar = "PAG-COM-".$conse; 
      $cant_lega = $recibo_caja->verLegRecibo($rec_cajaNum);
      for($i=0;$i<sizeof($novedad);$i++)
      {
        $suma_novedades = $suma_novedades+$novedad[$i];
        $descuentos[$i] = $_POST['descu'.$i]+$_POST['glosa'.$i];
		$leg_por_afiliado[$i]=$_POST['descu'.$i];
		/*if($nits[$i]==1543)
		{
			echo "total: ".$descuentos[$i]."<br>";
			echo $_POST['descu'.$i]."___".$_POST['glosa'.$i]; 
			
		}
		
		*/
		
        $reporte->actGlosa($_POST['jor_glo'.$i],$sigla,$nits[$i]);
        $des_total = $des_total + $_POST['descu'.$i];
        if($cant_lega==0)
            $recibo_caja->guardar_legReciboProv($rec_cajaNum,$_POST['descu'.$i]);
      }
      
      	for($i=0;$i<sizeof($_SESSION['num_jor']);$i++)
		{
          if($tipo==1)
          {
            $novedad_suel = $novedad[$i];//-$descuentos[$i];
            //$novedad_suel = $novedad[$i]-$descuentos[$i];
            $centro_cos = $fac->bus_cenNit($_SESSION['factura']);
            $centros = mssql_fetch_array($centro_cos);
            $centro = $centros['cen_id'];
          }
          else
          {
            $centro_cos = $fac->bus_cenNit($_SESSION['factura']);
            $centros = mssql_fetch_array($centro_cos);
            $centro = $centros['cen_id'];
            $contra = $con->contrato($centro);
            $dat_contrato =  mssql_fetch_array($contra);
            if($dat_contrato['con_val_hor_trabajada']=="")
            {
                $val_jornada = ($_SESSION['val_recibo1']/*+$des_total*/)/$suma_novedades;
                $novedad_suel = ($val_jornada*$novedad[$i]);//-$descuentos[$i];
                //$novedad_suel = ($val_jornada*$novedad[$i])-$descuentos[$i];
                $_SESSION['val_unitario']=$val_jornada;
                $_SESSION['val_total']=$novedad;
            }
            else
            {
                $val_unitario = $dat_contrato['con_val_hor_trabajada'];
                $novedad_suel = ((($_SESSION['val_recibo1'])/($dat_contrato['con_val_hor_trabajada']*6))*$novedad[$i]);//-$descuentos[$i];
                //$novedad_suel = ((($_SESSION['val_recibo1']+$des_total)/($dat_contrato['con_val_hor_trabajada']*6))*$novedad[$i])-$descuentos[$i];
                $_SESSION['val_unitario']=$val_unitario;
                $_SESSION['val_total']=$val_total;
            }
           }
          $val_ganado = $novedad_suel;
          //echo $val_ganado."<br>";
          if($i==0)
             $cons_jornada = $_SESSION["rep_jornadas"][0];
          else
             $cons_jornada = $_SESSION["rep_jornadas"][0] + $i;
        
         if($val_ganado>0)
         {
          $rep = $reporte->repJornadas($cons_jornada);
          $dat_reporte = mssql_fetch_array($rep);
          $cue_cenCosto = $cuen_centro->cue_centro($centro);
          $cen_pagar[$i] = $cue_cenCosto;
          $guar_nomina = $nomina->guardar_compensacion($dat_reporte['rep_jor_mes'],$val_ganado,$dat_reporte['id_nit_por_cen'],$dat_reporte['rep_jor_id'],$_SESSION['num_recibo'],$conse);
          $nueTran = $transaccion->guaPagTransaccion($sigla,$fecha,$nits[$i],$centro,$val_ganado,0,$fecha,$conse,$_SESSION["k_nit_id"],$fecha,102,$numRec,$mes,$ano);
          $transac = $transaccion->obtener_concecutivo();
          $num_tran = mssql_fetch_array($transac);
          $form = $movimiento->consul_formulas(3);
          $nit_conta = $nits[$i];
		  
		  //VALOR LEGALIZACION AFILIADO
		  $val_leg_por_afiliado=$leg_por_afiliado[$i];
		  
          $j=1;$matriz;
          if($form)
            {
             $dat_matriz = mssql_fetch_array($form);    
             //Consulta las cuentas para la pagada
             while($j<=21)
              {
                $arre = split(",",$dat_matriz["for_cue_afecta".$j]);
                $a = $arre[0];
                $b = $arre[1];
                $c = $arre[2];
                $d = $arre[3];
                if($a != "" && $b != "" && $c != "")
                {
                $matriz[$j][0]= $a;
                $matriz[$j][1]= $b;
                $matriz[$j][2]= $c;
                $matriz[$j][3]= $d;
                }
               $j++;    
              }//cierra el while
            }
          //Consulta de las cuentas de la causacion
          $form = $movimiento->consul_formulas(2);
          $can=1;$matriz_cau;
          if($form)
         {
          $dat_matriz = mssql_fetch_array($form);
          while($can<=21)
          {
           $arre = split(",",$dat_matriz["for_cue_afecta".$can]);
           $a = $arre[0];
           $b = $arre[1];
           $c = $arre[2];
           $d = $arre[3];
           if($a != "" && $b != "" && $c != "")
           {
             $matriz_cau[$can][0]= $a;
             $matriz_cau[$can][1]= $b;
             $matriz_cau[$can][2]= $c;
             $matriz_cau[$can][3]= $d;
           }
          $can++;
          }
         }
          $cantidad_cuentas = 18;
          $estado = $nit->est_asociado($nit_conta);
          $datos = $reporte->bus_datCompensacion();
          $dat_compe = mssql_fetch_array($datos);
          if($estado == 1)
          {
              /**********PORCENTAJES DE DESCEUNTO DATOS COMPENSACION**********/
              $aux_inmo = $val_ganado*($dat_compe['dat_nom_compensacion']/100);
			  
              //INICIO PONER AQUI EL BLOQUE 3 DE FONDO DE RETIRO Y COMENTAREAR LO ANTERIOR
              
              
              //ANTERIOR
              //$aportes = $val_ganado*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
              
             
			
			
			$cue1=$movimiento->saldo_cuenta_nit($cue_uno_retiro,$ano,$mes,$nit_conta);
 			$cue2=$movimiento->saldo_cuenta_nit($cue_dos_retiro,$ano,$mes,$nit_conta);
			
			$tot_sal_cuenta=$cue1+$cue2;
			
			$tot_sal_creditos=$fac->TotalSaldoCreditosPorNit($nit_conta);
			
			
			
			$saldo_final=$tot_sal_cuenta-$tot_sal_creditos;//LO QUE TIENE EN EL FONDO - LO QUE DEBE DE CREDITOS
			$can_sal_minimos=40;
			$val_tot_sal_minimos=$minimo*$can_sal_minimos;
			
			if($res_dat_factura['fac_ano_servicio']==2018)
			{
				if($res_dat_factura['fac_mes_servicio']<=9)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
				{
					$aportes = $val_ganado*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
				}
				else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
				{
					//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
					//if(($tot_sal_cuenta > $val_tot_sal_minimos) && ($tot_sal_creditos < $val_tot_sal_minimos))//SI TIENE MÁS DE 40SMMLV
					if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))
					{
						$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit_conta'";
						$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
						$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
				
						$aportes=$val_ganado*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
					}
					else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
					{
						$aportes = $val_ganado*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
					}
				}
			}
            else
			{
				if($res_dat_factura['fac_ano_servicio']<2018)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
				{
					$aportes = $val_ganado*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
				}
				else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
				{
					//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
					//if(($tot_sal_cuenta > $val_tot_sal_minimos) && ($tot_sal_creditos < $val_tot_sal_minimos))//SI TIENE MÁS DE 40SMMLV
					if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))
					{
						$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit_conta'";
						$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
						$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
					
						$aportes=$val_ganado*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
					}
					else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
					{
						$aportes = $val_ganado*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
					}
				}
			}
              
              //FIN PONER AQUI EL BLOQUE 3 DE FONDO DE RETIRO Y COMENTAREAR LO ANTERIOR
              
              $legalizacion = $val_ganado*($dat_compe['dat_nom_legalizacion']/100);
			  
			 
			if($res_dat_factura['fac_ano_servicio']==2017)
			{
				if($res_dat_factura['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
				{
					$adminBasica=$val_ganado*(5/100);//ADMINISTRACION BASICA
				}
				else//CALCULA LA ADMON NUEVA 5.5%
				{
					$adminBasica=$val_ganado*($dat_compe['dat_nom_gastos']/100);//ADMINISTRACION BASICA
				}
			}
			else//CALCULA LA ADMON NUEVA 5.5%
			{
				if($res_dat_factura['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
				{
					$adminBasica=$val_ganado*(5/100);//ADMINISTRACION BASICA
				}
				else//CALCULA LA ADMON NUEVA 5.5%		
				{
					$adminBasica=$val_ganado*($dat_compe['dat_nom_gastos']/100);//ADMINISTRACION BASICA
				}
			}
			  
			  
              
              //$adminBasica=$val_ganado*($dat_compe['dat_nom_gastos']/100);//ADMINISTRACION
              
              $adminExtraordinaria=$val_ganado*($dat_compe['dat_admonExtra']/100);
              $gastos=$adminBasica+$adminExtraordinaria;
              $educacion = $val_ganado*($dat_compe['dat_nom_educacion']/100);//EDUCACION
              
              //////////////////////////////////////////////////////////
           }
           elseif($estado == 3||$estado == 5)
           {
              //
              $aux_inmo = 0;
              $aportes = 0;
              $legalizacion = 0;
			  
			if($res_dat_factura['fac_ano_servicio']==2017)
			{
				if($res_dat_factura['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
				{
					$adminBasica=$val_ganado*(5/100);//ADMINISTRACION BASICA
				}
				else//CALCULA LA ADMON NUEVA 5.5%
				{
					$adminBasica=$val_ganado*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
				}
			}
			else//CALCULA LA ADMON NUEVA 5.5%
			{
				if($res_dat_factura['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
				{
					//echo "entra por aqui <br>";
					$adminBasica=$val_ganado*(12/100);//ADMINISTRACION BASICA
				}
				else//CALCULA LA ADMON NUEVA 5.5%		
				{
					$adminBasica=$val_ganado*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
				}
			}
			  
			  
              //$adminBasica=$val_ganado*($dat_compe['dat_admonNoAfi']/100);
			  
              $adminExtraordinaria=$val_ganado*($dat_compe['dat_admonNoAfiExtraordinaria']/100);
              $gastos=$adminBasica+$adminExtraordinaria;
                
              if($dat_compe['dat_admonAdministacion']==0)
                $educacion=0;
              else
                $educacion=$val_ganado*($dat_compe['dat_admonAdministacion']/100);
                
            }
          /*para la causada
          /*% PABS  y vacaciones*/
          if($estado == 1)
            {
                $datos = $nit->pabs_asociado($nit_conta);
                $dat_pabs = mssql_fetch_array($datos);
                $por_pabs = $dat_pabs['pabs'];
                $vacaciones = $dat_pabs['vac'];
                $valor_pabs = $val_ganado*($por_pabs/100);//FABS
                $_SESSION['pabs'][$i] = $valor_pabs;
                
                //echo "datos: ".$val_ganado."___".$por_pabs."<br>";
                if($vacaciones=='SI')
                {
                    $fon_vacaciones = $val_ganado*($dat_pabs['porce']/100);//VACACIONES
                }
                else
                    $fon_vacaciones = 0;//VACACIONES
                $_SESSION['vacaciones'][$i] = $fon_vacaciones;
            }
          else
             {
                $valor_pabs=0;
                $fon_vacaciones = 0;
             }
          /////////////////////////////////
    
    	  //////////////////////////INICIO FABS//////////////////////////
    	  
			$con_tod_por_fabs_pasivo=$ins_fabs->ConTodPorFonFabs();
			 
		  	$con_tod_por_fabs_costo=$ins_fabs->ConTodPorFonFabs();
			 
			 
			$nit_fabs = $nit_conta."_1";
			 
			/////////////////////CUENTAS PASIVO(2)/////////////////////
			while($res_tod_por_fabs_pasivo=mssql_fetch_array($con_tod_por_fabs_pasivo))
			{
				$val_porcentual_pasivo=round($valor_pabs*($res_tod_por_fabs_pasivo['dis_por_con_fab_cue_uno_por']/100),0);
				
				$cue_nueva_1=$res_tod_por_fabs_pasivo['dis_por_con_fab_cue_uno']."1";
				
				
				if($res_tod_por_fabs_pasivo['dis_por_con_fab_cue_uno_nat']==1)
					$nue_naturaleza_1=2;
				elseif($res_tod_por_fabs_pasivo['dis_por_con_fab_cue_uno_nat']==2)
					$nue_naturaleza_1=1;
			
				$sql2 ="EXECUTE insMovimiento '$sigla','$factura','$cue_nueva_1',
				'$res_tod_por_fabs_pasivo[dis_por_con_fab_cue_nit]','$nit_fabs','$centro',
				'$val_porcentual_pasivo','$nue_naturaleza_1',
				'$conse','$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			 	//echo $sql2."<br>";
			 	$query2 = mssql_query($sql2);
			 }
			 /////////////////////FIN CUENTAS PASIVO(2)/////////////////////
			 
			 
			 /////////////////////CUENTAS COSTO(6)/////////////////////
			 while($res_tod_por_fabs_costo=mssql_fetch_array($con_tod_por_fabs_costo))
			 {
			 	$val_porcentual_costo=round($valor_pabs*$res_tod_por_fabs_costo['dis_por_con_fab_cue_dos_por']/100,0);
				
				$cue_nueva_2=$res_tod_por_fabs_costo['dis_por_con_fab_cue_dos']."1";
				
				if($res_tod_por_fabs_costo['dis_por_con_fab_cue_dos_nat']==1)
					$nue_naturaleza_2=2;
				elseif($res_tod_por_fabs_costo['dis_por_con_fab_cue_dos_nat']==2)
					$nue_naturaleza_2=1;
				
			 	$sql_gas2 ="EXECUTE insMovimiento '$sigla','$factura',
			 	'$cue_nueva_2','$res_tod_por_fabs_costo[dis_por_con_fab_cue_nit]','$nit_fabs ','$centro',
			 	'$val_porcentual_costo','$nue_naturaleza_2','$conse',
			 	'$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			 	$query2 = mssql_query($sql_gas2);
			 }
        
       //$act_pabs = $nit->act_pabs($nit_conta,$valor_pabs);
        
        //////////////////////////FIN FABS//////////////////////////
        
        /////////////////////RETIRO SINDICAL/////////////////////
        $cuenta_cau = $matriz_cau[2][1];
        if($matriz_cau[2][2]==1)
          $naturaleza_cau = 2;
        else   
          $naturaleza_cau = 1;
        $total = $aux_inmo+$aportes;
        $sql_cau3 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta_cau','3','$nit_conta','$centro','$total','$naturaleza_cau','$conse','$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";
        //aportes sociales (Retiro Sindical)
        $_SESSION['auxi'][$i] = $aux_inmo;
        $query_cau3 = mssql_query($sql_cau3);
          //Para la pagada
        $cuenta = $matriz[2][1];
        $naturaleza = $matriz[2][2];
        $cue_gasto = $matriz_gasto[2][1];
        if($matriz_gasto[2][2]==1)
            $nat_gasto = 2;
        else
            $nat_gasto = 1;
        $total = $aux_inmo+$aportes;
        $sql3 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','3','$nit_conta','$centro','$total','$naturaleza','$conse','$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";
        //aportes sociales (Retiro Sindical)
        $_SESSION['auxi'][$i] = $aux_inmo;
        $query3 = mssql_query($sql3);
        
		////////////////////////////////////////////////////////
		
		//////////////////////VACACIONES///////////////////////////
		
        //Para la causada
        $cuenta_cau = $matriz_cau[3][1];
        if($matriz_cau[3][2]==1)
          $naturaleza_cau = 2;
        else   
          $naturaleza_cau = 1;
        $sql_cau4 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta_cau','3','$nit_conta','$centro','$fon_vacaciones','$naturaleza_cau','$conse', 
            '$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//vacaciones
        $query_cau4 = mssql_query($sql_cau4);
        //Para la pagada
        $cuenta = $matriz[3][1];
        $naturaleza = $matriz[3][2];
        $cue_gasto = $matriz_gasto[3][1];
        if($matriz_gasto[3][2]==1)
            $nat_gasto = 2;
        else
            $nat_gasto = 1;
        $sql4 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','3','$nit_conta','$centro','$fon_vacaciones','$naturaleza','$conse', '$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//vacaciones
        $query4 = mssql_query($sql4);
        
		//////////////////////////////////////////////////////////
        
        
        ///////////////////////EDUCACION//////////////////////////
        
        //para la causada
        $cuenta_cau = $matriz_cau[4][1];
        if($matriz_cau[4][2]==1)
          $naturaleza_cau = 2;
        else   
          $naturaleza_cau = 1;
        $sql_cau6 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta_cau','3','".$nit_conta."_".$nit_empresa."','$centro','$educacion','$naturaleza_cau','$conse', '$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";
        //EDUCACION
        $query_cau5 = mssql_query($sql_cau6);
        //Pala la pagada
        $cuenta = $matriz[4][1];
        $naturaleza = $matriz[4][2];
        $cue_gasto = $matriz_gasto[4][1];
        if($matriz_gasto[4][2]==1)
            $nat_gasto = 2;
        else
            $nat_gasto = 1;
        $sql6 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','3','".$nit_conta."_".$nit_empresa."','$centro','$educacion','$naturaleza','$conse', '$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";
        //EDUCACION
        $query6 = mssql_query($sql6);
		
		////////////////////////////////////////////////////////
		
		
		///////////////////////ADMINISTRACION//////////////////////////
        
        //para la causada
        $cuenta_cau = $matriz_cau[5][1];
        if($matriz_cau[5][2]==1)
          $naturaleza_cau = 2;
        else   
          $naturaleza_cau = 1;
        $sql_cau6 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta_cau','3','".$nit_conta."_".$nit_empresa."','$centro','$adminBasica','$naturaleza_cau','$conse', '$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";
        //ADMINISTRACION
        $query_cau5 = mssql_query($sql_cau6);
        //Pala la pagada
        $cuenta = $matriz[5][1];
        $naturaleza = $matriz[5][2];
        $cue_gasto = $matriz_gasto[5][1];
        if($matriz_gasto[5][2]==1)
            $nat_gasto = 2;
        else
            $nat_gasto = 1;
        $sql6 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','3','".$nit_conta."_".$nit_empresa."','$centro','$adminBasica','$naturaleza','$conse', '$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";
        //ADMINISTRACION
		$query6 = mssql_query($sql6);
		
		
		$_SESSION['legali'][$i] = $legalizacion;
		////////////////////////////////////////////////////////
		
        
        $ing_base = $val_ganado-$valor_pabs-$aportes-$fon_vacaciones-$legalizacion-$gastos-$educacion/*-$couta_sindical*/;

        $seguridad_social = $nit->por_segSocial_nit($nit_conta,$ing_base,0);
        $seg_social = $ing_base-$seguridad_social;
        $_SESSION['segu_social'][$i] = $seg_social;
        
        /*Empezamos a realizar los calculos para los fondos*/
          $cuenta_cau = $matriz_cau[7][1];
    if($matriz_cau[7][2]==1)
      $naturaleza_cau = 2;
    else   
      $naturaleza_cau = 1;
    $cuenta = $matriz[7][1];
    $naturaleza = $matriz[7][2];
    $cue_gasto = $matriz_gasto[7][1];
    if($matriz_gasto[7][2]==1)
        $nat_gasto = 2;
    else
        $nat_gasto = 1;
        
		
		//BORRAR DESDE AQUI
          if($estado == 1)
           {
            $ing_base1 = round($ing_base,0);
            $ingreso[$i] = round($ing_base1,0);
            //Para la causada
            $cuenta_cau = $matriz_cau[6][1];
            if($matriz_cau[6][2]==1)
               $naturaleza_cau = 2;
            else   
               $naturaleza_cau = 1;
			
			$ing_bas_nuevo=$ing_base1-$val_leg_por_afiliado;
			//echo "los datos son: ".$ing_base1."___".$val_leg_por_afiliado."<br>";
            $sql_cau9 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta_cau','3','$nit_conta','$centro','$ing_bas_nuevo','$naturaleza_cau','$conse','$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Ingreso 
            $query_cau9 = mssql_query($sql_cau9);
            
            
            //SALDOS
            //FABS
            //$tot_sal_fabs=0;
            $que_sal_fabs="
			SELECT(
			
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_nit_tercero LIKE('$nit_fabs') AND mov_doc_numer!=0
			AND mov_cuent IN(SELECT dis_por_con_fab_cue_fondo
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('CIE-2017')
			AND LEN(mov_cuent)=9 AND mov_tipo=1 AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_nit_tercero LIKE('$nit_fabs') AND mov_doc_numer!=0 AND mov_cuent IN(SELECT dis_por_con_fab_cue_fondo
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('PAG-COM-%')
			AND LEN(mov_cuent)=9 AND mov_tipo=1 AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables WHERE
			mov_nit_tercero LIKE('$nit_fabs') AND mov_cuent IN(SELECT dis_por_con_fab_cue_uno
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('DEV-FABS_%')
			AND mov_tipo=2 AND mov_mes_contable<=12)
			-
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_nit_tercero LIKE('$nit_fabs') AND mov_doc_numer!=0 AND mov_cuent IN(SELECT dis_por_con_fab_cue_uno
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('CAU-FABS_%')
			AND mov_tipo=1 AND mov_mes_contable<=12)
			
			
			-
			
			
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_nit_tercero LIKE('$nit_fabs') AND mov_doc_numer!=0
			AND mov_cuent IN(SELECT dis_por_con_fab_cue_fondo
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('CIE-2017')
			AND LEN(mov_cuent)=9 AND mov_tipo=2 AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_nit_tercero LIKE('$nit_fabs') AND mov_doc_numer!=0 AND mov_cuent IN(SELECT dis_por_con_fab_cue_fondo
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('PAG-COM-%')
			AND LEN(mov_cuent)=9 AND mov_tipo=2 AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_nit_tercero LIKE('$nit_fabs') AND mov_cuent IN(SELECT dis_por_con_fab_cue_uno
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('DEV-FABS_%')
			AND mov_tipo=1 AND mov_mes_contable<=12)
			-
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_nit_tercero LIKE('$nit_fabs') AND mov_doc_numer!=0 AND mov_cuent IN(SELECT dis_por_con_fab_cue_uno
			FROM distribucion_porcentajes_conceptos_fabs) AND mov_compro LIKE('CAU-FABS_%')
			AND mov_tipo=2 AND mov_mes_contable<=12)
			)
			AS res_sal_fabs";
            //echo $que_sal_fabs."<br>";
            $eje_sal_fabs=mssql_query($que_sal_fabs);
            $res_sal_fabs=mssql_fetch_array($eje_sal_fabs);
            $tot_sal_fabs=$res_sal_fabs['res_sal_fabs']+$valor_pabs;
            
            //FONDO RETIRO SINDICAL
            //$tot_sal_retiro=0;
            $que_sal_retiro="SELECT(
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(23803009,31400101) AND mov_nit_tercero='$nit_conta' and mov_tipo=2
			AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(23803009,31400101) AND mov_nit_tercero='$nit_conta' and mov_tipo=2
			AND mov_compro='CIE-2017')
			)
			-
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(23803009,31400101) AND mov_nit_tercero='$nit_conta' and mov_tipo=1
			AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(23803009,31400101) AND mov_nit_tercero='$nit_conta' and mov_tipo=1
			AND mov_compro='CIE-2017') AS res_sal_retiro";
            $eje_sal_retiro=mssql_query($que_sal_retiro);
            $res_sal_retiro=mssql_fetch_array($eje_sal_retiro);
            $tot_sal_retiro=$res_sal_retiro['res_sal_retiro']+$total;
            
            //VACACIONES
            //$tot_sal_vacaciones=0;
            $que_sal_vacaciones="SELECT(
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(25051005) AND mov_nit_tercero='$nit_conta' and mov_tipo=2
			AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(25051005) AND mov_nit_tercero='$nit_conta' and mov_tipo=2
			AND mov_compro='CIE-2017')
			)
			-
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(25051005) AND mov_nit_tercero='$nit_conta' and mov_tipo=1
			AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12)
			+
			(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
			WHERE mov_cuent IN(25051005) AND mov_nit_tercero='$nit_conta' and mov_tipo=1
			AND mov_compro='CIE-2017') AS res_sal_vacaciones";
			
            $eje_sal_vacaciones=mssql_query($que_sal_vacaciones);
            $res_sal_vacaciones=mssql_fetch_array($eje_sal_vacaciones);
            $tot_sal_vacaciones=$res_sal_vacaciones['res_sal_vacaciones']+$fon_vacaciones;
        
            //$nit,$fabs,$retiro,$vaca,'$nomina';
            $nom_comprobante = $com_nomina->compensacion($nit_conta,$tot_sal_fabs,$tot_sal_retiro,$tot_sal_vacaciones,$sigla);
            $_SESSION['vacaciones'][$i] = $tot_sal_vacaciones;
            
            
           }
         elseif($estado==3)
         {
            $ing_base1 = round($ing_base,0);
            $ingreso[$i] = round($ing_base1,0);
            //echo $ing_base1."---".$ingreso[$i]."<br>";
            
            //Para la pagada
            $cuenta = $matriz[7][1];
            $naturaleza = $matriz[7][2];
            $cue_gasto = $matriz_gasto[7][1];
            if($matriz_gasto[7][2]==1)
                $nat_gasto = 2;
            else
                $nat_gasto = 1;
            
            
			$ing_bas_nuevo=$ing_base1-$val_leg_por_afiliado;
			//echo "los datos son: ".$ing_base1."___".$val_leg_por_afiliado."<br>";
			$cuenta='23352501';
			$naturaleza='1';
            $sql10 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','3','$nit_conta','$centro','$ing_bas_nuevo','$naturaleza','$conse','$rep_recibo_caja[0]','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Honorarios
            $query10 = mssql_query($sql10);
            
            }

            $query = "SELECT COUNT(*) cant FROM mov_contable";
            $cant_mov = mssql_query($query);
            $cantidad = mssql_fetch_array($cant_mov);
            $mov = "EXECUTE movContable ".$cantidad['cant'];
            $ins_mov = mssql_query($mov);
         }


		
        }
 //}
 
  $act_recibo = $recibo_caja->act_reciboCaja($recibo_act[0]);
  if($act_recibo)
  	echo "<script>alert('Recibo de caja pagado satisfactoriamente');</script>";
  
  
$m=0;
for($i=0;$i<sizeof($nits);$i++)
{ 
    $m++;
    $estado = $nit->est_asociado($nits[$i]);
    if($ingreso[$i]>0 && $estado==1)
    {          
        $saldo_seguridad = $_POST['des_segSocial'.$i];
                
        $sql_cobrarSeg = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','13250594','3','$nits[$i]','$centro','$saldo_seguridad','2','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
        $query_cobrarSeg = mssql_query($sql_cobrarSeg); 
        $val_pagar = round($ingreso[$i] - $saldo_seguridad,0);
        /*****************Descuentos Factura******************************/
        if($adelanto==1)
          $recibo_act[0]=$_SESSION['factura'];
        $des_compensacion = $movimiento->bus_des_compensacion($nits[$i],$_SESSION['factura'],$rep_recibo_caja[0]);
        $result = mssql_num_rows($des_compensacion);
        $tot_descuento=0;
        if($result>0)
        {
          while($dat_descuentos = mssql_fetch_array($des_compensacion))
          {
            $sql="EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','".$dat_descuentos['des_nom_cuenta']."','3','$nits[$i]','$centro','".$dat_descuentos['des_nom_valor']."','2','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
            $query = mssql_query($sql);
            $tot_descuento += $dat_descuentos['des_nom_valor'];
          }
        }
        ///////////////////////////////////////////////////////////////////
        
        $que_tip_retencion="SELECT nit_tip_procedimiento,nit_por_ret_fuente FROM nits WHERE nit_id=$nits[$i]";
        //echo $que_tip_retencion."<br>";
        $eje_tip_retencion=mssql_query($que_tip_retencion);
        $res_tip_retencion=mssql_fetch_array($eje_tip_retencion);
        if($res_tip_retencion['nit_tip_procedimiento']==2)
        {
            /*$ssausente=($val_pagar*8/100);
            $base_rentecion = $val_pagar-$ssausente;
            $base_rentecion=$base_rentecion*75/100;*/
            //echo "la base es: ".$base_rentecion."<br>";
            
            $val_rete=round(($ingreso[$i])*($res_tip_retencion['nit_por_ret_fuente']/100),0);
            //echo "el valor de la retencion es: ".$val_rete."<br>";
        }
        else
        {
            $val_rete=$movimiento->cal_ret_fuente($ingreso[$i]);
        }
        $val_pagar = round($val_pagar-$val_rete-$tot_descuento,0);//ojo solo para estas
        $naturaleza = $nat_pagar[$i];   
        $sql_rete="EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','23650501','3','$nits[$i]','$centro','$val_rete','2','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
        $query_rete = mssql_query($sql_rete);
        
        /*********************DESCONTAR EL CREDITO EN LA NOMINA**********************/
        $cuota_cre=$ins_credito->bus_cuoDescontar($_SESSION['factura'],$nits[$i],1);
        if($cuota_cre)
        {
        	//echo "entra por aqui <br>";
            while($row_cuota = mssql_fetch_array($cuota_cre))
            {
            	//echo "entra por aqui";
                $ins_credito->ultimo_pago($row_cuota['des_cre_credito']);
                $mos_credito = $ins_credito->cueCreditos($row_cuota['des_cre_credito'],$nits[$i],$centro,$row_cuota['des_cre_credito']);
                $dat_retorno = split("--",$mos_credito);
                $sql_interes = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','".$dat_retorno[5]."','3','$nits[$i]','$centro','".$row_cuota['des_cre_interes']."','2','C-".$row_cuota['des_cre_credito']."','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";//INTERES
                $query_interes = mssql_query($sql_interes);
                $sql_capital ="EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','".$dat_retorno[2]."','3','$nits[$i]','$centro','".$row_cuota['des_cre_capital']."','2','C-".$row_cuota['des_cre_credito']."','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";//Capital
                $query_capital = mssql_query($sql_capital);
                $val_pagar = round($val_pagar-($row_cuota['des_cre_interes']+$row_cuota['des_cre_capital']),0);//ojo solo para estas
                
                $ins_credito->act_cuoCredito(3,$row_cuota['des_cre_id'],$sigla_cue_pagar,$rec_cajaNum,$mes,$ano);
				
				//ACTUALIZAR SALDO DEL CREDITO
				$saldo_credito=$ins_credito->saldo_credito($row_cuota['des_cre_credito']);
				$act_sal_cre_por_nomina=$ins_credito->act_saldo_credito($saldo_credito,$row_cuota['des_cre_id']);
            }
        }
        //////////////////////////////////////////////////////////////////////////////
        $val_pagar=$val_pagar-$descuentos[$i];

		/*if($nits[$i]==1543)
		{
			echo $val_pagar."<br>";
			echo $descuentos[$i];
		}*/
			
        if($val_pagar<0)//SI EL VALOR ES NEGATIVO SE ENVIA EL VALOR EN NATURALEZA CONTRARIA
		{
			$val_pagar=$val_pagar*-1;
		
        	$sql_pagar = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','25051001','3','$nits[$i]','$centro','$val_pagar','1','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
        	$query_pagar = mssql_query($sql_pagar);
        }

		else
		{
			$sql_pagar = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','25051001','3','$nits[$i]','$centro','$val_pagar','2','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
        	$query_pagar = mssql_query($sql_pagar);
		}
           
    }
//0471
    elseif($ingreso[$i]>0 && $estado==3)
    {
        $val_pagar=$ingreso[$i];
        /*****************Descuentos Factura******************************/
        $des_compensacion = $movimiento->bus_des_compensacion($nits[$i],$_SESSION['factura'],$rep_recibo_caja[0]);
        $result = mssql_num_rows($des_compensacion);
        //echo $result."<br>";
        $tot_descuento=0;
        if($result>0)
        {
          while($dat_descuentos = mssql_fetch_array($des_compensacion))
          {
            $sql="EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','".$dat_descuentos['des_nom_cuenta']."','3','$nits[$i]','$centro','".$dat_descuentos['des_nom_valor']."','2','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
            $query = mssql_query($sql);
            $tot_descuento += $dat_descuentos['des_nom_valor'];
          }
        }
        ///////////////////////////////////////////////////////////////////     
        $naturaleza = $nat_pagar[$i];
        $val_rete = ($ingreso[$i]*10/100);
        $sql_rete="EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','23651501','3','$nits[$i]','$centro','$val_rete','2','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
        $query_rete = mssql_query($sql_rete);
        ///////////////////////RETENCION FUENTE////////////////////////////
        
        /*********************DESCONTAR EL CREDITO EN LA NOMINA**********************/
        $cuota_cre=$ins_credito->bus_cuoDescontar($_SESSION['factura'],$nits[$i],1);
        if($cuota_cre)
        {
            while($row_cuota = mssql_fetch_array($cuota_cre))
            {
                $ins_credito->ultimo_pago($row_cuota['des_cre_credito']);
                $mos_credito = $ins_credito->cueCreditos($row_cuota['des_cre_credito'],$nits[$i],$centro,$row_cuota['des_cre_credito']);
                $dat_retorno = split("--",$mos_credito);
                $sql_interes = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','".$dat_retorno[5]."','3','$nits[$i]','$centro','".$row_cuota['des_cre_interes']."','2','C-".$row_cuota['des_cre_credito']."','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";//INTERES
                $query_interes = mssql_query($sql_interes);
                $sql_capital ="EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','".$dat_retorno[2]."','3','$nits[$i]','$centro','".$row_cuota['des_cre_capital']."','2','C-".$row_cuota['des_cre_credito']."','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";//Capital
                $query_capital = mssql_query($sql_capital);
                $val_pagar = round($val_pagar-($row_cuota['des_cre_interes']+$row_cuota['des_cre_capital']),0);//ojo solo para estas
                
                
                $ins_credito->act_cuoCredito(3,$row_cuota['des_cre_id'],$sigla_cue_pagar,$rec_cajaNum,$mes,$ano);
				
				//ACTUALIZAR SALDO DEL CREDITO
				$saldo_credito=$ins_credito->saldo_credito($row_cuota['des_cre_credito']);
				$act_sal_cre_por_nomina=$ins_credito->act_saldo_credito($saldo_credito,$row_cuota['des_cre_id']);
            }
        }

		$saldo_seguridad = $_POST['des_segSocial'.$i];
                
        $sql_cobrarSeg = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','13250594','3','$nits[$i]','$centro','$saldo_seguridad','2','$conse','$rep_recibo_caja[0]','0','3','$fecha','$mes','$ano','$bas_retencion'";
        $query_cobrarSeg = mssql_query($sql_cobrarSeg); //SEGURIDAD SOCIAL
        //$val_pagar = round($ingreso[$i] - $saldo_seguridad,0);
		
        $val_pagar = round($val_pagar-$val_rete-$tot_descuento-$descuentos[$i]-$saldo_seguridad,0);//ojo solo para estas
        
        
        if($val_pagar<0)//SI EL VALOR ES NEGATIVO SE ENVIA EL VALOR EN NATURALEZA CONTRARIA
		{
			$val_pagar=$val_pagar*-1;
        
        	$sql_pagar = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','25051001','3','$nits[$i]','$centro','$val_pagar','1','$conse','$rep_recibo_caja[0]','0','1','$fecha','$mes','$ano','$bas_retencion'";
        	$query_pagar = mssql_query($sql_pagar);
		}
		else
		{
			$sql_pagar = "EXECUTE insMovimiento '$sigla_cue_pagar','".$_SESSION['factura']."','25051001','3','$nits[$i]','$centro','$val_pagar','2','$conse','$rep_recibo_caja[0]','0','1','$fecha','$mes','$ano','$bas_retencion'";
        	$query_pagar = mssql_query($sql_pagar);
		}
		
		
    }
 /*************************************************************************************/
    $query = "SELECT COUNT(*) cant FROM mov_contable";
    $cant_mov = mssql_query($query);
    $cantidad = mssql_fetch_array($cant_mov);
    $mov = "EXECUTE movContable ".$cantidad['cant'];
    $ins_mov = mssql_query($mov);
	
	
	$que_act_cuenta="UPDATE movimientos_contables SET mov_cuent='510595961' WHERE mov_cuent='220505'
	AND mov_ano_contable='$ano' AND mov_mes_contable='$mes' AND mov_compro='$sigla'";
	//echo $que_act_cuenta;
	$eje_act_cuenta=mssql_query($que_act_cuenta);
	
}


//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA EL AJUSTE
$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
aud_mov_con_descripcion='$aud_mov_con_descripcion'
WHERE mov_compro='$sigla' AND mov_mes_contable='$mes' AND mov_ano_contable='$ano'
AND tip_mov_aud_id IS NULL";
//echo $que_aud_mov_contable;
$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);

///////////////////////////////////////////////////////////////////////////////////


/*A PARTIR DE AQUI SE HACE EL AJUSTE DE LA NOMINA CON LOS RECIBOS DE CAJA PAGADOS*/
	
if($val_recibo=="")
{$val_recibo=0;$val_factura=0;}
  
  
if($val_recibo==$val_factura)
{
	//echo "entra <br>";
  	//$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,date('Y'),$rep_recibo_caja[0]);
  	$contador=0;
  	while($contador<$_POST['can_jornadas'])
	{
		$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,$ano,$rep_recibo_caja[0],$sigla_cue_pagar,$_POST['nit'.$contador],$fecha,$centro);
		$contador++;
	}
	
}
elseif(($val_recibo+$tot_glosa)==$val_factura)
{
	//$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,date('Y'),$rep_recibo_caja[0]);
	
	$contador=0;
  	while($contador<$_POST['can_jornadas'])
	{
		$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,$ano,$rep_recibo_caja[0],$sigla_cue_pagar,$_POST['nit'.$contador],$fecha,$centro);
		$contador++;
	}
}
elseif(($val_recibo+$_POST['lasumaglosa2'])==$val_factura)
{
  	//$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,date('Y'),$rep_recibo_caja[0]);
	$contador=0;
  	while($contador<$_POST['can_jornadas'])
	{
		$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,$ano,$rep_recibo_caja[0],$sigla_cue_pagar,$_POST['nit'.$contador],$fecha,$centro);
		$contador++;
	}
}
elseif(($val_recibo+$_POST['descuentos_glosas'])==$val_factura)
{
	//$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,date('Y'),$rep_recibo_caja[0]);
	$contador=0;
  	while($contador<$_POST['can_jornadas'])
	{
		$ajuste = $movimiento->ajuste_causacion($_SESSION['factura'],$mes,$ano,$rep_recibo_caja[0],$sigla_cue_pagar,$_POST['nit'.$contador],$fecha,$centro);
		$contador++;
	}	
}


$sigla_ajuste='AJUS-NOM-'.$_SESSION['factura'];

$aud_mov_con_descripcion='CREACION DE AJUSTE NOMINA AFILIADOS';

//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA EL AJUSTE
$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET
aud_mov_con_usuario='$usuario_actualizador',aud_mov_con_fecha='$fecha_actualizacion',
aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
aud_mov_con_descripcion='$aud_mov_con_descripcion'
WHERE mov_compro='$sigla_ajuste' AND mov_mes_contable='$mes' AND mov_ano_contable='$ano'
AND tip_mov_aud_id IS NULL";
//echo $que_aud_mov_contable;
$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);

	
/////////////////////////////////////////////////////////////////////////////////////////



//echo "<script>abreFactura('../reportes_PDF/pago_causacion.php?valor=".$_SESSION['factura']."&compensacion=".$sigla_cue_pagar."&elrecibo=".$rec_cajaNum."&tipo_reporte=1',1)</script>";
//echo "<script>abreFactura('../reportes_PDF/ajuste_causacion.php?valor=".$_SESSION['factura']."&compensacion=".$sigla_cue_pagar."&elrecibo=".$rec_cajaNum."&tipo_reporte=1',2)</script>";
//echo "<script>abreFactura('../reportes_PDF/ord_pago.php?valor=".$factura."&sigla=".$sigla_cue_pagar."&elrecibo=".$rec_cajaNum."&tipo_reporte=1',3)</script>";
echo "<script>preguntar(".$conse.",4);</script>";

//}

?>