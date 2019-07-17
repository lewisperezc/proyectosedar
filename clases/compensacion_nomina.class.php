<?php
@include_once('../conexion/conexion.php');
class compensacion_nomina
{
	public function causacion($consecutivo)
	{
		$sql = "SELECT * FROM movimientos_contables WHERE mov_compro = 'CAU-NOM-$consecutivo'";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function cau_pagada($consecutivo,$compensacion,$recibo_id)
	{
		//$sql = "SELECT * FROM movimientos_contables WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume = $consecutivo";
		$sql = "SELECT * FROM movimientos_contables WHERE mov_compro = '$compensacion' AND mov_nume = $consecutivo AND mov_doc_numer='$recibo_id'";
		//echo $sql; 
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function cau_pagConsulta($consecutivo)
	{
		$sql = "SELECT * FROM movimientos_contables WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume = $consecutivo";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function centro_fac($consecutivo)
	{
		$sql = "SELECT DISTINCT cen_cos_nombre FROM movimientos_contables INNER JOIN centros_costo on cen_cos_id=mov_cent_costo WHERE mov_compro LIKE ('PAG-COM-%') AND mov_nume = $consecutivo";
		$query = mssql_query($sql);
		if($query)
		{
		   $dat_centro = mssql_fetch_array($query);
		   return $dat_centro['cen_cos_nombre'];
		}
		else
		  return false;
	}
	
	public function ajuste($consecutivo,$recibo_id)
	{
		$sql = "SELECT * FROM movimientos_contables WHERE mov_compro = 'AJUS-NOM-$consecutivo' AND mov_doc_numer='$recibo_id'";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;   
	}
	
	public function compensacion($nit,$fabs,$retiro,$vaca,$nomina)//GUARDA LOS SALDOS DE LOS FONDOS
	{
		$sql = "INSERT INTO compensacion VALUES ($nit,$fabs,$retiro,$vaca,'$nomina')";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		   return false;   
	}
	
	/****Antes 22/11/2014  ***********
	public function con_compensacion($nit,$nomina)
	{
		$par_nomina = split("-",$nomina,3);
		$nomi = $par_nomina[0]."-".$par_nomina[1]."-";
		$fabs=0;$retiro=0;$vacaciones=0;
		for($i=0;$i<=$par_nomina[2];$i++)
		{
			$nomina = $nomi.$i;
			$sql = "SELECT * FROM compensacion WHERE nit_id=$nit AND nomina = '$nomina'";
                        //echo $sql;
			$query = mssql_query($sql);
			if($query)
			{
				$dat_compensacion = mssql_fetch_array($query);
				$fabs = $fabs+$dat_compensacion['fabs'];
				$retiro = $retiro+$dat_compensacion['retiro'];
				$vacaciones = $vacaciones+$dat_compensacion['vacaciones'];
			}
		}
		$retorna = $fabs."__".$retiro."__".$vacaciones;
                //echo $retorna;
		return $retorna;
	}*/
	
	public function con_compensacion($nit,$nomina)
	{
			$fabs=0;$retiro=0;$vacaciones=0;
			$sql = "SELECT * FROM compensacion WHERE nit_id=$nit AND nomina = '$nomina'";
			$query = mssql_query($sql);
			if($query)
			{
				$dat_compensacion = mssql_fetch_array($query);
				$fabs = $fabs+$dat_compensacion['fabs'];
				$retiro = $retiro+$dat_compensacion['retiro'];
				$vacaciones = $vacaciones+$dat_compensacion['vacaciones'];
			}
		$retorna = $fabs."__".$retiro."__".$vacaciones;
		return $retorna;
	}

	public function val_cobrar($comprobante,$cuenta,$nit)
	{
		$sql = "SELECT SUM(mov_valor) valor FROM movimientos_contables WHERE mov_compro='$comprobante' AND mov_cuent = $cuenta AND mov_nit_tercero like('$nit%')";
		$query = mssql_query($sql);
		if($query)
		  {
			 $res_query = mssql_fetch_array($query);
			 return $res_query['valor'];
		  }
	}
	
	public function pagoCausacion($anio_contable)
	{
	   $cue_com_nom_pagadas='25051001';
	   $des_por_pagar='25051009';
	   $sql="EXECUTE cuentasPagar $anio_contable,'$cue_com_nom_pagadas','$des_por_pagar'";
	   $query=mssql_query($sql);	   
	   $mes_contable = date('m');
		$sql = "select * from reportes order by cuatro";
		$query = mssql_query($sql);
		if($query)
		   return $query;
		else
		  return false;
	}
        
        
    public function ConsultarDescuentosCompensacion($des_nom_nit,$des_nom_factura,$des_nom_rec_caja)
    {
        $query="SELECT dc.*,cue_nombre FROM descuentos_compensacion dc
        INNER JOIN cuentas c ON dc.des_nom_cuenta=c.cue_id
        WHERE des_nom_nit=$des_nom_nit AND des_nom_factura='$des_nom_factura' AND des_nom_rec_caja='$des_nom_rec_caja'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        {return $ejecutar;}
        else
        {return false;}
    }
	
	public function ConsultarDescuentosLegalizacionAdicionales($rec_caja,$tipo_descuento,$tipo_adicion,$descripcion)
    {
        $query="SELECT * FROM descuentos WHERE des_factura='$rec_caja'
		AND des_tipo='$tipo_descuento' AND des_tip_adicion='$tipo_adicion' AND des_descripcion $descripcion";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        {return $ejecutar;}
        else
        {return false;}
    }
	
	
        
        
    public function EliminarDescuentoCompensacion($des_com_id)
    {
        $query="DELETE FROM descuentos_compensacion WHERE des_nom_id=$des_com_id";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        {return $ejecutar;}
        else
        {return false;}
    }
    
    public function GuardarDescuentoNominaAdministrativa($nit,$valor,$sigla_causacion,$cuenta,$fecha,$sigla_pago,$estado)
   {
        $query="INSERT INTO descuentos_nomina_administrativa(des_nom_adm_nit,des_nom_adm_valor,des_nom_adm_sigla_causacion,des_nom_adm_cuenta,des_nom_adm_fecha,des_nom_adm_sigla_pago,des_nom_adm_estado)
                VALUES('$nit',$valor,'$sigla_causacion','$cuenta','$fecha','$sigla_pago',$estado)";
        $ejecutar=mssql_query($query);
        if($ejecutar)
            return $ejecutar;
        else
            return false;

   }
        
    public function ConsultarDescuentosNominaAdministrativa($des_nom_nit,$des_nom_factura,$des_nom_rec_caja,$estado)
    {
        $query="SELECT dna.*,cue_nombre FROM descuentos_nomina_administrativa dna
        INNER JOIN cuentas c ON dna.des_nom_adm_cuenta=c.cue_id
        WHERE dna.des_nom_adm_nit='$des_nom_nit' AND dna.des_nom_adm_sigla_causacion='$des_nom_factura' AND dna.des_nom_adm_sigla_pago='$des_nom_rec_caja' AND des_nom_adm_estado='$estado'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        {return $ejecutar;}
        else
        {return false;}
    }
        
        
    public function EliminarDescuentoNominaAdministrativa($des_com_id)
    {
        $query="DELETE FROM descuentos_nomina_administrativa WHERE des_nom_adm_id=$des_com_id";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        {return $ejecutar;}
        else
        {return false;}
    }
    
    public function ActualizarOtroDescuento($des_nom_adm_sigla_causacion,$des_nom_adm_estado,$des_com_id)
    {
        $query="UPDATE descuentos_nomina_administrativa SET des_nom_adm_sigla_causacion='$des_nom_adm_sigla_causacion',des_nom_adm_estado='$des_nom_adm_estado' WHERE des_nom_adm_id='$des_com_id'";
        //echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
        {return $ejecutar;}
        else
        {return false;}
    }
    
    public function EliminarOtroDescuentoDescontabilizar($des_nom_adm_sigla_causacion,$des_nom_adm_sigla_pago,$des_nom_adm_estado)
    {
        $query="DELETE FROM descuentos_nomina_administrativa WHERE des_nom_adm_sigla_causacion='$des_nom_adm_sigla_causacion' AND des_nom_adm_sigla_pago='$des_nom_adm_sigla_pago' AND des_nom_adm_estado='$des_nom_adm_estado'";
        //echo $query."<br>";
        $ejecutar=mssql_query($query);
        if($ejecutar)
        {return $ejecutar;}
        else
        {return false;}
    }  
}
?>