<?php
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/varios.class.php');

$ins_varios=new varios();
$ins_mov_contable = new movimientos_contables();
$nit = new nits();
$centro = new centro_de_costos();
$mes_pago=$_POST['mes'];
$mes = $_POST['mes']-1;
$ano = $_SESSION['elaniocontable'];
//$cuenta = '25301005,23809511,23809506,23809502';
$cuenta='61150510';
if($mes<=0)
{
	$mes = 12+$mes;
	$ano--;
}
$dias=cal_days_in_month(1,$mes,$ano);
$segSocial=$nit->seguridadNit($mes,$ano,$cuenta,$dias,1,1);

$sedar=$segSocial[0];
$nits_sedar=$nit->con_aso_por_id_estado(1,1);

$i=0;
$minimo = $nit->sal_minimo();
$tope_seg_social=$ins_varios->ConsultarDatosVariablesPorId(7);
$por_solida=1/100;
while($asoSedar=mssql_fetch_array($nits_sedar))
{	
	$res[$i]["nit_id"] = $asoSedar['nit_id'];
	$res[$i]["nit_cedula"] = $asoSedar['nits_num_documento'];
	$res[$i]["nombre"] = $asoSedar['nits_apellidos']." ".$asoSedar['nits_nombres'];
    
    $res[$i]["mes_servicio_factura"] = $mes;
    $res[$i]["mes_pago"] = $mes_pago;
    
    //INICIO FACTURAS QUE HICIERON BASE PARA EL PAGO DE LA SEGURIDAD SOCIAL
    $con_facturas=$ins_mov_contable->FacturasBaseSeguridadSocial($mes,$ano,$cuenta,$dias,$asoSedar['nit_id']);
    $num_filas=mssql_num_rows($con_facturas);
    $j=0;
    $facturas="";
    if($num_filas>0)
    {
        while($res_facturas=mssql_fetch_array($con_facturas))
        {
            $facturas.=$res_facturas['fac_id']."_";
            $j++;
        }
    
        $lista_facturas = substr ($facturas, 0, -1);
    }
    else
        $lista_facturas=0;
    
    $res[$i]["facturas"] = $lista_facturas;
    //INICIO FACTURAS QUE HICIERON BASE PARA EL PAGO DE LA SEGURIDAD SOCIAL
    
	$dat_porcentaje = $nit->tip_seguridad($asoSedar['nit_id']);
	$porcentaje=$nit->porSeguridad($dat_porcentaje);
	
    $segMinimo = $minimo*($porcentaje/100);
    
    
    $res[$i]["por_seg_social"] = $porcentaje;
    
	$dat_val_seg_social=$nit->MontoFijoSeguridadSocial($asoSedar['nit_id']);
	if($dat_val_seg_social['nit_mon_fij_seg_social']==1)//TIENE MONTO FIJO
	{
		if($sedar[$asoSedar['nit_id']]>$dat_val_seg_social['nit_val_seg_social'])//SI LA BASE QUE DA EL CALCULO ES MAYOR, NO SE TOMA EL MONTO FIJO
			$aso_valSedar=$sedar[$asoSedar['nit_id']];
		else
			$aso_valSedar=$dat_val_seg_social['nit_val_seg_social'];//SE TOMA EL MONTO FIJO
	}
	else
	{
		$aso_valSedar = $sedar[$asoSedar['nit_id']];
		if(!$aso_valSedar)
	   		$aso_valSedar = 0;
	}
	if($aso_valSedar>=$minimo)
	{
		$tope=round($minimo*$tope_seg_social['var_valor'],-2);
		if($aso_valSedar>=$tope)
			$a=$tope;
		else
			$a=round($aso_valSedar,-2);

		$res[$i]["pag_sedar"] = $a;
		
		$res[$i]["des_sedar"] = round($a*($porcentaje/100),-2);//$res[$i]["pag_sedar"]*($porcentaje/100);
			
		//SI LA SUMA DE COMPENSACIONES/NOMINAS CAUSADAS ES >= 4 SALARIOS MINIMOS SE LE CALCULA EL PORCENTAJE CORRESPONDIENTE A LA TABLA
		
		if((int)$res[$i]["pag_sedar"]/$minimo < 4)
		  $res[$i]["fon_solidaridad"]=0;
			
        elseif((int)$res[$i]["pag_sedar"]/$minimo >= 4 && (int)$res[$i]["pag_sedar"]/$minimo<16)
        {
			$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1/100);
            
            $res[$i]["fon_solidaridad"]=1;
        }
		elseif((int)$res[$i]["pag_sedar"]/$minimo >=16 && (int)$res[$i]["pag_sedar"]/$minimo <17)
        {
			$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.2/100);
            $res[$i]["fon_solidaridad"]=1.2;
        }	
		elseif((int)$res[$i]["pag_sedar"]/$minimo >=17 && (int)$res[$i]["pag_sedar"]/$minimo <18)
        {
			$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.4/100);
            $res[$i]["fon_solidaridad"]=1.4;
        }	
		elseif((int)$res[$i]["pag_sedar"]/$minimo >=18 && (int)$res[$i]["pag_sedar"]/$minimo <19)
        {
			$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.6/100);
            $res[$i]["fon_solidaridad"]=1.6;
        }	
		elseif((int)$res[$i]["pag_sedar"]/$minimo >=19 && (int)$res[$i]["pag_sedar"]/$minimo <20)
        {
			$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(1.8/100);
			$res[$i]["fon_solidaridad"]=1.8;
        }	
		elseif((int)$res[$i]["pag_sedar"]/$minimo >=20)
        {
			$res[$i]["des_sedar"] += $res[$i]["pag_sedar"]*(2/100);
            $res[$i]["fon_solidaridad"]=2;
        }
	}
	else//SI NO TIENE BASE SE PAGA SOBRE UN MINIMO
	{
		$res[$i]["pag_sedar"] = round($minimo,-2);
		$res[$i]["des_sedar"] = round(($minimo)*($porcentaje/100),-2);
			
	}
	$i++;
	//}
}
echo json_encode($res);
?>