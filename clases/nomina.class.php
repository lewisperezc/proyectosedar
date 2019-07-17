<?php
	@include_once('../conexion/conexion.php');
	@include_once('conexion/conexion.php');
	
class nomina
{
	public function guardar_compensacion($pago_mes,$valor,$nit,$compensacion,$rec_caja,$consecutivo)
	{
		$fecha = date('d-m-Y');
		$sql = "INSERT INTO nomina VALUES('$pago_mes',$valor,'$fecha','$nit',1,$rec_caja,2,$consecutivo)";
                //INSERT INTO nomina VALUES(,19974451.37,25-09-2014,,1,1481,2,1875)
                //echo "Los datos de la nomina: ".$sql."<br>";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;
	}
	
	public function consultar_compensacion($recibo,$cedula)
	{	
		$con_recibo = "SELECT rec_caj_consecutivo FROM recibo_caja WHERE rec_caj_id = $recibo";
		$con_query = mssql_query($con_recibo);
		if($con_query)
		{
		  $dat_con = mssql_fetch_array($con_query);
		  $sql = "SELECT * FROM transacciones WHERE trans_sigla = 'REC-CAJ_".$dat_con[rec_caj_consecutivo]."'";
		  $query = mssql_query($sql);
		  if($query)
		   {
			  $tran = mssql_fetch_array($query);
			  $dato = $tran['trans_id'];
			  $dat_nominas = "SELECT * FROM transacciones tra INNER JOIN nits nit 
			  ON tra.trans_nit = nit.nit_id WHERE tra.tran_tran_id = $dato AND nit.nits_num_documento = $cedula";
			  return $dat_nom_query = mssql_query($dat_nominas);
		   }
		}
		else
		  return false;
	}
	
	public function dat_compensacion($comprobante)
	{
		$sql = "SELECT mov_valor FROM movimientos_contables WHERE mov_compro = '$comprobante'";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;
	}
	public function con_nom_por_nit_estado($nit_id,$nom_estado)
	{
		$query = "SELECT nom_id FROM nomina WHERE nom_nit_aso = $nit_id AND nom_estado = $nom_estado";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		return $ejecutar;
		else
		return false;
	}
	
	public function aux_inmobiliario()
	{
		$sql = "SELECT dat_can_nom_compensacion FROM datos_nomina WHERE dat_nom_id = 1";
		$query = mssql_query($sql);
		if($query)
		 {
			$max = mssql_fetch_array($query);
			return $max['dat_can_nom_compensacion'];
		 }
		else
		 return false; 
	}
	
	public function gua_fondos($soli,$subsis16,$subsis17,$subsis18,$subsis19,$subsismayor)
	{
		$sql = "UPDATE por_pension SET adic_solidaridad = $soli, adic_subsis17=$subsis16, adic_subsis18=$subsis17, adic_subsis19=$subsis18, adic_subsis20=$subsis19, adic_subsismayor=$subsismayor";
		$query = mssql_query($sql);
		if($query)
		  return true;
		else
		  return false;  
	}
	
	public function consul_fondos()
	{
		$sql = "SELECT * FROM por_pension";
		$query = mssql_query($sql);
		if($query)
		  return $query;
		else
		  return false;  
	}
	
	
	public function trae_datos_nomina($sigla,$nit,$tipo){
		$query="SELECT mov_nume, mov_compro,n.nits_num_documento,n.nit_id,nits_apellidos+' '+nits_nombres nombre,nit_est_id,mov_cuent,cue_nombre,mov_valor 
				FROM movimientos_contables inner join cuentas c on c.cue_id = mov_cuent
				INNER JOIN nits n ON CAST(n.nit_id as VARCHAR(20)) = mov_nit_tercero
		        WHERE mov_compro = '$sigla' AND mov_nit_tercero LIKE('$nit%') AND mov_tipo = $tipo";
		//echo $query;				
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function trae_cuentas_nomina($sigla,$nit,$tipo,$cuenta){
		$query="SELECT mov_valor 
			    FROM movimientos_contables
			    WHERE mov_compro = '$sigla' AND mov_nit_tercero LIKE('$nit%') AND mov_tipo = $tipo
			    AND mov_cuent IN('$cuenta')";
				
				/*if($nit=='2952' && $cuenta='253010061')
			    	echo $query;*/
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['mov_valor'];
		}
		else
			return false;
	}
	
