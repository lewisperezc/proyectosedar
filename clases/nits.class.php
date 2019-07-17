<?php
@include_once('../conexion/conexion.php');
include_once('tipo_identificacion.class.php');
include_once('estado_civil.class.php');
include_once('ciudades.class.php');
include_once('tipo_cuenta_bancaria.class.php');
include_once('estado_nits.class.php');
include_once('nits_tipo.class.php');
include_once('centro_de_costos.class.php');
include_once('tipo_contrato_nits.class.php');
include_once('periodo_pago.class.php');
include_once('regimenes.class.php');
include_once('tipo_regimen.class.php');
include_once('bancos.class.php');
include_once('tipo_seguridad_social.class.php');
include_once('parentesco.class.php');
include_once('departamento.class.php');
include_once('moviminetos_contables.class.php');
include_once('usuario.class.php');
include_once('reporte_jornadas.class.php');
@include_once('varios.class.php');
@include_once('../varios.class.php');

@include_once('../inicializar_session.php');
@include_once('inicializar_session.php');
//ELIMINAR ESTUDIOS Y BENEFICIARIO

class nits
{
  private $cod_nit;
  private $nom_nit;
  //INICIO VARIABLES UTILIZADA PARA LOS REGIMENES
  private $regimen;
  private $tip_regimen;
  //FIN VARIABLES UTILIZADAS PARA LOS REGIMENES
  
  //INICIO VARIABLES PARA LOS BANCOS DE LOS NITS
  
  private $banco;
  
  //INICIO VARIABLES UTILIZADAS PARA LA CREACIÒN DE ASOCIADOS
  private $tip_nit;
  private $tip_identificacion;
  private $est_civil;
  private $ciudades;
  private $tip_cue_bancaria;
  private $est_nits;
  //FIN VARIABLES UTILIZADAS PARA LA CREACIÒN DE ASOCIADOS
  
  //INICIO VARIABLES UTILIZADAS PARA LA CREACIÒN DE EMPLEADOS
  private $cen_costos;
  private $tip_contrato_nit;
  private $per_pago;
 //FIN VARIABLES UTILIZADAS PARA LA CREACIÒN DE EMPLEADOS
 
  private $parentesco;
 
  private $tip_seg_social;
  
  private $dpto;
  private $movimientos;
  
  private $usuario;
  
  private $reporte_jornadas;
  
  private $varios;
  
  public function __construct()
  {
	$this->tip_identificacion = new tipo_identificacion();
	$this->est_civil = new estado_civil();
	$this->ciudades = new ciudades();
	$this->tip_cue_bancaria = new tipo_cienta_bancaria();
	$this->est_nits = new estado_nits();
	$this->tip_nit = new tipo_nit();
	$this->cen_costos = new centro_de_costos();
	$this->tip_contrato_nit = new tipo_contrato_nits();
	$this->per_pago = new periodo_pago();
	$this->regimen = new regimenes();
	$this->tip_regimen = new tipo_regimen();
	$this->banco = new bancos();
	$this->tip_seg_social = new tipo_seguridad_social();
	$this->parentesco = new parentesco();
	$this->dpto = new departamento();
	$this->movimientos = new movimientos_contables();
	$this->usuario = new usuario();
    $this->reporte_jornadas = new reporte_jornadas();
    $this->varios=new varios();
  }
  
  public function get_perfiles()
  {
	  return $this->usuario->get_perfiles();
  }
  
  public function buscar_departamentos(){
	  return $this->dpto->buscar_departamentos();
  }
  
  public function con_tod_parentescos(){
	  return $this->parentesco->con_tod_parentescos();
  }
  
  //INICIO CONSULTAR TODOS LOS TIPOS DE SEGURIDAD SOCIAL
  public function con_tip_seg_social()
  {
	  return $this->tip_seg_social->con_tip_seg_social();
  }
  //FIN CONSULTAR TODOS LOS TIPOS DE SEGURIDAD SOCIAL
  
  //INICIO CONSULTAR TODOS LOS TIPOS DE NITS
  public function con_tod_tip_nits()
  {
	return $this->tip_nit->con_tod_tip_nits();
  }
  //FIN CONSULTAR TODOS LOS TIPOS DE NITS
  
  //INICIO CONSULTAR LOS NITS POR TIPO EJEMPLO: NITS DE TIPO ARP O EPS
  public function con_tip_nit($id_tip_nit)
  {
	return $this->tip_nit->con_tip_nit($id_tip_nit);
  }
  
  public function con_tip_nit_eps($id_tip_nit,$nit_id)
  {
	return $this->tip_nit->con_tip_nit_eps($id_tip_nit,$nit_id);
  }
  //INICIO CONSULTAR LOS NITS POR TIPO EJEMPLO: NITS DE TIPO ARP O EPS
  public function con_tip_identificacion()
  {
	return $this->tip_identificacion->con_tip_identificacion();
  }
  public function con_est_civil()
  {
  	return $this->est_civil->con_est_civil();
  }
  public function consultar_ciudades()
  {
  	return $this->ciudades->consultar_ciudades();
  }
  public function con_tip_cuenta()
  {
  	return $this->tip_cue_bancaria->con_tip_cuenta();
  }
  public function con_est_nits()
  {
		return $this->est_nits->con_est_nits(); 
  }
  
  public function con_cen_cos_nit($nit_id)
  {
	  return $this->cen_costos->con_cen_cos_nit($nit_id);
  }
  //INICIO TRAER DATOS DE LA CLASE CENTRO_DE_COSTOS
  public function cen_cos_prin()
  {
	return $this->cen_costos->cen_cos_prin();
  }
  
  public function cons_centro_costos()
  {
	return $this->cen_costos->conultar_centro_costos();
  }
  
  public function conultar_centro_costos()
  {
	  return $this->cen_costos->conultar_centro_costos();
  }
  
  public function cen_cos_contrato()
  {
  	return $this->cen_costos->cen_cos_contrato();
  }
  
  public function con_cen_cos_ord_por_hospital()
  {
	  return $this->cen_costos->con_cen_cos_ord_por_hospital();
  }
  
  public function con_cen_cos_credito($id_asociado,$id_empleado)
  {
	  return $this->cen_costos->con_cen_cos_credito($id_asociado,$id_empleado);
  }
  
  public function con_cen_cos_credito2($per_cen_cos)
  {
	  return $this->cen_costos->con_cen_cos_credito2($per_cen_cos);
  }
  
  //
  public function cen_cos_con($cen_cos_id){
	  return $this->cen_costos->cen_cos_con($cen_cos_id);
  }
  //
  //FIN TRAER DATOS DE LA CLASE CENTRO_DE_COSTOS
  
  //INICIO TRAER DATOS DE LA CLASE TIPO_CONTRATO  
  public function con_tip_contrato()
  {
	return $this->tip_contrato_nit->con_tip_contrato();	
  }
  //FIN TRAER DATOS DE LA CLASE TIPO_CONTRATO  
  
  //INICIO TRAER DATOS DE LA CLASE PERIODO_PAGO
  public function con_per_pago()
  {
	return $this->per_pago->con_per_pago();	
  }
  //FIN TRAER DATOS DE LA CLASE PERIODO_PAGO  
  
  public function cen_cos_sec()
  {
	return $this->cen_costos->cen_cos_sec();
  }
  
    public function get_proveedores()
    {
     $prov = "SELECT nit_id,nits_num_documento,nits_nombres,nits_apellidos FROM nits WHERE tip_nit_id = 3 ORDER BY nits_apellidos ASC";
	 $lis_pro = mssql_query($prov);
	 return $lis_pro;
    }
  
  
  public function bus_datCompensacion()
  {
      return $this->reporte_jornadas->bus_datCompensacion();
  }
  
  public function FondoSolidaridad($base)
  {
        return $this->varios->FondoSolidaridad($base);
  }
  
//////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

  //INICIO METODOS PARA INSERTAR UN ASOCIADO
public function insertar_asociado($aso_apellidos,$aso_nombres,$aso_tip_documento,$aso_num_documento,$aso_nac_fecha,$aso_genero,
				$aso_est_civil,$aso_ciu_nacimiento,$aso_dir_residencia,$aso_tel_residencia,$aso_num_celular,$aso_cor_electronico,
				$aso_cor_electronico_adicional,$aso_ciu_residencia,$aso_por_pabs,$aso_por_ret_fuente,$nit_tip_procedimiento,$aso_fon_vacaciones,$aso_por_fon_vacaciones,$aso_banco,$aso_tip_cue_bancaria,
				$aso_num_cue_bancaria,$aso_eps,$aso_arp,$aso_tip_seg_social,$aso_per_cargo,$aso_num_hijos,$aso_estado,$aso_afi_scare,$aso_sec_scare,$aso_fepasde,$nit_caj_compensacion,
				$aso_cot_men_fon_pen_obligatoria,$nit_pensiones,$aso_cot_men_fon_pen_voluntaria,$nit_pen_voluntaria,$aso_fec_afiliacion,$aso_mon_fij_seg_social,$tipo_descuento_seg_social,$nit_por_fon_ret_sindical,
				$aso_uni_pregrado,$aso_fec_pregrado,$aso_tit_gra_obtenido,
				$aso_ciu_pregrado,$aso_uni_posgrado,$aso_fec_posgrado,$aso_tit_pos_obtenido,$aso_ciu_posgrado,$aso_uni_otros,$aso_fec_otros,$aso_tit_otr_obtenido,$aso_ciu_otr_obtenido)
  {
  	
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	
	  $nit_fec_creacion = date("d-m-Y");
	  $tip_nit=1;
	  $query="EXECUTE crearAsociado '$aso_apellidos','$aso_nombres',$aso_tip_documento,'$aso_num_documento','$aso_nac_fecha',$aso_genero,$aso_est_civil,$aso_ciu_nacimiento,'$aso_dir_residencia','$aso_tel_residencia','$aso_num_celular','$aso_cor_electronico','$aso_cor_electronico_adicional',$aso_ciu_residencia,$aso_por_pabs,$aso_por_ret_fuente,$nit_tip_procedimiento,'$aso_fon_vacaciones',$aso_por_fon_vacaciones,$aso_banco,$aso_tip_cue_bancaria,'$aso_num_cue_bancaria',$aso_eps,$aso_arp,$aso_tip_seg_social,$aso_per_cargo,$aso_num_hijos,$aso_estado,'$aso_afi_scare',$aso_sec_scare,'$aso_fepasde',$nit_caj_compensacion,'$aso_cot_men_fon_pen_obligatoria',$nit_pensiones,'$aso_cot_men_fon_pen_voluntaria',$nit_pen_voluntaria,'$nit_fec_creacion',$tip_nit,'$aso_fec_afiliacion','$aso_mon_fij_seg_social','$tipo_descuento_seg_social','$nit_por_fon_ret_sindical','$aso_uni_pregrado','$aso_fec_pregrado','$aso_tit_gra_obtenido',$aso_ciu_pregrado,'$aso_uni_posgrado','$aso_fec_posgrado','$aso_tit_pos_obtenido',$aso_ciu_posgrado,'$aso_uni_otros','$aso_fec_otros','$aso_tit_otr_obtenido',$aso_ciu_otr_obtenido,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	  //echo $query;
	  $ejecutar=mssql_query($query);
	   if($ejecutar)
			return $ejecutar;
		else
			return false;
  }
  
