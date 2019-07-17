<?php session_start();
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$ins_nits=new nits();

//echo "Encuentra el archivo";

if(!empty($_POST['pri_formulario']))//FORMULARIO 1
{	
    $_SESSION['aso_pri_apellido']=strtoupper($_POST['aso_pri_apellido']);$_SESSION['aso_seg_apellido']=strtoupper($_POST['aso_seg_apellido']);
    $_SESSION['aso_nombres']=strtoupper($_POST['aso_nombres']);$_SESSION['aso_tip_documento']=$_POST['aso_tip_documento'];
    $_SESSION['aso_num_documento']=$_POST['aso_num_documento'];$_SESSION['aso_nac_fecha']=$_POST['aso_nac_fecha'];
    $_SESSION['aso_genero']=$_POST['aso_genero'];$_SESSION['aso_est_civil']=$_POST['aso_est_civil'];$_SESSION['select1']=$_POST['select1'];
    $_SESSION['select2']=$_POST['select2'];$_SESSION['aso_dir_residencia']=strtoupper($_POST['aso_dir_residencia']);$_SESSION['aso_tel_residencia']=$_POST['aso_tel_residencia'];
    $_SESSION['aso_num_celular']=$_POST['aso_num_celular'];$_SESSION['aso_cor_electronico']=strtoupper($_POST['aso_cor_electronico']);
    $_SESSION['aso_cor_electronico_adicional']=strtoupper($_POST['aso_cor_electronico_adicional']);
    if(trim($_SESSION['aso_cor_electronico_adicional'])==""){ $_SESSION['aso_cor_electronico_adicional']='NULL'; }
    $_SESSION['aso_por_pabs']=$_POST['aso_por_pabs'];$_SESSION['select3']=$_POST['select3'];
    $_SESSION['select4']=$_POST['select4'];
    $_SESSION['aso_fon_vacaciones']=$_POST['aso_fon_vacaciones'];
    if(isset($_SESSION['aso_fon_vacaciones'])){ $_SESSION['aso_fon_vacaciones'] = "SI"; }
    else { $_SESSION['aso_fon_vacaciones'] = "NO"; }
    $_SESSION['aso_por_fon_vacaciones']=$_POST['aso_por_fon_vacaciones'];
    $_SESSION['aso_tip_procedimiento']=$_POST['aso_tip_procedimiento'];
    $_SESSION['aso_por_ret_fuente']=$_POST['aso_por_ret_fuente'];
	
	$_SESSION['aso_por_fon_retiro_sindical']=$_POST['aso_por_fon_retiro_sindical'];
	
    if(trim($_SESSION['aso_por_ret_fuente'])==""){ $_SESSION['aso_por_ret_fuente']='NULL'; }
	$_SESSION['aso_fec_afiliacion']=$_POST['aso_fec_afiliacion'];
}
elseif(!empty($_POST['seg_formulario']))//FORMULARIO 2
{
    $_SESSION['aso_banco']=$_POST['aso_banco'];$_SESSION['aso_tip_cuenta']=$_POST['aso_tip_cuenta'];$_SESSION['aso_num_cuenta']=$_POST['aso_num_cuenta'];
    $_SESSION['aso_eps']=$_POST['aso_eps'];$_SESSION['aso_arp']=$_POST['aso_arp'];$_SESSION['aso_tip_seg_social']=$_POST['aso_tip_seg_social'];
	
	$_SESSION['aso_mon_fij_seg_social']=$_POST['aso_mon_fij_seg_social'];
	$_SESSION['tipo_descuento_seg_social']=$_POST['tipo_descuento_seg_social'];
	if($_SESSION['tipo_descuento_seg_social'])
		$_SESSION['tipo_descuento_seg_social']=1;
	else
		$_SESSION['tipo_descuento_seg_social']=2;
}
elseif(!empty($_POST['ter_formulario']))//FORMULARIO 3
{
    $_SESSION['aso_per_cargo']=$_POST['aso_per_cargo'];$_SESSION['aso_num_hijos']=$_POST['aso_num_hijos'];$_SESSION['aso_estado']=$_POST['aso_estado'];
    $_SESSION['aso_afi_scare']=$_POST['aso_afi_scare'];$_SESSION['aso_sec_scare']=$_POST['aso_sec_scare'];$_SESSION['aso_fepasde']=$_POST['aso_fepasde'];
    $_SESSION['aso_caj_compensacion']=$_POST['aso_caj_compensacion'];
    $_SESSION['aso_fon_pen_obli']=$_POST['aso_fon_pen_obli'];
    if($_SESSION['aso_fon_pen_obli']=="SI"){ $_SESSION['fon_pen_obligatorio']=$_POST['fon_pen_obligatorio']; } else{ $_SESSION['fon_pen_obligatorio']="NULL"; }
    
    $_SESSION['aso_fon_pen_voluntaria']=$_POST['aso_fon_pen_voluntaria'];
    if($_SESSION['aso_fon_pen_voluntaria']=="SI"){ $_SESSION['fon_pen_vol']=$_POST['fon_pen_vol']; } else{ $_SESSION['fon_pen_vol']="NULL"; }
}
elseif(!empty($_POST['cua_formulario']))//FORMULARIO 4
{
    $_SESSION['cuantos_beneficiarios']=$_POST['cuantos_beneficiarios'];
    $i=0;
    while($i<=$_SESSION['cuantos_beneficiarios'])
    {
        $_SESSION['aso_ape_beneficiario'][$i]=strtoupper($_POST['aso_ape_beneficiario'.$i]);
        $_SESSION['aso_nom_beneficiario'][$i]=strtoupper($_POST['aso_nom_beneficiario'.$i]);
        $_SESSION['aso_parentesco'][$i]=$_POST['aso_parentesco'.$i];
        $_SESSION['aso_tip_doc_beneficiario'][$i]=$_POST['aso_tip_doc_beneficiario'.$i];
        $_SESSION['aso_num_doc_beneficiario'][$i]=$_POST['aso_num_doc_beneficiario'.$i];
        $_SESSION['aso_por_ben_beneficiario'][$i]=$_POST['aso_por_ben_beneficiario'.$i];
        $i++;
    }
}
elseif(!empty($_POST['qui_formulario']))//FORMULARIO 5
{
    $_SESSION['aso_uni_pregrado']=strtoupper($_POST['aso_uni_pregrado']);$_SESSION['aso_fec_pregrado']=$_POST['aso_fec_pregrado'];
    $_SESSION['aso_tit_gra_obtenido']=strtoupper($_POST['aso_tit_gra_obtenido']);$_SESSION['aso_ciu_pregrado']=$_POST['aso_ciu_pregrado'];
    
    $_SESSION['aso_uni_posgrado']=strtoupper($_POST['aso_uni_posgrado']);$_SESSION['aso_fec_posgrado']=$_POST['aso_fec_posgrado'];
    $_SESSION['aso_tit_pos_obtenido']=strtoupper($_POST['aso_tit_pos_obtenido']);$_SESSION['ciu_posgrado']=$_POST['ciu_posgrado'];
    
    $_SESSION['aso_uni_otros']=strtoupper($_POST['aso_uni_otros']);$_SESSION['aso_fec_otros']=$_POST['aso_fec_otros'];
    $_SESSION['aso_tit_otr_obtenido']=strtoupper($_POST['aso_tit_otr_obtenido']);$_SESSION['aso_ciu_otr_obtenido']=$_POST['aso_ciu_otr_obtenido'];
}
elseif(!empty($_POST['sex_formulario']))//FORMULARIO 6
{
    //GUARDAR LOS DATOS DEL AFILIADO
    $guardar_afiliado=$ins_nits->insertar_asociado($_SESSION['aso_pri_apellido']." ".$_SESSION['aso_seg_apellido'],$_SESSION['aso_nombres'],$_SESSION['aso_tip_documento'],
            $_SESSION['aso_num_documento'],$_SESSION['aso_nac_fecha'],$_SESSION['aso_genero'],$_SESSION['aso_est_civil'],$_SESSION['select2'],$_SESSION['aso_dir_residencia'],
            $_SESSION['aso_tel_residencia'],$_SESSION['aso_num_celular'],$_SESSION['aso_cor_electronico'],$_SESSION['aso_cor_electronico_adicional'],$_SESSION['select4'],
            $_SESSION['aso_por_pabs'],$_SESSION['aso_por_ret_fuente'],$_SESSION['aso_tip_procedimiento'],$_SESSION['aso_fon_vacaciones'],$_SESSION['aso_por_fon_vacaciones'],
            $_SESSION['aso_banco'],$_SESSION['aso_tip_cuenta'],$_SESSION['aso_num_cuenta'],$_SESSION['aso_eps'],$_SESSION['aso_arp'],$_SESSION['aso_tip_seg_social'],
            $_SESSION['aso_per_cargo'],$_SESSION['aso_num_hijos'],$_SESSION['aso_estado'],$_SESSION['aso_afi_scare'],$_SESSION['aso_sec_scare'],$_SESSION['aso_fepasde'],
            $_SESSION['aso_caj_compensacion'],$_SESSION['aso_fon_pen_obli'],$_SESSION['fon_pen_obligatorio'],$_SESSION['aso_fon_pen_voluntaria'],$_SESSION['fon_pen_vol'],$_SESSION['aso_fec_afiliacion'],
            $_SESSION['aso_mon_fij_seg_social'],$_SESSION['tipo_descuento_seg_social'],$_SESSION['aso_por_fon_retiro_sindical'],
            $_SESSION['aso_uni_pregrado'],$_SESSION['aso_fec_pregrado'],$_SESSION['aso_tit_gra_obtenido'],$_SESSION['aso_ciu_pregrado'],
            $_SESSION['aso_uni_posgrado'],$_SESSION['aso_fec_posgrado'],$_SESSION['aso_tit_pos_obtenido'],$_SESSION['ciu_posgrado'],
            $_SESSION['aso_uni_otros'],$_SESSION['aso_fec_otros'],$_SESSION['aso_tit_otr_obtenido'],$_SESSION['aso_ciu_otr_obtenido']);
    
    $j=0;
    while($j<sizeof($_POST['aso_cen_costos']))
    {
        $_SESSION['aso_cen_costos']=$_POST['aso_cen_costos'];
        $guardad_nits_por_centros=$ins_nits->insertar_nits_por_centro_costos($_SESSION['aso_cen_costos'][$j]);
        $j++;
    }
    
    $k=0;
    while($k<sizeof($_SESSION['aso_ape_beneficiario']))
    {
        $guardar_beneficiarios=$ins_nits->insertar_beneficiarios($_SESSION['aso_num_doc_beneficiario'][$k],strtoupper($_SESSION['aso_ape_beneficiario'][$k]),strtoupper($_SESSION['aso_nom_beneficiario'][$k]),$_SESSION['aso_tip_doc_beneficiario'][$k],$_SESSION['aso_por_ben_beneficiario'][$k],$_SESSION['aso_parentesco'][$k]);
	$k++;
    }
    
    if($guardar_afiliado)
    { echo "Afiliado creado correctamente."; }
    else
    { echo "Error al crear el afiliado, intentelo de nuevo."; }
        
}
?>