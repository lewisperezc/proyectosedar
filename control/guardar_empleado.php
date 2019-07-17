<?php session_start();
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$ins_nits=new nits();

if(!empty($_POST['emp_pri_apellido']))//FORMULARIO 1
{
    $_SESSION['emp_pri_apellido']=$_POST['emp_pri_apellido'];$_SESSION['emp_seg_apellido']=$_POST['emp_seg_apellido'];$_SESSION['emp_nombres']=$_POST['emp_nombres'];
    $_SESSION['emp_tip_documento']=$_POST['emp_tip_documento'];
    
    $_SESSION['emp_num_documento']=$_POST['emp_num_documento'];$_SESSION['emp_fec_nacimiento']=$_POST['emp_fec_nacimiento'];$_SESSION['emp_genero']=$_POST['emp_genero'];
    $_SESSION['emp_est_civil']=$_POST['emp_est_civil'];$_SESSION['select1']=$_POST['select1'];
    
    $_SESSION['select2']=$_POST['select2'];$_SESSION['emp_dir_residencia']=$_POST['emp_dir_residencia'];$_SESSION['emp_tel_residencia']=$_POST['emp_tel_residencia'];
    $_SESSION['emp_num_celular']=$_POST['emp_num_celular'];$_SESSION['emp_cor_electronico']=$_POST['emp_cor_electronico'];
    
    $_SESSION['emp_cor_electronico_adicional']=$_POST['emp_cor_electronico_adicional'];
    if($_SESSION['emp_cor_electronico_adicional']==""){
    $_SESSION['emp_cor_electronico_adicional']=NULL;
    }
    $_SESSION['select3']=$_POST['select3'];$_SESSION['select4']=$_POST['select4'];
}
elseif(!empty($_POST['emp_tip_contrato']))//FORMULARIO 2
{
    $_SESSION['emp_tip_contrato']=$_POST['emp_tip_contrato'];$_SESSION['emp_per_pag_contrato']=$_POST['emp_per_pag_contrato'];$_SESSION['emp_sal_contrato']=$_POST['emp_sal_contrato'];
    $_SESSION['emp_fec_ini_contrato']=$_POST['emp_fec_ini_contrato'];$_SESSION['emp_fec_fin_contrato']=$_POST['emp_fec_fin_contrato'];$_SESSION['emp_aux_transporte']=$_POST['emp_aux_transporte'];
    $_SESSION['bonificacion']=$_POST['bonificacion'];$_SESSION['emp_cargo']=$_POST['emp_cargo'];$_SESSION['emp_estado']=$_POST['emp_estado'];
    $_SESSION['emp_tip_procedimiento']=$_POST['emp_tip_procedimiento'];
	
	if(isset($_POST['emp_pag_aux_transporte']))
		$_SESSION['emp_pag_aux_transporte']=1;
	else
		$_SESSION['emp_pag_aux_transporte']=2;
    
    //echo $_SESSION['emp_tip_procedimiento']."_";
    if($_SESSION['emp_tip_procedimiento']==1)
        $_SESSION['emp_por_ret_fuente']=0;
    else
        $_SESSION['emp_por_ret_fuente']=$_POST['emp_por_ret_fuente'];
       
    //echo $_SESSION['emp_por_ret_fuente']."_";
}

elseif(!empty($_POST['emp_banco']))//FORMULARIO 3
{
    $_SESSION['emp_banco']=$_POST['emp_banco'];$_SESSION['emp_tip_cuenta']=$_POST['emp_tip_cuenta'];$_SESSION['emp_num_cuenta']=$_POST['emp_num_cuenta'];
    $_SESSION['emp_eps']=$_POST['emp_eps'];$_SESSION['emp_arp']=$_POST['emp_arp'];$_SESSION['pensiones']=$_POST['pensiones'];
    $_SESSION['caja_compensacion']=$_POST['caja_compensacion'];$_SESSION['emp_tip_arl']=$_POST['emp_tip_arl'];
}