  public function insertar_nits_por_centro_costos($cen_cos_id)
  {
	   $query = "EXECUTE InsertAsoCenCos $cen_cos_id";
	   $ejecutar = mssql_query($query);
	   if($ejecutar)
			return $ejecutar;
	   else
			return false;
  }

  public function insertar_beneficiarios($aso_num_doc_beneficiario,
                                         $aso_ape_beneficiario,$aso_nom_beneficiario,
										 $aso_tip_doc_beneficiario,$aso_por_ben_beneficiario,$aso_parentesco)
  {
  		$query = "EXECUTE dataAsoBen $aso_num_doc_beneficiario,'$aso_ape_beneficiario','$aso_nom_beneficiario',
									 $aso_tip_doc_beneficiario,$aso_por_ben_beneficiario,$aso_parentesco";
		 
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
  }
  //FIN METODOS PARA INSERTAR UN ASOCIADO
  
  //INICIO ACTUALIZA BENEFICIARIOS AFILIADO
  public function actualizar_nuevos_beneficiarios($aso_num_doc_beneficiario,$aso_ape_beneficiario,$aso_nom_beneficiario,
  $aso_tip_doc_beneficiario,$aso_por_ben_beneficiario,$aso_parentesco,$nit_id)
  {
		
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
  		$query = "EXECUTE dataAsoBenActualiza $aso_num_doc_beneficiario,'$aso_ape_beneficiario','$aso_nom_beneficiario',
		$aso_tip_doc_beneficiario,$aso_por_ben_beneficiario,$aso_parentesco,$nit_id,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
		 
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
  }
  //FIN ACTUALIZA BENEFICIARIOS AFILIADO
  