	public function trae_cuentas_fabs($sigla,$nit,$tipo)
	{
		$query="SELECT SUM(mov_valor) mov_valor 
		FROM movimientos_contables
		WHERE mov_compro = '$sigla' AND mov_nit_tercero LIKE('$nit%') AND mov_tipo = $tipo
		AND mov_cuent IN(SELECT dis_por_con_fab_cue_fondo FROM distribucion_porcentajes_conceptos_fabs)";
		/*if($nit=='2952' && $cuenta='253010061')
			echo $query;*/
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['mov_valor'];
		}
		else
			return false;
	}
	
	public function trae_cuentas_nomin_no($sigla,$nit,$tipo,$cuenta,$recibo){
		$query="SELECT mov_valor 
			    FROM movimientos_contables
			    WHERE mov_compro = '$sigla' AND mov_nit_tercero LIKE('$nit%') AND mov_tipo = $tipo AND mov_cuent='$cuenta' AND mov_doc_numer = '$recibo'";
		//if($nit=='2364' && $cuenta='23651501')
			//    	echo $query;	  
        
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['mov_valor'];
		}
		else
			return false;
	}
	
	public function trae_val_hon_no_afiliado($sigla,$nit,$tipo,$cuenta){
		$query="SELECT SUM(mov_valor) mov_valor 
			    FROM movimientos_contables
			    WHERE mov_compro = '$sigla' AND mov_nit_tercero LIKE('$nit%') AND mov_tipo = $tipo AND mov_cuent='$cuenta'";
                //echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['mov_valor'];
		}
		else
			return false;
	}
	
	public function trae_descuentos_por_factura($fac_id){
		$query="SELECT SUM(des_monto) descuentos
				FROM descuentos d
				INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id
				INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id
				WHERE f.fac_id=$fac_id";
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['descuentos'];
		}
		else
			return false;
	}
