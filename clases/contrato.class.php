<?php
@include_once('../conexion/conexion.php');
include_once('tipo_contrato_externo.class.php');
include_once('tipo_contrato.class.php');
include_once('tipo_contrato_prestacion.class.php');
include_once('centro_de_costos.class.php');
include_once('estado_contrato.class.php');
include_once('nits.class.php');
include_once('estado_contrato_legalizado.class.php');
include_once('concepto.class.php');
@include_once('inicializar_session.php');
@include_once('../inicializar_session.php');
	
class contrato
{
	private $tip_con_externo;
	private $tip_contrato;
	private $tip_con_prestacion;
	private $cen_costos;
	private $est_contrato;
	private $nits;
	private $est_con_legalizado;
	private $concepto;
	
	public function __construct()
    {
	    $this->tip_con_externo = new tipo_contrato_externo();
		$this->tip_contrato = new tipo_contrato();
		$this->tip_con_prestacion = new tipo_contrato_prestacion();
		$this->cen_costos = new centro_de_costos();
		$this->est_contrato = new estado_contrato();
		$this->nits = new nits();
		$this->est_con_legalizado = new estado_contrato_legalizado();
		$this->concepto = new concepto();
	}
	
	public function con_tip_concepto($id_tip_concepto)
	{
		return $this->concepto->con_tip_concepto($id_tip_concepto);
	}
	
	public function con_tip_nit($id_tip_nit)
  	{
		return $this->nits->con_tip_nit($id_tip_nit);
  	}
	
	public function con_tip_con_externo()
	{
		return $this->tip_con_externo->con_tip_con_externo();
	}
	
	public function  consulta_tipo_contrato()
	{
		return $this->tip_contrato->consulta_tipo_contrato();
	}
	
	public function con_tip_con_prestacion()
	{
		return $this->tip_con_prestacion->con_tip_con_prestacion();
	}
	
	public function cons_centro_costos()
	{
		return $this->cen_costos->cons_centro_costos();
	}
	
	public function con_nit_por_cen_costo($cen_cos_id)
	{
		return $this->cen_costos->con_nit_por_cen_costo($cen_cos_id);
	}

	public function con_est_contrato()
	{
		return $this->est_contrato->con_est_contrato();
	}
	
	public function con_cen_cos_es_nit()
	{
		return $this->cen_costos->con_cen_cos_es_nit();
	}
	
	public function con_aso_por_id_estado($tip_nit_id,$nit_estado)
	{
		return $this->nits->con_aso_por_id_estado($tip_nit_id,$nit_estado);
	}
	public function con_est_con_legalizado()
	{
		return $this->est_con_legalizado->con_est_con_legalizado();
	}
	//Inicio Funciones para insertar el contrato por monto fijo
        public function ins_pol_o_imp_por_contrato($con_pol_nombre,$con_nom_pol_aseguradora,$con_pol_porcentaje)
	{
		$query = "EXECUTE InserPoloImpConMonFijo $con_pol_nombre,$con_nom_pol_aseguradora,$con_pol_porcentaje";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return true;
		else
			return false;
	}
        
	public function ins_pol_por_adicion($contrato_id,$concepto_id,$nit_id,$con_por_con_porcentaje,$adi_otr_id,$con_por_con_observacion){
		if($adi_otr_id=="")
			$adi_otr_id="NULL";
		$query="INSERT INTO dbo.contratos_por_conceptos(contrato_id,concepto_id,nit_id,con_por_con_porcentaje,con_activo,adi_otr_id,con_por_con_observacion)
    			VALUES($contrato_id,$concepto_id,$nit_id,$con_por_con_porcentaje,1,$adi_otr_id,'$con_por_con_observacion')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
        
        public function ins_pol_por_adicion_informativo($contrato_id,$concepto_id,$nit_id,$con_por_con_porcentaje,$adi_otr_id,$con_por_con_observacion)
        {
            if($adi_otr_id=="")
		$adi_otr_id="NULL";
		$query="INSERT INTO dbo.contratos_informativos(contrato_id,concepto_id,nit_id,con_por_con_porcentaje,adi_otr_id,con_por_con_observacion)
    			VALUES($contrato_id,$concepto_id,$nit_id,$con_por_con_porcentaje,$adi_otr_id,'$con_por_con_observacion')";
                //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_ult_adi_otrosi($id)
	{
		$query="SELECT MAX(adi_otr_id) ultimo
				FROM adiciones_otrosi
				WHERE tip_adi_otr_id=$id";
                //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['ultimo'];
		}
		else
			return false;
	}
	
	public function con_ult_adi_otr_general()
	{
		$query="SELECT MAX(adi_otr_id) ultimo
				FROM adiciones_otrosi";
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['ultimo'];
		}
		else
			return false;
	}
	
