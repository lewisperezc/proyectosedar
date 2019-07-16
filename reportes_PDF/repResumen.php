<?php 

session_start();
include_once('../conexion/conexion.php');
include_once('../clases/nomina.class.php');
include_once('../clases/reporte_jornadas.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/pabs.class.php');

$factura=$_GET['valor'];//ID DE LA FACTURA
//echo "la factura es:".$factura."<br>";
$valfactura=$_GET['valfactura'];
$elrecibo=$_GET['elrecibo'];
//echo $elrecibo;
$conse_recibo=$_GET['conse_recibo'];

//echo "fac_".$factura."valor_".$valfactura."recibo_".$elrecibo."<br>";

$ins_nomina=new nomina();
$reporte = new reporte_jornadas();
$instancia_factura = new factura();
$ins_nits=new nits();
$minimo=$ins_nits->sal_minimo();
$ins_fabs=new pabs();

$res_dat_factura=$instancia_factura->ConsultarTodosDatosFacturaPorId($factura);


$que_1="SELECT rec_caj_id FROM recibo_caja WHERE rec_caj_factura='$valor' AND rec_caj_id='$elrecibo'";
//echo $que_1;
$eje_1=mssql_query($que_1);
$res_1=mssql_fetch_array($eje_1);
$los_id=$res_1['rec_caj_id'];

$que_2="SELECT DISTINCT mov_cent_costo,mov_compro FROM movimientos_contables
WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume = '$valor' AND mov_doc_numer='$los_id'";
//echo $que_2;
$eje_2=mssql_query($que_2);
$res_2=mssql_fetch_array($eje_2);

$conse_nomina=$res_2['mov_compro'];
$el_centro=$res_2['mov_cent_costo'];

$dat_factura = $instancia_factura->datFactura($factura);
$dat_facMes = mssql_fetch_array($dat_factura);


$que_fac_consecutivo="SELECT fac_consecutivo FROM factura WHERE fac_id='$factura'";
$eje_fac_consecutivo=mssql_query($que_fac_consecutivo);
$res_fac_consecutivo=mssql_fetch_array($eje_fac_consecutivo);

//mov_compro LIKE ('PAG-COM-%') AND
//$conse_nomina=PAG-COM-1368
$nits="SELECT distinct mc.mov_nit_tercero,mc.mov_compro,nits_apellidos
FROM movimientos_contables mc
INNER JOIN nits n ON mc.mov_nit_tercero=n.nit_id
WHERE mc.mov_compro LIKE ('PAG-COM-%')
AND mc.mov_compro='$conse_nomina' AND mc.mov_nit_tercero NOT LIKE('%[_]%')
ORDER BY n.nits_apellidos ASC";
$arreglo=array('');
//$conse_nomina = "";
$nit = mssql_query($nits);
$j=0;
while($row = mssql_fetch_array($nit))
{
	if(!strpos($row['mov_nit_tercero'],'_'))
	{
		if(!in_array($row['mov_nit_tercero'],$arreglo,0))
		{
			$arreglo[$j]=$row['mov_nit_tercero'];
                   // echo $arreglo[$j]."<br>";
			$j++;
		}
	}
}

$html="";

$html.='<table border="0" style="font-size:8px;width:100%">';
$html.='<tr><th colspan="6"><img src="../imagenes/logo_sedar_dentro.png" width="520" height="120" alt="Logo Sedar" /></th></tr>';
$html.='<tr><td><b>'."FACTURA: ".$res_fac_consecutivo['fac_consecutivo'].'</b></td><tr>';
$html.='</table>';


