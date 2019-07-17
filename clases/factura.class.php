<?php
session_start();
@include_once('../conexion/conexion.php');
include_once('nits.class.php');
include_once('centro_de_costos.class.php');
include_once('producto.class.php');
include_once('comprobante.class.php');
include_once('reporte_jornadas.class.php');
include_once('tipo_facturacion.class.php');
include_once('transacciones.class.php');
include_once('moviminetos_contables.class.php');
include_once('saldos.class.php');
include_once('nomina.class.php');
include_once('cuenta.class.php');
include_once('contrato.class.php');
include_once('pabs.class.php');
@include_once('../inicializar_session.php');
@include_once('inicializar_session.php');

class factura
 { 
   private $orden_compra;
   private $fecha;
   private $nit;
   private $cen_cos;
   private $tip_facturacion;
   private $rep_jornadas;
   private $mov_contable;
   private $ins_cont;
   private $ins_fabs;
   
   public function __construct()
   {
     $this->cen_cos = new centro_de_costos();
     $this->nit = new nits();
     $this->tip_facturacion = new tipo_facturacion();
     $this->rep_jornadas = new reporte_jornadas();
     $this->mov_contable = new movimientos_contables();
     $this->ins_cont=new contrato();
	 $this->ins_fabs=new pabs();
   }
   
	public function ConTodPorFonFabs()
   	{
		return $this->ins_fabs->ConTodPorFonFabs();	
   	}
   
   public function contratoServicio($nit,$mes,$ano,$fecha)
   {
       return $this->ins_cont->contratoServicio($nit,$mes,$ano,$fecha);
   }
   
   public function con_tipo_facturacion()
   {
		return $this->tip_facturacion->con_tip_facturacion();  
   }
   
   public function get_cenCos()
   {
        $getOrdCom = "SELECT MAX(ord_com_id) ord_com FROM ordenes_compra";
	$guaOrdCom = mssql_query($getOrdCom);
	$this->orden_compra = mssql_fetch_array($guaOrdCom);
	return $this->orden_compra['ord_com']; 
   }
   
   public function guardar_ordCompra($nit,$cen_cos,$total)
   {
	 $fecha = date('d-m-Y G:i:s');
     $queOrdCom = "INSERT INTO ordenes_compra(est_ord_com_id, nit_id, cen_cos_id, ord_com_val_total, ord_com_fecha) 
	               VALUES (1,'$nit','$cen_cos',$total,'$fecha')";
	 $guaOrdCom = mssql_query($queOrdCom);
	 if($guaOrdCom)
	   return true;
	 else
	   return false;			   
   }
   
   public function obt_consecutivo($comprobante)
   {
	   $sql = "SELECT tip_com_consecutivo FROM tipo_comprobante WHERE tip_com_id = $comprobante";
	   $query = mssql_query($sql);
	   if($query)
	   {
		   $row = mssql_fetch_array($query);
		   return $row['tip_com_consecutivo'];
	   }
	   return false;
   }
   
   public function act_consecutivo($comprobante)
   {
	   $sql = "UPDATE tipo_comprobante SET tip_com_consecutivo = tip_com_consecutivo+1 WHERE tip_com_id = $comprobante";
	   $query = mssql_query($sql);
	   if($query)
		   return true;
		else
	       return false;
   }
   /*guarda factura*/
   
   
	public function ConSalCuePorNit($nit_id,$cue_uno,$cue_dos)
   	{
   		$query="SELECT(
		(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
		WHERE mov_cuent IN($cue_uno,$cue_dos) AND mov_nit_tercero='$nit_id' and mov_tipo=2
		AND mov_compro NOT LIKE('CIE-%')  AND mov_mes_contable<=12)
		+
		(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
		WHERE mov_cuent IN($cue_uno,$cue_dos) AND mov_nit_tercero='$nit_id' and mov_tipo=2
		AND mov_compro='CIE-2017')
		)
		-
		(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
		WHERE mov_cuent IN($cue_uno,$cue_dos) AND mov_nit_tercero='$nit_id' and mov_tipo=1
		AND mov_compro NOT LIKE('CIE-%')  AND mov_mes_contable<=12)
		+
		(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
		WHERE mov_cuent IN($cue_uno,$cue_dos) AND mov_nit_tercero='$nit_id' and mov_tipo=1
		AND mov_compro='CIE-2017') AS res_sal_retiro";
		
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$eje_saldo=mssql_fetch_array($ejecutar);
			$res_saldo=$eje_saldo['res_sal_retiro'];
			return $res_saldo;
		}
		else
			return false;
   	}
   
   
	public function TotalSaldoCreditosPorNit($nit_id)
   	{
   		/*$query="SELECT DISTINCT cre.cre_id,c.con_nombre,cre.cre_observacion,cre.cre_fec_desembolso,cre_fec_solicitud,
		cre.cre_num_cuotas,cre_valor,ISNULL((SELECT SUM(des_cre_capital)
		FROM des_credito
		WHERE des_cre_credito=cre.cre_id AND des_cre_estado=3),0) AS capital, cre_valor-ISNULL((
		SELECT SUM(des_cre_capital) FROM des_credito WHERE des_cre_credito=cre.cre_id AND des_cre_estado=3),0)
		saldo FROM creditos cre
		INNER JOIN conceptos c ON c.con_id=cre.cue_id
		INNER JOIN movimientos_contables mc ON 'CRE_'+CAST(cre.cre_id AS VARCHAR)=mc.mov_compro
		WHERE nit_id='$nit_id' AND cre.cre_fec_desembolso IS NOT NULL ORDER BY cre.cre_id ASC";*/
		
		$query="SELECT DISTINCT cre.cre_id,c.con_nombre,cre.cre_observacion,cre.cre_fec_desembolso,cre_fec_solicitud,
		cre.cre_num_cuotas,cre_valor,ISNULL((SELECT SUM(des_cre_capital)
		FROM des_credito
		WHERE des_cre_estado=3 AND des_cre_credito=cre.cre_id),0) AS capital, cre_valor-ISNULL((
		SELECT SUM(des_cre_capital) FROM des_credito WHERE des_cre_credito=cre.cre_id),0) saldo
		FROM creditos cre INNER JOIN conceptos c ON c.con_id=cre.cue_id
		INNER JOIN movimientos_contables mc ON 'CRE_'+CAST(cre.cre_id AS VARCHAR)=mc.mov_compro
		where nit_id='$nit_id' AND cre.cre_fec_desembolso IS NOT NULL AND c.con_id NOT IN(312,314,316,302,317,310,311)";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$saldo=0;
			while($rec_saldo=mssql_fetch_array($ejecutar))
			{
				if($rec_saldo['saldo']>0)
				{
					$saldo+=$rec_saldo['saldo'];
				}
			}
			return $saldo;
		}
		else
		{
			$saldo=0;
			return $saldo;
		}

   	}
   
   
   public function guardar_factura($centro,$descrip,$val_uni,$val_total,$conse,$cen_costo,$mes,$ano,$nit,$rep_jornadas,$mes,$opcion,$fec_impresion,$mes_servi,$num_jornadas,$periodo_facturacion,$anio_servicio)
   {
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
   	
	   $saldos = new saldos();
	   $nomina = new nomina();
	   $cuenta = new cuenta();
	   $tran = new transacciones();
	   $fecha = date('d-m-Y');
	   
	   $bas_retencion=0;
	   
	   if(strstr($centro,"-"))
	   {
		   $dat_centro = split("-",$centro);
		   $centro = $dat_centro[0];
		   $factura = $dat_centro[1];
		   $cons = $this->datFactura($factura);
		   $tod_fac = mssql_fetch_array($cons);
		   $num_factura = $tod_fac['fac_consecutivo'];
		   $sigla = "CAU-NOM-".$conse;
	   }
	   
	   if($mes == "")
	     $mes = date('m');
	   	 $fac = $this->rep_jornadas->fac_facturas($rep_jornadas);

            if($fac!=0)
            {
                //$con_id=$this->con_id_con_por_cen_cos(2,1,$centro);
                $con_contrato=$this->contratoServicio($nit,$mes,$ano,$fec_impresion);
                $res_contrato=mssql_fetch_array($con_contrato);
                $con_id=$res_contrato['con_id'];
                if(empty($con_id))
                	$con_id=0;
                $fec_sistema=date('d-m-Y');
				
				if(trim($anio_servicio)=="")
					$anio_servicio=$ano;
				
                $sql="INSERT INTO factura(fac_cen_cos,fac_fecha,fac_descripcion,fac_val_unitario,fac_val_total,fac_consecutivo,
				fac_nit,fac_estado,fac_rep_reconfirmado,fac_contrato,fac_fec_creacion,fac_mes_servicio,fac_ano_servicio,fac_jornadas,fac_perFacturacion)
				VALUES ($centro,'$fec_impresion','$descrip',$val_uni,$val_total,'$conse',$nit,2,0,$con_id,'$fec_sistema',$mes_servi,'$anio_servicio','$num_jornadas','$periodo_facturacion')";
				//echo $sql;
                $query = mssql_query($sql);
            }
            if($opcion!=0 && $opcion!=5)
            {	  
                $this->act_consecutivo(2);
				$factura = $this->ult_factura();
				$this->rep_jornadas->actReporte_jor($factura,$rep_jornadas);
				$movimiento = new movimientos_contables();
				//guaTransaccion($fec_ven,$num_oc_fa,$usu,$fecha,$mes_contable,$ano_contable)
				$nueTran = $tran->guaTransaccion("FAC-".$factura,$fecha,$nit,$centro,$val_total,0,$fecha,$conse,$_SESSION['k_nit_id'],$fecha,$mes,$ano);
				$transacc = $tran->obtener_concecutivo();
				$num_tran = mssql_fetch_array($transacc);
				
				//$mov = $movimiento->guarCam_movimiento($centro,$factura,"FAC-".$factura,$nit,$fecha,$num_tran[0],$fecha,0,$val_total,101,0,0,$mes,$ano);
				
				//GUARDAR FACTURA AHORA
				$que_cue_1="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
				mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('FAC-$factura','$num_tran[0]','$fecha','41150105','101','$nit','$centro','$val_total','2','$num_tran[0]','3','$mes','$ano')";
				$eje_cue_1=mssql_query($que_cue_1);
				
				//echo $que_cue_1."<br>";
				
				$dat_dat_cen_cos_por_id=$this->cen_cos->ConsultarDatosCentroCostoPorId($centro);
				$res_dat_cen_cos_por_id=mssql_fetch_array($dat_dat_cen_cos_por_id);
				
				$con_uni_funcional=$this->nit->ConsultarUnidadFuncionalPorId($res_dat_cen_cos_por_id['cen_cos_nit']);//CUENTA QUE MuEVE SEGUN LA UNIDAD FUNCIONAL
				$tie_uni_funcional=mssql_fetch_array($con_uni_funcional);
				
				$que_cue_2="INSERT INTO movimientos_contables(mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo,mov_documento,
				mov_doc_numer,mov_mes_contable,mov_ano_contable) VALUES('FAC-$factura','$num_tran[0]','$fecha','$tie_uni_funcional[nit_uni_funcional]','101','$nit','$centro','$val_total','1','$num_tran[0]','3','$mes','$ano')";
				$eje_cue_2=mssql_query($que_cue_2);
				//echo $que_cue_2."<br>";

				
				//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
				$que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET aud_mov_con_usuario='$usuario_actualizador',
				aud_mov_con_fecha='$fecha_actualizacion',aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
				aud_mov_con_descripcion='$aud_mov_con_descripcion'
				WHERE mov_compro='FAC-$factura' AND mov_mes_contable='$mes' AND mov_ano_contable='$ano'
				AND tip_mov_aud_id IS NULL";
				//echo $que_aud_mov_contable;
				$eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
				
								
				
				$sigla = "CAU-NOM-".$factura;
            }
            else
            {
            	$factura = $_SESSION["conse"][1];
            	$sigla = "CAU-NOM-".$factura;
            }

            $dat_repor = $this->rep_jornadas->canJorFac($factura);
            $val_jornada = $val_total/$dat_repor;
            $datos_rep_jornadas = $this->rep_jornadas->buscarReporteJornadas_Factura($factura);
            $nueTran = $tran->guaTransaccion($sigla,$fecha,$nit,$centro,$val_total,0,$fecha,$factura,4,$fecha,$mes,$ano);
	    	$transacc = $tran->obtener_concecutivo();
            $empresa = $this->nit->con_dat_nit(13);
            $dat_empresa = mssql_fetch_array($empresa);
            $nit_empresa = $dat_empresa['nit_id'];
            $j=0;
            while($row = mssql_fetch_array($datos_rep_jornadas))
            {
                $reporte = $row['rep_jor_id'];
				$form = $this->mov_contable->consul_formulas(2);
                $i=1;$matriz;
                if($form)
                {
                    $dat_matriz = mssql_fetch_array($form);
                    while($i<=21)
                    {
                        $arre=split(",",$dat_matriz["for_cue_afecta".$i]);
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
			   
                    $form_gasto = $this->mov_contable->consul_formulas(4);
                    $i=1;$matriz_gasto;
             if($form_gasto)
             {
              $dat_matriz = mssql_fetch_array($form_gasto);
              while($i<=21)
               {
	            $arre = split(",",$dat_matriz["for_cue_afecta".$i]);
		        $a = $arre[0];
		        $b = $arre[1];
		        $c = $arre[2];
			    $d = $arre[3];
		        if($a != "" && $b != "" && $c != "")
		 	     {
			       $matriz_gasto[$i][0]= $a;
			       $matriz_gasto[$i][1]= $b;
			       $matriz_gasto[$i][2]= $c;
			       $matriz_gasto[$i][3]= $d;
			     }
		        $i++;
		       }//cierra el while  
			 }
			 
			 $nitcc = $row['id_nit_por_cen'];
			 $datnit = $this->cen_cos->cenCos_nit($nitcc);
			 $nit = $datnit[0];
			 $cantidad_cuentas = 18;
			 if($val_jornada==0 || $val_jornada=="")
			    $val_jornada = 1;
			 $val_jornadas = $row['rep_jor_num_jornadas']*$val_jornada;
			 $estado = $this->nit->est_asociado($nit);
			 $minimo = $this->nit->sal_minimo();
			 
			 ////////////////////////////////////////APLICA MODELO ECONOMICO DE LA TABLA datos_nomina////////////////////////////////////////
			 if($val_jornadas>0)
			 {
			 $datos = $this->rep_jornadas->bus_datCompensacion();
			 $dat_compe = mssql_fetch_array($datos);	 
			 if($estado == 1)
			 {
			 	//CALCULO DE LA ADMINISTRACION SEGUN LA FECHA DE LA FACTURA
				if($anio_servicio==2017)
				{
					if($mes_servi<=8)//CALCULA LA ADMON ANTERIOR 5%
					{
						$adminBasica=$val_jornadas*(5/100);//ADMINISTRACION BASICA
					}
					else//CALCULA LA ADMON NUEVA 5.5%
					{
						$adminBasica=$val_jornadas*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
					}
				}
				else//CALCULA LA ADMON NUEVA 5.5%
				{
					if($anio_servicio<2017)//CALCULA LA ADMON ANTERIOR 5%
					{
						$adminBasica=$val_jornadas*(5/100);//ADMINISTRACION BASICA
					}
					else//CALCULA LA ADMON NUEVA 5.5%		
					{
						$adminBasica=$val_jornadas*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
					}
				}
				
				
				/**********PORCENTAJES DE DESCEUNTO DATOS COMPENSACION**********/
				
				
				//INICIO PONER AQUI EL BLOQUE 1 DE FONDO DE RETIRO Y COMENTAREAR LO ANTERIOR
				
				
				$cue_uno='23803009';
				$cue_dos='31400101';
				$tot_sal_creditos=$this->TotalSaldoCreditosPorNit($nit);
				$tot_sal_cuenta=$this->ConSalCuePorNit($nit,$cue_uno,$cue_dos);
				$saldo_final=$tot_sal_cuenta-$tot_sal_creditos;//LO QUE TIENE EN EL FONDO - LO QUE DEBE DE CREDITOS
				$can_sal_minimos=40;
				$val_tot_sal_minimos=$minimo*$can_sal_minimos;
				
				if($anio_servicio==2018)
				{
					if($mes_servi<=9)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
					{
						$aportes=$val_jornadas*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
					}
					else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
					{
						//if($dat_saldo['saldo']>($sal_minimo*40) && $dat_salCredito < ($sal_minimo*40))
						
						//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
						if(($tot_sal_cuenta > $val_tot_sal_minimos) && ($tot_sal_creditos < $val_tot_sal_minimos))//SI TIENE MÁS DE 40SMMLV
						{
							$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit'";
							$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
							$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
						
							$aportes=$val_jornadas*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
						}
						else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
						{
							$aportes=$val_jornadas*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
						}
					}
				}
				else
				{
					if($anio_servicio<2018)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
					{
						$aportes=$val_jornadas*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
					}
					else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
					{
						
						//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
						if(($tot_sal_cuenta > $val_tot_sal_minimos) && ($tot_sal_creditos < $val_tot_sal_minimos))//SI TIENE MÁS DE 40SMMLV
						{
							$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit'";
							$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
							$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
							$aportes=$val_jornadas*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
						}
						else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
						{
							$aportes=$val_jornadas*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
						}
					}
				}
				
				//ANTERIOR
			 	//$aportes=$val_jornadas*($dat_compe['dat_nom_aportes']/100);//FONDO DE RETIRO SINDICAL
				
				
				
				
				//FIN PONER AQUI EL BLOQUE 1 DE FONDO DE RETIRO Y COMENTAREAR LO ANTERIOR
				
				
			 	
			 	
			 	
			 	
			 	
			 	$legalizacion = $val_jornadas*($dat_compe['dat_nom_legalizacion']/100);//LEGALIZACION CONTRATOS
			 	
				//$adminBasica=$val_jornadas*($dat_compe['dat_nom_gastos']/100);//ADMINISTRACION BASICA
				
				$adminExtraordinaria=$val_jornadas*($dat_compe['dat_admonExtra']/100);//ADMINISTRACION EXTRAORDINARIA
				$gastos=$adminBasica+$adminExtraordinaria;
			 	$educacion = $val_jornadas*($dat_compe['dat_nom_educacion']/100);//EDUCACION
			 }
			 elseif($estado == 3)
			 {
			 	
				//echo "entra por aqui!";
				$aux_inmo = 0;
			   	$aportes = 0;
			   	$legalizacion = 0;
				
				
				if($anio_servicio==2017)
				{
					if($mes_servi<=8)//CALCULA LA ADMON ANTERIOR 5%
					{
						$adminBasica=$val_jornadas*(5/100);//ADMINISTRACION BASICA
					}
					else//CALCULA LA ADMON NUEVA 5.5%
					{
						$adminBasica=$val_jornadas*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
					}
				}
				else//CALCULA LA ADMON NUEVA 5.5%
				{
					if($anio_servicio<2017)//CALCULA LA ADMON ANTERIOR 5%
					{
						$adminBasica=$val_jornadas*(5/100);//ADMINISTRACION BASICA
					}
					else//CALCULA LA ADMON NUEVA 5.5%		
					{
						$adminBasica=$val_jornadas*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
					}
				}
				
			   
			    /**********PORCENTAJES DE DESCEUNTO DATOS COMPENSACION**********/
			    
			   	//$adminBasica=$val_jornadas*($dat_compe['dat_admonNoAfi']/100);
				$adminExtraordinaria=$val_jornadas*($dat_compe['dat_admonNoAfiExtraordinaria']/100);
				if($dat_compe['dat_admonAdministacion']==0)
					$educacion=0;
				else
					$educacion=$val_jornadas*($dat_compe['dat_admonAdministacion']/100);//FONDO DE EDUCACION
				
				$gastos=$adminBasica+$adminExtraordinaria;
				
			   }
 			   $_SESSION['auxilio1'][$j] = $aux_inmo;
			   $_SESSION['aportes1'][$j] = $aportes;
			   $_SESSION['legalizacion1'][$j] = $legalizacion;
			   $_SESSION['gastos1'][$j] = $gastos;
			   $_SESSION['educacion1'][$j] = $educacion;
			   $_SESSION['asociado1'][$j] = $nit;
			   $_SESSION['jornada1'][$j] = $val_jornadas;
			 ///////////////////////////////////////////////////////////////////
			 /*% PABS  y vacaciones*/
			 if($estado==1)
			 {
				 $datos = $this->nit->pabs_asociado($nit);
				 $dat_pabs = mssql_fetch_array($datos);
				 $por_pabs = $dat_pabs['pabs'];
				 $vacaciones_afi = $dat_pabs['vac'];
				 $valor_pabs = $val_jornadas*($por_pabs/100);
				 $_SESSION['pabs'][$j] = $valor_pabs;
				 if($vacaciones_afi=='SI')
					$fon_vacaciones = $val_jornadas*($dat_pabs['porce']/100);
				 else
					$fon_vacaciones = 0;

				 $_SESSION['vacaciones'][$j] = $fon_vacaciones;	
			 }
			 else
			 {
				$valor_pabs=0;
				$fon_vacaciones = 0;
			 }
			 //////////////////////////INICIO INICIO FABS//////////////////////////
			 
			 $con_tod_por_fabs_pasivo=$this->ConTodPorFonFabs();
			 
			 $con_tod_por_fabs_costo=$this->ConTodPorFonFabs();
			 
			 
			 $nit_fabs = $nit."_1";
			 
			 /////////////////////CUENTAS PASIVO(2)/////////////////////
			 while($res_tod_por_fabs_pasivo=mssql_fetch_array($con_tod_por_fabs_pasivo))
			 {
			 	$val_porcentual_pasivo=round($valor_pabs*($res_tod_por_fabs_pasivo['dis_por_con_fab_cue_uno_por']/100),0);
				//echo $res_tod_por_fabs_pasivo['dis_por_con_fab_cue_uno_porce']."<br>";
				
				//echo $val_porcentual_pasivo,"<br>";
			 	$sql2 ="EXECUTE insMovimiento '$sigla','$factura','$res_tod_por_fabs_pasivo[dis_por_con_fab_cue_uno]',
			 	'$res_tod_por_fabs_pasivo[dis_por_con_fab_cue_nit]','$nit_fabs','$centro','$val_porcentual_pasivo','$res_tod_por_fabs_pasivo[dis_por_con_fab_cue_uno_nat]',
			 	'$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			 	//echo $sql2."<br>";
			 	$query2 = mssql_query($sql2);
			 }
			 /////////////////////FIN CUENTAS PASIVO(2)/////////////////////
			 
			 
			 /////////////////////CUENTAS COSTO(6)/////////////////////
			 while($res_tod_por_fabs_costo=mssql_fetch_array($con_tod_por_fabs_costo))
			 {
			 	$val_porcentual_costo=round($valor_pabs*$res_tod_por_fabs_costo['dis_por_con_fab_cue_dos_por']/100,0);
				
			 	$sql_gas2 ="EXECUTE insMovimiento '$sigla','$factura',
			 	'$res_tod_por_fabs_costo[dis_por_con_fab_cue_dos]','$res_tod_por_fabs_costo[dis_por_con_fab_cue_nit]','$nit_fabs ','$centro',
			 	'$val_porcentual_costo','$res_tod_por_fabs_costo[dis_por_con_fab_cue_dos_nat]','$factura',
			 	'3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			 	$query2 = mssql_query($sql_gas2);
			 }
			 /////////////////////FIN CUENTAS COSTO(6)/////////////////////
			 
			 //////////////////////////FIN FABS//////////////////////////
			 
			 $cuenta = $matriz[2][1];
			 $naturaleza = $matriz[2][2];
			 $cue_gasto = $matriz_gasto[2][1];
			 $nat_gasto = $matriz_gasto[2][2];
			 $total = $aportes+$aux_inmo;
			 $sql3 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','$nit','$centro','$total',
			    	  '$naturaleza','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Retiro sindical
			 $query3 = mssql_query($sql3);
			 $sql_gas3 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto',2,'$nit','$centro','$total',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//retiro sindcal
			 $query3 = mssql_query($sql_gas3);
			 
			 $cuenta = $matriz[3][1];
			 $naturaleza = $matriz[3][2];
			 $cue_gasto = $matriz_gasto[3][1];
			 $nat_gasto = $matriz_gasto[3][2];
			 $sql4 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta',2,'$nit','$centro','$fon_vacaciones',
			 	   '$naturaleza','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//vacaciones(Recreacion)
			 //echo $sql4."<br>";
		     $query4 = mssql_query($sql4);
			 $sql_gas4 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','$nit','$centro','$fon_vacaciones',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//vacaciones(Recreacion)
			 //echo $sql_gas4."<br>";
			 
			 $query4 = mssql_query($sql_gas4);
			 
			 $cuenta = $matriz[4][1];
			 $naturaleza = $matriz[4][2];
			 $cue_gasto = $matriz_gasto[4][1];
			 $nat_gasto = $matriz_gasto[4][2];
			 $sql6 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','".$nit."_".$nit_empresa."','$centro','$educacion','$naturaleza',
			 '$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Educacion
		     $query6 = mssql_query($sql6);
			 $sql_gas6 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','".$nit."_".$nit_empresa."','$centro','$educacion',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Educacion
			 $query6 = mssql_query($sql_gas6);
			 
			 
			 $cuenta = $matriz[5][1];
			 $naturaleza = $matriz[5][2];
			 $cue_gasto = $matriz_gasto[5][1];
			 $nat_gasto = $matriz_gasto[5][2];
			 $sql7 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','".$nit."_".$nit_empresa."','$centro','$adminBasica','$naturaleza',
			 '$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Administracion
		     $query7 = mssql_query($sql7);
			 $sql_gas7 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','".$nit."_".$nit_empresa."','$centro','$adminBasica',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Administracion
			 $query7 = mssql_query($sql_gas7);
			 
			 
			 $tot_admin = ($legalizacion+$gastos+$educacion);
			 
			 
			 /*
			 //EDUCACION
			 $sql7 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','".$nit."_".$nit_empresa."','$centro','$educacion','$naturaleza','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//educacion
		     $query7 = mssql_query($sql7);
			 $sql_gas7 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','".$nit."_".$nit_empresa."','$centro','$educacion','$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//educacion
			 $query7 = mssql_query($sql_gas7);
			 
			 //ADMINISTRACION
			 $sql50 ="EXECUTE insMovimiento '$sigla','$factura','263535011','2','".$nit."_".$nit_empresa."','$centro','$adminBasica','1','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Admin_basica
		     $query50 = mssql_query($sql50);
			 $sql_gas50 ="EXECUTE insMovimiento '$sigla','$factura','263535011','2','".$nit."_".$nit_empresa."','$centro','$adminBasica','2','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Admin_basica
			 $query50 = mssql_query($sql_gas50);
			 */
			 
			 
		$cal_segSocial = $val_jornadas-$valor_pabs-$aux_inmo-$aportes-$fon_vacaciones-$tot_admin/*-$couta_sindical*/;
		$seguridad_social=$cal_segSocial-$this->nit->por_segSocial_nit($nit,$cal_segSocial,0);
		$ing_base = $val_jornadas-$valor_pabs-$aux_inmo-$aportes-$fon_vacaciones-/*$seguridad_social-*/$tot_admin/*-$couta_sindical*/;
		/*Empezamos a realizar los calculos para los fondos*/
		
		
		/*Termina el calculo para los fondos*/
			 if($estado == 1)
			 {
			   $extra_ordinaria = $ing_base+1;
			   $_SESSION['extra'][$j] = $extra_ordinaria;
			   $cuenta = $matriz[6][1];
		       $naturaleza = $matriz[6][2];
			   $cue_gasto = $matriz_gasto[6][1];
			   $nat_gasto = $matriz_gasto[6][2];
			   $sql9 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','$nit','$centro','$ing_base','$naturaleza','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Ingreso Base
		       $query9 = mssql_query($sql9);
			   $sql_gas9 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','$nit','$centro','$ing_base',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			   $query9 = mssql_query($sql_gas9);
			   $cuenta = $matriz[7][1];
			   $naturaleza = $matriz[7][2];
			   $cue_gasto = $matriz_gasto[7][1];
			   $nat_gasto = $matriz_gasto[7][2]; 
			   $sql10 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','$nit','$centro','0','$naturaleza',
			            '$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//honorarios
		       $query10= mssql_query($sql10);
			   $sql_gas10 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','$nit','$centro','0',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			   $query10 = mssql_query($sql_gas10);
			   $cantidad_cuentas=$cantidad_cuentas-2;
			 }
			 elseif($estado == 3)
			 {
			   $honorarios = $ing_base+1;
			   $_SESSION['honorarios'][$j] = $honorarios-$gastos;
			   $cuenta = $matriz[6][1];
			   $naturaleza = $matriz[6][2];
			   $cue_gasto = $matriz_gasto[6][1];
			   $nat_gasto = $matriz_gasto[6][2]; 
			   $sql9 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','$nit','$centro','0','$naturaleza',
			            '$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//Ordinario
		       $query9 = mssql_query($sql9);
			   $sql_gas9 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','$nit','$centro','0',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			   $query9 = mssql_query($sql_gas9);
			   $cuenta = $matriz[7][1];
			   $naturaleza = $matriz[7][2];
			   $cue_gasto = $matriz_gasto[7][1];
			   $nat_gasto = $matriz_gasto[7][2]; 
			   $sql10 ="EXECUTE insMovimiento '$sigla','$factura','$cuenta','2','$nit','$centro','$honorarios','$naturaleza',
			            '$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//honorarios*/
		       $query10 = mssql_query($sql10);
			   $sql_gas10 ="EXECUTE insMovimiento '$sigla','$factura','$cue_gasto','2','$nit','$centro','$honorarios',
			 	   '$nat_gasto','$factura','3','0','$cantidad_cuentas','$fecha','$mes','$ano','$bas_retencion'";//FABS
			   $query10 = mssql_query($sql_gas10);
			   $cantidad_cuentas=$cantidad_cuentas-2;
			 }
			
				$query = "SELECT COUNT(*) cant FROM mov_contable";
		   	 	$cant_mov = mssql_query($query);
		    	$cantidad = mssql_fetch_array($cant_mov);
				$mov = "EXECUTE movContable ".$cantidad['cant'];
		        $ins_mov = mssql_query($mov);
				$_SESSION['num_factura'] = "";
			 //$this->rep_jornadas->act_causado($reporte,$ing_base);
			 }
		    }
		  }
		  $_SESSION['auxilio1'] = array();
		  $_SESSION['aportes1'] = array();
		  $_SESSION['legalizacion1'] = array();
		  $_SESSION['gastos1'] = array();
		  $_SESSION['educacion1'] = array();
		  $_SESSION['asociado1'] = array();
		  $_SESSION['jornada1'] = array();
		/*}
	  }*/
	  
	  $aud_mov_con_descripcion='CREACION DE CAUSACION DE FACTURA';
	  $que_aud_mov_contable="UPDATE AUDITORIA_MOVIMIENTOS_CONTABLES SET aud_mov_con_usuario='$usuario_actualizador',
	  aud_mov_con_fecha='$fecha_actualizacion',aud_mov_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
	  aud_mov_con_descripcion='$aud_mov_con_descripcion'
	  WHERE mov_compro='$sigla' AND mov_mes_contable='$mes' AND mov_ano_contable='$ano'
	  AND tip_mov_aud_id IS NULL";
	  //echo $que_aud_mov_contable;
	  $eje_aud_mov_contable=mssql_query($que_aud_mov_contable);
	  
	  
   }
   
   public function bus_factura($factura)
   {
	   $sql = "SELECT * FROM factura WHERE fac_consecutivo = '$factura'"; 
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
   
   public function bus_facturas($centro)
   {
	   $sql = "SELECT * FROM factura fac INNER JOIN centros_costo cen ON fac.fac_cen_cos = cen.cen_cos_id WHERE cen.cen_cos_id = $centro OR cen.per_cen_cos = $centro";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
   
   public function bus_facMes($centro,$mes)
   {
	   $sql = "SELECT fac_consecutivo FROM factura WHERE fac_cen_cos = $centro AND fac_mes_servicio=$mes";
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;
   }
   
   public function bus_cenNit($factura)
   {
	   $sql = "SELECT cen.cen_cos_id cen_id,cen.cen_cos_nit cen_nit FROM centros_costo cen 
       		   INNER JOIN factura fac ON fac.fac_cen_cos = cen.cen_cos_id WHERE fac.fac_id = $factura";   
	   $query = mssql_query($sql);
	   if($query)
	     return $query;
	   else
	     return false;   
   }
	
	public function datFactura($factura)
	{
		$query = "SELECT * FROM factura WHERE fac_id = $factura";
		/*echo "datos: ";
		echo $query;
		echo "<br>";*/
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}   
	
	public function ult_factura()
	{
		$sql = "SELECT MAX(fac_id) fac_id FROM factura";
		$query = mssql_query($sql);
		if($query)
		 {
			 $dat_fac = mssql_fetch_array($query);
			 return $dat_fac["fac_id"];
		 }
		else
		  return false; 
	}
	//Inicio Consultar Las Facturas Que No Han Sido Pagadas Por Los Hospitales
	//Todas La Facturas
	public function cons_fac_no_pagadas()
	{
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			if($tip_nit_id!=2)
			{
				//NO TIENE CC PRINCIPAL
				//echo "no tiene principal <br>";
    			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
    			$lasciudades=substr($_SESSION['k_ciudades'],0,-1);
				$query="SELECT f.fac_id,f.fac_consecutivo,f.fac_estado,cc.ciud_ciu_id,fac_cen_cos
						FROM factura f
						INNER JOIN centros_costo cc ON f.fac_cen_cos=cc.cen_cos_id
						WHERE f.fac_estado in (2,3) AND (f.fac_rep_reconfirmado =1 OR f.fac_contrato IS NULL)
						AND (f.fac_cen_cos IN(".$loscentros.") OR cc.ciud_ciu_id IN(".$lasciudades."))
						ORDER BY f.fac_consecutivo";
			}
			else
			{
				$query="SELECT * FROM nits WHERE tip_nit_id=500";
			}
		}
		else
		{
			//PERTENECE AL PRINCIPAL
			//echo "tiene principal <br>";
	    	$query = "SELECT fac_id,fac_consecutivo,fac_estado
FROM factura WHERE fac_estado in (2,3) AND fac_rep_reconfirmado =1 OR fac_contrato IS NULL ORDER BY fac_consecutivo";
		}
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	//Datos Factura Seleccionada
	public function dat_fac_seleccionada($fac_id)
	{
		$query = "SELECT fac.fac_id,cc.cen_cos_id,cc.cen_cos_nombre,fac.fac_fecha,fac.fac_descripcion,fac.fac_val_unitario,
                  fac.fac_val_total,fac.fac_consecutivo,nit.nit_id,nit.nits_nombres FROM dbo.factura fac
                  INNER JOIN dbo.centros_costo cc ON fac.fac_cen_cos = cc.cen_cos_id INNER JOIN nits nit ON fac.fac_nit = nit.nit_id WHERE fac_id =  $fac_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	//Fin Consultar Las Facturas Que No Han Sido Pagadas Por Los Hospitales
	
	//Inicio Cambiar Estados Factura
	public function act_est_factura($estado,$fac_id)
	{
		$query = "UPDATE dbo.factura SET fac_estado = $estado WHERE fac_id = $fac_id";
		$ejecutar = mssql_query($query);
	   if($ejecutar)
		   return true;
		else   
	       return false;
	}
	//Fin Cambiar Estados Factura
	public function fact($est_rec_caja,$ano_contable)
	{
		$fac_pagadas="";
		$i=0;
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			//NO TIENE CC PRINCIPAL
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			$sql="SELECT DISTINCT fac_consecutivo,fac_id,rec_caj_id,rec_caj_consecutivo,fac.fac_val_total,rec_caj_monto,
			n.nits_nombres,rc.rec_caj_fecha
			FROM recibo_caja rc
			INNER JOIN factura fac on fac.fac_id=rc.rec_caj_factura 
			INNER JOIN movimientos_contables mc on fac.fac_id=mov_nume AND mov_compro LIKE('PAG-COM-%')
			INNER JOIN nits n ON fac.fac_nit=n.nit_id
			INNER JOIN centros_costo cc ON fac.fac_cen_cos=cc.cen_cos_id
			WHERE (cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.")) AND rec_caj_estado='$est_rec_caja' AND mov_ano_contable='$ano_contable'
			ORDER BY fac_consecutivo";
		}
		else
		{
			$sql="SELECT DISTINCT fac_consecutivo, fac_id,rec_caj_id,rec_caj_consecutivo,fac.fac_val_total,rec_caj_monto,
			n.nits_nombres,rc.rec_caj_fecha
			FROM recibo_caja rc inner join factura fac on fac.fac_id=rc.rec_caj_factura
			INNER JOIN movimientos_contables mc on fac.fac_id=mov_nume AND mov_compro LIKE('PAG-COM-%')
			INNER JOIN nits n ON fac.fac_nit=n.nit_id
			WHERE rec_caj_estado='$est_rec_caja' AND mov_ano_contable='$ano_contable'
			ORDER BY fac_consecutivo";
				
		}
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		{
		  while($row = mssql_fetch_array($query))
		  {
			 $fac_pagadas[$i][0]=$row['fac_consecutivo'];
			 $fac_pagadas[$i][1]=$row['fac_id'];
			 $fac_pagadas[$i][2]=$row['rec_caj_id'];
			 $fac_pagadas[$i][3]=$row['rec_caj_consecutivo'];
			 $fac_pagadas[$i][4]=$row['fac_val_total'];
             $fac_pagadas[$i][5]=$row['rec_caj_monto'];
             $fac_pagadas[$i][6]=$row['nits_nombres'];
             $fac_pagadas[$i][7]=$row['rec_caj_fecha'];
			 $i++;
		  }
		  return $fac_pagadas;
		}
		else
		  return false;
	}
	
	public function val_factura($recibo)
	{	
		$descuentos = 0;$rec_caja=0;
		$sql2 = "SELECT SUM(des_monto) descu FROM descuentos INNER JOIN recibo_caja on rec_caj_id = des_factura WHERE rec_caj_consecutivo = $recibo AND des_tipo NOT IN (11,1,2,12)";
		$query2 = mssql_query($sql2);
		if($query2)
		{
		 $dat_query = mssql_fetch_array($query2);
		 $descuentos = $descuentos+$dat_query['descu'];
		}
		$sql3 = "SELECT SUM(rec_caj_monto) rec FROM recibo_caja WHERE rec_caj_consecutivo =$recibo";
		$query3 = mssql_query($sql3);
		if($query3)
		{
		  $dat_query = mssql_fetch_array($query3);
		  $rec_caja = $rec_caja+$dat_query['rec'];
		}
		$sql = "SELECT fac_val_total fac FROM 
					recibo_caja INNER JOIN factura ON fac_id=rec_caj_factura WHERE rec_caj_consecutivo = $recibo";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_query = mssql_fetch_array($query);
			$factura = $dat_query['fac'];
			$suma = $descuentos+$rec_caja;
		    $suma = $suma."-".$factura;
			return $suma;
		}
		else
		  return false;
	}
	
	public function busCausacion($factura)
	{
		$sql="SELECT DISTINCT mov_compro FROM movimientos_contables WHERE mov_nume=$factura AND mov_compro LIKE('CAU-NOM-%')";
		$query = mssql_query($sql);	
		if($query)
		{
			$dat_query = mssql_fetch_array($query);
			return $dat_query['mov_compro'];
		}
		else
		  return false;
	}
	
	public function busConsePag($factura)
	{
		$sql = "SELECT rec_caj_id FROM recibo_caja WHERE rec_caj_factura = $factura";
		$query = mssql_query($sql);
		$i=0;
		if($query)
		{
			while($row = mssql_fetch_array($query))
			{
				$sql1 = "SELECT DISTINCT nom_consecutivo FROM nomina WHERE nom_recCaja =".$row['rec_caj_id'];
				$query1 = mssql_query($sql1);
				if($query1)
				{
				  $dat_nomina = mssql_fetch_array($query1);
				  $dat_pagada[$i] =  $dat_nomina['nom_consecutivo'];
				  $i++;
				}
			}
			return $dat_pagada;
		}
		else
		  return false;
	}
	
	public function buscarPagadas($valor){
		$query = "SELECT mov_compro,mov_cuent,mov_nit_tercero,mov_cent_costo,mov_valor,mov_tipo FROM movimientos_contables WHERE mov_compro= 'PAG-COM-$valor' ORDER BY mov_compro,mov_nit_tercero,mov_cent_costo";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function facReporte($reporte)
	{
		$sql="SELECT fac_consecutivo FROM factura fac INNER JOIN reporte_jornadas rj ON fac.fac_id=rj.rep_jor_num_factura WHERE rj.rep_jor_consecutivo = $reporte";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_fac = mssql_fetch_array($query);
			return $dat_fac['fac_consecutivo'];
		}
		else
		  return false;
	}
	
	public function mesFactura($recibo)
	{
		$sql = "SELECT f.fac_fecha FROM recibo_caja rc INNER JOIN factura f on f.fac_id = rc.rec_caj_factura WHERE rc.rec_caj_consecutivo = $recibo";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_recibo = mssql_fetch_array($query);
			$fecha = split("-",$dat_recibo['fac_fecha'],3);
			return $fecha[1];
		}
	}
	
	public function factura_adel($factura)
	{
		$sql = "SELECT f.fac_fecha FROM factura f WHERE fac_id = $factura";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_recibo = mssql_fetch_array($query);
			$fecha = split("-",$dat_recibo['fac_fecha'],3);
			return $fecha[1];
		}
	}
	
	public function con_emp_por_nomina($sigla){
		$query="SELECT DISTINCT mov_nit_tercero
				FROM movimientos_contables
				WHERE mov_compro='$sigla'
				AND mov_nit_tercero NOT LIKE('%[_]%')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_cue_pag_nomina($sigla,$nit,$cuenta){
		$query="SELECT mov_valor
				FROM movimientos_contables
				WHERE mov_compro='$sigla' AND mov_nit_tercero='$nit' AND mov_cuent='$cuenta'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function mod_fec_radicado($fac,$fecha)
	{
		$sql = "UPDATE factura SET fac_fec_radicado='$fecha' WHERE fac_consecutivo='$fac'";
		$query = mssql_query($sql);
		if($query)
		   return true;
		else
		   return false;   
	}
	
	public function fac_sin_reporte()
	{
		
		$principal="1169,";
		$lacadena=$_SESSION['k_cen_costo'];
		$comparacion=strpos($lacadena,$principal);
		if($comparacion===false)
		{
			//NO TIENE CC PRINCIPAL
			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
			//$sql = "SELECT * FROM factura WHERE fac_id NOT IN (SELECT DISTINCT rep_jor_num_factura FROM reporte_jornadas)";
			$sql="SELECT * FROM factura WHERE fac_id NOT IN(SELECT DISTINCT rep_jor_num_factura FROM reporte_jornadas WHERE rep_jor_num_factura IS NOT NULL) AND fac_estado NOT IN(5) AND (fac_cen_cos IN(".$loscentros.") OR fac_cen_cos IN(SELECT cen_cos_id FROM centros_costo WHERE per_cen_cos IN (".$loscentros." ))) ORDER BY fac_consecutivo";
		}
		else
		{
			$sql="SELECT *
				  FROM factura
				  WHERE fac_id NOT IN(
				  SELECT DISTINCT rep_jor_num_factura
				  FROM reporte_jornadas
				  WHERE rep_jor_num_factura IS NOT NULL) AND fac_estado NOT IN(5)
				  ORDER BY fac_consecutivo";
		}
		$query=mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}


	public function ConsultarValorNotasPorFactura($fac_id)
	{
		$query="SELECT(
		(SELECT ISNULL(SUM(not_monto),0) not_debito
		FROM notas
		WHERE not_factura='$fac_id'
		AND not_sigla LIKE('NOT-DEB_%'))
		-
		(SELECT ISNULL(SUM(not_monto),0) not_credito
		FROM notas
		WHERE not_factura='$fac_id'
		AND not_sigla LIKE('NOT-CRE_%')
		)
		) valor_notas";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['valor_notas'];
		}
		else
			return false;
	}
	
	//TRAER ID DEL CONTRATO POR CENTRO DE COSTO//
	public function con_id_con_por_cen_cos($tip_con_id,$est_con_id,$cen_cos_id)
	{
		$query="SELECT con_id
			FROM contrato c
			INNER JOIN nits n ON c.nit_id=n.nit_id
			INNER JOIN centros_costo cc ON n.nit_id=cc.cen_cos_nit
			WHERE tip_con_id=$tip_con_id AND est_con_id=$est_con_id AND cc.cen_cos_id=$cen_cos_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['con_id'];
		}
		else
			return false;
	}
	
	public function facConcepto($centro,$fecha,$descrip,$valor,$consecu,$nit,$fac_ano_servicio,$fac_mes_servicio)
	{
		$fec_creacion=date('d-m-Y');
		$sql="INSERT INTO factura(fac_cen_cos,fac_fecha,fac_descripcion,fac_val_unitario,fac_val_total,fac_consecutivo,
		fac_nit,fac_estado,fac_ano_servicio,fac_mes_servicio,fac_fec_creacion)
		VALUES ('$centro','$fecha','$descrip',$valor,$valor,'$consecu','$nit','2','$fac_ano_servicio','$fac_mes_servicio','$fec_creacion')";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}
	
	public function legFactura($factura,$recibo_caja)
	{
		$sql= "SELECT con.con_vigencia,cpc.con_can_descu,SUM(cpc.con_por_con_porcentaje) monto,fac.fac_id
			   FROM contratos_por_conceptos cpc right join dbo.contrato con on con.con_id=cpc.contrato_id inner join factura fac on fac.fac_contrato=con.con_id
			   WHERE fac.fac_id = $factura
			   GROUP BY con.con_vigencia,cpc.con_can_descu,fac.fac_id";
			   //echo $sql."<BR>";
		$query = mssql_query($sql);
		if($query)
		{
			$resultado=mssql_fetch_array($query);
			if($resultado['con_vigencia']!=$resultado['con_can_descu'])
			{
				if($resultado['con_vigencia']==0||trim($resultado['con_vigencia'])=="")
					$resultado['con_vigencia']=1;
				//echo "datos: ".$resultado['monto']/$resultado['con_vigencia'];
				
				$que_recibo="SELECT * FROM recibo_caja WHERE rec_caj_id='$recibo_caja'";
				$eje_recibo=mssql_query($que_recibo);
				$res_recibo=mssql_fetch_array($eje_recibo);
				
				$que_des_legalizacion="SELECT ISNULL(SUM(des_monto),0) monto FROM descuentos WHERE des_factura = '$recibo_caja' AND
				(des_tipo not in(1,2) OR des_distribucion IS NOT NULL)";
				$eje_des_legalizacion=mssql_query($que_des_legalizacion);
				$res_des_legalizacion=mssql_fetch_array($eje_des_legalizacion);
				
				if($res_recibo['rec_caj_anticipo']==1&&$res_des_legalizacion['monto']==0)
				{
					$des_monto=round($resultado['monto']/$resultado['con_vigencia'],0);
					$des_tipo=11;
					$gua_leg_factura="INSERT INTO descuentos(des_factura,des_monto,des_tipo) VALUES('$recibo_caja','$des_monto','$des_tipo')";
					$eje_leg_factura=mssql_query($gua_leg_factura);
				}
				$que_des_legalizacion_1="SELECT ISNULL(SUM(des_monto),0) monto FROM descuentos WHERE des_factura = '$recibo_caja' AND
				(des_tipo not in(1,2) OR des_distribucion IS NOT NULL)";
				$eje_des_legalizacion_1=mssql_query($que_des_legalizacion_1);
				$res_des_legalizacion_1=mssql_fetch_array($eje_des_legalizacion_1);
				
				return $res_des_legalizacion_1['monto'];
				
			}
			else
				return 0;
		}
		
	}
	
	public function actFactura($factura)
	{
		$sql = "UPDATE factura SET fac_estado=5 WHERE fac_consecutivo='$factura'";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}
	public function anularFactura($factura,$num_fac)
	{
		
			
			$fecha = date('d-m-Y');
			$mes = date('m');
			if($num_fac<=521)
				$num_fac = $factura;

			$bas_retencion=0;

			$compro_fac = "FAC-".$num_fac;
			$compro_cau = "CAU-NOM-".$num_fac;
			$sql="SELECT * FROM mes_por_ano_contable WHERE mes_id=(
			SELECT TOP 1 mc.mov_mes_contable FROM movimientos_contables mc WHERE mc.mov_compro='$compro_fac'
			AND mov_tipo=1) AND ano_con_id=(
			SELECT TOP 1 mc.mov_ano_contable FROM movimientos_contables mc WHERE mc.mov_compro='$compro_fac'
			AND mov_tipo=1
			)";
			//echo $sql;
			
			$query = mssql_query($sql);
			
			if($query)
			{
				$dat_mes=mssql_fetch_array($query);
				
				//echo $dat_mes['mes_estado'];
                if($dat_mes['est_mes_por_ano_con_id']==1)//SI EL MES ESTÁ CERRADO SE REVERSAN LOS MOVIMIENTOS
                {
                	//echo "entra";
                	$mes_actual=date('m');
                	$anio_actual=date('Y');
					
					$que_ver_mes="SELECT DISTINCT *
					FROM mes_por_ano_contable
					WHERE mes_id='$mes_actual' AND ano_con_id='$anio_actual'";
					//echo $que_ver_mes;
					$eje_ver_mes=mssql_query($que_ver_mes);
					$res_ver_mes=mssql_fetch_array($eje_ver_mes);
					
					if($res_ver_mes['est_mes_por_ano_con_id']==2)//MES ACTUAL ABIERTO
					{
						//echo "entra aqui debe hacer el insert en este mes";

						//CAMBIAR ESTADO A 5(ANULADO A FACTURA)
						$act_factura = $this->actFactura($factura);
	                	
	    				$mov_factura = $this->mov_contable->consultar_movimiento_contable($compro_fac,$dat_mes['mes_id'],$dat_mes['ano_con_id']);
	    				$can_cue = mssql_num_rows($mov_factura);
	    				while($row = mssql_fetch_array($mov_factura))
	    				{
	    					if($row['mov_tipo']==1)
	    					  $anu_fac ="EXECUTE insMovimiento 'ANU-".$row['mov_compro']."','".$row['mov_nume']."','".$row['mov_cuent']."','".$row['mov_concepto']."', '".$row['mov_nit_tercero']."','".$row['mov_cent_costo']."','".$row['mov_valor']."','2','".$row['mov_nume']."','3','0','$can_cue','$fecha','$mes_actual',".$anio_actual.",'$bas_retencion'";
	    					else
	    					  $anu_fac ="EXECUTE insMovimiento 'ANU-".$row['mov_compro']."','".$row['mov_nume']."','41751501','".$row['mov_concepto']."', '".$row['mov_nit_tercero']."','".$row['mov_cent_costo']."','".$row['mov_valor']."','1','".$row['mov_nume']."','3','0','$can_cue','$fecha','$mes_actual',".$anio_actual.",'$bas_retencion'";
	    				   $query = mssql_query($anu_fac);
	    				}
	    					
	    				$mov_causacion = $this->mov_contable->consultar_movimiento_contable($compro_cau,$dat_mes['mes_id'],$dat_mes['ano_con_id']);
	    				$can_cue = mssql_num_rows($mov_causacion);
	    				while($row = mssql_fetch_array($mov_causacion))
	    				{
	    					if($row['mov_tipo']==1)
	    					  $anu_fac ="EXECUTE insMovimiento 'ANU-".$row['mov_compro']."','".$row['mov_nume']."','".$row['mov_cuent']."','".$row['mov_concepto']."', '".$row['mov_nit_tercero']."','".$row['mov_cent_costo']."','".$row['mov_valor']."','2','".$row['mov_nume']."','3','0','$can_cue','$fecha','$mes_actual',".$anio_actual.",'$bas_retencion'";
	    					else
	    					  $anu_fac ="EXECUTE insMovimiento 'ANU-".$row['mov_compro']."','".$row['mov_nume']."','".$row['mov_cuent']."','".$row['mov_concepto']."','".$row['mov_nit_tercero']."','".$row['mov_cent_costo']."','".$row['mov_valor']."','1','".$row['mov_nume']."','3','0','$can_cue','$fecha','$mes_actual',".$anio_actual.",'$bas_retencion'";
	    				   $query = mssql_query($anu_fac);
	    				}
	                    
	                    $query = "SELECT COUNT(*) cant FROM mov_contable";
	                    $cant_mov = mssql_query($query);
	                    $cantidad = mssql_fetch_array($cant_mov);
	                    $mov = "EXECUTE movContable ".$cantidad['cant'];
	                    $ins_mov = mssql_query($mov);
	                    
	                    return 1;
						
	                  }
					  else//EL MES ACTUAL ESTÁ CERRADO NO SE HACE NADA Y SE INFORMA QUE DEBE ABRIRLO
					  {
					  	//echo "entra por aqui";
						return 0;
					  }
					  		
                }
                elseif($dat_mes['est_mes_por_ano_con_id']==2)//SI EL MES ESTA ABIERTO SE BORRA LA FACTURA Y LA CAUSACION
                {
                	//echo "entra aqui a reversar";
                	
                	//CAMBIAR ESTADO A 5(ANULADO A FACTURA)
					$act_factura = $this->actFactura($factura);
					
					
					
                	//echo "entra a eliminar los movimientos";
                    $query1="DELETE FROM movimientos_contables WHERE mov_compro='$compro_fac' AND mov_mes_contable='$dat_mes[mes_id]' AND mov_ano_contable='$dat_mes[ano_con_id]'";
                    $ejecutar1=mssql_query($query1);
                    if($ejecutar1)
                    {
                        $query2="DELETE FROM movimientos_contables WHERE mov_compro='$compro_cau' AND mov_mes_contable='$dat_mes[mes_id]' AND mov_ano_contable='$dat_mes[ano_con_id]'";
                        $ejecutar2=mssql_query($query2);
                        if($ejecutar2)
                        {
                            $query3="DELETE FROM transacciones WHERE trans_sigla='$compro_fac' AND tran_mes_contable='$dat_mes[mes_id]' AND trans_ano_contable='$dat_mes[ano_con_id]'";
                            $ejecutar3=mssql_query($query3);
                            $query4="DELETE FROM transacciones WHERE trans_sigla='$compro_cau' AND tran_mes_contable='$dat_mes[mes_id]' AND trans_ano_contable='$dat_mes[ano_con_id]'";
                            $ejecutar4=mssql_query($query4);
                        }
                 	}
					return 1;            
				}
			}
	}
	public function RepLisFacturas($mes,$tipo,$anio)
	{
		if($tipo==1)
		{
			//echo "entra por aqui";
			if($mes<10)
				$mes="0".$mes;
			$query="SELECT f.fac_id,f.fac_fecha,f.fac_consecutivo,cc.cen_cos_nombre,f.fac_val_unitario,fac_fecha,
			fac_ano_servicio,n.nits_num_documento,f.fac_rep_reconfirmado,f.fac_contrato,f.fac_mes_servicio
			FROM factura f
			INNER JOIN centros_costo cc ON f.fac_cen_cos=cc.cen_cos_id
			INNER JOIN nits n ON cc.cen_cos_nit=n.nit_id
			WHERE fac_fecha LIKE('%-$mes-$anio') AND fac_estado!=5 AND f.fac_mes_servicio IS NOT NULL AND
			f.fac_rep_reconfirmado IS NOT NULL AND f.fac_contrato IS NOT NULL
			ORDER BY f.fac_id ASC";
			//echo $query;
		}
		elseif($tipo==2)
		{
			$query="SELECT f.fac_id,f.fac_fecha,f.fac_consecutivo,cc.cen_cos_nombre,f.fac_val_unitario,n.nits_num_documento,f.fac_rep_reconfirmado,f.fac_contrato,f.fac_mes_servicio
					FROM factura f INNER JOIN centros_costo cc ON f.fac_cen_cos=cc.cen_cos_id INNER JOIN nits n ON cc.cen_cos_nit=n.nit_id
                    WHERE fac_estado!=5 AND f.fac_mes_servicio IS NOT NULL AND f.fac_rep_reconfirmado IS NOT NULL AND f.fac_contrato IS NOT NULL
					ORDER BY f.fac_id ASC";
		}
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function buscar_consecutivo($centro)
	{
		$sql="SELECT cen_cos_resolucion FROM centros_costo WHERE cen_cos_id=$centro";
		$query = mssql_query($sql);
		if($query)
		 {
			 $dat_sql = mssql_fetch_array($query);
			 $sql="SELECT res_prefijo,(res_consecutivo+1) conse FROM dbo.resoluciones WHERE res_id =".$dat_sql['cen_cos_resolucion'];
			 //echo $sql;
			 $query=mssql_query($sql);
			 if($query)
			  {
				  $dat_query=mssql_fetch_array($query);
				  
				  if(is_numeric($dat_query['res_prefijo']))
				  	return $dat_query['res_prefijo']."".$dat_query['conse'];
				  else
				  	return $dat_query['res_prefijo']." ".$dat_query['conse'];
			  }
		 }
		else
		  return false;
	}
	
	public function actConse_resolucion($centro)
	{
		$sql="UPDATE resoluciones SET res_consecutivo=res_consecutivo+1 WHERE res_id = (SELECT res_id FROM centros_costo INNER JOIN resoluciones ON cen_cos_resolucion=res_id WHERE cen_cos_id=$centro)";
		$query=mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}

	/********************Trae los recibos de caja pendientes de pago para hacer el recaudo************/ 

	public function fac_cen_recaudo()
	{
		$sql="SELECT DISTINCT fac_id,fac_consecutivo FROM factura LEFT JOIN recibo_caja ON rec_caj_factura=fac_id WHERE rec_caj_estado=0 OR fac_estado=3";
		$query=mssql_query($sql);
		if($query)
			return $query;
		else
			return false;
	}
	
	public function ConsultarDatosFacturaPorReciboCaja($rec_caj_id)
	{
		$sql="SELECT *
		FROM factura f
		INNER JOIN recibo_caja rc ON f.fac_id=rc.rec_caj_factura
		WHERE rc.rec_caj_id='$rec_caj_id'";
		$query=mssql_query($sql);
		if($query)
			return $query;
		else
			return false;
	}

	public function cant_facDigita($nit,$docu)
	{
		$sql = "SELECT COUNT(*) fact FROM transacciones WHERE trans_nit=$nit AND trans_fac_num=$docu AND trans_sigla LIKE('TRA%')";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_query = mssql_fetch_array($query);
			if($dat_query['fact']>0)
				return true;
			else
				return false;
		}
	}

	public function ano_factura($factura)
	{
		$sql="SELECT fac_ano_servicio FROM factura WHERE fac_id=$factura";
		$query=mssql_query($sql);
		if($query)
		{
			$dat_factura = mssql_fetch_array($query);
			return $dat_factura['fac_ano_servicio'];
		}
		else
			return false;
	}
	
	public function ConsultarTodosDatosFacturaPorId($fac_id)
	{
		$sql="SELECT * FROM factura WHERE fac_id=$fac_id";
		//echo $sql;
		$query=mssql_query($sql);
		if($query)
		{
			$dat_factura = mssql_fetch_array($query);
			return $dat_factura;
		}
		else
			return false;
	}
	
	//INICIO REPORTE DESCUENTOS DE LEGALIZACIÓN POR CONTRATO Y/O FACTURA
        public function ConTodFacSinPagar($fac_estado,$fac_rep_reconfirmado)
        {
            $sql="SELECT * FROM factura WHERE fac_estado=$fac_estado AND fac_rep_reconfirmado=$fac_rep_reconfirmado";
            //echo $sql;
            $query=mssql_query($sql);
            if($query)
            { return $query; }
            else
            { return false; }
        }
        
        public function ConContratoPorFactura($factura)
        {
            $sql="SELECT f.fac_contrato
                  FROM factura f
                  WHERE fac_consecutivo='$factura'";
            //echo $sql;//INNER JOIN recibo_caja rc ON f.fac_id=rc.rec_caj_factura
            $query=mssql_query($sql);
            if($query)
            { $res_con_id=mssql_fetch_array($query); $con_id=$res_con_id['fac_contrato']; return $con_id; }
            else
            { return false; }
        }
        
        public function ConLegDescPorContrato($fac_contrato,$des_tipo)
        {
            $sql="SELECT SUM(des_monto) valor_descontado,des_factura,f.fac_consecutivo,td.tip_des_nombre,c.con_id,c.con_vigencia,c.con_fec_inicio,c.con_fec_fin,c.con_hos_consecutivo,cc.cen_cos_nombre,f.fac_mes_servicio,f.fac_ano_servicio,f.fac_val_total
                  FROM descuentos d
                  INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id
                  INNER JOIN factura f ON f.fac_id=rc.rec_caj_factura
                  INNER JOIN contrato c ON f.fac_contrato=c.con_id
                  INNER JOIN tipo_descuentos td ON d.des_tipo=td.tip_des_id
                  INNER JOIN centros_costo cc ON  f.fac_cen_cos=cc.cen_cos_id
                  WHERE f.fac_contrato=$fac_contrato AND des_monto>0 AND d.des_tipo IN($des_tipo)  AND des_descripcion IS NULL AND des_fecha IS NULL
                  GROUP BY des_factura,f.fac_consecutivo,td.tip_des_nombre,c.con_id,c.con_vigencia,c.con_fec_inicio,c.con_fec_fin,c.con_hos_consecutivo,cc.cen_cos_nombre,f.fac_mes_servicio,f.fac_ano_servicio,f.fac_val_total";
			//echo "los datos son: ".$sql;
            $query=mssql_query($sql);
            if($query)
            { return $query; }
            else
            { return false; }
        }
        
        public function ValDesLegPorContrato($contrato_id)
        {
            $sql="SELECT SUM(con_por_con_porcentaje) descontar FROM contratos_por_conceptos WHERE contrato_id=$contrato_id";
            $query=mssql_query($sql);
            if($query)
            { $con_val_descontar=mssql_fetch_array($query); $res_val_descontado=$con_val_descontar['descontar']; return $res_val_descontado; }
            else
            { return false; }
        }
        //FIN REPORTE DESCUENTOS DE LEGALIZACIÓN POR CONTRATO Y/O FACTURA
        
        //INICIO ELIMINAR FACTURA, DEVOLVER CONSECUTIVO DE RESOLUCION, ELIMINAR REPORTE DE JORNADAS
        public function ConFacSinPagar($est_factura)
        {
            $sql="SELECT *
                FROM factura
                WHERE fac_estado=2 AND fac_id not in(SELECT rec_caj_factura FROM recibo_caja)";
            $query=mssql_query($sql);
            if($query)
            { return true; }
            else
            { return false; }
        }
        //FIN ELIMINAR FACTURA, DEVOLVER CONSECUTIVO DE RESOLUCION, ELIMINAR REPORTE DE JORNADAS
	
	public function val_pag_factura($factura,$cuenta)
    {
		$sql = "SELECT SUM(mov_valor) pago FROM movimientos_contables WHERE mov_compro IN (SELECT 'REC-CAJ_'+CAST(rec_caj_consecutivo AS VARCHAR) from recibo_caja where rec_caj_factura=$factura) and mov_cuent=$cuenta";
	   	$query = mssql_query($sql);
	   	if($query)
	   	{
	   		$dat_query = mssql_fetch_array($query);
	   		return $dat_query['pago'];
	   	}
	   	else
	    	return false;
	}
        
	 public function ConFacPorSigla($sigla)
     {
     	$sql="SELECT DISTINCT f.fac_consecutivo,f.fac_cen_cos,f.fac_id
			  FROM factura f
			  INNER JOIN movimientos_contables mc ON mc.mov_nume=f.fac_id
			  WHERE mc.mov_compro='$sigla'";
        $query=mssql_query($sql);
        if($query)
        {
        	return $query;
        }
        else
        { return false; }
	}

	public function proServicios($doc,$num_doc,$mes,$ano)
	  {
	  	
		
		$cue_administracion_1='25052001';
		$cue_administracion_2='25051004';
		$cue_administracion_3='25051006';
		$cue_administracion_4='25051002';
		$cue_administracion_5='23352501';
		$cue_pro_por_ven_servicios='13990502';
		$cue_pro_servicios='51059910';
		
		
	  	$sql="execute proServicios '$doc',$num_doc,$mes,$ano,'$cue_administracion','$cue_pro_por_ven_servicios','$cue_pro_servicios'";
	  	//echo $sql;
	  	$query=mssql_query($sql);
	  	if($query)
	  		return true;
	  	else
	  		return false;
	  }

	public function FacMes($mes_contable,$ano_contable)
	{
		$sql="SELECT DISTINCT f.fac_id,f.fac_consecutivo,f.fac_val_total,mv.mov_compro FROM movimientos_contables mv
		INNER JOIN factura f ON mv.mov_compro='FAC-'+CAST(f.fac_id AS VARCHAR)
		WHERE mov_mes_contable=$mes_contable AND mov_ano_contable=$ano_contable AND mov_compro LIKE('FAC-%')";
		$query=mssql_query($sql);
		if($query)
			return $query;
		else
			return false;
	}
	
	
	public function ConMesAnoFacturaPorConsecutivoIdSiglaNitCentro($fac_consecutivo,$fac_id,$nit_id,$cen_costo)
     {
     	$sql="SELECT DISTINCT f.fac_id,f.fac_consecutivo,f.fac_val_total,mv.mov_compro,mv.mov_mes_contable,mv.mov_ano_contable
	 	FROM movimientos_contables mv INNER JOIN factura f ON mv.mov_compro='FAC-'+CAST(f.fac_id AS VARCHAR)
		WHERE f.fac_consecutivo='$fac_consecutivo' AND fac_id='$fac_id' AND mov_compro LIKE('FAC-$fac_id')
		AND mv.mov_nit_tercero='$nit_id' AND mv.mov_cent_costo='$cen_costo'";
	 	//echo $sql;
        $query=mssql_query($sql);
        if($query)
        {
        	return $query;
        }
        else
        { return false; }
	}
	
	
	public function ActualizarFechaFactura($nue_fec_factura,$fac_id,$fac_sigla,$nit_id,$cen_costo,$cau_sigla,$ant_mes_contable,$ant_ano_contable,$nue_mes_contable,$nue_ano_contable,$usuario_id)
	{
		$sql="UPDATE factura SET fac_fecha='$nue_fec_factura' WHERE fac_id=$fac_id";
	 	//echo $sql."<br>"; 
		$query=mssql_query($sql);
		if($query)
	 	{
	 		$sql2="UPDATE movimientos_contables SET mov_fec_elabo='$nue_fec_factura',mov_mes_contable=$nue_mes_contable,mov_ano_contable=$nue_ano_contable
			WHERE mov_compro='$fac_sigla' AND mov_nit_tercero='$nit_id' AND mov_cent_costo='$cen_costo' AND mov_mes_contable=$ant_mes_contable
			AND mov_ano_contable=$ant_ano_contable";
	 		//echo $sql2."<br>";
	 		$query2=mssql_query($sql2);
	 		
	 		if($query2)
			{
	 			$sql3="UPDATE movimientos_contables SET mov_fec_elabo='$nue_fec_factura',mov_mes_contable=$nue_mes_contable,mov_ano_contable=$nue_ano_contable
				WHERE mov_compro='$cau_sigla' AND mov_nume='$fac_id' AND mov_cent_costo='$cen_costo' AND mov_mes_contable=$ant_mes_contable
				AND mov_ano_contable=$ant_ano_contable";
	 			//echo $sql3."<br>"; 
	 			$query3=mssql_query($sql3);
	 			if($query3)
				{
					$sql4="UPDATE transacciones SET trans_fec_doc='$nue_fec_factura',tran_mes_contable=$nue_mes_contable,trans_ano_contable=$nue_ano_contable,trans_user='$usuario_id'
					WHERE trans_sigla='$fac_sigla' AND trans_nit='$nit_id' AND trans_centro='$cen_costo' AND tran_mes_contable=$ant_mes_contable
					AND trans_ano_contable=$ant_ano_contable";
	 				//echo $sql4."<br>"; 
	 				$query4=mssql_query($sql4);
	 				
	 				$sql5="UPDATE transacciones SET trans_fec_doc='$nue_fec_factura',tran_mes_contable=$nue_mes_contable,trans_ano_contable=$nue_ano_contable,trans_user='$usuario_id'
					WHERE trans_sigla='$cau_sigla' AND trans_nit='$nit_id' AND trans_centro='$cen_costo' AND tran_mes_contable=$ant_mes_contable
					AND trans_ano_contable=$ant_ano_contable";
	 				//echo $sql4."<br>"; 
	 				$query5=mssql_query($sql5);
	 				
	 				return $query3;
				}
	 			else
	 				return false;
	 		}
	 		else
	 			return false; 
	 	}
		else
			return false;
	}

	public function ConDatFacPorId($fac_id)
	{
		$query="SELECT * FROM factura WHERE fac_id='$fac_id'";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
}
?>