	public function con_cen_cos_id_por_contrato($con_id){
		$query="SELECT cen_cos_id
				FROM contrato c
				LEFT JOIN nits n ON c.nit_id=n.nit_id
				LEFT JOIN centros_costo cc ON n.nit_id=cc.cen_cos_nit
				WHERE c.con_id=$con_id";
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['cen_cos_id'];
		}
		else
			return false;
	}
	
    //Fin Funciones para insertar el contrato por monto fijo
	
	//Inicio Funciones insertar constrato por jornadas
        public function ins_con_jornadas($con_jor_num_consecutivo,$con_jor_hospital,$con_jor_vigencia,$con_jor_valor,$con_jor_val_hor_trabajada,$con_jor_fec_inicial,$con_jor_fec_fin,$con_jor_estado,$observacion,$fec_legalizacion,$dias,$con_jor_val_hor_nocturna)
	{
		$query = "EXECUTE CrearContratoJornadas '$con_jor_num_consecutivo',$con_jor_hospital,$con_jor_vigencia,$con_jor_valor,$con_jor_val_hor_trabajada,'$con_jor_fec_inicial','$con_jor_fec_fin',$con_jor_estado,'$observacion','$fec_legalizacion',$dias,$con_jor_val_hor_nocturna";
	    $ejecutar = mssql_query($query);
		if($ejecutar)
			return true;
		else
			return false;
	}	
	//Fin Funciones insertar contrato por jornadas
	
	//Inicio insertar constrato administraci�n de IPS
	public function ins_con_adm_ips($con_adm_ips_num_consecutivo,$con_adm_ips_hospital,$con_adm_ips_vigencia,$con_adm_ips_valor,$con_adm_ips_cuo_mensual,$con_adm_ips_fec_inicial,$con_adm_ips_fec_fin,$con_adm_ips_estado,$con_adm_ips_est_legalizado)
	{
		$query = "EXECUTE CrearContratoAdminIPS '$con_adm_ips_num_consecutivo',$con_adm_ips_hospital,$con_adm_ips_vigencia,$con_adm_ips_valor,$con_adm_ips_cuo_mensual,'$con_adm_ips_fec_inicial','$con_adm_ips_fec_fin',$con_adm_ips_estado,$con_adm_ips_est_legalizado";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return true;
		else
			return false;
	}
	//Fin insertar constrato administraci�n de IPS
	
	public function contrato($nit)
	{
		$sql = "SELECT con.con_id con_id,con.est_con_id est_con_id,con.tip_con_ext_id tip_con_ext_id, 
	               con.tip_con_pre_id tip_con_pre_id,con.con_valor con_valor,con.con_val_fac_mensual con_val_fac_mensual,con.con_val_hor_trabajada con_val_hor_trabajada,con.con_fac_vencimiento dias,ciu.ciu_nombre ciu_nombre 
			    FROM contrato con
			    INNER JOIN factura f ON con.con_id=f.fac_contrato
			    LEFT JOIN nits_por_ciudades npc ON con.nit_id = npc.nit_id 
				LEFT JOIN ciudades ciu ON npc.ciu_id = ciu.ciu_id
				WHERE con.est_con_id=1 AND con.tip_con_ext_id = 2 AND con.nit_id = $nit";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;	
	}

	/*public function contratoServicio($nit,$mes,$ano,$fecha)
	{
		$sql = "SELECT * FROM contrato WHERE nit_id=$nit AND CAST('$fecha' AS DATETIME) between CAST(con_fec_inicio AS DATETIME)
		AND CAST(con_fec_fin AS DATETIME)";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;	
	}*/
	
	
	public function contratoServicio($nit,$mes,$ano,$fecha)
	{
		$sql = "SELECT TOP 1 * FROM contrato WHERE nit_id='$nit' AND est_con_id=1 ORDER BY con_id DESC";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;	
	}

        //INICIO CONSULTAR CONSTRATO EXTERNO POR ADMIN IPS
        public function consultar_todos_contratos_externos($est_con_id)
        {

                $principal="1169,";
                $lacadena=$_SESSION['k_cen_costo'];
                $comparacion=strpos($lacadena,$principal);
                if($comparacion===false)
                {
                		//NO PERTENECE AL CENTRO DE COSTO PRINCIPAL(ES AUXILIAR DE NUCLEO)
                        $query = "EXECUTE ConsContExt1 $est_con_id,$_SESSION[k_nit_id]";
                }
                else
                {
                		//PERTENECE AL CENTRO DE COSTO PRINCIPAL
                        $query="SELECT con.con_id,nit.nit_id,nit.nits_nombres,nit.nits_num_documento,con.tip_con_ext_id,con.con_hos_consecutivo
                                        FROM dbo.contrato con INNER JOIN dbo.nits nit
                                        ON con.nit_id = nit.nit_id
                                        INNER JOIN centros_costo cc ON cc.cen_cos_nit=nit.nit_id
                                        WHERE con.tip_con_id = 2 AND con.est_con_id=$est_con_id";
                }
                $ejecutar = mssql_query($query);
                return $ejecutar;
        }

        public function consultar_un_contrato_externo1($id_contrato)
        {
                $query = "EXECUTE ConsContExt2 $id_contrato";
                $ejecutar = mssql_query($query);
                return $ejecutar;
        }

        public function consultar_un_contrato_externo2($id_contrato)
        {
                $query = "EXECUTE ConsContExt3 $id_contrato";
                $ejecutar = mssql_query($query);
                return $ejecutar;
        }

        public function consultar_poliza_o_impuesto($id_contrato)
        {
                $query="EXECUTE ConsContExtPolImp $id_contrato";
                //echo $query;
                $ejecutar = mssql_query($query);
                return $ejecutar;
        }
        
        public function consultar_poliza_o_impuesto_informativo($contrato_id)
        {
                $query = "EXECUTE ConsContExtPolImpInformativo $contrato_id";
                //echo $query;
                $ejecutar = mssql_query($query);
                return $ejecutar;
        }
        
         public function consultar_poliza_o_impuesto_informativo_adicion_otrosi($contrato_id)
        {
                $query = "EXECUTE ConsContExtPolImpInformativoAdicionOtrosi $contrato_id";
                //echo $query;
                $ejecutar = mssql_query($query);
                return $ejecutar;
        }
        
        public function ConAfiConPrestacion($con_id,$tip_nit)
        {
            $query="SELECT n.nit_id,n.nits_num_documento,n.nits_nombres,n.nits_apellidos
                    FROM nits_por_cen_costo npcc
                    INNER JOIN nits n ON npcc.nit_id=n.nit_id
                    WHERE cen_cos_id=((SELECT cen_cos_id FROM centros_costo WHERE cen_cos_nit=(SELECT nit_id FROM contrato WHERE con_id=$con_id))) AND tip_nit_id=$tip_nit";
            //echo $query;
            $ejecutar=mssql_query($query);
            if($ejecutar)
                return $ejecutar;
            else
                return false;
            
        }
        //FIN CONSULTAR CONTRATO EXTERNO

        //INICIO METODOS AGREGAR P�LIZA ADMIN IPS

        public function agregar_otra_poliza_contrato($sel_contrato,$con_adm_ips_nom_pol_aseguradora_2,$con_adm_ips_con_pol_nombre_2,$con_adm_ips_pol_porcentaje_2,$con_estado,$con_observacion)
        {
        	$fecha =date('d-m-Y');
            $query="EXECUTE AgrNuePolContrato $sel_contrato,$con_adm_ips_nom_pol_aseguradora_2,$con_adm_ips_con_pol_nombre_2,$con_adm_ips_pol_porcentaje_2,$con_estado,'$con_observacion','$fecha'";
            $ejecutar = mssql_query($query);
            if($ejecutar)
                return $ejecutar;
            else
                return false;
        }
        
        public function agregar_otra_poliza_contrato_informativo($sel_contrato,$con_adm_ips_nom_pol_aseguradora_2,$con_adm_ips_con_pol_nombre_2,$con_adm_ips_pol_porcentaje_2,$con_observacion)
        {	
        	$fecha =date('d-m-Y');
            $query="EXECUTE AgrNuePolContratoInformativo $sel_contrato,$con_adm_ips_nom_pol_aseguradora_2,$con_adm_ips_con_pol_nombre_2,$con_adm_ips_pol_porcentaje_2,'$con_observacion','$fecha'";
            $ejecutar = mssql_query($query);
            if($ejecutar)
                return $ejecutar;
            else
                return false;
        }
        //FIN METODOS AGREGAR P�LIZA ADMIN IPS

        /////////////////////////////////OTROSI O ADICIONES//////////////////////////////////
        public function con_adi_o_otrosi(){
                $query = "SELECT * FROM adicion_o_otrosi";
                $ejecutar = mssql_query($query);
                if($ejecutar)
                return $ejecutar;
                else
                return false;
        }


        public function con_tip_adi_otrosi($id){
                $query = "SELECT * FROM tipos_adicion_otrosi WHERE adi_o_otr_id = $id";
				//echo $query;
                $ejecutar = mssql_query($query);
                if($ejecutar)
                return $ejecutar;
                else
                return false;
        }

        public function con_con_pre_ser_ane_activo($tip_con_id,$tip_con_ext_id,$est_con_id){
                $query = "SELECT con.con_id,nit.nits_nombres nombres
                                  FROM contrato con INNER JOIN nits nit ON nit.nit_id = con.nit_id
                                  WHERE tip_con_id = $tip_con_id AND tip_con_ext_id = $tip_con_ext_id AND est_con_id = $est_con_id
                                  ORDER BY nits_nombres ASC";
                $ejecutar = mssql_query($query);
                if($ejecutar)
                return $ejecutar;
                else
                return false;
        }

        public function ins_otr_adi_contrato($tip_adi_otr_id,$adi_otr_nota,$adi_otr_meses,$adi_otr_valor,$con_id,$adi_otr_fec_inicio,$adi_otr_fec_fin)
        {
                $fecha = date('d-m-Y');
                $query = "INSERT INTO dbo.adiciones_otrosi(tip_adi_otr_id,adi_otr_nota,adi_otr_meses,adi_otr_valor,con_id,adi_otr_fecha,adi_otr_fec_inicio,adi_otr_fec_fin)
        VALUES($tip_adi_otr_id,'$adi_otr_nota',$adi_otr_meses,$adi_otr_valor,$con_id,'$fecha','$adi_otr_fec_inicio','$adi_otr_fec_fin')";
                //echo $query;
                $ejecutar = mssql_query($query);
                if($ejecutar)
                        return $ejecutar;
                else
                        return false;
        }


        public function con_adi_otr_contrato($tip_adi_otr_id,$con_id){
                $query = "SELECT * FROM dbo.adiciones_otrosi ao INNER JOIN dbo.tipos_adicion_otrosi tao ON ao.tip_adi_otr_id = tao.tip_adi_otr_id INNER JOIN dbo.adicion_o_otrosi aoo ON aoo.adi_o_otr_id = tao.adi_o_otr_id WHERE aoo.adi_o_otr_id = $tip_adi_otr_id AND ao.con_id = $con_id";
                $ejecutar = mssql_query($query);
                if($ejecutar)
                return $ejecutar;
                else
                return false;
        }
        /////////////////////////////////////////////////////////////////////////////////////

        /*CONSULTAR LOS ASOCIADOS QUE PERTENECEN A UN CONTRATO SELECCIONADO*/
        public function con_aso_exi_contrato($tip_nit_id,$con_id){
                $query = "SELECT nit.nit_id,nit.nits_nombres,nit.nits_apellidos FROM nits nit INNER JOIN dbo.nits_por_cen_costo npcc ON npcc.nit_id = nit.nit_id INNER JOIN dbo.centros_costo cc ON cc.cen_cos_id = npcc.cen_cos_id INNER JOIN dbo.contrato con ON  con.nit_id = cc.cen_cos_nit WHERE tip_nit_id = $tip_nit_id AND con.con_id =  $con_id";
                $ejecutar = mssql_query($query);
                if($ejecutar)
                        return $ejecutar;
                else
                        return false;
        }
        ////////////////////////////////////////////////////////////////////////

        /*ELIMINAR LOS ASOCIADOS QUE PERTENECEN A UN CONTRATO*/
        //public function eli_aso_contrato($)
        ///////////////////////////////////////////////////////

        /*AGREGAR ASOCIADOS AL CONTRATO*/
        public function agr_nit_contrato($cen_cos,$nit_id){
                $query = "INSERT INTO dbo.nits_por_cen_costo(cen_cos_id,nit_id) VALUES($cen_cos,$nit_id)";
                $ejecutar = mssql_query($query);
                if($ejecutar)
                return $ejecutar;
                else
                return false;
        }
        /////////////////////////////////

        public function con_nit_cen_costo($cen_cos_nit){
                $query = "SELECT cen_cos_id FROM dbo.centros_costo WHERE cen_cos_nit = $cen_cos_nit";
                $ejecutar = mssql_query($query);
                if($ejecutar){
                $res = mssql_fetch_array($ejecutar);
                $resultado = $res['cen_cos_id'];
                return $resultado;
                }
                else
                return false;
        }
        
	public function ins_con_evento($con_jor_num_consecutivo,$con_jor_hospital,$con_jor_vigencia,$con_jor_valor,$con_jor_fec_inicial,$con_jor_fec_fin,$con_jor_estado,$observacion,$fec_legalizacion,$dias){
		$query="EXECUTE CrearContratoEvento '$con_jor_num_consecutivo',$con_jor_hospital,$con_jor_vigencia,$con_jor_valor,'$con_jor_fec_inicial','$con_jor_fec_fin',$con_jor_estado,'$observacion','$fec_legalizacion',$dias";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	public function desc_contrato($fac)
	{
		$sql = "SELECT cpc.concepto_id, cpc.con_por_con_porcentaje/(SELECT COUNT(*) FROM factura
		WHERE fac_estado!=5 AND fac_mes_servicio = (select fac_mes_servicio from factura where fac_id=$fac) and fac_cen_cos = (select fac_cen_cos from factura where fac_id=$fac) 
		and fac_ano_servicio = (select fac_ano_servicio from factura where fac_id=$fac) AND (fac_contrato IS NOT NULL OR fac_contrato!=0)) con_por_con_porcentaje,conc.con_nombre,form.for_cue_afecta2,con.con_vigencia,cpc.con_por_con_id 
		FROM contratos_por_conceptos cpc INNER JOIN factura fac on fac.fac_contrato = cpc.contrato_id
		INNER JOIN contrato con on con.con_id = fac.fac_contrato INNER JOIN conceptos conc on conc.con_id = cpc.concepto_id
		INNER JOIN formulas form on form.for_id = conc.form_for_id
		WHERE fac.fac_id = $fac AND con_activo=1 AND cpc.con_activo=1 AND con.con_vigencia > cpc.con_can_descu AND cpc.adi_otr_id IS NULL
		AND (fac.fac_contrato IS NOT NULL OR fac.fac_contrato!=0)";
		//echo $sql."<br>";
		$query = mssql_query($sql);
		if($query)
			return $query;
		else
			return false;
	}
	
	public function desc_adicion($fac)
	{
		$sql="SELECT cpc.concepto_id, cpc.con_por_con_porcentaje,conc.con_nombre,form.for_cue_afecta2,con.con_vigencia,
		cpc.con_por_con_id,cpc.adi_otr_id
		FROM contratos_por_conceptos cpc INNER JOIN factura fac on fac.fac_contrato = cpc.contrato_id
		INNER JOIN contrato con on con.con_id = fac.fac_contrato INNER JOIN conceptos conc on conc.con_id = cpc.concepto_id
		INNER JOIN formulas form on form.for_id = conc.form_for_id INNER JOIN adiciones_otrosi aos ON aos.adi_otr_id=cpc.adi_otr_id
		WHERE fac_estado!=5 AND fac.fac_id = $fac AND con_activo=1 AND cpc.con_activo=1 AND aos.adi_otr_meses > cpc.con_can_descu AND cpc.adi_otr_id IS NOT NULL";
		//echo $sql;
		$query = mssql_query($sql);
		if($query)
			return $query;
		else
			return false;
	}
	
	public function con_ult_contrato(){
		$query="SELECT MAX(con_id) ultimo FROM contrato";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['ultimo'];
		}
		else
			return false;
	}
	
	public function act_impuesto($impuesto)
	{
		$sql = "UPDATE contratos_por_conceptos SET con_can_descu=con_can_descu+1 WHERE con_por_con_id=$impuesto";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}
	
	public function ActDurContrato($nue_mes,$con_id){
		$query="UPDATE contrato SET con_vigencia = (con_vigencia+$nue_mes) WHERE con_id=$con_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function VerDisCenCos($cen_cos_id,$est_con_id){
		$query="SELECT COUNT(*) numero FROM contrato c
				INNER JOIN nits n ON c.nit_id=n.nit_id
				INNER JOIN centros_costo cc ON n.nit_id=cc.cen_cos_nit
				WHERE cc.cen_cos_id=$cen_cos_id AND c.est_con_id=$est_con_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['numero'];
		}
		else
			return false;
	}
	
	public function CamEstContrato($nue_estado,$con_id){
		$query="UPDATE contrato SET est_con_id=$nue_estado WHERE con_id=$con_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConContratoConAdiAtrosi($tip_con_id,$tip_con_ext_id,$est_con_id)
	{
		$query="SELECT DISTINCT con.con_id,con.con_hos_consecutivo+' - '+nit.nits_nombres nombres
				FROM contrato con INNER JOIN nits nit ON nit.nit_id = con.nit_id
				INNER JOIN adiciones_otrosi ao ON con.con_id=ao.con_id
				WHERE tip_con_id=$tip_con_id AND tip_con_ext_id=$tip_con_ext_id AND est_con_id=$est_con_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConAdiOtrPorContrato($con_id)
	{
		$query="SELECT * FROM adiciones_otrosi ao
				INNER JOIN tipos_adicion_otrosi tao ON ao.tip_adi_otr_id=tao.tip_adi_otr_id
				INNER JOIN adicion_o_otrosi aoo ON tao.adi_o_otr_id=aoo.adi_o_otr_id
				WHERE ao.con_id=$con_id";
                //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConTodDatAdioOtrosi($adi_otr_id)
	{
		$query="SELECT * FROM dbo.adiciones_otrosi WHERE adi_otr_id=$adi_otr_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConPolOImpPorAdicion($contrato_id,$adi_otr_id)
	{
		$query="SELECT cpc.con_por_con_id,n.nit_id,n.nits_nombres,c.con_id,c.con_nombre,cpc.con_por_con_porcentaje,cpc.con_por_con_observacion
				FROM contratos_por_conceptos cpc
				INNER JOIN nits n ON cpc.nit_id=n.nit_id
				INNER JOIN conceptos c ON cpc.concepto_id=c.con_id
				WHERE cpc.contrato_id=$contrato_id AND cpc.adi_otr_id=$adi_otr_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
        
        public function ConPolOImpInformativaPorAdicion($contrato_id,$adi_otr_id)
	{
		$query="SELECT ci.contrato_id,n.nit_id,n.nits_nombres,c.con_id,c.con_nombre,ci.con_por_con_porcentaje,ci.con_por_con_observacion
                FROM contratos_informativos ci
                INNER JOIN nits n ON ci.nit_id=n.nit_id
                INNER JOIN conceptos c ON ci.concepto_id=c.con_id
                WHERE ci.contrato_id=$contrato_id AND ci.adi_otr_id=$adi_otr_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function DiaVenFactura($elid)
	{
		if(is_numeric($elid))
		{
		    //echo "entra por el if";
			$query="SELECT con_fac_vencimiento FROM contrato WHERE con_id=$elid";
			$ejecutar=mssql_query($query);
			if($ejecutar)
			{
				$resultado=mssql_fetch_array($ejecutar);
				if($resultado['con_fac_vencimiento']==""||$resultado['con_fac_vencimiento']==0)
				{
					$query_2="SELECT * FROM variables WHERE var_id=8";
					$ejecutar_2=mssql_query($query_2);
					$res_dias=mssql_fetch_array($ejecutar_2);
					return $res_dias['var_valor'];
				}
				else
					return $resultado['con_fac_vencimiento'];
			}
		}
		else
		{
		    //echo "entra por el else";
			$query_2="SELECT * FROM variables WHERE var_id=8";
			$ejecutar_2=mssql_query($query_2);
			$res_dias=mssql_fetch_array($ejecutar_2);
			return $res_dias['var_valor'];
		}
	}
	
	public function actPoliza($poliza)
	{
		$sql = "UPDATE contratos_por_conceptos SET con_causado=1 WHERE con_por_con_id=$poliza";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;
	}
	
	public function ConTodConExterno($tipo)
	{
		$query="SELECT c.con_id,c.con_hos_consecutivo,n.nits_num_documento,n.nits_nombres,c.con_fec_inicio,c.con_fec_fin,
c.con_vigencia
				FROM contrato c
				INNER JOIN nits n ON c.nit_id=n.nit_id
				WHERE tip_con_id=$tipo
				ORDER BY c.con_id DESC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConDatAdiOtrSinopsis($elid)
	{
		$query="SELECT ao.adi_otr_meses,ao.adi_otr_fec_inicio,ao.adi_otr_fec_fin,tao.tip_adi_nombre,aoo.adi_o_otr_nombre,ao.adi_otr_valor,ao.adi_otr_nota,con_id
                        FROM adiciones_otrosi ao INNER JOIN tipos_adicion_otrosi tao ON ao.tip_adi_otr_id=tao.tip_adi_otr_id INNER JOIN adicion_o_otrosi aoo ON tao.adi_o_otr_id=aoo.adi_o_otr_id
                        WHERE adi_otr_id=$elid";
        //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado;
		}
		else
			return false;
	}
	
	public function ConTodAdiOtrsi()
	{
		$query="SELECT ao.adi_otr_meses,ao.adi_otr_fec_inicio,ao.adi_otr_fec_fin,tao.tip_adi_nombre,aoo.adi_o_otr_nombre,
ao.adi_otr_valor,ao.adi_otr_nota,c.con_hos_consecutivo,n.nits_num_documento,n.nits_nombres,c.con_id,ao.adi_otr_id
				FROM adiciones_otrosi ao
				INNER JOIN tipos_adicion_otrosi tao ON ao.tip_adi_otr_id=tao.tip_adi_otr_id
				INNER JOIN adicion_o_otrosi aoo ON tao.adi_o_otr_id=aoo.adi_o_otr_id
				INNER JOIN contrato c ON ao.con_id=c.con_id
				INNER JOIN nits n ON c.nit_id=n.nit_id
				ORDER BY n.nits_nombres ASC";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConDatPolImpAdiOtrSinopsis($concepto,$adi_otr_id)
	{
		$query="EXECUTE ConsContExtPolImpAdiOtrosi $concepto,$adi_otr_id";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
        
        
        //INICIO GUARDAR CONTRATO PRESTACI�N DE SERVICIOS DE ANESTESIA
        
        public function obt_nit_id($cen_cos_id)
	{
		$query="EXECUTE ObtenerIdNit $cen_cos_id";
                //echo $query;
                $ejecutar = mssql_query($query);
		return $ejecutar;
	}
        
    public function ins_con_prestacion($tip_con_prestacion,$con_num_consecutivo,$nit_id,$con_vigencia,$con_valor,
    	$con_cuo_mensual,$con_fec_inicial,$con_fec_fin,$con_estado,$con_est_legalizado,$observa,$dias,$con_val_hor_trabajada,$con_val_hor_nocturna,$cen_cos_id)
	{
		
		$usuario_actualizador=$_SESSION['k_nit_id'];
		$fecha_actualizacion=date('d-m-Y');
			
		$hora=localtime(time(),true);
		if($hora[tm_hour]==1)
			$hora_dia=23;
		else
			$hora_dia=$hora[tm_hour]-1;
		$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];			
        
        
			
		
    	$query="EXECUTE CrearContratoPrestacion
            $tip_con_prestacion,'$con_num_consecutivo',$nit_id,$con_vigencia,$con_valor,$con_cuo_mensual,'$con_fec_inicial','$con_fec_fin',	
            $con_estado,'$con_est_legalizado','$observa',$dias,$con_val_hor_trabajada,$con_val_hor_nocturna,$cen_cos_id,
            '$usuario_actualizador','$fecha_actualizacion','$hora_actualizacion'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        { return $ejecutar; }
        else
        { return false; }
	}
        
	public function ins_pol_o_imp_por_con_prestacion($con_pol_nombre,$con_nom_pol_aseguradora,$con_pol_porcentaje,$con_por_con_observacion)
	{
	    $fecha=date('d-m-Y');
		$query="EXECUTE InserPoloImpConPrestacion $con_pol_nombre,$con_nom_pol_aseguradora,$con_pol_porcentaje,'$con_por_con_observacion','$fecha'";
                //echo
                $ejecutar=mssql_query($query);
		if($ejecutar)
                    return $ejecutar;
		else
                    return false;
	}
        
	public function ins_pol_o_imp_info_por_con_prestacion($con_pol_nombre,$con_nom_pol_aseguradora,$con_pol_porcentaje,$con_por_con_observacion)
	{
	    $fecha=date('d-m-Y');
		$query="EXECUTE InserPoloImpInfoConPrestacion $con_pol_nombre,$con_nom_pol_aseguradora,$con_pol_porcentaje,'$con_por_con_observacion','$fecha'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
                    return $ejecutar;
		else
                    return false;
	}
        
	public function ins_aso_por_cen_cos_contrato($aso_cen_costos,$con_hospital)
	{
		$query = "EXECUTE InserAsoContrato $aso_cen_costos,$con_hospital";
		$ejecutar = mssql_query($query);
		if($ejecutar)
			return true;
		else
			return false;
	}    
        //INICIO GUARDAR CONTRATO PRESTACI�N DE SERVICIOS DE ANESTESIA
        
	public function ActDacConPrestacion($con_hos_consecutivo,$con_fac_vencimiento,$con_val_hor_trabajada,$con_val_hor_nocturna,$est_con_id,$con_fec_leg,$tip_con_pre_id,$con_observacion,$con_id)
	{
		$usuario_actualizador=$_SESSION['k_nit_id'];
		$fecha_actualizacion=date('d-m-Y');
			
		$hora=localtime(time(),true);
		if($hora[tm_hour]==1)
			$hora_dia=23;
		else
			$hora_dia=$hora[tm_hour]-1;
		$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];
		
		$tip_mov_aud_id=2;//ACTUALIZACION DE CONTRATO
		
		$aud_con_descripcion='MODIFICACION DE CONTRATO PRESTACION DE SERVICIOS DE ANESTESIA';
		
    	$query="UPDATE contrato SET con_hos_consecutivo='$con_hos_consecutivo',
    	con_fac_vencimiento='$con_fac_vencimiento',con_val_hor_trabajada=$con_val_hor_trabajada,
    	con_val_hor_nocturna=$con_val_hor_nocturna,est_con_id=$est_con_id,con_fec_leg='$con_fec_leg',
    	tip_con_pre_id=$tip_con_pre_id,con_observacion='$con_observacion'
    	WHERE con_id=$con_id";
        $ejecutar=mssql_query($query);
        if($ejecutar)
		{
			$con_ult_reg_actualizado="SELECT MAX(aud_con_id) aud_con_id FROM AUDITORIA_CONTRATOS";
			$eje_ult_reg_actualizado=mssql_query($con_ult_reg_actualizado);
			$res_ult_reg_actualizado=mssql_fetch_array($eje_ult_reg_actualizado);
			
			
			//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA EL AJUSTE
			$que_aud_contrato="UPDATE AUDITORIA_CONTRATOS SET
			aud_con_usuario='$usuario_actualizador',aud_con_fecha='$fecha_actualizacion',
			aud_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
			aud_con_descripcion='$aud_con_descripcion'
			WHERE aud_con_id='".$res_ult_reg_actualizado['aud_con_id']."'
			AND tip_mov_aud_id IS NULL";
			//echo $que_aud_contrato;
			$eje_aud_contrato=mssql_query($que_aud_contrato);
			
        	return $ejecutar;
		}
         else
         	return false;
	}
        
    public function ConsultarContratosActivos($tip_con_id,$tip_con_ext_id)
    {
    	$query="SELECT c.con_id,n.nit_id,n.nits_num_documento,n.nits_nombres,c.con_hos_consecutivo,c.con_vigencia,c.con_valor,SUM(cpc.con_por_con_porcentaje) val_legalizacion,c.con_fec_inicio,c.con_fec_fin
                    FROM contrato c INNER JOIN nits n ON c.nit_id=n.nit_id
                    LEFT JOIN contratos_por_conceptos cpc ON c.con_id=cpc.contrato_id
                    WHERE tip_con_id=$tip_con_id AND tip_con_ext_id=$tip_con_ext_id
                    GROUP BY c.con_id,n.nit_id,n.nits_num_documento,n.nits_nombres,c.con_hos_consecutivo,c.con_vigencia,c.con_valor,c.con_fec_inicio,c.con_fec_fin";
        $ejecuar=mssql_query($query);
        if($ejecuar)
        	{ return $ejecuar; }
        else
        	{ return false; }
	}
        
        public function ConsultarAfiliadosPorContrato($tip_nit_id,$con_id)
        {
            $query="SELECT DISTINCT n.nits_apellidos,n.nits_nombres,n.nits_num_documento
                    FROM nits n INNER JOIN nits_por_cen_costo npcc ON n.nit_id=npcc.nit_id
                    INNER JOIN centros_costo cc ON cc.cen_cos_id=npcc.cen_cos_id
                    INNER JOIN contrato c ON cc.cen_cos_nit=c.nit_id
                    WHERE n.tip_nit_id=$tip_nit_id AND c.con_id=$con_id
                    ORDER BY n.nits_apellidos ASC";
            $ejecutar=mssql_query($query);
            if($ejecutar)
            { return $ejecutar; }
            else
            { return false; }
        }
        
        public function ConCanMesAdiOtrPorContrato($con_id)
        {
            $query="SELECT SUM(adi_otr_meses) can_meses FROM adiciones_otrosi WHERE con_id=$con_id";
            $ejecutar=mssql_query($query);
            if($ejecutar)
            {
                $res_meses=mssql_fetch_array($ejecutar);
                if($res_meses['can_meses']=="NULL"||$res_meses['can_meses']=="")
                { $res_meses['can_meses']=0; }

                return $res_meses['can_meses'];
                
            }
            else
            { return false; }
        }
        
        public function ConAdiOtrPorConActivo($con_id)
        {
            $query="SELECT ao.adi_otr_id,adi_otr_fecha,aoo.adi_o_otr_id,aoo.adi_o_otr_nombre,tao.tip_adi_otr_id,tao.tip_adi_nombre,ao.adi_otr_meses,ao.adi_otr_valor,ao.adi_otr_nota,ao.adi_otr_fec_inicio,adi_otr_fec_fin
                    FROM adiciones_otrosi ao
                    INNER JOIN tipos_adicion_otrosi tao ON ao.tip_adi_otr_id=tao.tip_adi_otr_id
                    INNER JOIN adicion_o_otrosi aoo ON tao.adi_o_otr_id=aoo.adi_o_otr_id
                    WHERE ao.con_id=$con_id";
            $ejecutar=mssql_query($query);
            if($ejecutar)
            { return $ejecutar; }
            else
            { return false; }
        }
		
		public function ConDatConPorNit($nit_id)
        {
            $query="SELECT * FROM contrato WHERE nit_id=$nit_id";
            $ejecutar=mssql_query($query);
            if($ejecutar)
            { return $ejecutar; }
            else
            { return false; }
        }
		
		public function GuaConEmpleado($nit_id,$per_pag_nit_id,$con_fec_inicio,$con_fec_fin,$tip_nit_id)
        {
        	
        	$usuario_actualizador=$_SESSION['k_nit_id'];
			$fecha_actualizacion=date('d-m-Y');
			
			$hora=localtime(time(),true);
			if($hora[tm_hour]==1)
				$hora_dia=23;
			else
				$hora_dia=$hora[tm_hour]-1;
			$hora_actualizacion=$hora_dia.":".$hora[tm_min].":".$hora[tm_sec];			
        	
        	$tip_mov_aud_id=2;//MODIFICACION CONTRATO
        	$aud_mov_con_descripcion='ACTUALIZAR EMPLEADO - DATOS CONTRATO';
			
        	
            $query1="SELECT con_nit_id FROM dbo.nits_tipos WHERE nit_tip_id=$tip_nit_id";
            $ejecutar1=mssql_query($query1);
            if($ejecutar1)
            {
				$res_datos=mssql_fetch_array($ejecutar1);
				$consecutivo_contrato=$res_datos['con_nit_id']+1;
				$query2="UPDATE dbo.nits_tipos SET con_nit_id = con_nit_id+1 WHERE nit_tip_id=$tip_nit_id";
				$ejecutar2=mssql_query($query2);
				if($ejecutar2)
				{
					$query3="INSERT INTO contrato(nit_id,per_pag_nit_id,con_fec_inicio,con_fec_fin,con_consecutivo) 
   							VALUES('$nit_id','$per_pag_nit_id','$con_fec_inicio','$con_fec_fin','$consecutivo_contrato')";
					$ejecutar3=mssql_query($query3);
					if($ejecutar3)
					{
						$ult_contrato=$this->con_ult_contrato();
						
						//SE GUARDA EN LA TABLA DE AUDITORIA AUDITORIA
						$que_aud_contrato="UPDATE AUDITORIA_CONTRATOS SET
						aud_con_usuario='$usuario_actualizador',aud_con_fecha='$fecha_actualizacion',
						aud_con_hora='$hora_actualizacion',tip_mov_aud_id='$tip_mov_aud_id',
						aud_con_descripcion='$aud_mov_con_descripcion'
						WHERE con_id=$ult_contrato
						AND tip_mov_aud_id IS NULL";
						//echo $que_aud_mov_contable;
						$eje_aud_contrato=mssql_query($que_aud_contrato);
						
						
						return $ejecutar3;
					}
					else
					{ return false; }
				}
				else
				{ return false; }
			}
            else
            { return false; }
        }
		
		
		public function GuardarLegalizacionDescontable($contrato_id,$concepto_id,$nit_id,$con_por_con_porcentaje,$con_can_descu,$con_activo,$adi_otr_id,$con_por_con_observacion)
		{
			$query="INSERT INTO contratos_por_conceptos(contrato_id,concepto_id,nit_id,con_por_con_porcentaje,con_can_descu,con_activo,adi_otr_id,con_por_con_observacion)
		  			VALUES('$contrato_id','$concepto_id','$nit_id','$con_por_con_porcentaje','$con_can_descu','$con_activo','$adi_otr_id','$con_por_con_observacion')";
			$ejecutar=mssql_query($query);
			if($ejecutar)
				return $ejecutar;
			else
				return false;
		}
		
		public function GuardarLegalizacionInformativo($contrato_id,$concepto_id,$nit_id,$con_por_con_porcentaje,$adi_otr_id,$con_por_con_observacion)
		{
			$query="INSERT INTO contratos_informativos(contrato_id,concepto_id,nit_id,con_por_con_porcentaje,adi_otr_id,con_por_con_observacion)
		  			VALUES('$contrato_id','$concepto_id','$nit_id','$con_por_con_porcentaje','$adi_otr_id','$con_por_con_observacion')";
			$ejecutar=mssql_query($query);
			if($ejecutar)
				return $ejecutar;
			else
				return false;
		}
}
?>