$html.='<table border="0" style="font-size:9px;width:100%">';
	$html.='<tr>';
		$html.='<th style="text-align: left">CEDULA</th>';
		$html.='<th  style="text-align: left"colspan="5">NOMBRE</th>';
		$html.='<th style="text-align: right">NOVEDAD</th>';
		$html.='<th style="text-align: right">DESC LEGALES</th>';
		$html.='<th style="text-align: right">VALOR TEORICO</th>';
		$html.='<th style="text-align: right">FONDO FABS</th>';
		$html.='<th style="text-align: right">FONDO RETIRO SINDICAL</th>';
		$html.='<th style="text-align: right">FONDO RECREACION</th>';
		$html.='<th style="text-align: right">ADMON BASICA</th>';
		$html.='<th style="text-align: right">FONDO EDUCACION</th>';
		$html.='<th style="text-align: right">SEGURIDAD SOCIAL</th>';
		$html.='<th style="text-align: right">RETE FUENTE</th>';
		$html.='<th style="text-align: right">DESC FACTURA</th>';
		$html.='<th style="text-align: right">OTROS DESCUENTOS</th>';
		$html.='<th style="text-align: right">VALOR A PAGAR</th>';
	$html.='</tr>';


$i = 0;
$facturado=0;
$desc=0;
$descuen=0;
$fabs=0;
$retSindical=0;
$vacaciones=0;
$adminis=0;
$educa=0;
$segSocial=0;
$rete=0;
$descu_fac=0;
$val_descu=0;
$compensacion=0;

