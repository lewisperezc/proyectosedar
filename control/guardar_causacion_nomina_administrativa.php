<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/transacciones.class.php');
@include_once('../clases/factura.class.php');
@include_once('../clases/nits.class.php');
@include_once('../clases/comprobante.class.php');
@include_once('../clases/nomina.class.php');
@include_once('clases/transacciones.class.php');
@include_once('clases/factura.class.php');
@include_once('clases/nits.class.php');
@include_once('clases/comprobante.class.php');
@include_once('clases/nomina.class.php');
@include_once('clases/credito.class.php');
@include_once('../clases/credito.class.php');
@include_once('clases/varios.class.php');
@include_once('../clases/varios.class.php');
@include_once('clases/compensacion_nomina.class.php');
@include_once('../clases/compensacion_nomina.class.php');
@include_once('clases/moviminetos_contables.class.php');
@include_once('../clases/moviminetos_contables.class.php');

?>
<script>
function ventanaemergente(url)
{
	day = new Date();
	id = day.getTime();
	eval("page" + (id) + " = window.open('"+url+"', '" + (id) + "','toolbar=1,scrollbars=1,location=0,statusbar=1,menubar=1,resizable=1,width=800,height=500,left = 200,top = 200');");
}
</script>
<?php

$ins_transaccion=new transacciones();
$ins_factura=new factura();
$ins_nits=new nits();
$comprobante= new comprobante();
$ins_nomina=new nomina();
$con_dat_nom_administrativa=$ins_nits->con_dat_nom_administrativa();
$res_dat_nom_administrativa=mssql_fetch_array($con_dat_nom_administrativa);
$ins_credito=new credito();
$ins_varios=new varios();
$ins_com_nomina=new compensacion_nomina();
$ins_mov_contable=new movimientos_contables();

$res_val_uvt=$ins_varios->ConsultarDatosVariablesPorId(2);


$bas_retencion=0;

//INICIO CAPTURA LOS DATOS QUE VIENEN DEL  FORMULARIO ANTERIOR

$fecha=date('d-m-Y');
//SEDAR
$nit_id=380;//SEDAR
//PRINCIPAL
$cen_cos_id=1169;
$num_quincena=$_POST['num_quincena'];
$mes_sele=$_POST['mes_sele'];
$mes=split('-',$mes_sele,2);

$consecutivo = $comprobante->cons_comprobante($ano,$mes[1],30);
$sig = $comprobante->sig_comprobante(30);
$comprobante->act_comprobante($ano,$mes[1],30);
$sigla=$sig.$consecutivo;

$con_provision = $comprobante->cons_comprobante($ano,$mes[1],33);
$sig = $comprobante->sig_comprobante(33);
$comprobante->act_comprobante($ano,$mes[1],33);
$sigla_provision=$sig.$con_provision;



$fecha_liquidacion=$_POST['fec_liquidacion'];
$mes_pago=$_POST['mes_pago'];


$per_pag_nomina=$_POST['per_pag_nomina'];


