<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
include_once('../clases/contrato.class.php');

$ins_contrato=new contrato();
$instancia_nits = new nits();

if($_POST['num_formulario']==1)
{
    $emp_apellidos = $_POST['emp_pri_apellido']." ".$_POST['emp_seg_apellido'];
    $emp_nombres = $_POST['emp_nombres'];
    $emp_tip_documento = $_POST['emp_tip_documento'];
    $emp_num_documento = $_POST['emp_num_documento'];
    $emp_fec_nacimiento = $_POST['emp_fec_nacimiento'];
    $emp_genero = $_POST['emp_genero'];
    $emp_est_civil = $_POST['emp_est_civil'];

    $emp_dir_residencia = $_POST['emp_dir_residencia'];
    $emp_tel_residencia = $_POST['emp_tel_residencia'];
    $emp_num_celular = $_POST['emp_num_celular'];
    $emp_cor_electronico = $_POST['emp_cor_electronico'];
    $emp_cor_electronico_adicional = $_POST['emp_cor_electronico_adicional'];

    $emp_ciu_nacimiento = $_POST['select2'];
    $emp_des_ubicacion_1 = 1;

    $emp_ciu_residencia = $_POST['select4'];
    $emp_des_ubicacion_2 = 2;

    $actualizar_datos_personales = $instancia_nits->act_dat_per_empleado(strtoupper($emp_apellidos),strtoupper($emp_nombres),$emp_tip_documento,$emp_num_documento,$emp_fec_nacimiento,$emp_genero,$emp_est_civil,strtoupper($emp_dir_residencia),$emp_tel_residencia,$emp_num_celular,strtoupper($emp_cor_electronico),strtoupper($emp_cor_electronico_adicional),$_SESSION['emp_id']);
	
	$con_ciu_nit=$instancia_nits->ConCiuPorNit($_SESSION['emp_id']);
	$num_filas=mssql_num_rows($con_ciu_nit);
	if($num_filas>0)
	{
    	$actualizar_ciudades_1_empleado = $instancia_nits->act_ciu_dep_empleado($emp_ciu_nacimiento,$emp_des_ubicacion_1,$_SESSION['emp_id']);
    	$actualizar_ciudades_2_empleado = $instancia_nits->act_ciu_dep_empleado($emp_ciu_residencia,$emp_des_ubicacion_2,$_SESSION['emp_id']);
	}
	else
	{
		$actualizar_ciudades_1_empleado = $instancia_nits->GuaCiuNit($_SESSION['emp_id'],$emp_ciu_nacimiento,1);
    	$actualizar_ciudades_2_empleado = $instancia_nits->GuaCiuNit($_SESSION['emp_id'],$emp_ciu_residencia,2);
	}

    if($actualizar_datos_personales&&$actualizar_ciudades_1_empleado&&$actualizar_ciudades_2_empleado)
        echo "Empleado actualizado correctamente.";
    else
        echo "Error al actualizar el empleado, Intentelo de nuevo.";
}