while($i<sizeof($arreglo))
{
	$p=0;
	$res_nomina=$ins_nomina->trae_datos_nomina($conse_nomina,$arreglo[$i],2);
	$dat_asociado = mssql_fetch_array($res_nomina);
	//$admon=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'263535');
	$honorarios=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23352501');
	$retefuente=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23650501');
	$compenasociado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'25051001');
	
	if($compenasociado=="" || $compenasociado==0)//EL VALOR POR PAGAR ES NEGATIVO
	{
		$compenasociado=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],1,'25051001');
		$compenasociado=$compenasociado*-1;
	}
	
	
	//$compenompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23809501');
	$segsocialnomcau=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'13250594');//25302001//13250591
	
	$vacnompagada=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'25051005');
	
	////////////////INICIO CONSULTAR CUENTAS FABS////////////////
	
	$fabspagado=$ins_nomina->trae_cuentas_fabs($conse_nomina,$arreglo[$i],1);
	
	////////////////FIN CONSULTAR CUENTAS FABS////////////////
	
	$fonretsindical=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'23803009');
	//$pubcontrato=$ins_nomina->trae_cuentas_nomina($conse_nomina,$arreglo[$i],2,'61651035');
	$pubcontrato=0;
	$nov = $honorarios + $retefuente + $compenasociado + $vacnompagada + $fabspagado + $fonretsindical + $pubcontrato + $segsocialnomcau;
	
	//EDUCACION
	$tercero=$arreglo[$i].'_380';
	$valor_educacion=$ins_nomina->trae_cuentas_nomina($conse_nomina,$tercero,1,'250510121');
	
	$datos = $reporte->bus_datCompensacion();
	$dat_compe = mssql_fetch_array($datos);
	
	if($res_dat_factura['fac_ano_servicio']==2017)
	{
		if($res_dat_factura['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
		{
			$porcen_admon=5;//ADMINISTRACION BASICA
		}
		else//CALCULA LA ADMON NUEVA 5.5%
		{
			$porcen_admon=$dat_compe['dat_nom_gastos'];//ADMINISTRACION BASICA
		}
	}
	else//CALCULA LA ADMON NUEVA 5.5%
	{
		if($res_dat_factura['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
		{
			$porcen_admon=5;//ADMINISTRACION BASICA
		}
		else//CALCULA LA ADMON NUEVA 5.5%		
		{
			$porcen_admon=$dat_compe['dat_nom_gastos'];//ADMINISTRACION BASICA
		}
	}
	
	
	$porcentaje=$nov*($porcen_admon+$dat_compe['dat_nom_educacion'])/100;//5 DE ADMINISTRACI�N Y 1 DE EDUCACI�N = 5
	//ojo se debe mirar el porcentaje segun el mes de servicio de la factura
	$novedad=$nov+$porcentaje;
	
	$res_compenasociado=$compenasociado;
	$descuento_factura=0;
	
	$valor_recibo="SELECT rec_caj_monto FROM recibo_caja WHERE rec_caj_id=".$elrecibo;
    	$dat_fac = mssql_query($valor_recibo);
	$datos_fac = mssql_fetch_array($dat_fac);
	$fac_id = $conse_nom;
	$fac_valor = $datos_fac['rec_caj_monto'];//ESTA
	$cen_cos = $el_centro;
 

	//echo "la consulta: ".$recibo_caja,"<br>";
	//$query_recibo = mssql_query($recibo_caja);
	//$dat_recibo = mssql_fetch_array($query_recibo);

	$dat_descuentos = "SELECT SUM(des_nom_valor) valor FROM descuentos_compensacion
	WHERE des_nom_nit = $arreglo[$i] AND des_nom_factura=$factura
	AND des_nom_rec_caja = ".$elrecibo." AND des_nom_cuenta NOT IN('13250594')";
	//echo "Los datos: ".$dat_descuentos."<br>";

	$con_des_creditos="SELECT SUM(mov_valor) suma_creditos
	FROM movimientos_contables
	WHERE mov_compro='$conse_nomina'
	AND mov_cuent IN('42954105','42100506','42100501','42100502','42100503','42100509','42100510','42100511','42100503',
	'42100504','42100508','13250501','13250502','13250503','13250504','13250505','13250506','13250507','13250508',
	'13250509','13250510','13250511','13250592','23803001','13805608','13652102','13250590')
	AND mov_nit_tercero='$arreglo[$i]'";
	//echo $con_des_creditos;
	$eje_des_creditos=mssql_query($con_des_creditos);
	$res_des_creditos=mssql_fetch_array($eje_des_creditos);

	//echo $dat_descuentos;
 	$des_factura = mssql_query($dat_descuentos);
 	$datos_descuento=mssql_fetch_array($des_factura);
 
 	$valor_descuento=$datos_descuento['valor']+$res_des_creditos['suma_creditos'];
 	
 	$sum_descuentos="SELECT SUM(des_monto) descuentos
	FROM descuentos d
	INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id
	INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id
	WHERE f.fac_id=".$factura." AND (des_tipo not in(1,2) OR des_distribucion IS NOT NULL)
	AND rc.rec_caj_id=$elrecibo";
	//echo $sum_descuentos;
	$rec_caj_des = mssql_query($sum_descuentos);
	$dat_rec_caja = mssql_fetch_array($rec_caj_des);
	$total_descuentos=$dat_rec_caja['descuentos'];
 
    //echo $total_descuentos;
	/*if($total_descuentos=="")
   	$total_descuentos=$instancia_factura->legFactura($factura,$elrecibo);*/
	//$cant_jornadas = $reporte->canJorFac($fac_id);
 	
 	$con_jor_con_rec_caja="SELECT ISNULL(SUM(rep_jor_con_rec_numero),0) rep_jornadas
	FROM rep_jor_con_recibo
	WHERE fac_id=".$factura." AND rec_caj_consecutivo=".$conse_recibo;
	//echo $con_jor_con_rec_caja."<br>";
	$eje_jor_con_rec_caja=mssql_query($con_jor_con_rec_caja);
	//$num_fil_rep_jor_con_rec_caja=mssql_num_rows($eje_jor_con_rec_caja);
 	$num_fil_rep_jor_con_rec_caja=mssql_fetch_array($eje_jor_con_rec_caja);
	if($num_fil_rep_jor_con_rec_caja['rep_jornadas']==0)
 	{
        //echo "entra por aca";
		$cant_jornadas=$reporte->canJorFac($factura);
		$jor_asociado="select rep_jor_num_jornadas from reporte_jornadas rj 
		inner join nits_por_cen_costo npcc on npcc.id_nit_por_cen = rj.id_nit_por_cen
		where npcc.nit_id=".$arreglo[$i]." and cen_cos_id = $cen_cos and rep_jor_num_factura=$factura";
		//echo $jor_asociado;
		
		$total_jornadas="SELECT SUM(rep_jor_num_jornadas) as rep_jor_num_jornadas from reporte_jornadas rj
        inner join nits_por_cen_costo npcc on npcc.id_nit_por_cen = rj.id_nit_por_cen
        where cen_cos_id = $cen_cos and rep_jor_num_factura=$factura";
	}
	else
	{
		$que_3="SELECT SUM(rep_jor_con_rec_numero) rep_jornadas
			 		 FROM rep_jor_con_recibo
					 WHERE fac_id=".$factura." AND rec_caj_consecutivo=".$conse_recibo;
		$eje_3=mssql_query($que_3);
		$res_4=mssql_fetch_array($eje_3);
		$cant_jornadas=$res_4['rep_jornadas'];
		 
		$jor_asociado="select distinct rep_jor_con_rec_numero as rep_jor_num_jornadas from rep_jor_con_recibo rjcr
		inner join nits_por_cen_costo npcc on npcc.nit_id=rjcr.nit_id
		where npcc.nit_id=".$arreglo[$i]." and npcc.cen_cos_id=".$cen_cos." and fac_id=".$factura." and rec_caj_consecutivo=".$conse_recibo;

		$total_jornadas="select SUM(rep_jor_con_rec_numero) as rep_jor_num_jornadas from rep_jor_con_recibo rjcr
        inner join nits_por_cen_costo npcc on npcc.nit_id=rjcr.nit_id
        where npcc.cen_cos_id=".$cen_cos." and fac_id=".$factura."
        and rec_caj_consecutivo=".$conse_recibo;
	}
	
	//TOTAL FACTURA SEA ABONO O PAGO TOTAL
    $eje_tot_jornadas=mssql_query($total_jornadas);
	$res_tot_jornadas=mssql_fetch_array($eje_tot_jornadas);
	//echo "AAA: ".$res_tot_jornadas['rep_jor_num_jornadas'];
	
	//echo $jor_asociado;
	//$val_jornada=$fac_valor/$cant_jornadas;
	$jor_aso=mssql_query($jor_asociado);
	$can_jornadas=mssql_fetch_array($jor_aso);
	$cantidad=$can_jornadas['rep_jor_num_jornadas'];
	
	//NUEVA CONSULTA VALOR FACTURADO
	
	if($dat_asociado['nit_est_id']==1)
	{
		//echo "entra";
		//echo $valor_educacion."___".$dat_compe['dat_admonAdministacion']."<br>";
    	$val_facturado=$valor_educacion*100/$dat_compe['dat_nom_educacion'];
	}
	elseif($dat_asociado['nit_est_id']==3)
	{
		//echo "entra";
		//echo $valor_educacion."___".$dat_compe['dat_admonAdministacion']."<br>";
    	$val_facturado=$valor_educacion*100/$dat_compe['dat_admonAdministacion'];
	}
    //DESCUENTOS DE GLOSA//
    //$res_tot_jornadas['rep_jor_num_jornadas'];
    $descuento_factura=$ins_nomina->con_des_nomina($factura,$elrecibo,$arreglo[$i],$val_facturado,$res_tot_jornadas['rep_jor_num_jornadas']);
	//echo $trae_descuentos;

	//$val_facturado=$cantidad;
    if($cant_jornadas==0)
    	$cant_jornadas=1;
 	
 	//echo "el valor: ".$por_descontado."<br>";
 	$sql_glosa = "SELECT SUM(disGlo_valor) valor
 	FROM distGlosa WHERE disGlo_nit = $arreglo[$i] AND disGlo_compensacion = '$conse_nomina'";		
 	$query_glosa = mssql_query($sql_glosa);
 	$dat_glosa = mssql_fetch_array($query_glosa);

 	if($dat_glosa['valor']>0)
		$val_facturado = $val_facturado;//-$dat_glosa['valor'];
	//echo $val_facturado;
 
	 $sql_glosa_2 = "SELECT SUM(disGlo_valor) valor
	 FROM distGlosa WHERE disGlo_compensacion = '$conse_nomina'";        
	 $query_glosa_2 = mssql_query($sql_glosa_2);
	 $res_tot_glosa=mssql_fetch_array($query_glosa_2);
 
	 //echo $val_facturado."___".$cantidad."___".$cant_jornadas."___".$total_descuentos."___".$res_tot_glosa['valor']."<br>";
 
 	//$nue_val_factura=$cant_jornadas-$res_tot_glosa['valor'];
 	$nue_val_factura=$res_tot_jornadas['rep_jor_num_jornadas'];
 	$nue_por_facturado=$val_facturado*100/$nue_val_factura;
 	$por_descontado=$nue_por_facturado*($total_descuentos/100);
 	//echo $nue_val_factura."___".$nue_por_facturado."___".$por_descontado."<br>";
 
 	//$por_descontado=$res_tot_glosa['valor']+$total_descuentos;
 
 	$descuento = $val_facturado-$por_descontado;
 	//echo $descuento."<br>";  
 	
 	
 	if($dat_asociado['nit_est_id']==1)
 	{
		if($res_dat_factura['fac_ano_servicio']==2017)
		{
			if($res_dat_factura['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
			{
				$adminbasica=$val_facturado*(5/100);//ADMINISTRACION BASICA
			}
			else//CALCULA LA ADMON NUEVA 5.5%
			{
				$adminbasica=$val_facturado*($dat_compe['dat_nom_gastos']/100);//ADMINISTRACION BASICA
			}
		}
		else//CALCULA LA ADMON NUEVA 5.5%
		{
			if($res_dat_factura['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
			{
				$adminbasica=$val_facturado*(5/100);//ADMINISTRACION BASICA
			}
			else//CALCULA LA ADMON NUEVA 5.5%		
			{
				$adminbasica=$val_facturado*($dat_compe['dat_nom_gastos']/100);//ADMINISTRACION BASICA
			}
		}
	
    	//$adminbasica=$val_facturado*($dat_compe['dat_nom_gastos']/100);
	
    	$adminExtraordinaria=$val_facturado*($dat_compe['dat_admonExtra']/100);
    	$administracion = $adminbasica+$adminExtraordinaria;
    	$educacion = $val_facturado*($dat_compe['dat_nom_educacion']/100);
        
 		//$adminbasica=$descuento*($dat_compe['dat_nom_gastos']/100);
 		//$adminExtraordinaria=$descuento*($dat_compe['dat_admonExtra']/100);
		//$administracion = $adminbasica+$adminExtraordinaria;
	 	//$educacion = $descuento*($dat_compe['dat_nom_educacion']/100);
 	}

	elseif($dat_asociado['nit_est_id']==3)
 	{
		if($res_dat_factura['fac_ano_servicio']==2017)
		{
			if($res_dat_factura['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
			{
				$adminbasica=$val_facturado*(5/100);//ADMINISTRACION BASICA
			}
			else//CALCULA LA ADMON NUEVA 5.5%
			{
				$adminbasica=$val_facturado*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
			}
		}
		else//CALCULA LA ADMON NUEVA 5.5%
		{
			if($res_dat_factura['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
			{
				$adminbasica=$val_facturado*(12/100);//ADMINISTRACION BASICA
			}
			else//CALCULA LA ADMON NUEVA 5.5%		
			{
				$adminbasica=$val_facturado*($dat_compe['dat_admonNoAfi']/100);//ADMINISTRACION BASICA
			}
		}
	
		//$adminbasica=$val_facturado*($dat_compe['dat_admonNoAfi']/100);
			
		$adminExtraordinaria=$val_facturado*($dat_compe['dat_admonNoAfiExtraordinaria']/100);
		//$adminExtraordinaria=$descuento*($dat_compe['dat_admonNoAfiExtraordinaria']/100);
		
		$administra=$adminbasica+$adminExtraordinaria;
		//echo "suma: ".$adminis."<br>";
		//echo "los gatos son: ".$gastos."<br>";
				
		if($dat_compe['dat_admonAdministacion']==0)
			$educacion=0;
		else
            $educacion=$val_facturado*($dat_compe['dat_admonAdministacion']/100);
			//$educacion=$descuento*($dat_compe['dat_admonAdministacion']/100);
		
		//echo "educa: ".$educacion."<br>";
		$administracion=$administra;
		//echo "total: ".$administracion."<br>";
		
		
		$retefuente = $ins_nomina->trae_cuentas_nomin_no($conse_nomina,$arreglo[$i],2,'23651501',$los_id);
		$res_compenasociado = $ins_nomina->trae_cuentas_nomin_no($conse_nomina,$arreglo[$i],2,'25051001',$los_id);
		
		if($res_compenasociado=="" || $res_compenasociado==0)//EL VALOR POR PAGAR ES NEGATIVO
		{
			$res_compenasociado=$ins_nomina->trae_cuentas_nomin_no($conse_nomina,$arreglo[$i],1,'25051001',$los_id);
			$res_compenasociado=$res_compenasociado*-1;
		}
	 
 	}

	$html.='<tr>';
		$html.='<td style="text-align:left;text-size:6">'.$dat_asociado['nits_num_documento'].'</td>';
		
		$html.='<td colspan="5" style="text-align:left;text-size:6">'.substr($dat_asociado['nombre'],0,12).'</td>';
		
		$html.='<td style="text-align: right">'.number_format($val_facturado,0).'</td>';
		$facturado+=$val_facturado;
		
		$html.='<td style="text-align: right">'.number_format($por_descontado,0).'</td>';
		$desc+=$por_descontado;
		
		$html.='<td style="text-align: right">'.number_format($val_facturado,0).'</td>';
		$descuen+=$val_facturado;
		
		$html.='<td style="text-align: right">'.number_format($fabspagado,0).'</td>';
		$fabs+=$fabspagado;
		
		$html.='<td style="text-align: right">'.number_format($fonretsindical,0).'</td>';
		$retSindical+=$fonretsindical;
		
		$html.='<td style="text-align: right">'.number_format($vacnompagada,0).'</td>';
		$vacaciones+=$vacnompagada;
		
		$html.='<td style="text-align: right">'.number_format($administracion,0).'</td>';
		$adminis+=$administracion;
		
		$html.='<td style="text-align: right">'.number_format($educacion,0).'</td>';
		$educa+=$educacion;
		
		$html.='<td style="text-align: right">'.number_format($segsocialnomcau,0).'</td>';
		$segSocial+=$segsocialnomcau;
		
		$html.='<td style="text-align: right">'.number_format($retefuente,0).'</td>';
		$rete+=$retefuente;
		
		$html.='<td style="text-align: right">'.number_format($descuento_factura,0).'</td>';
		$descu_fac+=$descuento_factura;
		
		$html.='<td style="text-align: right">'.number_format($valor_descuento,0).'</td>';
		$val_descu+=$valor_descuento;
		
		$html.='<td style="text-align: right">'.number_format($res_compenasociado,0).'</td>';
		$compensacion+=$res_compenasociado;
				
	$html.='</tr>';
	
$i++;
}

$html.='<tr><td colspan="19"><hr></td></tr>';


$html.='<tr>';
//$html.='<th>&nbsp;</th>';
$html.='<th colspan="6">TOTALES</th>';
$html.='<th style="text-align: right;">'.number_format($facturado,0).'</th>';
$html.='<th style="text-align: right">'.number_format($desc,0).'</th>';
$html.='<th style="text-align: right">'.number_format($descuen,0).'</th>';
$html.='<th style="text-align: right">'.number_format($fabs,0).'</th>';
$html.='<th style="text-align: right">'.number_format($retSindical,0).'</th>';
$html.='<th style="text-align: right">'.number_format($vacaciones,0).'</th>';
$html.='<th style="text-align: right">'.number_format($adminis,0).'</th>';
$html.='<th style="text-align: right">'.number_format($educa,0).'</th>';
$html.='<th style="text-align: right">'.number_format($segSocial,0).'</th>';
$html.='<th style="text-align: right">'.number_format($rete,0).'</th>';
$html.='<th style="text-align: right">'.number_format($descu_fac,0).'</th>';
$html.='<th style="text-align: right">'.number_format($val_descu,0).'</th>';
$html.='<th style="text-align: right">'.number_format($compensacion,0).'</th>';
$html.='</tr>';

$html.='</table>';


require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->set_paper ('a4','landscape'); 
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR
?>