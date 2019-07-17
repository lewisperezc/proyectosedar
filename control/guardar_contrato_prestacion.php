<?php session_start();
@include_once('../clases/contrato.class.php');
@include_once('../clases/moviminetos_contables.class.php');
@include_once('clases/contrato.class.php');
@include_once('clases/moviminetos_contables.class.php');
$instancia_contrato=new contrato();
$ins_mov_contable=new movimientos_contables();



if(!empty($_POST['con_num_consecutivo']))//FORMULARIO 1
{
	$elcencosto="";
    //echo "entra al primero: ".$_POST['con_hospital'];
    $_SESSION['con_num_consecutivo']=$_POST['con_num_consecutivo'];$_SESSION['elhospital']=$_POST['con_hospital_seleccionado'];
    //echo "el hos es: ".$_SESSION['elhospital']."<br>";
	
    $_SESSION['con_vigencia']=ereg_replace("[.]","",$_POST['con_vigencia']);
	$_SESSION['con_vigencia']=ereg_replace("[$]","",$_SESSION['con_vigencia']);
	$_SESSION['con_vigencia']=ereg_replace("[,]","",$_SESSION['con_vigencia']);
	
	
	$_SESSION['con_valor']=ereg_replace("[.]","",$_POST['con_valor']);
	$_SESSION['con_valor']=ereg_replace("[$]","",$_SESSION['con_valor']);
	$_SESSION['con_valor']=ereg_replace("[,]","",$_SESSION['con_valor']);
	
    $_SESSION['con_cuo_mensual']=ereg_replace("[.]","",$_POST['con_cuo_mensual']);
	$_SESSION['con_cuo_mensual']=ereg_replace("[$]","",$_SESSION['con_cuo_mensual']);
	$_SESSION['con_cuo_mensual']=ereg_replace("[,]","",$_SESSION['con_cuo_mensual']);
	
	$_SESSION['ven_fac']=$_POST['ven_fac'];
	
    $_SESSION['con_mon_fij_val_hor_diurna']=ereg_replace("[.]","",$_POST['con_mon_fij_val_hor_diurna']);
	$_SESSION['con_mon_fij_val_hor_diurna']=ereg_replace("[$]","",$_SESSION['con_mon_fij_val_hor_diurna']);
	$_SESSION['con_mon_fij_val_hor_diurna']=ereg_replace("[,]","",$_SESSION['con_mon_fij_val_hor_diurna']);
	
	
	
    $_SESSION['con_mon_fij_val_hor_nocturna']=ereg_replace("[.]","",$_POST['con_mon_fij_val_hor_nocturna']);
	$_SESSION['con_mon_fij_val_hor_nocturna']=ereg_replace("[$]","",$_SESSION['con_mon_fij_val_hor_nocturna']);
	$_SESSION['con_mon_fij_val_hor_nocturna']=ereg_replace("[,]","",$_SESSION['con_mon_fij_val_hor_nocturna']);
	
	
	$_SESSION['con_fec_inicial']=$_POST['con_fec_inicial'];
    $_SESSION['con_fec_fin']=$_POST['con_fec_fin'];$_SESSION['con_estado']=$_POST['con_estado'];$_SESSION['fec_legalizado']=$_POST['fec_legalizado'];
    $_SESSION['sel_tip_con_pre_servicios']=$_POST['sel_tip_con_pre_servicios'];$_SESSION['con_observaciones']=$_POST['con_observaciones'];
    //Inicio Obtener el ID del NIT que seleccionan como centro de costos(hospital)
    
    if($_SESSION['elnit_id']=="")
    {
        $obt_nit=$instancia_contrato->obt_nit_id($_SESSION['elhospital']);
        $consulta=mssql_fetch_array($obt_nit);
        $_SESSION['elnit_id']=$consulta['nit_id'];
    }
    
    //Fin Obtener el ID del NIT que seleccionan como centro de costos(hospital)
    $elcencosto=$_SESSION['elhospital'];
    //echo "entra al primero: ".$elcencosto;
}
elseif(!empty($_POST['cant_campos']))
{
    //echo "el hos es: ".$_SESSION['elhospital']."<br>";
    if($_SESSION['elnit_id']=="")
    {
        $obt_nit=$instancia_contrato->obt_nit_id($_SESSION['elhospital']);
        $consulta=mssql_fetch_array($obt_nit);
        $_SESSION['elnit_id']=$consulta['nit_id'];
    }
    $_SESSION['cant_campos']=$_POST['cant_campos'];
    $i=1;
    while($i<=$_SESSION['cant_campos'])
    {
        $_SESSION['ary_con_nom_pol_aseguradora'][$i]=$_POST['con_nom_pol_aseguradora'.$i];
        $_SESSION['ary_con_pol_nombre'][$i]=$_POST['con_pol_nombre'.$i];
		
        $_SESSION['ary_con_pol_porcentaje'][$i]=ereg_replace("[.]","",$_POST['con_pol_porcentaje'.$i]);
		$_SESSION['ary_con_pol_porcentaje'][$i]=ereg_replace("[$]","",$_SESSION['ary_con_pol_porcentaje'][$i]);
		$_SESSION['ary_con_pol_porcentaje'][$i]=ereg_replace("[,]","",$_SESSION['ary_con_pol_porcentaje'][$i]);
		
		
        $_SESSION['ary_tip_pol_impuesto'][$i]=$_POST['tip_pol_impuesto'.$i];
        $_SESSION['ary_obs_pol_impuesto'][$i]=$_POST['obs_pol_impuesto'.$i];
        //echo "los datos: ".$_SESSION['ary_con_nom_pol_aseguradora'][$i]."_".$_SESSION['ary_con_pol_nombre'][$i]."_".$_SESSION['ary_con_pol_porcentaje'][$i]."_".$_SESSION['ary_tip_pol_impuesto'][$i]."_".$_SESSION['ary_obs_pol_impuesto'][$i]."<br>";        
        $i++;
    }
}
elseif(!empty($_POST['ult_frm']))
{
    //echo "el hos es: ".$_SESSION['elhospital']."<br>";
    if($_SESSION['elnit_id']=="")
    {
        $obt_nit=$instancia_contrato->obt_nit_id($_SESSION['elhospital']);
        $consulta=mssql_fetch_array($obt_nit);
        $_SESSION['elnit_id']=$consulta['nit_id'];
    }
    $guardar_contrato=$instancia_contrato->ins_con_prestacion($_SESSION['sel_tip_con_pre_servicios'],strtoupper($_SESSION['con_num_consecutivo']),$_SESSION['elnit_id'],$_SESSION['con_vigencia'],$_SESSION['con_valor'],$_SESSION['con_cuo_mensual'],
$_SESSION['con_fec_inicial'],$_SESSION['con_fec_fin'],$_SESSION['con_estado'],$_SESSION['con_estado'],strtoupper($_SESSION['con_observaciones']),$_SESSION['ven_fac'],$_SESSION['con_mon_fij_val_hor_diurna'],
$_SESSION['con_mon_fij_val_hor_nocturna'],$_SESSION['elhospital']);
    
    $k=0;
    while($k<=sizeof($_SESSION['ary_con_nom_pol_aseguradora']))
    {
        if($_SESSION['ary_tip_pol_impuesto'][$k]==1)
        {
            $guardar_poliza_contrato=$instancia_contrato->ins_pol_o_imp_por_con_prestacion($_SESSION['ary_con_pol_nombre'][$k],$_SESSION['ary_con_nom_pol_aseguradora'][$k],
$_SESSION['ary_con_pol_porcentaje'][$k],strtoupper($_SESSION['ary_obs_pol_impuesto'][$k]));
        }
        elseif($_SESSION['ary_tip_pol_impuesto'][$k]==2)
        {
            $guardar_poliza_contrato=$instancia_contrato->ins_pol_o_imp_info_por_con_prestacion($_SESSION['ary_con_pol_nombre'][$k],$_SESSION['ary_con_nom_pol_aseguradora'][$k],
$_SESSION['ary_con_pol_porcentaje'][$k],strtoupper($_SESSION['ary_obs_pol_impuesto'][$k]));
        }
        $k++;
    }
    
    $_SESSION['ult_frm']=$_POST['ult_frm'];
    $j=0;
    while($j<sizeof($_POST['aso_cen_costos']))
    {
        $_SESSION['aso_cen_costos'][$j]=$_POST['aso_cen_costos'][$j];
        $guardar_aso_por_cen_cos_contrato=$instancia_contrato->ins_aso_por_cen_cos_contrato($_SESSION['aso_cen_costos'][$j],$_SESSION['elhospital']);
        $j++;
    }
    
    if($guardar_contrato)
    {
        echo "Contrato creado correctamente.";
    }
    else
    {
        echo "Error al crear el contrato, intentelo de nuevo."; 
    }
	unset($_SESSION['con_num_consecutivo']);unset($_SESSION['elhospital']);unset($_SESSION['con_vigencia']);unset($_SESSION['con_valor']);
    unset($_SESSION['con_cuo_mensual']);unset($_SESSION['ven_fac']);unset($_SESSION['con_mon_fij_val_hor_diurna']);
    unset($_SESSION['con_mon_fij_val_hor_nocturna']);unset($_SESSION['con_fec_inicial']);
    unset($_SESSION['con_fec_fin']);unset($_SESSION['con_estado']);unset($_SESSION['fec_legalizado']);
    unset($_SESSION['sel_tip_con_pre_servicios']);unset($_SESSION['con_observaciones']);

    unset($_SESSION['cant_campos']);unset($_SESSION['ary_con_nom_pol_aseguradora']);unset($_SESSION['ary_con_pol_nombre']);
    unset($_SESSION['ary_con_pol_porcentaje']);unset($_SESSION['ary_tip_pol_impuesto']);unset($_SESSION['ary_obs_pol_impuesto']);

	unset($_SESSION['elnit_id']);
	
    unset($_SESSION['aso_cen_costos']);
}
?>