///////////////DATOS DEL CONTRARO///////////////
elseif($_POST['num_formulario']==2)
{
    $emp_tip_contrato=$_POST['emp_tip_contrato'];
    $emp_sal_contrato=ereg_replace("[.]","",$_POST['emp_sal_contrato']);
    $emp_estado=$_POST['emp_estado'];
    $emp_per_pag_contrato=$_POST['emp_per_pag_contrato'];
    $emp_fec_ini_contrato=$_POST['emp_fec_ini_contrato'];
    $emp_fec_fin_contrato=$_POST['emp_fec_fin_contrato'];
    $bonificacion=ereg_replace("[.]","",$_POST['bonificacion']);
    $emp_por_bonificacion=$_POST['emp_por_bonificacion'];
    if(trim($emp_por_bonificacion)=="")
        $emp_por_bonificacion=0;
    $emp_cen_cos_per_contrato=$_POST['emp_cen_cos_per_contrato'];
    $emp_aux_transporte=ereg_replace("[.]","",$_POST['emp_aux_transporte']);
    $emp_cargo=$_POST['emp_cargo'];
    $emp_tip_procedimiento=$_POST['emp_tip_procedimiento'];
    if($emp_tip_procedimiento==1)
        $emp_por_ret_fuente=0;
    else
        $emp_por_ret_fuente=$_POST['emp_por_ret_fuente'];
    
    
    if(isset($_POST['emp_pag_aux_transporte']))
    	$emp_pag_aux_transporte=1;
	else
		$emp_pag_aux_transporte=2;
	
    $actualizar_datos_contrato_1_empleado=$instancia_nits->act_dat_con_1_empleado($emp_tip_contrato,$emp_sal_contrato,$emp_estado,$emp_aux_transporte,$emp_cargo,$bonificacion,$emp_por_bonificacion,$emp_tip_procedimiento,$emp_por_ret_fuente,$emp_pag_aux_transporte,$_SESSION['emp_id']);

	
	$con_dat_contrato=$ins_contrato->ConDatConPorNit($_SESSION['emp_id']);
	$num_fil_contrato=mssql_num_rows($con_dat_contrato);
	if($num_fil_contrato>0)
	{
    	$actualizar_datos_contrato_2_empleado=$instancia_nits->act_dat_con_2_empleado($emp_per_pag_contrato,$emp_fec_ini_contrato,$emp_fec_fin_contrato,$_SESSION['emp_id']);
	}
	else
	{
		$actualizar_datos_contrato_2_empleado=$ins_contrato->GuaConEmpleado($_SESSION['emp_id'],$emp_per_pag_contrato,$emp_fec_ini_contrato,$emp_fec_fin_contrato,2);
	}
	
    if($actualizar_datos_contrato_1_empleado&&$actualizar_datos_contrato_2_empleado)
        echo "Empleado actualizado correctamente.";
    else
        echo "Error al actualizar el empleado, intentelo de nuevo.";
}
elseif($_POST['num_formulario']==3)
{
    $emp_banco=$_POST['emp_banco'];
    $emp_tip_cuenta=$_POST['emp_tip_cuenta'];
    $emp_num_cuenta=$_POST['emp_num_cuenta'];
    $emp_eps=$_POST['emp_eps'];
    $emp_arp=$_POST['emp_arp'];
    $emp_pension=$_POST['pensiones'];
    $emp_cesantias = $_POST['cesantias'];
    $emp_caj_compensacion=$_POST['caj_compensacion'];
	$emp_tip_arl=$_POST['emp_tip_arl'];

    $actualizar_datos_complementarios_empleado = $instancia_nits->act_dat_com_empleado($emp_banco,$emp_tip_cuenta,$emp_num_cuenta,$emp_eps,$emp_arp,$_SESSION['emp_id'],$emp_pension,$emp_cesantias,$emp_caj_compensacion,$emp_tip_arl);
    if($actualizar_datos_complementarios_empleado)
        echo "Empleado actualizado correctamente.";
    else
        echo "Error al actualizar el empleado, Intentelo de nuevo.";
}
elseif($_POST['num_formulario']==4)
{
    $nit_por_cen_cos_id=$_POST['valor'];
    $cen_cos_empleado=$_POST['cen_cos_empleado'];
    $opcion=$_POST['opcion'];
	
    if($opcion==1)
        $nue_centro=$_POST['emp_cen_cos_ciudad'];
    elseif($opcion==2)
        $nue_centro=$_POST['emp_cen_cos_hospital'];

    $i=0;
    while($i<sizeof($cen_cos_empleado))
    {
        $actualizar_centro_costos_asociado=$instancia_nits->act_dat_cen_cos_asociado(strtoupper($cen_cos_empleado[$i]),strtoupper($nit_por_cen_cos_id[$i]));
        $i++;
    }
    if($opcion!="")
    {
        $j=0;
        while($j<sizeof($nue_centro))
        {
            $guardar_nits_por_centros=$instancia_nits->agr_cen_cos_asociado(strtoupper($nue_centro[$j]),$_SESSION['emp_id']);
            $j++;
        }
    }
    if($actualizar_centro_costos_asociado||$guardar_nits_por_centros)
        echo "Empleado actualizado correctamente.";
    else
        echo "Error al actualizar el empleado, Intentelo de nuevo.";
}
?>