if($per_pag_nomina==1)//EL PAGO ES QUINCENAL
{
	////////////////////////////////////////////PRIMERA QUINCENA////////////////////////////////////////////
	if($num_quincena==1)
	{
		$j=0;
		while($j<$_POST['can_reg_primera'])
		{
			//echo "primera quincena ".$j."<br>";
			$pri_emp_id[]=$_POST['pri_emp_id'.$j];
			$pri_emp_salario[]=$_POST['pri_emp_salario'.$j];
			$pri_emp_documento[]=$_POST['pri_emp_documento'.$j];
			$pri_emp_nombres[]=$_POST['pri_emp_nombres'.$j];
			$pri_emp_quincena[]=$_POST['pri_emp_quincena'.$j];
			$pri_emp_aux_transporte[]=$_POST['pri_emp_aux_transporte'.$j];
			$pri_emp_salud[]=$_POST['pri_emp_salud'.$j];
			$pri_emp_pension[]=$_POST['pri_emp_pension'.$j];
			$pri_emp_tot_pagar[]=$_POST['pri_emp_tot_pagar'.$j];
			$pri_emp_fon_sol_pensional[]=$_POST['pri_emp_fon_sol_pensional'.$j];
			$pri_emp_tot_pagar_1[]=$_POST['pri_emp_tot_pagar'.$j];
			
			$pri_emp_dia_trabajados[]=$_POST['pri_emp_dia_trabajados'.$j];
			
		    //$pri_emp_pagar=$_POST['pri_emp_pagar'];
			//FIN CAPTURA LOS DATOS QUE VIENEN DEL  FORMULARIO ANTERIOR
		$j++;
		}
		$pri_minimo=$res_dat_nom_administrativa['dat_nom_sal_minimo'];
		//INICIO SUMO EL VALOR TOTAL DE LA NOMINA PARA GUARDARLO EN LA TRANSACCIÓN
		$i=0;
		$sum_pri_nomina=0;
		while($i<sizeof($pri_emp_tot_pagar_1))
		{
			$sum_pri_nomina=$sum_pri_nomina+$pri_emp_tot_pagar_1[$i];
			$i++;
		}
		//FIN SUMO EL VALOR TOTAL DE LA NOMINA PARA GUARDARLO EN LA TRANSACCIÓN
		
		$query="INSERT INTO dbo.transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,trans_fec_vencimiento,trans_fac_num,trans_user,
		trans_fec_grabado,tran_mes_contable,trans_ano_contable)
		VALUES('$sigla','$fecha','$nit_id','$cen_cos_id','$sum_pri_nomina','$consecutivo','$fecha_liquidacion','$num_quincena','$_SESSION[k_nit_id]','$fecha_liquidacion','$mes[1]','$ano')";
		$ejecutar=mssql_query($query);
		/*$gua_transaccion = $ins_transaccion->guaTransaccion(strtoupper($sigla),$fecha,$nit_id,$cen_cos_id,$sum_pri_nomina,$consecutivo,$fecha_liquidacion,$num_quincena,$_SESSION['k_nit_id'],$fecha,$mes[1]);*/
		
		//EL ESTADO 1 ES CAUSADO Y EL 2 ES PAGADO
		$act_est_nom_administrativa=$ins_transaccion->act_est_nom_adm_causada(1);
		
		$a=0;
		$cant_cuentas=0;
		while($a<sizeof($pri_emp_id))
		{
			//echo $pri_emp_tot_pagar[$a]."___".$a."<br>";
			$con_fondos=$ins_nits->fon_nits($pri_emp_id[$a]);
			$res_fondos=mssql_fetch_array($con_fondos);
			//TRAE LA EPS DEL EMPLEADO
			$eps=$res_fondos['nits_eps'];
			//TRAE LA PENSION DEL EMPLEADO
			$pension=$res_fondos['nit_pensiones'];
			
			//NOMINA DE PLANTA
			$query="EXECUTE insMovimiento '$sigla','$pri_emp_dia_trabajados[$a]','51050506','$num_quincena','$pri_emp_id[$a]','$cen_cos_id','$pri_emp_quincena[$a]','1','$mes_pago','$pri_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			//echo $query;
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			
			//AUXILIIO DE TRANSPORTE
			$con_dat_aux_tra_empleado=$ins_nits->consultar($pri_emp_id[$a]);
			$res_dat_aux_tra_empleado=mssql_fetch_array($con_dat_aux_tra_empleado);
			if($pri_emp_salario[$a]<=($pri_minimo*2)&&$res_dat_aux_tra_empleado['nit_pag_aux_transporte']==1)
		    {
				$query="EXECUTE insMovimiento '$sigla','$pri_emp_dia_trabajados[$a]','51050527','$num_quincena','$pri_emp_id[$a]','$cen_cos_id','$pri_emp_aux_transporte[$a]','1','$mes_pago','$pri_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
				$ejecutar=mssql_query($query);
				$cant_cuentas++;
				$est_pri_aux_transporte=1;
			}
			else
			{
				$est_pri_aux_transporte=2;
			}
			
			//FONDO DE SOLIDARIDAD PENSIONAL
			if($pri_emp_fon_sol_pensional[$a]>0)
			{
			$query="EXECUTE insMovimiento '$sigla','$pri_emp_dia_trabajados[$a]','23700501','$num_quincena','$pension','$cen_cos_id','$pri_emp_fon_sol_pensional[$a]','2','$mes_pago','$pri_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			}
			//SALUD
			$query="EXECUTE insMovimiento '$sigla','$pri_emp_dia_trabajados[$a]','23701001','$num_quincena','".$eps."','$cen_cos_id','$pri_emp_salud[$a]','2','$mes_pago','$pri_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			//PENSION
			$query="EXECUTE insMovimiento '$sigla','$pri_emp_dia_trabajados[$a]','23700501','$num_quincena','".$pension."','$cen_cos_id','$pri_emp_pension[$a]','2','$mes_pago','$pri_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
            
			//CUENTA POR PAGAR
			
			if($est_pri_aux_transporte==2)
			{
				$pri_emp_tot_pagar_1[$a]=$pri_emp_tot_pagar_1[$a]-$pri_emp_aux_transporte[$a];
			}
			
			$query="EXECUTE insMovimiento '$sigla','$pri_emp_dia_trabajados[$a]','25050501','$num_quincena','$pri_emp_id[$a]','$cen_cos_id','$pri_emp_tot_pagar_1[$a]','2','$mes_pago','$pri_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			$array_empleados[$a]=$pri_emp_id[$a];
			$a++;
		}
			$mov = "EXECUTE movContable $cant_cuentas";
			$ins_mov = mssql_query($mov);
			
			$arr_empleados=$ins_varios->envia_array_url($array_empleados);
			
			echo "<script>alert('Causacion registrada correctamente.');</script>";
			echo "<script>ventanaemergente('../reportes/causacion_nomina_administrativa.php?mes_pago=".$mes_pago."&sigla=".$sigla."&num_quincena=".$num_quincena."&mes_contable=".$mes[1]."&anio_contable=".$ano."&lista_empleados=".$arr_empleados."')</script>";
	}
	////////////////////////////////////////////SEGUNDA QUINCENA////////////////////////////////////////////
	
	elseif($num_quincena==2)
	{
		//echo "segunda quincena";
		//INICIO CONSULTAR PORCENTAJES PROVISIÓN//
		$con_dat_pro_nom_administrativa=$ins_nomina->ConsDatProNomAdministrativa();
		while($res_dat_pro_nom_administrativa=mssql_fetch_array($con_dat_pro_nom_administrativa))
		{
			$dat_id[]=$res_dat_pro_nom_administrativa['dat_pro_nom_adm_id'];
			$dat_nombre[]=$res_dat_pro_nom_administrativa['dat_pro_nom_adm_nombre'];
			$dat_porcentaje[]=$res_dat_pro_nom_administrativa['dat_pro_nom_adm_porcentaje'];
			/*CESANTIAS EMPLEADOS	8,33,INTERESES SOBRE CESANTIAS EMPLEADOS	1,PRIMA DE SERVICIOS EMPLEADOS	8,33
			VACACIONES EMPLEADOS	4,17,EPS EMPLEADOS	8,5,APORTES A FONDOS DE PENSIONES EMPLEADOS	12,CAJA DE COMPENSACIÓN FAMILIAR EMPLEADOS	4
			APORTES ICBF EMPLEADO	3,APORTES SENA EMPLEADO	2*/
		}
		//FIN CONSULTAR PORCENTAJES PROVISIÓN//
		
		//$pri_emp_id=$_POST['seg_emp_id'];
		$j=0;
		while($j<$_POST['can_reg_segunda'])
		{
			$seg_emp_id[]=$_POST['seg_emp_id'.$j];
			$seg_emp_salario[]=$_POST['seg_emp_salario'.$j];
			$seg_emp_documento[]=$_POST['seg_emp_documento'.$j];
			$seg_emp_nombres[]=$_POST['seg_emp_nombres'.$j];
			$seg_emp_quincena[]=$_POST['seg_emp_quincena'.$j];
			$seg_emp_bonificacion[]=$_POST['seg_emp_bonificacion'.$j];
			$seg_emp_aux_transporte[]=$_POST['seg_emp_aux_transporte'.$j];
			$seg_emp_credito[]=$_POST['seg_emp_credito'.$j];
			$seg_emp_tot_pagar_2[]=$_POST['seg_emp_tot_pagar'.$j];
			
			$seg_emp_dia_trabajados[]=$_POST['seg_emp_dia_trabajados'.$j];
			
			$seg_emp_sal_basico[]=$_POST['seg_emp_sal_basico'.$j];
		    //$pri_emp_pagar=$_POST['pri_emp_pagar'];
			//FIN CAPTURA LOS DATOS QUE VIENEN DEL  FORMULARIO ANTERIOR
			$j++;
		}
		$lasmetasprovision=$_POST['lasmetasprovision'];
		$seg_minimo=$res_dat_nom_administrativa['dat_nom_sal_minimo'];
		//INICIO SUMO EL VALOR TOTAL DE LA NOMINA PARA GUARDARLO EN LA TRANSACCIÓN
		$i=0;
		$sum_seg_nomina=0;
		while($i<sizeof($seg_emp_tot_pagar_2))
		{
			$sum_seg_nomina=$sum_seg_nomina+$seg_emp_tot_pagar_2[$i];
			$i++;
		}
		//FIN SUMO EL VALOR TOTAL DE LA NOMINA PARA GUARDARLO EN LA TRANSACCIÓN
		
		$query="INSERT INTO dbo.transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,trans_fec_vencimiento,trans_fac_num,trans_user,
		trans_fec_grabado,tran_mes_contable,trans_ano_contable,trans_observacion)
		VALUES('$sigla','$fecha','$nit_id','$cen_cos_id','$sum_seg_nomina','0','$fecha_liquidacion','$num_quincena','$_SESSION[k_nit_id]','$fecha_liquidacion','$mes[1]','$ano','$sigla_provision')";
		$ejecutar=mssql_query($query);
		/*
		$gua_transaccion = $ins_transaccion->guaTransaccion(strtoupper($sigla),$fecha,$nit_id,$cen_cos_id,$sum_seg_nomina,0,$fecha_liquidacion,$num_quincena,$_SESSION['k_nit_id'],$fecha,$mes[1]);*/
		$act_est_nom_administrativa=$ins_transaccion->act_est_nom_adm_causada(1);
		$a=0;
		$cant_cuentas=0;
		while($a<sizeof($seg_emp_id))
		{
			$con_seg_fondos=$ins_nits->fon_nits($seg_emp_id[$a]);
			$res_seg_fondos=mssql_fetch_array($con_seg_fondos);
			//TRAE LA ARP DEL EMPLEADO
			$seg_arp=$res_seg_fondos['nits_arp'];
			//TRAE LA EPS DEL EMPLEADO
			$seg_eps=$res_seg_fondos['nits_eps'];
			//TRAE LA PENSION DEL EMPLEADO
			$seg_pension=$res_seg_fondos['nit_pensiones'];
			//TRAE LA CAJA DE COMPENSACIÓN DEL EMPLEADO
			$seg_caj_compensacion=$res_seg_fondos['nit_cajaCompensacion'];
			//TRAE LA EL PORCENTAJE DE ARL DEL EMPLEADO
			$arl_empleado=$ins_nomina->ConArlNit($seg_emp_id[$a]);
			
			
			//NOMINA DE PLANTA
			$query="EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','51050506','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','$seg_emp_quincena[$a]','1','$mes_pago','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			
			//AUXILIIO DE TRANSPORTE
			$con_dat_aux_tra_empleado=$ins_nits->consultar($seg_emp_id[$a]);
			$res_dat_aux_tra_empleado=mssql_fetch_array($con_dat_aux_tra_empleado);
			
			//echo $seg_emp_id[$a]."<br>";
			//echo $seg_emp_salario[$a]."___".($seg_minimo*2)."___".$res_dat_aux_tra_empleado['nit_pag_aux_transporte']."<br>";
			
			if($seg_emp_salario[$a]<=($seg_minimo*2)&&$res_dat_aux_tra_empleado['nit_pag_aux_transporte']==1)
		    {
				$query="EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','51050527','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','$seg_emp_aux_transporte[$a]','1','$mes_pago','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
				$ejecutar=mssql_query($query);
				$cant_cuentas++;
				$est_seg_aux_transporte=1;
			}
			else
			{
				$est_seg_aux_transporte=2;
			}
			
			//METAS Y RESPONSABILIDADES
			$query="EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','51050548','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','$seg_emp_bonificacion[$a]','1','$mes_pago','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			
			//OTROS DESCUENTOS NOMINA
            $con_otr_des_nomina=$ins_com_nomina->ConsultarDescuentosNominaAdministrativa($seg_emp_id[$a],0,0,1);
            $tot_otr_descuentos=0;
            while($res_otr_des_nomina=mssql_fetch_array($con_otr_des_nomina))
            {
                $sql_otr_descuentos ="EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','".$res_otr_des_nomina['des_nom_adm_cuenta']."','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','".$res_otr_des_nomina['des_nom_adm_valor']."','2','D-".$res_otr_des_nomina['des_nom_adm_id']."','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
                $query_otr_descuentos=mssql_query($sql_otr_descuentos);
                $cant_cuentas++;
                $tot_otr_descuentos+=$res_otr_des_nomina['des_nom_adm_valor'];
                $act_otr_descuento=$ins_com_nomina->ActualizarOtroDescuento($sigla,2,$res_otr_des_nomina['des_nom_adm_id']);
            }
            
            
            //RETENCION EN LA FUENTE EMPLEADOS
            
            //DESCUENTOS SS DE ESE MES
            $que_des_seg_soc="SELECT ISNULL(SUM(mov_valor),0) mov_valor FROM movimientos_contables WHERE mov_cuent IN(23701001,23700501)
                              AND mov_mes_contable='$mes[1]' AND mov_ano_contable='$ano' AND mov_compro NOT LIKE('PRO-NOM_ADM_%')
                              AND (mov_nit_tercero LIKE('$seg_emp_id[$a]%') OR mov_doc_numer LIKE('$seg_emp_id[$a]%'))";
                              
            $eje_des_seg_soc=mssql_query($que_des_seg_soc);
            $res_des_seg_soc=mssql_fetch_array($eje_des_seg_soc);
			
			//DESDE AQUI
			
        	$ingresos=$seg_emp_sal_basico[$a]+$seg_emp_bonificacion[$a];
		
			$can_uvt_ded_dependientes=$ins_varios->ConsultarDatosVariablesPorId(9);
			$can_por_ded_dependientes=$ins_varios->ConsultarDatosVariablesPorId(12);
			
			$dat_1=$ingresos*$can_por_ded_dependientes['var_valor']/100;
			$dat_2=$res_val_uvt['var_valor']*$can_uvt_ded_dependientes['var_valor'];
			
			if($dat_1>$dat_2)
				$ded_dependientes=$dat_2;
			else
				$ded_dependientes=$dat_1;
			
			
			
			$can_uvt_por_salud=$ins_varios->ConsultarDatosVariablesPorId(10);
			$ded_pag_por_salud=$res_val_uvt['var_valor']*$can_uvt_por_salud['var_valor'];
			
			$egresos=$res_des_seg_soc['mov_valor']+$ded_dependientes+$ded_pag_por_salud;
			
	        $base_retencion=$ingresos-$egresos;
            
            $que_tip_retencion="SELECT nit_tip_procedimiento,nit_por_ret_fuente FROM nits WHERE nit_id='$seg_emp_id[$a]'";
            $eje_tip_retencion=mssql_query($que_tip_retencion);
            $res_tip_retencion=mssql_fetch_array($eje_tip_retencion);
            if($res_tip_retencion['nit_tip_procedimiento']==2)
                $val_retencion=round($base_retencion*($res_tip_retencion['nit_por_ret_fuente']/100),0);
            else
                $val_retencion=$ins_mov_contable->cal_ret_fuente($base_retencion);
            
            $sql_ret_fuente ="EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','23650501','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','$val_retencion','2','$mes_pago','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";//Retencion en la fuente
            $query_ret_fuente=mssql_query($sql_ret_fuente);
            $cant_cuentas++;
            
            //CREDITOS
            /*********************DESCONTAR EL CREDITO EN LA NOMINA**********************/
            $factura='0';
            $cuota_cre=$ins_credito->bus_cuoDescontar($factura,$seg_emp_id[$a],1);
            $num_filas=mssql_num_rows($cuota_cre);
            $tot_des_creditos=0;
            if($num_filas>0)
            {
                while($row_cuota=mssql_fetch_array($cuota_cre))
                {
                    $ins_credito->ultimo_pago($row_cuota['des_cre_credito']);
                    $mos_credito = $ins_credito->cueCreditos($row_cuota['des_cre_credito'],$seg_emp_id[$a],$cen_cos_id,$row_cuota['des_cre_credito']);
                    $dat_retorno = split("--",$mos_credito);
                    $sql_interes = "EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','".$dat_retorno[5]."','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','".$row_cuota['des_cre_interes']."','2','C-".$row_cuota['des_cre_credito']."','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";//INTERES
                    $cant_cuentas++;
                    //echo $sql_interes."<br>";
                    $query_interes = mssql_query($sql_interes);
                    $sql_capital ="EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','".$dat_retorno[2]."','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','".$row_cuota['des_cre_capital']."','2','C-".$row_cuota['des_cre_credito']."','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";//Capital
                    $cant_cuentas++;
                    //echo $sql_capital."<br>";
                    $query_capital = mssql_query($sql_capital);
                    //echo "el valor es: ".$men_emp_tot_pagar_3[$a]."<br>";
                    $tot_des_creditos+=$row_cuota['des_cre_interes']+$row_cuota['des_cre_capital'];
                    $ins_credito->act_cuoCredito(3,$row_cuota['des_cre_id'],$sigla,'',$mes[1],$ano);
                }
            }
                        
            //echo "e total es: ".$men_emp_tot_pagar[$a]."___".$tot_otr_descuentos."<br>";
            $total_pagar_segunda_quincena=$seg_emp_tot_pagar_2[$a]-$tot_des_creditos-$tot_otr_descuentos-$val_retencion;
            
            
            if($est_seg_aux_transporte==2)
            {
            	$total_pagar_segunda_quincena=$total_pagar_segunda_quincena-$seg_emp_aux_transporte[$a];
            }
			
			
			
			//CUENTA POR PAGAR
			$query="EXECUTE insMovimiento '$sigla','$seg_emp_dia_trabajados[$a]','25050501','$num_quincena','$seg_emp_id[$a]','$cen_cos_id','$total_pagar_segunda_quincena','2','$mes_pago','$seg_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			
			/*
			//////////INICIO CRUZAR CUENTA POR PAGAR CON CUENTA DEL BANCO///////////
			
			//CUENTA POR PAGAR
		$query="EXECUTE insMovimiento '$sigla',$consecutivo,'25050501',0,'$seg_emp_id[$a]',$cen_cos_id,$seg_emp_tot_pagar[$a],1,'$consecutivo','$_SESSION[k_nit_id]',0,0,'$fecha','$mes[1]','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
	//CUENTA BANCO
			$query="EXECUTE insMovimiento '$sigla',$consecutivo,'11100501',0,'$seg_emp_id[$a]',$cen_cos_id,$seg_emp_tot_pagar[$a],2,'$consecutivo','$_SESSION[k_nit_id]',0,0,'$fecha','$mes[1]','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			
			//////////FIN CRUZAR CUENTA POR PAGAR CON CUENTA DEL BANCO///////////
			
			*/
			
			
			///////////INICIO PROVISIÓN DE NOMINA////////////
			
			
			//PONER AQUI EL CODIGO DE LA PROVISION

			///////////FIN PROVISIÓN DE NOMINA////////////
			
			
			$array_empleados[$a]=$seg_emp_id[$a];
			
			
		$a++;	
		}//CIERRA EL WHILE
			$mov = "EXECUTE movContable $cant_cuentas";
			$ins_mov = mssql_query($mov);
			
			//GUARDAR LA PROVISIÓN EN TRANSACCIONES
			
			/*$query="INSERT INTO dbo.transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,trans_fec_vencimiento,trans_fac_num,trans_user,
			trans_fec_grabado,tran_mes_contable,trans_ano_contable,trans_observacion)
			VALUES('$sigla_provision','$fecha','$nit_id','$cen_cos_id','$sum_seg_nomina','0','$fecha_liquidacion','$num_quincena','$_SESSION[k_nit_id]','$fecha','$mes[1]','$ano','$sigla')";
			$ejecutar=mssql_query($query);*/
			
			/*
			$gua_tra_provision=$ins_transaccion->guaTransaccion(strtoupper($sigla_provision),$fecha,$nit_id,$cen_cos_id,$sum_seg_nomina,0,$fecha_liquidacion,$num_quincena,$_SESSION['k_nit_id'],$fecha,$mes[1]);
			*/
			
			$act_consecutivo_provision=$ins_factura->act_consecutivo(33);
			
			$arr_empleados=$ins_varios->envia_array_url($array_empleados);
			
			echo "<script>alert('Causacion registrada correctamente.');</script>";
			echo "<script>ventanaemergente('../reportes/causacion_nomina_administrativa.php?mes_pago=".$mes_pago."&sigla=".$sigla."&num_quincena=".$num_quincena."&mes_contable=".$mes[1]."&anio_contable=".$ano."&lista_empleados=".$arr_empleados."')</script>";
			
			
	}//CIERRA EL ELSEIF
}
elseif($per_pag_nomina==2)//EL PAGO ES MENSUAL
{
	//INICIO CAPTURA LOS DATOS QUE VIENEN DEL  FORMULARIO ANTERIOR
	$j=0;
	while($j<$_POST['can_reg_mensual'])
	{
		$men_emp_id[]=$_POST['men_emp_id'.$j];
		//$men_emp_salario=$_POST['men_emp_sal_basico'];
		$men_emp_documento[]=$_POST['men_emp_documento'.$j];
		$men_emp_nombres[]=$_POST['men_emp_nombres'.$j];
		$men_emp_quincena[]=$_POST['men_emp_sal_basico'.$j];//SALARIO TOTAL PORQUE ES MENSUAL
		
		$men_emp_bonificacion[]=$_POST['men_emp_bonificacion'.$j];
		
		$men_emp_aux_transporte[]=$_POST['men_emp_aux_transporte'.$j];
		
		$men_emp_credito[]=$_POST['men_emp_credito'.$j];
		
		$men_emp_salud[]=$_POST['men_emp_salud'.$j];
		$men_emp_pension[]=$_POST['men_emp_pension'.$j];
		//$men_emp_tot_pagar=$_POST['men_emp_tot_pagar'];
		$men_emp_fon_sol_pensional[]=$_POST['men_emp_fon_sol_pensional'.$j];
		$men_emp_tot_pagar_3[]=$_POST['men_emp_tot_pagar'.$j];
		
		$men_emp_dia_trabajados[]=$_POST['men_emp_dia_trabajados'.$j];
		//$pri_emp_pagar=$_POST['pri_emp_pagar'];
		//FIN CAPTURA LOS DATOS QUE VIENEN DEL  FORMULARIO ANTERIOR
		$j++;
	}
	
	$lasmetasprovision=$_POST['lasmetasprovision'];
	$men_minimo=$res_dat_nom_administrativa['dat_nom_sal_minimo'];
	$men_num_quincena=3;
	
	//INICIO CONSULTAR PORCENTAJES PROVISIÓN//
	$con_dat_pro_nom_administrativa=$ins_nomina->ConsDatProNomAdministrativa();
	while($res_dat_pro_nom_administrativa=mssql_fetch_array($con_dat_pro_nom_administrativa))
	{
		$dat_id[]=$res_dat_pro_nom_administrativa['dat_pro_nom_adm_id'];
		$dat_nombre[]=$res_dat_pro_nom_administrativa['dat_pro_nom_adm_nombre'];
		$dat_porcentaje[]=$res_dat_pro_nom_administrativa['dat_pro_nom_adm_porcentaje'];
		/*CESANTIAS EMPLEADOS	8,33,INTERESES SOBRE CESANTIAS EMPLEADOS	1,PRIMA DE SERVICIOS EMPLEADOS	8,33
		VACACIONES EMPLEADOS	4,17,EPS EMPLEADOS	8,5,APORTES A FONDOS DE PENSIONES EMPLEADOS	12,CAJA DE COMPENSACIÓN FAMILIAR EMPLEADOS	4
		APORTES ICBF EMPLEADO	3,APORTES SENA EMPLEADO	2*/
	}
	//FIN CONSULTAR PORCENTAJES PROVISIÓN//
	
	//INICIO SUMO EL VALOR TOTAL DE LA NOMINA PARA GUARDARLO EN LA TRANSACCIÓN
	$i=0;
	$sum_men_nomina=0;
	while($i<sizeof($men_emp_tot_pagar_3))
	{
		$sum_men_nomina+=$men_emp_tot_pagar_3[$i];
		$i++;
	}
	//FIN SUMO EL VALOR TOTAL DE LA NOMINA PARA GUARDARLO EN LA TRANSACCIÓN
	
	$query="INSERT INTO dbo.transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,trans_fec_vencimiento,trans_fac_num,trans_user,
	trans_fec_grabado,tran_mes_contable,trans_ano_contable,trans_observacion)
	VALUES('$sigla','$fecha','$nit_id','$cen_cos_id','$sum_men_nomina','0','$fecha_liquidacion','2','$_SESSION[k_nit_id]','$fecha_liquidacion','$mes[1]','$ano','$sigla_provision')";
	//echo $query;
	//INSERT INTO dbo.transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,trans_fec_vencimiento,trans_fac_num,trans_user,
	//trans_fec_grabado,tran_mes_contable,trans_ano_contable,trans_observacion)
	//VALUES('CAU-NOM_ADM_8','04-12-2015','380','1169','2665000','0','04-12-2015','Array','389','04-12-2015','12','2015','PRO-NOM_ADM_8')
	/*
	$query="INSERT INTO dbo.transacciones(trans_sigla,trans_fec_doc,trans_nit,trans_centro,trans_val_total,trans_iva_total,trans_fec_vencimiento,trans_fac_num,trans_user,
	trans_fec_grabado,tran_mes_contable,trans_ano_contable)
	VALUES('$sigla','$fecha','$nit_id','$cen_cos_id','$sum_pri_nomina','$consecutivo','$fecha_liquidacion','$num_quincena','$_SESSION[k_nit_id]','$fecha_liquidacion','$mes[1]','$ano')";  
	*/
	$ejecutar=mssql_query($query);
	//EL ESTADO 1 ES CAUSADO Y EL 2 ES PAGADO
	$act_est_nom_administrativa=$ins_transaccion->act_est_nom_adm_causada(1);
	
	//INICIO GUARDAR TODOS LOS REGISTROS POR PERSONA//
	$a=0;
	$cant_cuentas=0;
	while($a<sizeof($men_emp_id))
	{
		$con_men_fondos=$ins_nits->fon_nits($men_emp_id[$a]);
		$res_men_fondos=mssql_fetch_array($con_men_fondos);
		//TRAE LA ARP DEL EMPLEADO
		$men_arp=$res_men_fondos['nits_arp'];
		//TRAE LA EPS DEL EMPLEADO
		$men_eps=$res_men_fondos['nits_eps'];
		//TRAE LA PENSION DEL EMPLEADO
		$men_pension=$res_men_fondos['nit_pensiones'];
		//TRAE LA CAJA DE COMPENSACIÓN DEL EMPLEADO
		$men_caj_compensacion=$res_men_fondos['nit_cajaCompensacion'];
		//TRAE LA EL PORCENTAJE DE ARL DEL EMPLEADO
		$arl_empleado=$ins_nomina->ConArlNit($men_emp_id[$a]);
			
		//SUELDOS EMPLEADOS DE PLANTA
		$query="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','51050506','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','$men_emp_quincena[$a]','1','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
		//echo $query;
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
			
		//AUXILIIO DE TRANSPORTE
		
		$con_dat_aux_tra_empleado=$ins_nits->consultar($pri_emp_id[$a]);
		$res_dat_aux_tra_empleado=mssql_fetch_array($con_dat_aux_tra_empleado);
		if($men_emp_salario[$a]<=($men_minimo*2)&&$res_dat_aux_tra_empleado['nit_pag_aux_transporte']==1)
		{
			$query="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','51050527','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','$men_emp_aux_transporte[$a]','1','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
			$ejecutar=mssql_query($query);
			$cant_cuentas++;
			$est_men_aux_transporte=1;
		}
		else
		{
			$est_men_aux_transporte=2;
		}
		
		//METAS Y RESPONSABILIDADES
		$query="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','51050548','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','$men_emp_bonificacion[$a]','1','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
			
		//FONDO DE SOLIDARIDAD PENSIONAL
		$query="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','23700501','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','$men_emp_fon_sol_pensional[$a]','2','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
			
		//SALUD
		$query="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','23701001','$men_num_quincena','".$men_eps."','$cen_cos_id','$men_emp_salud[$a]','2','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
		
		//PENSION
		$query="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','23700501','$men_num_quincena','".$men_pension."','$cen_cos_id','$men_emp_pension[$a]','2','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
        
        //OTROS DESCUENTOS NOMINA
        $con_otr_des_nomina=$ins_com_nomina->ConsultarDescuentosNominaAdministrativa($men_emp_id[$a],0,0,1);
        $tot_otr_descuentos=0;
        while($res_otr_des_nomina=mssql_fetch_array($con_otr_des_nomina))
        {
            $sql_otr_descuentos ="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','".$res_otr_des_nomina['des_nom_adm_cuenta']."','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','".$res_otr_des_nomina['des_nom_adm_valor']."','2','D-".$res_otr_des_nomina['des_nom_adm_id']."','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";//Capital
            $query_otr_descuentos=mssql_query($sql_otr_descuentos);
            $cant_cuentas++;
            $tot_otr_descuentos+=$res_otr_des_nomina['des_nom_adm_valor'];
            $act_otr_descuento=$ins_com_nomina->ActualizarOtroDescuento($sigla,2,$res_otr_des_nomina['des_nom_adm_id']);
        }
        
        
        //RETENCION EN LA FUENTE EMPLEADOS
        $ingresos=$men_emp_quincena[$a]+$men_emp_bonificacion[$a];
		
		$can_uvt_ded_dependientes=$ins_varios->ConsultarDatosVariablesPorId(9);
		$can_por_ded_dependientes=$ins_varios->ConsultarDatosVariablesPorId(12);
			
		$dat_1=$ingresos*$can_por_ded_dependientes['var_valor']/100;
		$dat_2=$res_val_uvt['var_valor']*$can_uvt_ded_dependientes['var_valor'];
			
		if($dat_1>$dat_2)
			$ded_dependientes=$dat_2;
		else
			$ded_dependientes=$dat_1;
		
		$can_uvt_por_salud=$ins_varios->ConsultarDatosVariablesPorId(10);
		$ded_pag_por_salud=$res_val_uvt['var_valor']*$can_uvt_por_salud['var_valor'];
		
		$egresos=$men_emp_salud[$a]+$men_emp_pension[$a]+$men_emp_fon_sol_pensional[$a]+$ded_dependientes+$ded_pag_por_salud;
		
        $base_retencion=$ingresos-$egresos;
        //echo "la base es: ".$base_retencion;
		
        $que_tip_retencion="SELECT nit_tip_procedimiento,nit_por_ret_fuente FROM nits WHERE nit_id=$men_emp_id[$a]";
        $eje_tip_retencion=mssql_query($que_tip_retencion);
        $res_tip_retencion=mssql_fetch_array($eje_tip_retencion);
        if($res_tip_retencion['nit_tip_procedimiento']==2)
            $val_retencion=round($base_retencion*($res_tip_retencion['nit_por_ret_fuente']/100),0);
        else
            $val_retencion=round($ins_mov_contable->cal_ret_fuente($base_retencion),-3);
        
        $sql_ret_fuente ="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','23650501','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','$val_retencion','2','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";//Retencion en la fuente
        $query_ret_fuente=mssql_query($sql_ret_fuente);
        $cant_cuentas++;
        
        //CREDITOS
        /*********************DESCONTAR EL CREDITO EN LA NOMINA**********************/
        $factura='0';
        $cuota_cre=$ins_credito->bus_cuoDescontar($factura,$men_emp_id[$a],1);
        $num_filas=mssql_num_rows($cuota_cre);
        $tot_des_creditos=0;
        if($num_filas>0)
        {
            while($row_cuota=mssql_fetch_array($cuota_cre))
            {
                $ins_credito->ultimo_pago($row_cuota['des_cre_credito']);
                $mos_credito = $ins_credito->cueCreditos($row_cuota['des_cre_credito'],$men_emp_id[$a],$cen_cos_id,$row_cuota['des_cre_credito']);
                $dat_retorno = split("--",$mos_credito);
                $sql_interes = "EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','".$dat_retorno[5]."','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','".$row_cuota['des_cre_interes']."','2','C-".$row_cuota['des_cre_credito']."','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";//INTERES
                $cant_cuentas++;
                //echo $sql_interes."<br>";
                $query_interes = mssql_query($sql_interes);
                $sql_capital ="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','".$dat_retorno[2]."','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','".$row_cuota['des_cre_capital']."','2','C-".$row_cuota['des_cre_credito']."','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";//Capital
                $cant_cuentas++;
                //echo $sql_capital."<br>";
                $query_capital = mssql_query($sql_capital);
                //echo "el valor es: ".$men_emp_tot_pagar_3[$a]."<br>";
                $tot_des_creditos+=($row_cuota['des_cre_interes']+$row_cuota['des_cre_capital']);
                $ins_credito->act_cuoCredito(3,$row_cuota['des_cre_id'],$sigla,'',$mes[1],$ano);
            }
        }
		
        $total_pagar_mensual=$men_emp_tot_pagar_3[$a]-($tot_otr_descuentos+$val_retencion+$tot_des_creditos);
        
		
		if($est_men_aux_transporte==2)
		{
			$total_pagar_mensual=$total_pagar_mensual-$men_emp_aux_transporte[$a];
		}
		
		//NOMINA DE PLANTA
		$query="EXECUTE insMovimiento '$sigla','$men_emp_dia_trabajados[$a]','25050501','$men_num_quincena','$men_emp_id[$a]','$cen_cos_id','$total_pagar_mensual','2','$mes_pago','$men_emp_id[$a]','0','0','$fecha','$mes[1]','$ano','$bas_retencion'";
		$ejecutar=mssql_query($query);
		$cant_cuentas++;
		
		///////////INICIO PROVISIÓN DE NOMINA////////////
		//PONER AQUI EL CODIGO DE LA PROVISION
		
		
		///////////FIN PROVISIÓN DE NOMINA////////////
		
		$array_empleados[$a]=$men_emp_id[$a];
		$a++;
	}
	$mov = "EXECUTE movContable $cant_cuentas";
	$ins_mov = mssql_query($mov);
	
	$arr_empleados=$ins_varios->envia_array_url($array_empleados);
		
	echo "<script>alert('Causacion registrada correctamente.');</script>";
	echo "<script>ventanaemergente('../reportes/causacion_nomina_administrativa.php?mes_pago=".$mes_pago."&sigla=".$sigla."&num_quincena=".$men_num_quincena."&mes_contable=".$mes[1]."&anio_contable=".$ano."&lista_empleados=".$arr_empleados."')</script>";
	//INICIO GUARDAR TODOS LOS REGISTROS POR PERSONA//
	
}
$act_consecutivo=$ins_factura->act_consecutivo(30);
?>