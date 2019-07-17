<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
include_once('../clases/contrato.class.php');
$ins_contrato=new contrato();
if($_POST['num_formulario']==1)
{
    $con_num_consecutivo=$_POST['con_num_consecutivo'];
    $ven_fac=$_POST['ven_fac'];
    $con_mon_fij_val_hor_diurna=$_POST['con_mon_fij_val_hor_diurna'];
    $con_mon_fij_val_hor_nocturna=$_POST['con_mon_fij_val_hor_nocturna'];
    $con_estado=$_POST['con_estado'];
    $fec_legalizado=$_POST['fec_legalizado'];
    $sel_tip_con_pre_servicios=$_POST['sel_tip_con_pre_servicios'];
    $observa=$_POST['observa'];
    
    $act_dat_contrato=$ins_contrato->ActDacConPrestacion($con_num_consecutivo,$ven_fac,$con_mon_fij_val_hor_diurna,$con_mon_fij_val_hor_nocturna,$con_estado,$fec_legalizado,$sel_tip_con_pre_servicios,$observa,$_SESSION['con_pre_id']);
    if($act_dat_contrato)
        echo "Contrato actualizado correctamente.";
    else
        echo "Error al actualizar el contrato, intentelo de nuevo.";
}
elseif($_POST['num_formulario']==2)
{
    $cant_campos=$_POST['cant_campos'];
    //echo "la cantidad de campos es: ".$cant_campos."___";
    $i=0;
    while($i<=$cant_campos)
    {
        if($_POST['tip_pol_impuesto'.$i]==1)
        {
            //echo "Entra por el if <br>";
            $guardar_poliza_contrato=$ins_contrato->agregar_otra_poliza_contrato($_SESSION['con_pre_id'],$_POST['con_nom_pol_aseguradora'.$i],$_POST['con_pol_nombre'.$i],ereg_replace("[.]","",$_POST['con_pol_porcentaje'.$i]),1,$_POST['obs_pol_impuesto'.$i]);
        }
        elseif($_POST['tip_pol_impuesto'.$i]==2)
        {
            //echo "Entra por el else <br>";
            $guardar_poliza_contrato=$ins_contrato->agregar_otra_poliza_contrato_informativo($_SESSION['con_pre_id'],$_POST['con_nom_pol_aseguradora'.$i],$_POST['con_pol_nombre'.$i],ereg_replace("[.]","",$_POST['con_pol_porcentaje'.$i]),$_POST['obs_pol_impuesto'.$i]);
        }
        $i++;
    }
    if($guardar_poliza_contrato)
        echo "Contrato actualizado correctamente.";
    else
        echo "Error al actualizar el contrato, intentelo de nuevo.";
}



/*$nue_estado=$_POST['con_estado'];
$con_id=$_SESSION['id_contrato'];
$cambiar_estado=$ins_contrato->CamEstContrato($nue_estado,$con_id);
if($cambiar_estado)
echo "<script>alert('Estado Del Contrato Actualizado Correctamente!!!');history.back(-1);</script>";
else
echo "<script>alert('Error Al Cambiar El Estado Del Contrato, Intentelo De Nuevo!!!');history.back(-1);</script>";*/
?>