  //INICIO METODOS PARA INSERTAR UN EMPLEADO
	public function ins_empleado($emp_apellidos,$emp_nombres,$emp_tip_documento,$emp_num_documento,$emp_fec_nacimiento,$emp_genero,$emp_est_civil,$emp_ciu_nacimiento,
$emp_dir_residencia,$emp_tel_residencia,$emp_num_celular,$emp_cor_electronico,$emp_cor_electronico_adicional,$emp_ciu_residencia,$emp_tip_contrato,$emp_per_pag_contrato,
$emp_sal_contrato,$emp_fec_ini_contrato,$emp_fec_fin_contrato,$emp_estado,$emp_bonificacion,$emp_banco,$emp_tip_cuenta,$emp_num_cuenta,$emp_eps,$emp_arp,$bonificacion,$pension,
$caj_compensacion,$emp_aux_transporte,$emp_cargo,$emp_por_bonificacion,$emp_tip_arl,$nit_tip_procedimiento,$nit_por_ret_fuente,$nit_pag_aux_transporte)
	{
		
		
		$usuario_actualizador=$_SESSION['k_nit_id'];
		$fecha_actualizacion=date('d-m-Y');
	
		$hora=localtime(time(),true);
		if($hora[tm_hour]==1)
			$hora_dia=23;
		else
			$hora_dia=$hora[tm_hour]-1;
		$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
		
		$query = "EXECUTE crearEmpleado '$emp_apellidos','$emp_nombres',$emp_tip_documento,'$emp_num_documento','$emp_fec_nacimiento',$emp_genero,$emp_est_civil,$emp_ciu_nacimiento,
'$emp_dir_residencia','$emp_tel_residencia','$emp_num_celular','$emp_cor_electronico','$emp_cor_electronico_adicional',$emp_ciu_residencia,$emp_tip_contrato,$emp_per_pag_contrato,
$emp_sal_contrato,'$emp_fec_ini_contrato','$emp_fec_fin_contrato',$emp_estado,$emp_bonificacion,$emp_banco,
$emp_tip_cuenta,'$emp_num_cuenta',$emp_eps,$emp_arp,$bonificacion,$pension,$caj_compensacion,$emp_aux_transporte,$emp_cargo,$emp_por_bonificacion,$emp_tip_arl,'$nit_tip_procedimiento','$nit_por_ret_fuente','$nit_pag_aux_transporte','$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
        //echo $query;
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
  //FIN METODOS PARA INSERTAR UN EMPLEADO
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //METODO PARA CONSULTAR LOS REGIMENES
  public function cons_regimen()
  {
	  return $this->regimen->cons_regimen();
  }
//METODO PARA CONSULTAR LOS TIPOS DE REGIMEN
   public function cons_tipo_regimen()
   {
	  return $this->tip_regimen->cons_tipo_regimen();
   }
//METODO PARA CONSULTAR EL BANCO DE UN NIT
	public function cons_bancos()
	{
	return $this->banco->cons_bancos();
	}
	
	public function ins_proveedor($nits_nombres,$nits_num_documento,$nits_representante,$tip_nit_id,$tip_ide_id,$reg_id,
          $tip_reg_id,$nit_por_ciu_id,$nits_dir_residencia,$nits_tel_residencia,$nits_contacto,
          $nits_cor_electronico,$nits_num_celular,$nits_ban_id,$tip_cue_ban_id,
          $nits_num_cue_bancaria,$diaPro)
 	{
 		
		
		$usuario_actualizador=$_SESSION['k_nit_id'];
		$fecha_actualizacion=date('d-m-Y');
	
		$hora=localtime(time(),true);
		if($hora[tm_hour]==1)
			$hora_dia=23;
		else
			$hora_dia=$hora[tm_hour]-1;
		$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
		
		
  		$sql="EXECUTE crearProveedor '$nits_nombres','$nits_num_documento','$nits_representante',$tip_nit_id,$tip_ide_id,$reg_id,
          $tip_reg_id,$nit_por_ciu_id,'$nits_dir_residencia','$nits_tel_residencia','$nits_contacto',
		  '$nits_cor_electronico','$nits_num_celular',$nits_ban_id,$tip_cue_ban_id,'$nits_num_cue_bancaria',$diaPro,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
		//echo $sql;
  		$ejecutar=mssql_query($sql);
  		return $ejecutar;		
 	}
	public function consul_nits($nit)
	{
		if(!empty($nit))
		{
			$dat_nit=explode("_",$nit,3);
			if(sizeof($dat_nit)>1)
			{
				$sql = "SELECT * FROM nits WHERE nit_id='$dat_nit[1]' ORDER BY nits_apellidos ASC";
				//echo "por aqui";
			}
			else
			{
				$sql = "SELECT * FROM nits WHERE nit_id='$nit' ORDER BY nits_apellidos ASC";
				//echo $sql;
				//echo "por el else";
			}
			$query = mssql_query($sql);
			if($query)
		   		return $query;
			else
		   		return false;
		}
		else
		 return false;
	}
	
	public function consultar($nit)
	{
		$sql = "SELECT * FROM nits WHERE nit_id = '$nit' ORDER BY nits_apellidos ASC";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false;
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//INICIO METODOS PARA CONSULTAR UN ASOCIADO
	public function con_dat_per_asociado($nit_id)
	{	
		$query = "EXECUTE consulAsociado $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_ciu_dep_asociado($des_ubi_id,$nit_id)
	{
		$query = "EXECUTE ConCiuAsociado $des_ubi_id,$nit_id";
    	$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_eps_asociado($nit_id)
	{
		$query = "EXECUTE ConEPSAsociado $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_fon_pen_asociado($nit_id){
		$query = "EXECUTE ConFonPenAsociado $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_fon_pen_vol_asociado($nit_id){
		$query = "EXECUTE ConFonPenVoluntariaAsociado $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_arp_asociado($nit_id)
	{
		$query = "EXECUTE ConARPAsociado $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_caj_com_asociado($nit_id){
		$query = "EXECUTE ConCajComAsociado $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_ben_asociado($nit_id)
	{
		$query = "EXECUTE ConBenAsociado $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_est_asociado_1($nit_id)
	{
		$query = "EXECUTE ConEstAsociado1 $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_est_asociado_2($nit_id)
	{
		$query = "EXECUTE ConEstAsociado2 $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_est_asociado_3($nit_id)
	{
		$query = "EXECUTE ConEstAsociado3 $nit_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_cen_cos_asociado($nit_id)
	{
		$query = "EXECUTE ConCenCosAsociado $nit_id";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

//FIN METODOS PARA CONSULTAR UN ASOCIADO

//INICIO METODOS PARA CONSULTAR UN EMPLEADO
	public function con_dat_per_empleado($emp_id)
	{
		$query = "EXECUTE ConsulEmpleado $emp_id";
		//echo $query;
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_ciu_dep_empleado($des_ubi_id,$emp_id)
	{
		$query = "EXECUTE ConCiuEmpleado $des_ubi_id,$emp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}

	public function con_eps_empleado($emp_id)
	{
		$query = "EXECUTE ConEPSEmpleado $emp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_caj_com_empleado($emp_id)
	{
		$query = "EXECUTE ConCajComEmpleado $emp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_arp_empleado($emp_id)
	{
		$query = "EXECUTE ConARPEmpleado $emp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
	public function con_pension_empleado($emp_id)
	{
		$query = "EXECUTE ConPensionEmpleado $emp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	
		public function con_cesantias_empleado($emp_id)
	{
		$query = "EXECUTE ConCesantiasEmpleado $emp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}	
//FIN METODOS PARA CONSULTAR UN EMPLEADO

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//INICIO  METODOS PARA ACTUALIZAR UN ASOCIADO
public function act_dat_per_asociado($aso_apellidos,$aso_nombres,$aso_tip_documento,$aso_num_documento,$aso_nac_fecha,$aso_genero,$aso_est_civil,$aso_dir_residencia,$aso_tel_residencia,$aso_num_celular,$aso_cor_electronico,$nit_cor_electronico_adicional,$aso_por_pabs,$nit_por_ret_fuente,$nits_fon_vacaciones,$nits_por_fon_vacaciones,$nit_por_seg_social,$nit_tip_procedimiento,$nit_por_fon_ret_sindical,$aso_fec_retiro,$aso_fec_afiliacion,$id_asociado)
{
	
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	
	$query = "EXECUTE ActuAsociciado1 '$aso_apellidos','$aso_nombres',$aso_tip_documento,'$aso_num_documento','$aso_nac_fecha',$aso_genero,$aso_est_civil,'$aso_dir_residencia','$aso_tel_residencia','$aso_num_celular','$aso_cor_electronico','$nit_cor_electronico_adicional','$aso_por_pabs','$nit_por_ret_fuente','$nits_fon_vacaciones',$nits_por_fon_vacaciones,$nit_por_seg_social,$nit_tip_procedimiento,'$nit_por_fon_ret_sindical','$aso_fec_retiro','$aso_fec_afiliacion',$id_asociado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return $ejecutar;
	else
		return false;
}

public function act_ciu_dep_1_asociado($ciu_id,$des_ubi_asociado,$id_asociado)
{
	$query_1="SELECT * FROM nits_por_ciudades WHERE des_ubi_id='$des_ubi_asociado' AND nit_id='$id_asociado'";
	$ejecutar_1=mssql_query($query_1);
	if($ejecutar_1)
	{
		$num_filas=mssql_num_rows($ejecutar_1);
		if($num_filas>0)
		{
			//echo "entra al if";
			$query="EXECUTE ActuCiuAsociado1 $ciu_id,$des_ubi_asociado,$id_asociado";
			$ejecutar=mssql_query($query);
			if($ejecutar)
				return $ejecutar;
			else
				return false;
		}
		else//GUARDARLO EN LA TABLA nits_por_ciudades
		{
			//echo "entra al else";
			$query_2="INSERT INTO nits_por_ciudades(nit_id,ciu_id,des_ubi_id) VALUES('$id_asociado','$ciu_id','$des_ubi_asociado')";
			$ejecutar_2=mssql_query($query_2);
			if($ejecutar_2)
				return $ejecutar_2;
			else
				return false;
		}
	}
	else
		return false;
}

public function act_dat_aso_asociado($aso_banco,$aso_tip_cue_bancaria,$aso_num_cue_bancaria,$aso_eps,$aso_arp,$aso_tip_seg_social,$aso_mon_fij_seg_social,$nit_mon_fij_seg_social,$nit_mes_ini_mon_fijo,$nit_ano_ini_mon_fijo,$nit_fec_act_ini_mon_fijo,$id_asociado)
{
	
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	
	$query = "EXECUTE ActuAsociado2 $aso_banco,$aso_tip_cue_bancaria,'$aso_num_cue_bancaria',$aso_eps,$aso_arp,$aso_tip_seg_social,$aso_mon_fij_seg_social,$nit_mon_fij_seg_social,$nit_mes_ini_mon_fijo,$nit_ano_ini_mon_fijo,'$nit_fec_act_ini_mon_fijo',$id_asociado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

public function act_dat_fam_asociado($nits_num_per_cargo,$nits_num_hijos,$nit_est_id,$nits_scare,$sec_scare,$nist_fepasde,$nit_cajaCompensacion,$nist_fon_pen_obligatoria,$nit_pensiones,$nits_fon_pen_voluntaria,$nit_pen_voluntaria,$id_asociado)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	
	$query = "EXECUTE ActuAsociado3 $nits_num_per_cargo,$nits_num_hijos,$nit_est_id,'$nits_scare',$sec_scare,'$nist_fepasde',$nit_cajaCompensacion,'$nist_fon_pen_obligatoria',$nit_pensiones,'$nits_fon_pen_voluntaria',$nit_pen_voluntaria,$id_asociado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

public function act_dat_ben_asociado($aso_ape_beneficiario,$aso_nom_beneficiario,$aso_tip_doc_beneficiario,$aso_num_doc_beneficiario,$aso_por_ben_beneficiario,$aso_parentesco,$aso_id_beneficiario)
{
	$query = "EXECUTE ActuAsociado4 '$aso_ape_beneficiario','$aso_nom_beneficiario',$aso_tip_doc_beneficiario,$aso_num_doc_beneficiario,$aso_por_ben_beneficiario,$aso_parentesco,$aso_id_beneficiario";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

//INICIO FALTA AGREGAR OTRO ASOCIADO//
public function insertar_otro_beneficiario($aso_ape_beneficiario_2,$aso_nom_beneficiario_2,$aso_tip_doc_beneficiario_2,$aso_num_doc_beneficiario_2,$aso_por_ben_beneficiario_2)
{
  		$query = "EXECUTE InsOtrBenAsociado '$aso_ape_beneficiario_2','$aso_nom_beneficiario_2',$aso_tip_doc_beneficiario_2,$aso_num_doc_beneficiario_2,$aso_por_ben_beneficiario_2";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return true;
		else
			return false;
}
public function insertar_otro_beneficiario2($id_asociado)
{
	$query = "EXECUTE InsOtrBenAsociado2 $id_asociado";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}
//FIN FALTA AGREGAR OTRO ASOCIADO

public function act_dat_edu_sup_asociado($aso_uni_pregrado,$aso_fec_pregrado,$aso_tit_gra_obtenido,$aso_ciu_pregrado,$aso_uni_posgrado,$aso_fec_posgrado,$aso_tit_pos_obtenido,$aso_ciu_posgrado,$aso_uni_otros,$aso_fec_otros,$aso_tit_otr_obtenido,$aso_ciu_otr_obtenido,$id_asociado)
{
	$query_1="SELECT * FROM estudios WHERE nit_id='$id_asociado'";
	$ejecutar_1=mssql_query($query_1);
	if($ejecutar_1)
	{
		$num_filas=mssql_num_rows($ejecutar_1);
		if($num_filas>0)
		{
			
			$usuario_actualizador=$_SESSION['k_nit_id'];
			$fecha_actualizacion=date('d-m-Y');
	
			$hora=localtime(time(),true);
			if($hora[tm_hour]==1)
				$hora_dia=23;
			else
			$hora_dia=$hora[tm_hour]-1;
			$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
			
			$query = "EXECUTE ActuAsociado5 '$aso_uni_pregrado','$aso_fec_pregrado','$aso_tit_gra_obtenido',$aso_ciu_pregrado,'$aso_uni_posgrado','$aso_fec_posgrado','$aso_tit_pos_obtenido',$aso_ciu_posgrado,'$aso_uni_otros','$aso_fec_otros','$aso_tit_otr_obtenido',$aso_ciu_otr_obtenido,$id_asociado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
			$ejecutar = mssql_query($query);
			if($ejecutar)
				return true;
			else
				return false;
		}
		else//HAY QUE GUARDARLO EN LA TABLA estudios
		{
			$query_2="INSERT INTO estudios(est_nom_uni_pregrado,est_fec_pregrado,est_tit_obt_pregrado,est_ciu_pregrado,est_nom_uni_posgrado,est_fec_posgrado,est_tit_obt_posgrado,est_ciu_posgrado,est_nom_uni_otros,est_fec_otros,est_tit_obt_otros,est_ciu_otros,nit_id)
			VALUES('$aso_uni_pregrado','$aso_fec_pregrado','$aso_tit_gra_obtenido','$aso_ciu_pregrado','$aso_uni_posgrado','$aso_fec_posgrado','$aso_tit_pos_obtenido','$aso_ciu_posgrado','$aso_uni_otros','$aso_fec_otros','$aso_tit_otr_obtenido','$aso_ciu_otr_obtenido','$id_asociado')";
			$ejecutar_2=mssql_query($query_2);
			
			
			if($ejecutar_2)
			{
				$tip_movimiento=2;//ES ACTUALIZACION
			
				$aud_nit_descripcion='DATOS EDUCACION SUPERIOR';
			
				$query_3="INSERT INTO AUDITORIA_NITS(aud_nit_usuario,aud_nit_fecha,aud_nit_hora,tip_mov_aud_id,
				aud_nit_descripcion)
				VALUES('$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion','$tip_movimiento',
				'$aud_nit_descripcion')";
				$ejecutar_3=mssql_query($query_3);
				
				if($ejecutar_3)
					return $ejecutar_3;
				else
					return false;
			}
			else
				return false;
		}
	}
	else
		return false;
}

public function agr_cen_cos_asociado($cen_cos_id,$asociado_id)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
		
	
	$query = "EXECUTE AgrCenCosAsociado $cen_cos_id,$asociado_id,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
        //echo $query;
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

public function act_dat_cen_cos_asociado($cen_cos_asociado,$id_asociado)
{
	
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	$query = "EXECUTE ActuAsociado6 $cen_cos_asociado,$id_asociado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}
//FIN METODOS PARA ACTUALIZAR UN ASOCIADO

//INICIO METODOS PARA ACTUALIZAR UN EMPLEADO
public function act_dat_per_empleado($emp_apellidos,$emp_nombres,$emp_tip_documento,$emp_num_documento,$emp_fec_nacimiento,$emp_genero,$emp_est_civil,$emp_dir_residencia,$emp_tel_residencia,$emp_num_celular,$emp_cor_electronico,$nits_cor_electronico_adicional,$id_empleado)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	$query = "EXECUTE ActuEmpleado1 '$emp_apellidos','$emp_nombres',$emp_tip_documento,'$emp_num_documento','$emp_fec_nacimiento',$emp_genero,$emp_est_civil,'$emp_dir_residencia','$emp_tel_residencia','$emp_num_celular','$emp_cor_electronico','$nits_cor_electronico_adicional',$id_empleado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

public function act_ciu_dep_empleado($emp_ciu,$emp_des_ubicacion,$id_empleado)
{
	$query = "EXECUTE ActuCiuEmpleado $emp_ciu,$emp_des_ubicacion,$id_empleado";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return $ejecutar;
	else
		return false;
}


public function act_dat_con_1_empleado($emp_tip_contrato,$emp_sal_contrato,$emp_estado,$emp_aux_transporte,$emp_cargo,$nit_bonificacion,$nit_por_bonificacion,$nit_tip_procedimiento,$nit_por_ret_fuente,$nit_pag_aux_transporte,$id_empleado)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	
	$query = "EXECUTE ActuEmpleado2 $emp_tip_contrato,$emp_sal_contrato,$emp_estado,$emp_aux_transporte,$emp_cargo,$nit_bonificacion,$nit_por_bonificacion,$nit_tip_procedimiento,$nit_por_ret_fuente,$nit_pag_aux_transporte,$id_empleado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	//echo $query;
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

public function act_dat_con_2_empleado($emp_per_pag_contrato,$emp_fec_ini_contrato,$emp_fec_fin_contrato,$id_empleado)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	
	$query = "EXECUTE ActuContEmpleado $emp_per_pag_contrato,'$emp_fec_ini_contrato','$emp_fec_fin_contrato',$id_empleado,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	//echo $query;
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

public function act_dat_con_3_empleado($emp_cen_cos_per_contrato,$id_empleado)
{
	$query = "EXECUTE ActuCenCosEmpleado $emp_cen_cos_per_contrato,$id_empleado";
	$ejecutar = mssql_query($query);
	if($ejecutar)
		return true;
	else
		return false;
}

public function act_dat_com_empleado($emp_banco,$emp_tip_cuenta,$emp_num_cuenta,$emp_eps,$emp_arp,$id_empleado,$pens,$cesa,$caj_compensacion,$emp_tip_arl)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	$query = "EXECUTE ActuEmpleado3 $emp_banco,$emp_tip_cuenta,'$emp_num_cuenta',$emp_eps,$emp_arp,$id_empleado,$pens,$caj_compensacion,$emp_tip_arl,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar = mssql_query($query);
	return $ejecutar;
}
//FIN METODOS PARA ACTUALIZAR UN EMPLEADO

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function consulProveedor($tipo,$nit){
	$sql="EXECUTE consulProveedor $tipo,'$nit'";
	$ejecutar= mssql_query($sql);
    return $ejecutar;
}
public function con_aso_por_id_estado($tip_nit_id,$nit_estado)
{
	$query = "SELECT nit_id,nits_nombres,nits_apellidos,nits_salario,nits_num_documento FROM dbo.nits 
			  WHERE tip_nit_id IN ($tip_nit_id) AND nit_est_id IN($nit_estado) ORDER BY nits_apellidos";
	$ejecutar = mssql_query($query);
	return $ejecutar;
}


public function con_nit_codeudor($tip_nit_id,$nit_estado,$nit_id)
{
	$query = "SELECT nit_id,nits_nombres,nits_apellidos,nits_salario FROM dbo.nits 
			  WHERE tip_nit_id = $tip_nit_id AND nit_est_id = $nit_estado AND nit_id NOT IN($nit_id) ORDER BY nits_apellidos ASC";
	$ejecutar = mssql_query($query);
	return $ejecutar;
}


//actualizar proveedor
public function actualizarProveedor($nits_nombres,$nits_num_documento,$nits_representante,$tip_ide_id,$reg_id,$tip_reg_id,$nits_dir_residencia,$nits_tel_residencia,$nits_contacto,$nits_cor_electronico,$nits_num_celular,$nits_ban_id,$tip_cue_ban_id,$nits_num_cue_bancaria,$tip_nit_id,$nit_id,$diaPro)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	
	$sql="EXECUTE actualizaProveedor '$nits_nombres','$nits_num_documento','$nits_representante',$tip_ide_id,$reg_id,$tip_reg_id,'$nits_dir_residencia','$nits_tel_residencia','$nits_contacto','$nits_cor_electronico','$nits_num_celular',$nits_ban_id,$tip_cue_ban_id,'$nits_num_cue_bancaria',$tip_nit_id,$nit_id,$diaPro,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	//echo $sql;
	$ejecutar=mssql_query($sql);
	if($ejecutar)
		return $ejecutar;
	else
		return false;
}
//metodo para crear hospitales
public function CrearHospital($raz,$num_nit,$nucleo,$cod_cen_costo,$reg,$tip_reg,$ciudad,$direccion,$telefono,$fax,$representante,$correo,$contacto,$tip_ide_id,$principal,$tip_hosp,$cuenta,$clase,$uni_funcional)
{
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
		
	$sql="EXECUTE CrearHospital '$raz','$num_nit',$nucleo,'$cod_cen_costo',$reg,$tip_reg,$ciudad,'$direccion','$telefono','$fax','$representante','$correo','$contacto',$tip_ide_id,$principal,$tip_hosp,$cuenta,$clase,'$uni_funcional','$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar=mssql_query($sql) or die("no se puede crear el hospital");
	return $ejecutar;
		  
}
public function consultar_centro_costo($ciudad){
		return $this->cen_costos->centro_costo_ciudad($ciudad);
	}
//METODO PARA CONSULTAR HOSPITAL POR NITS O POR RAZON O POR CENTRO DE COSTO	
public function consultar_hospital($raz,$nit,$tip_id){
 	if($raz!="")
		$sql="SELECT * FROM  NITS WHERE  NITS_NOMBRES LIKE ('%$raz%') AND TIP_NIT_ID=$tip_id ORDER BY nits_apellidos ASC";
	if($nit!="")
		$sql="SELECT nit_id,nits_num_documento FROM NITS WHERE TIP_NIT_ID=$tip_id AND NITS_NUM_DOCUMENTO LIKE ('%$nit%')";
	 $ejecutar = mssql_query($sql) or die ("no se puede realizar la consulta");
	return $ejecutar;	 	
}
//METODO PARA CONSULTAR UN HOSPITAL EXACTO
public function consulta_hospital_exacto($tipo,$nit)
{
	$sql="EXECUTE consultar_hospital $tipo,$nit";
	$ejecutar=mssql_query($sql) or die ("no se puede realizar la consulta");
	return $ejecutar;
}
public function consulta_hospital_exacto2($nit)
{
	$query = "EXECUTE consultar_hospita2 $nit";
	$ejecutar = mssql_query($query);
	return $ejecutar;
}
//METODO PARA ACTUALIZAR HOSPITAL
public function actualizar_hospital($nits_nombres,$nits_num_documento,$reg_id,$tip_reg_id,$nits_dir_residencia,$nits_tel_residencia,$nits_num_celular,$nits_representante,$nits_cor_electronico,$nits_contacto,$cen_cos_codigo,$nit_id,$clase,$nit_uni_funcional)
{
	
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
	
	$sql="EXECUTE actualizar_hospital '$nits_nombres','$nits_num_documento',$reg_id,$tip_reg_id,'$nits_dir_residencia','$nits_tel_residencia','$nits_num_celular','$nits_representante','$nits_cor_electronico','$nits_contacto','$cen_cos_codigo',$nit_id,$clase,'$nit_uni_funcional','$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar=mssql_query($sql);
	return $ejecutar;	
}
//METODO PARA CREAR NITS GENERALES
public function crear_nits_general($raz,$nit,$regimen,$tip_regimen,$ciudad,$dir,$tel,$cel,$contacto,$correo,$tip_id)
{
	
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];		
		
	
     $sql="EXECUTE crear_nits_general '$raz','$nit',$regimen,$tip_regimen,$ciudad,'$dir','$tel','$cel','$contacto','$correo',$tip_id,'$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	$ejecutar= mssql_query($sql) or die ("no se puede crear el nit");
	return $ejecutar;
}

  public function compra_pabs($nit,$valor)
  {
	$sql = "EXECUTE compra_pabs $nit,$valor";
	$query = mssql_query($sql);
	if($query)
	   return true;
	else
	   return false;
  }
  public function con_nits_generales($nit){
	$sql=" EXECUTE Consultar_nit_general $nit";
	$ejecutar= mssql_query($sql);
	return $ejecutar;
  }
  
  public function con_Nit_Gen_Exacto($nit_id){
	  $sql="EXECUTE consulNitGeneralExacto $nit_id";
	  $ejecutar = mssql_query($sql);
	  return $ejecutar;
  }
  
  public function tip_hospital()
  {
	  $sql = "SELECT * FROM tipo_hospital";
	  $query = mssql_query($sql);
	  if($query)
	    return $query;
	  else
	    return $false;	
  }
  
  public function gua_tip_hos($nombre,$cuenta)
  {
	  $sql = "INSERT INTO tipo_hospital(tip_hos_nombre,tip_hos_cue_pagar) VALUES ('$nombre',$cuenta)";
	  $query = mssql_query($sql);
	  if($query)
	    return true;
	  else
	    return false;
  }
  public function cons_nits()
  {
	  $query = "SELECT * FROM nits ORDER BY nits_apellidos ASC";
	  $ejecutar = mssql_query($query);
	  return $ejecutar;
  }
  public function actualizar_nit_gen($raz,$nit,$regimen,$tip_regimen,$dir,$tel,$cel,$contacto,$correo,$tip_id,$nit_id,$ciudad)
  {
				
			
	$usuario_actualizador=$_SESSION['k_nit_id'];
	$fecha_actualizacion=date('d-m-Y');
	
	$hora=localtime(time(),true);
	if($hora[tm_hour]==1)
		$hora_dia=23;
	else
		$hora_dia=$hora[tm_hour]-1;
	$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];	
	
  		
  	
	  $sql1="EXECUTE actualizaNitGeneral '$raz','$nit','$regimen','$tip_regimen','$dir','$tel','$cel','$contacto','$correo','$tip_id','$nit_id','$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
	  $ejecutar= mssql_query($sql1);
	  
	  $query="SELECT * FROM nits_por_ciudades WHERE nit_id='$nit_id' AND des_ubi_id=1";
	  //echo $query;
	  $eje_nit_por_ciudades=mssql_query($query);
	  $num_filas=mssql_num_rows($eje_nit_por_ciudades);
	  
	  if($num_filas>=1)
	  {
	  	$sql2="update nits_por_ciudades  set ciu_id='$ciudad',des_ubi_id=1 where nit_id='$nit_id'";
		$ejecutar2= mssql_query($sql2);
	  }
	  else
	  {
	  	$sql2="INSERT INTO nits_por_ciudades(ciu_id,des_ubi_id,nit_id) VALUES('$ciudad',1,'$nit_id')";
		$ejecutar2= mssql_query($sql2);
	  }
	  
	  return $ejecutar;
  }
	  
  public function aso_emp()
   {
	 $sql1="SELECT * FROM nits WHERE tip_nit_id in (1,2) ORDER BY nits_apellidos ASC";
	 $ejecutar2= mssql_query($sql1);
	 if($ejecutar2)
	   return $ejecutar2;
	 else  
	   return false;
   }
   
   public function ConProFondo($tipos)
   {
	 $sql="SELECT nit_id,nits_nombres,nits_apellidos,nits_num_documento FROM nits WHERE tip_nit_id IN($tipos) ORDER BY nits_num_documento ASC"; 
	 $ejecutar= mssql_query($sql);
	 if($ejecutar)
	   return $ejecutar;
	 else  
	   return false;
   }
   
  public function pabs_asociado($nit_centro)
  {
	  $sql = "SELECT nit.nits_por_pabs pabs, nit.nits_fon_vacaciones vac, nit.nit_por_fon_vacaciones porce
	  FROM nits nit
	  INNER JOIN nits_por_cen_costo npcc
	  ON npcc.nit_id = nit.nit_id WHERE nit.nit_id = $nit_centro";  
	  $query = mssql_query($sql);
	  if($query)
		  return $query;
	  else
	   return false;
  }
  
  public function est_asociado($npcc)
  {
	  $sql = "SELECT nit.nit_est_id estado FROM nits nit INNER JOIN dbo.nits_por_cen_costo npcc
  			  ON npcc.nit_id = nit.nit_id WHERE nit.nit_id = $npcc";	    
	  $query = mssql_query($sql);
	  if($query)
	    {
			$dat_query = mssql_fetch_array($query);
			if(!$dat_query['estado'])
			  {
				$sql = "SELECT nit.nit_est_id estado FROM nits nit INNER JOIN dbo.nits_por_cen_costo npcc
  			     		ON npcc.nit_id = nit.nit_id WHERE npcc.id_nit_por_cen = $npcc";
	  			$query = mssql_query($sql);
	  			if($query)
				  {
					  $dat_query = mssql_fetch_array($query);
					  return $dat_query['estado'];
				  }
		      }
	        else
	          return $dat_query['estado'];
          }
	   else
	     return false;
  }
  
  public function nit_centro($nit_cc)
  {
	  $sql = "SELECT nit.nit_id nit_id, nit.nits_num_documento doc, cc.cen_cos_id cc_id,cc.cen_cos_nombre cc_nombre,
	  		  nit.nits_nombres+' '+nit.nits_apellidos nom_completo  
			  FROM nits nit INNER JOIN nits_por_cen_costo npcc on nit.nit_id=npcc.nit_id 
			  INNER JOIN centros_costo cc on cc.cen_cos_id = npcc.cen_cos_id WHERE cc.cen_cos_id = $nit_cc ORDER BY nits_apellidos ASC";/*nit.nit_id = $nit_cc";*/
	  $query = mssql_query($sql);
	  if($query)
	  {
		$dat = mssql_fetch_array($query);
		if($dat)
		  return $query;
		else
		{
			$sql = "SELECT nit.nit_id nit_id, nit.nits_num_documento doc, cc.cen_cos_id cc_id,cc.cen_cos_nombre cc_nombre,
	  		  nit.nits_nombres+' '+nit.nits_apellidos nom_completo  
			  FROM nits nit INNER JOIN nits_por_cen_costo npcc on nit.nit_id=npcc.nit_id 
			  INNER JOIN centros_costo cc on cc.cen_cos_id = npcc.cen_cos_id 
			  WHERE cc.cen_cos_id = $nit_cc ORDER BY nits_apellidos ASC";/*npcc.id_nit_por_cen = $nit_cc";*/
			  $query = mssql_query($sql);
			  if($query)
			    return $query;
			  else
			    return false;
		}
	  }
	  else
	    return false;
  }
  
  public function cons_nombres_nit($id)
  {
	 $query="SELECT nits_nombres+' '+ISNULL(nits_apellidos,'') nombres,nits_num_documento
	 		 FROM nits WHERE nit_id = $id ORDER BY nits_apellidos ASC";
	 //echo $query;
	 $ejecutar = mssql_query($query);
	 if($ejecutar)
	 	return $ejecutar;
	 else
	 	return false;
  }
  
  public function consultar_segSocial()
  {
	  $sql = "SELECT * FROM tip_segSocial";
	  $query = mssql_query($sql);
	  if($query)
	    return $query;
	  else
	    return false;
  }
  
  public function por_segSocial_nit($nit,$valor,$minimo)
  {
	  if($minimo > 0 && $valor > ($minimo*25))
	     $valor = $minimo*25;
	  $sql = "SELECT tsc.tip_segSoc_porcentaje porcentaje FROM tip_segSocial tsc INNER JOIN nits nit ON 
	  		  tsc.tip_segSoc_id = nit.nit_tip_segSocial WHERE nit.nit_id=$nit";		  	  
	  $query = mssql_query($sql);
	  if($query)
	   {
		   $dat_por = mssql_fetch_array($query);
		   $seg_social = ($valor)/(1+($dat_por['porcentaje']/100));
		   return $seg_social;
	   }
  }
  
  public function por_segSocial_min($nit,$valor)
  {
	  $sql = "SELECT tsc.tip_segSoc_porcentaje porcentaje FROM tip_segSocial tsc INNER JOIN nits nit ON 
	  		  tsc.tip_segSoc_id = nit.nit_tip_segSocial WHERE nit.nit_id=$nit";
	  $query = mssql_query($sql);
	  if($query)
	   {
		   $dat_por = mssql_fetch_array($query);
		   $seg_social = ($valor)*($dat_por['porcentaje']/100);
		   return $seg_social;
	   }
  }
  
  public function con_nit_con_cre_cre_registrado($tip_nit,$est_nit)
  {
	  $query = "SELECT DISTINCT nit.nit_id,nit.nits_nombres,nit.nits_apellidos 
                FROM dbo.nits nit INNER JOIN dbo.creditos as cre ON nit.nit_id = cre.nit_id
                WHERE tip_nit_id = $tip_nit AND nit_est_id IN($est_nit) ORDER BY nits_apellidos ASC";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  return $ejecutar;
	  else
	  return false;
  }
  
  public function nom_nits($identificacion)
  {
	  $sql = "SELECT nits_nombres,nits_apellidos FROM nits WHERE nits_num_documento = '$identificacion' ORDER BY nits_apellidos ASC";
	  $query = mssql_query($sql);
	  if($query)
	  {
		  $row = mssql_fetch_array($query);
		  return $row['nits_nombres']." ".$row['nits_apellidos'];
	  }
	  else
	    return false;
  }
  
  public function sal_minimo()
  {
	  $sql = "SELECT var_valor FROM variables WHERE var_id = 1";
	  $query = mssql_query($sql);
	  if($query)
	    {
			$row = mssql_fetch_array($query);
			return $row['var_valor'];
		}
	  else
	    return false;	
  }
  
	public function act_pabs($nit,$valor)
  	{
		$sql = "UPDATE nits SET nits_can_pabs = nits_can_pabs+$valor WHERE nit_id = $nit";
	  	$query = mssql_query($sql);
	  	if($query)
	    	return true;
	  	else
		    return false;	 
  	}
  
  public function con_nit_contrato($tip_nit,$est_1,$est_2)
  {
	$query = "SELECT nit_id,nits_nombres,nits_apellidos,nits_salario,nits_num_documento
			  FROM dbo.nits
			  WHERE tip_nit_id = $tip_nit AND nit_est_id IN($est_1,$est_2)
			  ORDER BY nits_apellidos ASC";
			  //echo $query;
	$ejecutar = mssql_query($query);
	if($ejecutar)
	return $ejecutar;
	else
	return false;
  }
  /*INICIO ACTUALIZAR ESTADO NIT*/
  public function act_est_nit($est_id,$nit_id)
  {
	  $query = "UPDATE nits SET nit_est_id = $est_id WHERE nit_id = $nit_id";
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  return $ejecutar;
	  else
	  return false;
  }
   /*FIN ACTUALIZAR ESTADO NIT*/
  public function cant_pabs($nit)
  {
	  $sql = "SELECT nits_can_pabs FROM nits WHERE nit_id = $nit";
	  $query = mssql_query($sql);
	  if($query)
	   {
	      $cant_pabs = mssql_fetch_array($query);
		  return $cant_pabs['nits_can_pabs'];
	   }
	  else
	    return false;	
  }
  //////////////////**CONSULTAR LOS NITS QUE NO TIENEN PERFIL REGISTRADO**/////////////
  public function con_nit_sin_perfil($existe,$tip_nit_id,$est_nit_id){
	  $query = "SELECT nit_id,nits_nombres+' '+nits_apellidos as nombres,nit_perfil FROM nits
				WHERE nit_perfil $existe AND tip_nit_id = $tip_nit_id AND nit_est_id IN($est_nit_id) ORDER BY nits_apellidos ASC";
          //echo $query;
	  $ejecutar = mssql_query($query);
	  if($ejecutar)
	  return $ejecutar;
	  else
	  return false;
  }
////////////////****************************////////////////////////////////////
  
////////////////*ELIMINAR UN CENTRO DE COSTO DE UN ASOCIADO*////////////////
	public function eli_cen_cos_asociado($id_nit_por_cen){
		
		$query = "DELETE FROM nits_por_cen_costo WHERE id_nit_por_cen = $id_nit_por_cen";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
////////////////****************************////////////////////////////////

    /*public function fon_pensiones()
 	{
  		$sql = "SELECT nit_id, nits_nombres+' '+nits_apellidos nombre FROM nits WHERE";
  		$query = mssql_query($sql);
  		if($query)
    		return $query;
  		else
   		return false;
	}*/

	public function fon_cesantias()
	{
  		$sql = "SELECT nit_id, nits_nombres+' '+nits_apellidos nombre FROM nits WHERE";
  		$query = mssql_query($sql);
  		if($query)
    		return $query;
  		else
   		return false;
	}
	
	public function consulEmpresa()
	{
		$sql = "SELECT * FROM nits WHERE nit_id = 380";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function cuota_sindical()
	{
		$cuota = (5/100)*$this->sal_minimo();
		return $cuota;
	}
	
	public function dat_hospital($hos,$tipo)
	{
		if($tipo==1)
		{
			$sql="SELECT n.nits_nombres,n.nits_apellidos,n.nits_representante,ciu_nombre,dep_nombre,n.nits_num_documento,
c.con_hos_consecutivo,c.con_vigencia,c.con_fec_inicio,c.con_fec_fin,c.con_valor,c.con_fac_vencimiento,
c.con_val_hor_trabajada,c.con_val_hor_nocturna,c.con_observacion,n.tip_con_nit_id
FROM nits n
INNER JOIN centros_costo ON n.nit_id=cen_cos_nit
INNER JOIN ciudades ON ciu_id=ciud_ciu_id
INNER JOIN departamentos ON dep_id=depa_dep_id
INNER JOIN contrato c ON n.nit_id=c.nit_id
WHERE c.con_id=$hos";
		}
		elseif($tipo==2)
		{
			$sql = "SELECT nits_nombres,nits_apellidos,nits_representante,ciu_nombre,dep_nombre,nits_num_documento FROM nits INNER JOIN centros_costo ON nit_id=cen_cos_nit INNER JOIN ciudades ON ciu_id=ciud_ciu_id INNER JOIN departamentos ON dep_id=depa_dep_id WHERE cen_cos_id=$hos";
		}
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function con_ret_proveedor($nid_id){
		$query = "SELECT nit_retencion FROM nits WHERE tip_nit_id = 3 AND nit_id = $nid_id";
		$ejecutar = mssql_query($query);
		if($ejecutar){
			$res = mssql_fetch_array($ejecutar);
		    return $res['nit_retencion'];
		}
		else
		return false;
	}
	
	public function con_dat_nit($tip_nit_id)
	{
		if($tip_nit_id==1||$tip_nit_id==2)
		{
			$principal="1169,";
			$lacadena=$_SESSION['k_cen_costo'];
			$comparacion=strpos($lacadena,$principal);
			if($comparacion===false)
			{
				if($tip_nit_id!=2)
				{
				//NO TIENE CC PRINCIPAL
    			$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
				$query = "SELECT DISTINCT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos as nombres,nit.nits_num_documento,
nit.nits_ban_id,nit.nits_num_cue_bancaria FROM nits nit 
				  	  	  INNER JOIN nits_por_cen_costo npcc ON nit.nit_id=npcc.nit_id
				      	  INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
				  	  	  WHERE tip_nit_id = $tip_nit_id AND (cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros.")) ORDER BY nombres ASC";
				}
				else
				{
					$query="SELECT * FROM nits WHERE tip_nit_id=500";
				}
			}
			else
			{
				//PERTENECE AL PRINCIPAL
    			$query = "SELECT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos as nombres,nit.nits_num_documento,
nit.nits_ban_id,nit.nits_num_cue_bancaria
						  FROM nits nit 
				  	  	  WHERE tip_nit_id = $tip_nit_id ORDER BY nombres ASC";
			}
		}
		else
		{
			$query = "SELECT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos as nombres,nit.nits_num_documento,
nit.nits_ban_id,nit.nits_num_cue_bancaria FROM nits nit 
				  	  WHERE tip_nit_id = $tip_nit_id ORDER BY nombres ASC";
		}
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function tip_seguridad($nit)
	{
		$sql = "SELECT tip_segSoc_id id FROM tip_segSocial tsc INNER JOIN nits nit ON tsc.tip_segSoc_id = nit.nit_tip_segSocial WHERE nit.nit_id=$nit";
		$query = mssql_query($sql);
		if($query)
		{
			$dat_seg = mssql_fetch_array($query);
			return $dat_seg['id'];
		}
		else
		  return false;
	}
	
	public function porcentajes()
	{
		$sql = "SELECT * FROM por_pension";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false;   
	}
	
	public function con_caja_empleado($emp_id)
	{
		$query = "EXECUTE ConcajaEmpleado $emp_id";
		$ejecutar = mssql_query($query);
		return $ejecutar;
	}
	public function sena()
	{
		$sql = "SELECT nit_id, nits_nombres, nits_num_documento FROM nits WHERE nit_id = 1065";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function icbf()
	{
		$sql = "SELECT nit_id, nits_nombres, nits_num_documento FROM nits WHERE nit_id = 1066";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	public function fon_nits($nit)
	{
		$sql = "SELECT nits_eps,nits_arp,nit_pensiones,nit_cajaCompensacion FROM nits WHERE nit_id = $nit";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function seguridadNit($mes,$ano,$cuenta,$dias,$tipo_nit,$estados)
	{
	  $nits_sedar = "SELECT nit_id FROM nits WHERE tip_nit_id = $tipo_nit AND nit_est_id IN ($estados)";
	  $query_nit = mssql_query($nits_sedar);
	  $j=0;
	  while($dat_nits = mssql_fetch_array($query_nit))
	  {
		$centro_costo=0;  
	  	$cot_sedar = $this->movimientos->con_sal_cue_seg_soc_asociado($mes,$ano,$cuenta,$dias,$dat_nits['nit_id']);
		$i=0;
		
		while($row=mssql_fetch_array($cot_sedar))
		{
			$valor = $valor+$row['valor'];
		}
		$val_sedar[$dat_nits['nit_id']] = $valor;
		$datos_nit = $this->consul_nits($dat_nits['nit_id']);
		$dat_cedula = mssql_fetch_array($datos_nit);
		//$val_anestecoop[$dat_nits['nit_id']] = $this->seguridadAnestecoop($dat_cedula['nits_num_documento']);
		$valor = 0;
	  }
	  $dat_retorno[0] = $val_sedar;
	  return $dat_retorno;
	}
	
	public function con_nit_por_lin_tel($tip_reg_telefonia)
	{
	    $query="SELECT n.nit_id,n.nits_nombres+' '+n.nits_apellidos as nombres FROM nits n INNER JOIN lineas_telefonia lt
				ON n.nit_id=lt.nit_id WHERE tip_lin_tel = $tip_reg_telefonia ORDER BY nits_apellidos ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function porSeguridad($id)
	{
		if($id)
		{
			$sql = "SELECT tip_segSoc_porcentaje FROM tip_segSocial WHERE tip_segSoc_id = $id";
			$query = mssql_query($sql);
			if($query)
			{
				$dat_por = mssql_fetch_array($query);
				return $dat_por['tip_segSoc_porcentaje'];
			}
		}
	}
	
	public function MontoFijoSeguridadSocial($nit_id)
	{
		if($nit_id)
		{
			$sql = "SELECT nit_mon_fij_seg_social,nit_val_seg_social FROM nits WHERE nit_id = $nit_id";
			$query = mssql_query($sql);
			if($query)
			{
				$dat_por=mssql_fetch_array($query);
				return $dat_por;
			}
		}
	}
	
	public function busNit($documento)
	{
		$sql="SELECT nit_id FROM nits WHERE nits_num_documento = '$documento'";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query)
		{
			$dat_nit = mssql_fetch_array($query);
			return $dat_nit['nit_id'];
		}
		return false;
	}
	
	public function BuscarDocumentoPorId($nit_id)
	{
		$sql="SELECT nits_num_documento FROM nits WHERE nit_id='$nit_id'";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query)
		{
			$dat_nit = mssql_fetch_array($query);
			return $dat_nit['nits_num_documento'];
		}
		return false;
	}
	
	public function contodatnitpordocumento($documento)
	{
		$sql="SELECT * FROM nits WHERE nits_num_documento = '$documento'";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query)
		{
			$dat_nit=mssql_fetch_array($query);
			return $dat_nit;
		}
		return false;
	}
	
	public function con_dat_emp_por_tip_estado($tipo,$estado,$per_pago)
	{
		$query="SELECT n.nit_id,n.nits_num_documento,n.nits_apellidos+' '+n.nits_nombres nombres,nits_salario,n.nit_bonificacion,n.nits_por_pabs,c.per_pag_nit_id
				FROM nits n
				INNER JOIN contrato c ON n.nit_id=c.nit_id
				WHERE n.tip_nit_id='$tipo' AND nit_est_id='$estado' AND c.per_pag_nit_id='$per_pago'
				ORDER BY nits_apellidos ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

	public function con_dat_nom_administrativa(){
		$query="SELECT * FROM datos_nomina_administrativa";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_dat_fon_sol_pen_nom_administrativa()
	{
		$query="SELECT * FROM datos_solidaridad_pensional_nomina_administrativa";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_doc_tel_dir_nit($tipo)
	{
		$query="SELECT nit_id,nits_num_documento,nits_nombres+' '+nits_apellidos nombres,nits_tel_residencia,nits_dir_residencia
				FROM nits
				WHERE tip_nit_id = $tipo";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_ult_nit()
	{
		$query="SELECT MAX(nit_id) ultimo FROM nits";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['ultimo'];
		}
		else
			return false;
	}
	
	public function con_cor_nit_por_id($sigla,$nocontiene)
	{
		$query="SELECT DISTINCT mc.mov_nit_tercero,n.nits_cor_electronico
				FROM movimientos_contables mc
				INNER JOIN nits n
				ON mc.mov_nit_tercero=n.nit_id
				WHERE mov_compro='$sigla' AND mov_nit_tercero NOT LIKE('%$nocontiene%')";
                //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConDatProPorTipId($tip_nit_id,$nit_id)
	{
		$query="EXECUTE ConDatProveedor $tip_nit_id,$nit_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	//descuento con las facturas que se pago!!!
	/*public function des_segSocial($factura,$centro,$nit)
	{
		$sql = "BEGIN 
				DECLARE @descontar FLOAT 
				EXECUTE DES_SEG_SOCIAL $factura,$centro,$nit,@salida = @descontar OUTPUT 
				SELECT @descontar as descontar 
				END";		
		$query = mssql_query($sql);
		if($query)
		 {
			 $dat_query = mssql_fetch_array($query);
			 return $dat_query['descontar'];
		 }
	}*/
	
	//descuento con las facturas del mes
	public function des_segSocial($nit,$mes_trabajo,$facturacion,$fac,$ano,$estado_nit)
	{
		$mes=date('m');
		$anio_contable=$_SESSION['elaniocontable'];
		
		$cue_uno_retiro='23803009';
		$cue_dos_retiro='31400101';
		
		/*if($nit==1229)
		echo $nit."___".$mes_trabajo."___".$facturacion."___".$fac."___".$ano."___".$estado_nit."<br>";
		*/
		$dat_minimo=$this->varios->ConsultarDatosVariablesPorId(1);
		$salario_minimo=round($dat_minimo['var_valor'],-3);
		
		$facturado=round($facturacion,-3);
		
		if($facturado>0&&$estado_nit==1)
		{
			if($mes_trabajo==12)
			{
				$anio_actual=date('Y');
				//$resta_anios=$anio_actual-$ano;
				$ano=$anio_actual-1;
			}
			
			$que_fac="SELECT f.fac_mes_servicio,f.fac_ano_servicio FROM factura f WHERE f.fac_id='$fac'";
			//echo $que_fac."<br>";
			$eje_fac=mssql_query($que_fac);
			if($eje_fac)
				$res_dat_fac=mssql_fetch_array($eje_fac);
			
			$pago_mes="SELECT n.nits_num_documento,n.nits_apellidos,n.nits_nombres,
			(SUM(mc.mov_valor)+rj.rep_jor_num_jornadas-SUM(mc.mov_valor)) facturado,
			n.nits_por_pabs,tss.tip_segSoc_nombre,tss.tip_segSoc_porcentaje,
			ISNULL(n.nit_mon_fij_seg_social,2) nit_mon_fij_seg_social,ISNULL(n.nit_val_seg_social,0) nit_val_seg_social,
			n.nit_est_id,n.nit_por_fon_vacaciones,n.nit_tip_segSocial
			FROM reporte_jornadas rj
			INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
			INNER JOIN factura f ON rj.rep_jor_num_factura=f.fac_id
			INNER JOIN movimientos_contables mc ON f.fac_id=mc.mov_nume
			INNER JOIN nits n ON n.nit_id=npcc.nit_id
			INNER JOIN tip_segSocial tss ON n.nit_tip_segSocial=tss.tip_segSoc_id
			WHERE n.nit_id='$nit' AND f.fac_id='$fac' AND (mc.mov_compro like('CAU-NOM-%'))
			AND mc.mov_cuent IN('25051002','25052001','25051004','25051006','250510121')
			GROUP BY n.nits_num_documento,n.nits_apellidos,n.nits_nombres,rj.rep_jor_num_jornadas,n.nits_por_pabs,
			tss.tip_segSoc_nombre,tss.tip_segSoc_porcentaje,nit_mon_fij_seg_social,nit_val_seg_social,n.nit_est_id,
			n.nit_por_fon_vacaciones,n.nit_tip_segSocial
			ORDER BY n.nits_apellidos";
			/*if($nit==2512)
				echo $pago_mes;*/
			$query_pago=mssql_query($pago_mes);
			$ejecutar_pago=mssql_fetch_array($query_pago);
					
			$total_fac_mes="SELECT ISNULL(SUM(rep_jor_causado),0) tot_fac_mes FROM dbo.reporte_jornadas WHERE rep_jor_num_factura IN (SELECT fac_id FROM factura
			WHERE fac_cen_cos IN(SELECT cen_cos_id from dbo.nits_por_cen_costo WHERE nit_id=$nit) AND fac_mes_servicio=$res_dat_fac[fac_mes_servicio] AND fac_ano_servicio=$res_dat_fac[fac_ano_servicio] AND fac_estado!=5)
			AND id_nit_por_cen IN (select id_nit_por_cen from dbo.nits_por_cen_costo where nit_id = $nit)
			AND rep_jor_num_factura IN(
			SELECT DISTINCT f.fac_id
			FROM movimientos_contables mc
			INNER JOIN factura f ON mc.mov_nume=f.fac_id AND mov_compro LIKE('CAU-NOM-%')
			INNER JOIN reporte_jornadas rj ON f.fac_id=rj.rep_jor_num_factura
			INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
			INNER JOIN nits n ON npcc.nit_id=n.nit_id
			WHERE mov_compro LIKE('CAU-NOM-%') AND f.fac_mes_servicio=$res_dat_fac[fac_mes_servicio] AND
			f.fac_ano_servicio=$res_dat_fac[fac_ano_servicio]
			AND n.nit_id=$nit AND (mov_cuent=61151005 OR mov_cuent=61150515)
			AND mc.mov_nit_tercero='$nit')";
			/*if($nit==2512)
				echo $total_fac_mes;*/
			
			/*
			$total_fac_mes="SELECT ISNULL(SUM(rep_jor_causado),0) tot_fac_mes FROM dbo.reporte_jornadas WHERE rep_jor_num_factura IN (SELECT fac_id FROM factura
			WHERE fac_cen_cos IN(SELECT cen_cos_id from dbo.nits_por_cen_costo WHERE nit_id=$nit) AND fac_mes_servicio=$res_dat_fac[fac_mes_servicio] AND fac_ano_servicio=$res_dat_fac[fac_ano_servicio] AND fac_estado!=5)
			AND id_nit_por_cen IN (select id_nit_por_cen from dbo.nits_por_cen_costo where nit_id = $nit)
			AND rep_jor_num_factura IN(
			SELECT DISTINCT f.fac_id
			FROM movimientos_contables mc
			INNER JOIN factura f ON mc.mov_nume=f.fac_id AND mc.mov_documento=f.fac_id
			INNER JOIN reporte_jornadas rj ON f.fac_id=rj.rep_jor_num_factura
			INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
			INNER JOIN nits n ON npcc.nit_id=n.nit_id
			WHERE mov_compro LIKE('CAU-NOM-%') AND f.fac_mes_servicio=$res_dat_fac[fac_mes_servicio] AND
			f.fac_ano_servicio=$res_dat_fac[fac_ano_servicio]
			AND n.nit_id=$nit AND mov_cuent IN(51109502) AND mov_valor=0
			AND mc.mov_nit_tercero='$nit')";
			*/
			
			
			$query_tot_fac_mes = mssql_query($total_fac_mes);
			$total_facturacion_mensual=0;
			if($query_tot_fac_mes)
			{
				$resul_tot_fac_mes=mssql_fetch_array($query_tot_fac_mes);
				//if($nit='2236')
					//echo $nit."___".$facturado."___".$resul_tot_fac_mes['tot_fac_mes']."<br>";
				if($resul_tot_fac_mes['tot_fac_mes']!=0 || $resul_tot_fac_mes['tot_fac_mes']!="NULL" || $resul_tot_fac_mes['tot_fac_mes']!="")
				$porcentaje_facturado=$facturado*100/$resul_tot_fac_mes['tot_fac_mes'];
				else
					$porcentaje_facturado=$facturado*100/1;
				
				$total_facturacion_mensual+=$resul_tot_fac_mes['tot_fac_mes'];
			}
			
			$con_can_fac_mes="SELECT COUNT(rep_jor_num_factura) can_facturas FROM dbo.reporte_jornadas WHERE rep_jor_num_factura IN (SELECT fac_id FROM factura
			WHERE fac_cen_cos IN(SELECT cen_cos_id from dbo.nits_por_cen_costo WHERE nit_id=$nit) AND fac_mes_servicio=$res_dat_fac[fac_mes_servicio] AND fac_ano_servicio=$res_dat_fac[fac_ano_servicio] AND fac_estado!=5)
			AND id_nit_por_cen IN (select id_nit_por_cen from dbo.nits_por_cen_costo where nit_id = $nit)
			AND rep_jor_num_factura IN(
			SELECT DISTINCT f.fac_id
			FROM movimientos_contables mc
			INNER JOIN factura f ON mc.mov_nume=f.fac_id AND mc.mov_documento=f.fac_id
			INNER JOIN reporte_jornadas rj ON f.fac_id=rj.rep_jor_num_factura
			INNER JOIN nits_por_cen_costo npcc ON rj.id_nit_por_cen=npcc.id_nit_por_cen
			INNER JOIN nits n ON npcc.nit_id=n.nit_id
			WHERE mov_compro LIKE('CAU-NOM-%') AND f.fac_mes_servicio=$res_dat_fac[fac_mes_servicio] AND
			f.fac_ano_servicio=$res_dat_fac[fac_ano_servicio]
			AND n.nit_id=$nit AND mov_cuent IN(61151005) AND mov_valor=0
			AND mc.mov_nit_tercero='$nit')";
			
			/*if($nit==2512)
				echo $con_can_fac_mes;*/
			
			$eje_can_fac_mes=mssql_query($con_can_fac_mes);
			$res_can_fac_mes=mssql_fetch_array($eje_can_fac_mes);
			$cantidad_facturas_mensual=$res_can_fac_mes['can_facturas'];
			
			if($ejecutar_pago['nit_est_id']==1)//ES AFILIADO ACTIVO
			{
				$val_pag_fondos=0;
				$query_datos_nomina=$this->reporte_jornadas->bus_datCompensacion();
				$ejecutar_datos_nomina=mssql_fetch_array($query_datos_nomina);
				
				if($ejecutar_pago['nit_mon_fij_seg_social']==1&&$ejecutar_pago['nit_val_seg_social']>0)//TIENE MONTO FIJO
				{
					$ibc=round($ejecutar_pago['nit_val_seg_social']);
							
					if($ejecutar_pago['nit_tip_segSocial']!=3 && $ejecutar_pago['nit_tip_segSocial']!=4 && $ejecutar_pago['nit_tip_segSocial']!=5 && $ejecutar_pago['nit_tip_segSocial']!=8)
					{
						$val_solidaridad=$this->varios->FondoSolidaridad($ibc);
						$valor_fondo_solidaridad=$val_solidaridad[0];
					}
					else
						$valor_fondo_solidaridad=0;
					
					$val_pag_fondos=round($ibc*$ejecutar_pago['tip_segSoc_porcentaje']/100,-2);
							
					/*if($nit=='1386')
					{
						echo "% facturado: ".$porcentaje_facturado."___".$valor_fondo_solidaridad."___".$val_pag_fondos."<br>";
					}*/
					$total_pagar=round(($val_pag_fondos+$valor_fondo_solidaridad)*($porcentaje_facturado)/100,-2);
				}
						
				else
				{
					$fabs=0;$vacas=0;$admon=0;$educa=0;$apor=0;$por_base=40;$deducciones=0;
							
					if($cantidad_facturas_mensual>1)//TIENE VARIAS FACTURAS EN EL MES
					{
						$fabs=$total_facturacion_mensual*$ejecutar_pago['nits_por_pabs']/100;
						$vacas=$total_facturacion_mensual*$ejecutar_pago['nit_por_fon_vacaciones']/100;
								
						if($res_dat_fac['fac_ano_servicio']==2017)
						{
							if($res_dat_fac['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
							{
								$admon=$total_facturacion_mensual*5/100;//ADMINISTRACION BASICA
							}
							else//CALCULA LA ADMON NUEVA 5.5%
							{
								$admon=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_gastos']/100;//ADMINISTRACION BASICA
							}
						}
						else//CALCULA LA ADMON NUEVA 5.5%
						{
							if($res_dat_fac['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
							{
								$admon=$total_facturacion_mensual*5/100;//ADMINISTRACION BASICA
							}
							else//CALCULA LA ADMON NUEVA 5.5%		
							{
								$admon=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_gastos']/100;//ADMINISTRACION BASICA
							}
						}
												
						//$admon=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_gastos']/100;
								
						$educa=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_educacion']/100;
						
						
						//ANTERIOR APORTES:
						//$apor=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_aportes']/100;
						
						
						//NUEVO:
						$cue1=$this->saldo_cuenta_nit($cue_uno_retiro,$anio_contable,$mes,$nit);
 						$cue2=$this->saldo_cuenta_nit($cue_dos_retiro,$anio_contable,$mes,$nit);
			
						$tot_sal_cuenta=$cue1+$cue2;
						
						//$tot_sal_cuenta=$this->ConSalCuePorNit($nit,$cue_uno,$cue_dos);
						
						$tot_sal_creditos=$this->TotalSaldoCreditosPorNit($nit);
						
						$saldo_final=$tot_sal_cuenta-$tot_sal_creditos;//LO QUE TIENE EN EL FONDO - LO QUE DEBE DE CREDITOS
						$can_sal_minimos=40;
						
						$minimo=$this->sal_minimo();
						
						$val_tot_sal_minimos=$minimo*$can_sal_minimos;
						
						if($res_dat_fac['fac_ano_servicio']==2018)
						{
							//echo "entra";
							
							if($res_dat_fac['fac_ano_servicio']<=9)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
							{
								$apor=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
							}
							else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
							{
								if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))								 
								//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
								{
									$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit'";
									$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
									$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
							
									$apor=$total_facturacion_mensual*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
								}
								else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
								{
									$apor=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
								}
							}
						}
			            else
						{
							
							if($res_dat_fac['fac_ano_servicio']<2018)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
							{
								/*if($nit=='2512')
										echo "entra oir aqui";*/
								$apor=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
							}
							else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
							{
								//if($nit_conta=='2512')
									///echo "ebntra por 2019 2";
								if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))
								//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
								{
									$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit'";
									$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
									$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
									
									/*if($nit_conta=='2512')
										echo "el valor es: ".$res_por_fon_ret_afiliado['nit_por_fon_ret_sindical'];*/
									$apor=$total_facturacion_mensual*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
								}
								else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
								{
									$apor=$total_facturacion_mensual*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
								}
							}
						}
						
						
						$deducciones=$total_facturacion_mensual-$fabs-$vacas-$admon-$educa-$apor;
						
						//echo "varias!";
						
						if((($deducciones*$por_base)/100)<$salario_minimo)
							$ibc=$salario_minimo;
						else
							$ibc=round(($deducciones*$por_base/100));
						
						
						if($ejecutar_pago['nit_tip_segSocial']!=3 && $ejecutar_pago['nit_tip_segSocial']!=4 && $ejecutar_pago['nit_tip_segSocial']!=5 && $ejecutar_pago['nit_tip_segSocial']!=8)
						{
							$val_solidaridad=$this->varios->FondoSolidaridad($ibc);
							$valor_fondo_solidaridad=$val_solidaridad[0];
						}
								
						else
							$valor_fondo_solidaridad=0;
								
						$val_pag_fondos=round($ibc*$ejecutar_pago['tip_segSoc_porcentaje']/100,-2);
						$total_pagar=round(($val_pag_fondos+$valor_fondo_solidaridad)*($porcentaje_facturado)/100,-2);
					}
							
					else//SOLO TIENE UNA FACTURA
					{
						
						//echo "Una!";
						$fabs=$facturado*$ejecutar_pago['nits_por_pabs']/100;
						$vacas=$facturado*$ejecutar_pago['nit_por_fon_vacaciones']/100;
						
						if($res_dat_fac['fac_ano_servicio']==2017)
						{
							if($res_dat_fac['fac_mes_servicio']<=8)//CALCULA LA ADMON ANTERIOR 5%
							{
								$admon=$facturado*5/100;//ADMINISTRACION BASICA
							}
							else//CALCULA LA ADMON NUEVA 5.5%
							{
								$admon=$facturado*$ejecutar_datos_nomina['dat_nom_gastos']/100;//ADMINISTRACION BASICA
							}
						}
						else//CALCULA LA ADMON NUEVA 5.5%
						{
							if($res_dat_fac['fac_ano_servicio']<2017)//CALCULA LA ADMON ANTERIOR 5%
							{
								$admon=$facturado*5/100;//ADMINISTRACION BASICA
							}
							else//CALCULA LA ADMON NUEVA 5.5%		
							{
								$admon=$facturado*$ejecutar_datos_nomina['dat_nom_gastos']/100;//ADMINISTRACION BASICA
							}
						}
						
						//$admon=$facturado*$ejecutar_datos_nomina['dat_nom_gastos']/100;		
								
						$educa=$facturado*$ejecutar_datos_nomina['dat_nom_educacion']/100;
						
						//ANTERIOR APORTES:
						//$apor=$facturado*$ejecutar_datos_nomina['dat_nom_aportes']/100;
						
						//NUEVO:
						$cue1=$this->saldo_cuenta_nit($cue_uno_retiro,$anio_contable,$mes,$nit);
 						$cue2=$this->saldo_cuenta_nit($cue_dos_retiro,$anio_contable,$mes,$nit);
			
						$tot_sal_cuenta=$cue1+$cue2;
						//$tot_sal_cuenta=$this->ConSalCuePorNit($nit,$cue_uno,$cue_dos);
						
						$tot_sal_creditos=$this->TotalSaldoCreditosPorNit($nit);
						
						$saldo_final=$tot_sal_cuenta-$tot_sal_creditos;//LO QUE TIENE EN EL FONDO - LO QUE DEBE DE CREDITOS
						$can_sal_minimos=40;
						
						$minimo=$this->sal_minimo();
						
						$val_tot_sal_minimos=$minimo*$can_sal_minimos;
						
						/*if($nit==1212)
						echo "el nit es: ".$nit." cuenta 1: ".$cue1." cuenta 2: ".$cue2." Total cuenta: ".$tot_sal_cuenta." Total saldo creditos: ".$tot_sal_creditos." Saldo final: ".$saldo_final."<br>";
						*/
						
						if($res_dat_fac['fac_ano_servicio']==2018)
						{
							//echo "entra";
							
							if($res_dat_fac['fac_ano_servicio']<=9)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
							{
								$apor=$facturado*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
							}
							else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
							{
								/*if($nit==1212)
									echo "entra a buscar el de la HV";*/
								if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))
								//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
								{
									$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit'";
									$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
									$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
							
									$apor=$facturado*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
								}
								else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
								{
									$apor=$facturado*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
								}
							}
						}
			            else
						{
							if($res_dat_fac['fac_ano_servicio']<2018)//CALCULA EL FONDO DE RETIRO ANTERIOR 8%
							{
								$apor=$facturado*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
							}
							else//CALCULA EL FONDO DE RETIRO NUEVO(EL % QUE TENGA EN LA HOJA DE VIDA)
							{
								/*if($nit=='2512')
									echo "los saldos: ".$saldo_final."___".$val_tot_sal_minimos;*/
								if(($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos < ($val_tot_sal_minimos)) || ($tot_sal_cuenta>($val_tot_sal_minimos) && $tot_sal_creditos>($val_tot_sal_minimos) && $tot_sal_cuenta>$tot_sal_creditos))
								//if($saldo_final>$val_tot_sal_minimos)//SI TIENE MÁS DE 40SMMLV
								{
									$que_por_fon_ret_afiliado="SELECT nit_por_fon_ret_sindical FROM nits WHERE nit_id='$nit'";
									$eje_por_fon_ret_afiliado=mssql_query($que_por_fon_ret_afiliado);
									$res_por_fon_ret_afiliado=mssql_fetch_array($eje_por_fon_ret_afiliado);
								
									$apor=$facturado*($res_por_fon_ret_afiliado['nit_por_fon_ret_sindical']/100);//FONDO DE RETIRO SINDICAL
								}
								else//NO TIENE MAS DE 40SMMLV, ENTONCES LE CALCULA EL %  
								{
									$apor=$facturado*$ejecutar_datos_nomina['dat_nom_aportes']/100;//FONDO DE RETIRO SINDICAL
								}
							}
						}
						
						
						
						$deducciones=$facturado-$fabs-$vacas-$admon-$educa-$apor;
						
						if((($deducciones*$por_base)/100)<$salario_minimo)
							$ibc=$salario_minimo;
						else
							$ibc=round(($deducciones*$por_base/100));
						
								
						if($ejecutar_pago['nit_tip_segSocial']!=3 && $ejecutar_pago['nit_tip_segSocial']!=4 && $ejecutar_pago['nit_tip_segSocial']!=5 && $ejecutar_pago['nit_tip_segSocial']!=8)
						{
							$val_solidaridad=$this->varios->FondoSolidaridad($ibc);
							$valor_fondo_solidaridad=$val_solidaridad[0];
						}
						else
							$valor_fondo_solidaridad=0;
								
						$val_pag_fondos=round($ibc*$ejecutar_pago['tip_segSoc_porcentaje']/100,-2);
						$total_pagar=round(($val_pag_fondos+$valor_fondo_solidaridad),-2);
					}
				}
			}
			else
			{
				$total_pagar=0;
			}
			
		}
		else
		{
			$total_pagar=0;
		}
		return $total_pagar;
	    
	}

	public function ConNomNits()
	{
		$query="SELECT nit_id,nits_nombres,nits_apellidos,nits_num_documento FROM nits";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConAfiCrePorMesAnio($tipo)
	{
		$query="SELECT n.nit_id,n.nits_nombres,n.nits_apellidos,n.nit_fec_afiliacion,n.nit_fec_creacion,n.nits_num_documento,ne.nit_est_nombre,n.nit_fec_retiro
				FROM nits n
				INNER JOIN nits_estados ne ON n.nit_est_id=ne.nit_est_id
				WHERE tip_nit_id='$tipo'
				ORDER BY CAST(n.nit_fec_afiliacion AS DATETIME) DESC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}

	public function act_tipSeguridad($cedula,$tipSegsocial)
	{
            $sql="UPDATE nits SET nit_tip_segSocial=$tipSegsocial WHERE nits_num_documento='$cedula'";
            $query=mssql_query($sql);
            if($query)
            { return true; }
            else
            { return false; }
	}
	
	public function ConsultarTipoArlEmpleado()
	{
        $query="SELECT * FROM tipos_arl_empleados";
        $ejecutar=mssql_query($query);
        if($ejecutar)
            { return $ejecutar; }
        else
            { return false; }
	}
	
	public function nitsContrato($centro)
	{
		$sql="SELECT nit_id,nits_nombres,nits_apellidos,nits_num_documento FROM dbo.nits WHERE tip_nit_id = 1 AND nit_est_id in(1,3) AND nit_id NOT IN (SELECT nit_id FROM nits_por_cen_costo WHERE cen_cos_id=$centro) ORDER BY nits_apellidos ASC ";
		$ejecutar=mssql_query($sql);
        if($ejecutar)
            { return $ejecutar; }
        else
            { return false; }
	}
	
	public function ConDatCorNit($tip_nit,$ciu_id)
	{
		$query="SELECT DISTINCT n.nits_nombres,n.nits_apellidos,n.nits_cor_electronico
				FROM nits_por_ciudades npc INNER JOIN nits n ON npc.nit_id=n.nit_id
				WHERE tip_nit_id=$tip_nit AND ciu_id=$ciu_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConCiuPorNit($nit_id)
	{
		$query="SELECT * FROM nits_por_ciudades WHERE nit_id='$nit_id'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function GuaCiuNit($nit_id,$ciu_id,$des_ubi_id)
	{
		$query="INSERT INTO nits_por_ciudades(nit_id,ciu_id,des_ubi_id)
				VALUES('$nit_id','$ciu_id','$des_ubi_id')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function SolicitudesRetiroSindical($fec_inicial,$fec_final)
	{
		$query="SELECT rsrs.*,n.nits_num_documento,n.nits_apellidos,n.nits_nombres
				FROM registro_solicitudes_retiro_sindical rsrs
				INNER JOIN nits n ON rsrs.reg_sol_ret_sin_afiliado=n.nit_id
				WHERE CAST(reg_sol_ret_sin_fecha AS DATETIME) BETWEEN '$fec_inicial' AND '$fec_final'
				ORDER BY CAST(rsrs.reg_sol_ret_sin_fecha AS DATETIME),rsrs.reg_sol_ret_sin_hora ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConsultarEmpleadosNominaAdministrativa($mov_compro,$mov_concepto,$mov_mes_contable,$mov_ano_contable,$cedula_1,$cedula_2,$tip_nit)
	{
		$query="SELECT DISTINCT n.*,p.per_nombre,mc.mov_compro FROM nits n
				INNER JOIN movimientos_contables mc ON CAST(n.nit_id AS VARCHAR)=mc.mov_nit_tercero
				INNER JOIN perfiles p ON n.nit_perfil=p.per_id
				WHERE mc.mov_compro LIKE('$mov_compro%') AND mov_concepto='$mov_concepto' AND mc.mov_mes_contable='$mov_mes_contable' AND mc.mov_ano_contable='$mov_ano_contable'
				AND n.nits_num_documento BETWEEN '$cedula_1' AND '$cedula_2' AND n.tip_nit_id IN($tip_nit)
				ORDER BY n.nits_apellidos";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function EliminarBeneficiariosAfiliado($ben_id,$nit_id)
	{
		$query_1="DELETE FROM nits_por_beneficiarios WHERE ben_id='$ben_id' and nit_id='$nit_id'";
		$ejecutar_1=mssql_query($query_1);
		if($ejecutar_1)
		{
			$query_2="DELETE FROM beneficiarios WHERE ben_id='$ben_id'";
			$ejecutar_2=mssql_query($query_2);
			if($ejecutar_2)
				return $ejecutar_2;
			else
				return false;
		}
		else
			return false;
	}
	
	public function EliminarTodosBeneficiariosAfiliado($nit_id)
	{
		$query_1="DELETE FROM beneficiarios WHERE ben_id IN(SELECT ben_id FROM nits_por_beneficiarios WHERE nit_id='$nit_id')";
		$ejecutar_1=mssql_query($query_1);
		if($ejecutar_1)
		{
			$query_2="DELETE FROM nits_por_beneficiarios WHERE nit_id='$nit_id'";
			$ejecutar_2=mssql_query($query_2);
			if($ejecutar_2)
				return $ejecutar_2;
			else
				return false;
		}
		else
			return false;
	}
	
	public function ConsultarUnidadFuncionalPorId($nit_id)
	{
		$query_1="SELECT nit_uni_funcional FROM nits WHERE nit_id='$nit_id'";
		$ejecutar_1=mssql_query($query_1);
		if($ejecutar_1)
			return $ejecutar_1;
		else
			return false;
	}

	public function ConAfiPorAuxNucleo($tip_nit_id)
	{
		$loscentros=substr($_SESSION['k_cen_costo'],0,-1);
		$query="SELECT DISTINCT nit.nit_id,nit.nits_nombres+' '+nit.nits_apellidos as nombres,
		nit.nits_num_documento,nit.nits_ban_id,nit.nits_num_cue_bancaria FROM nits nit 
		INNER JOIN nits_por_cen_costo npcc ON nit.nit_id=npcc.nit_id
		INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
		WHERE tip_nit_id = $tip_nit_id AND (cc.cen_cos_id IN(".$loscentros.") OR cc.per_cen_cos IN(".$loscentros."))
		ORDER BY nombres ASC";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	
	public function ConsultarDatosNitGeneralPorId($nit_id,$des_ubicacion)
	{
		$query="SELECT *
		FROM nits n
		LEFT JOIN regimenes r ON n.reg_id=r.reg_id
		LEFT JOIN nits_por_ciudades npc ON n.nit_id=npc.nit_id AND npc.des_ubi_id IN($des_ubicacion)
		LEFT JOIN ciudades c ON npc.ciu_id=c.ciu_id
		LEFT JOIN departamentos d ON c.depa_dep_id=d.dep_id
		WHERE n.nit_id='$nit_id'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
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
		SELECT SUM(des_cre_capital) FROM des_credito WHERE des_cre_estado=3 AND des_cre_credito=cre.cre_id),0) saldo
		FROM creditos cre INNER JOIN conceptos c ON c.con_id=cre.cue_id
		INNER JOIN movimientos_contables mc ON 'CRE_'+CAST(cre.cre_id AS VARCHAR)=mc.mov_compro
		WHERE nit_id='$nit_id' AND cre.cre_fec_desembolso IS NOT NULL AND c.con_id NOT IN(312,314,316,302,317,310,311)";
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
   	
   	public function ConSalCuePorNit($nit_id,$cue_uno,$cue_dos)
   	{
   		$query="SELECT(
		(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
		WHERE mov_cuent IN($cue_uno,$cue_dos) AND mov_nit_tercero='$nit_id' and mov_tipo=2
		AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12)
		+
		(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
		WHERE mov_cuent IN($cue_uno,$cue_dos) AND mov_nit_tercero='$nit_id' and mov_tipo=2
		AND mov_compro='CIE-2017')
		)
		-
		(SELECT ISNULL(SUM(mov_valor),0) FROM movimientos_contables
		WHERE mov_cuent IN($cue_uno,$cue_dos) AND mov_nit_tercero='$nit_id' and mov_tipo=1
		AND mov_compro NOT LIKE('CIE-%') AND mov_mes_contable<=12)
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
   	
   	public function saldo_cuenta_nit($cuenta,$ano,$mes,$nit)
	 {
		 $sql = "SELECT [dbo].[SaldoCuentaPorMesNit] ('$cuenta','$ano','$mes','$nit') saldo_cuenta";
		 $query = mssql_query($sql);
		 if($query)
		  {
			  $saldo = mssql_fetch_array($query);
			  return $saldo['saldo_cuenta'];
		  }
	 }
}
?>