elseif(!empty($_POST['opcion']))//FORMULARIO 4
{   
    //$gua_cen_cos="";
    
    $_SESSION['emp_por_bonificacion']=0;
    $gua_empleado=$ins_nits->ins_empleado(strtoupper($_SESSION['emp_pri_apellido']." ".$_SESSION['emp_seg_apellido']),strtoupper($_SESSION['emp_nombres']),$_SESSION['emp_tip_documento'],
$_SESSION['emp_num_documento'],$_SESSION['emp_fec_nacimiento'],$_SESSION['emp_genero'],$_SESSION['emp_est_civil'],$_SESSION['select2'],strtoupper($_SESSION['emp_dir_residencia']),
$_SESSION['emp_tel_residencia'],$_SESSION['emp_num_celular'],strtoupper($_SESSION['emp_cor_electronico']),strtoupper($_SESSION['emp_cor_electronico_adicional']),$_SESSION['select4'],
$_SESSION['emp_tip_contrato'],$_SESSION['emp_per_pag_contrato'],$_SESSION['emp_sal_contrato'],$_SESSION['emp_fec_ini_contrato'],$_SESSION['emp_fec_fin_contrato'],$_SESSION['emp_estado'],
$_SESSION['bonificacion'],$_SESSION['emp_banco'],$_SESSION['emp_tip_cuenta'],$_SESSION['emp_num_cuenta'],$_SESSION['emp_eps'],$_SESSION['emp_arp'],$_SESSION['bonificacion'],
$_SESSION['pensiones'],$_SESSION['caja_compensacion'],$_SESSION['emp_aux_transporte'],$_SESSION['emp_cargo'],$_SESSION['emp_por_bonificacion'],$_SESSION['emp_tip_arl'],$_SESSION['emp_tip_procedimiento'],
$_SESSION['emp_por_ret_fuente'],$_SESSION['emp_pag_aux_transporte']);
    
    $con_ult_empleado=$ins_nits->con_ult_nit();
    $_SESSION['opcion']=$_POST['opcion'];
    //echo "Entra por este, el valor es: ".$_SESSION['opcion'];
    if($_SESSION['opcion']==1)
    {
        $_SESSION['emp_cen_cos_ciudad']=$_POST['emp_cen_cos_ciudad'];
        $i=0;
        while($i<sizeof($_SESSION['emp_cen_cos_ciudad']))
        {
            $gua_cen_cos=$ins_nits->agr_cen_cos_asociado($_SESSION['emp_cen_cos_ciudad'][$i],$con_ult_empleado);
            $i++;
        }
    }
    elseif($_SESSION['opcion']==2)
    {
        $_SESSION['emp_cen_cos_hospital']=$_POST['emp_cen_cos_hospital'];
        $i=0;
        while($i<sizeof($_SESSION['emp_cen_cos_hospital']))
        {
            $gua_cen_cos=$ins_nits->agr_cen_cos_asociado($_SESSION['emp_cen_cos_hospital'][$i],$con_ult_empleado);
            $i++;
        }
    }
    if($gua_empleado){
    ////////////////////////////////////LIMPIAR SESIONES////////////////////////////
    unset($_SESSION['emp_pri_apellido']);unset($_SESSION['emp_seg_apellido']);unset($_SESSION['emp_nombres']);unset($_SESSION['emp_tip_documento']);
    unset($_SESSION['emp_num_documento']);unset($_SESSION['emp_fec_nacimiento']);unset($_SESSION['emp_genero']);unset($_SESSION['emp_est_civil']);unset($_SESSION['select1']);
    unset($_SESSION['select2']);unset($_SESSION['emp_dir_residencia']);unset($_SESSION['emp_tel_residencia']);unset($_SESSION['emp_num_celular']);unset($_SESSION['emp_cor_electronico']);
    unset($_SESSION['emp_cor_electronico_adicional']);unset($_SESSION['select3']);unset($_SESSION['select4']);

    unset($_SESSION['emp_tip_contrato']);unset($_SESSION['emp_per_pag_contrato']);unset($_SESSION['emp_sal_contrato']);unset($_SESSION['emp_fec_ini_contrato']);
    unset($_SESSION['emp_fec_fin_contrato']);unset($_SESSION['emp_aux_transporte']);unset($_SESSION['bonificacion']);unset($_SESSION['emp_cargo']);unset($_SESSION['emp_estado']);
	unset($_SESSION['emp_pag_aux_transporte']);
    unset($_SESSION['emp_banco']);unset($_SESSION['emp_tip_cuenta']);unset($_SESSION['emp_num_cuenta']);unset($_SESSION['emp_eps']);unset($_SESSION['emp_arp']);
    unset($_SESSION['pensiones']);unset($_SESSION['caja_compensacion']);

    unset($_SESSION['opcion']);unset($_SESSION['emp_cen_cos_hospital']);unset($_SESSION['emp_cen_cos_ciudad']);
    
    echo "Empleado creado correctante.";
    }
    else
    {
        echo "Error al crear el empleado, intentelo de nuevo.";
    }
}
?>