/////////////////////////////////////////////////////INICIO NOMINA ADM/////////////////////////////////////////////////
	public function con_nom_causadas($mov_compro,$estado,$ano){
		$query="SELECT DISTINCT mov_compro,mov_nume,trans_val_total,mov_ano_contable,mov_fec_elabo,mov_mes_contable,mov_concepto
FROM movimientos_contables mc INNER JOIN transacciones t ON mc.mov_compro=t.trans_sigla
				WHERE mov_compro LIKE('$mov_compro%') AND t.estado_nomina_admin=$estado AND mov_ano_contable=$ano";
		//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_dat_nomina($documento){
		$query="SELECT DISTINCT trans_fac_num,trans_fec_vencimiento,mov_mes_contable
				FROM transacciones t INNER JOIN movimientos_contables mc ON t.trans_sigla=mc.mov_compro
				WHERE trans_sigla LIKE('CAU_NOM_ADM-$documento')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_dat_mov_con_nomina($documento)
	{
		$query="SELECT *
				FROM transacciones t
				INNER JOIN movimientos_contables mc ON t.trans_sigla=mc.mov_compro
				WHERE mc.mov_nume='$documento'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_mov_nom_administrativa($documento){
		$query="SELECT * FROM movimientos_contables WHERE mov_compro='$documento'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_des_nomina($fac_id,$rec_caj_id,$nit_id,$facturado,$valor_factura)
    {
	   $query="SELECT * FROM descuentos d INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id
	   INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id WHERE f.fac_id=$fac_id AND des_tipo in(1,2)
	   AND rc.rec_caj_id=$rec_caj_id AND des_distribucion IS NULL and des_monto>0";
	   //echo $query;
	   $ejecutar=mssql_query($query);
	   if($ejecutar)
	   {
		   	$num_filas=mssql_num_rows($ejecutar);
		   	if($num_filas>0)
		   	{
				$que_distribucion="SELECT SUM(disGlo_valor) disGlo_valor FROM distglosa where disGlo_rec_caj_id=$rec_caj_id
			 	AND disGlo_nit=$nit_id AND disGlo_estado=1";
			 	$eje_distribucion=mssql_query($que_distribucion);
			 	$res_distribucion=mssql_fetch_array($eje_distribucion);
				$num_filas_1=mssql_num_rows($eje_distribucion);
				if($num_filas_1>0)
			 		return $res_distribucion['disGlo_valor'];
				else
					return 0;
			}
			else
			{
				
				$que_distribucion="SELECT * FROM descuentos d INNER JOIN recibo_caja rc ON d.des_factura=rc.rec_caj_id
				INNER JOIN factura f ON rc.rec_caj_factura=f.fac_id WHERE f.fac_id=$fac_id AND des_tipo in(1,2)
				AND rc.rec_caj_id=$rec_caj_id AND des_distribucion=0 and des_monto>0";
				//echo $que_distribucion;
			 	$eje_distribucion=mssql_query($que_distribucion);
			 	$res_distribucion=mssql_fetch_array($eje_distribucion);
			 	$num_filas_2=mssql_num_rows($eje_distribucion);
				//echo "datos: ".$facturado."___".$valor_factura."<br>";
			 	$porcentaje=$facturado*100/$valor_factura;
			 	if($num_filas_2>0)
			 		$tot_glo_individual=$facturado*$porcentaje/100;
				else
					$tot_glo_individual=0;
					
			 	return $tot_glo_individual;
			}
			
			//echo $que_distribucion;
	   }
	   else
	   	return false;
    }
	
	public function con_nom_causadas_y_pagadas($mov_compro,$ano)
	{
		$query="SELECT DISTINCT mov_compro,mov_nume,trans_val_total,estado_nomina_admin,mc.mov_mes_contable,
mc.mov_ano_contable,mc.mov_concepto,mov_fec_elabo,mov_ano_contable
				FROM movimientos_contables mc
				INNER JOIN transacciones t ON mc.mov_compro=t.trans_sigla
				WHERE mov_compro LIKE('$mov_compro%') AND mov_ano_contable=$ano";
				//echo $query;
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConNomPagadas($sigla,$cuenta,$quincena,$fecha,$doc_inicial,$doc_final)
	{
		$query="SELECT n.nits_nombres+' '+n.nits_apellidos as nombres,n.nits_num_documento,p.per_nombre as cargo,n.nits_salario,mc.mov_concepto,mc.mov_cuent,mc.mov_fec_elabo
				FROM nits n
				INNER JOIN perfiles p ON n.nit_perfil=p.per_id
				INNER JOIN movimientos_contables mc ON n.nit_id=mov_nit_tercero
				WHERE mc.mov_compro LIKE('$sigla%') AND mc.mov_cuent='$cuenta' AND mc.mov_concepto=$quincena
				AND mc.mov_fec_elabo LIKE('%$fecha') AND n.nits_num_documento BETWEEN '$doc_inicial' AND '$doc_final'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConsDatProNomAdministrativa()
	{
		$query="SELECT * FROM datos_provision_nomina_administrativa";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function ConArlNit($nit_id)
	{
		$query="SELECT tip_arl_emp_porcentaje
		FROM tipos_arl_empleados WHERE tip_arl_emp_id=(SELECT tip_arl_emp_id FROM nits WHERE nit_id='$nit_id')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['tip_arl_emp_porcentaje'];
		}
		else
		{
			return false;
		}
	}
	
	public function ConMesPagNomAdministrativa($mov_compro)
	{
		$query="SELECT DISTINCT mov_mes_contable FROM movimientos_contables WHERE mov_compro='$mov_compro'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
			$resultado=mssql_fetch_array($ejecutar);
			return $resultado['mov_mes_contable'];
		}
		else
		{
			return false;
		}
	}
}
?>