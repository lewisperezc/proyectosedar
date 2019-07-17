<?php
include_once('../conexion/conexion.php');
class presupuesto
{
	public function gua_presupuesto($cen_costo,$fecha,$cuenta,$valor)
	{
		$query = "INSERT INTO dbo.cuentas_por_centros_costo(cen_cos_id,cue_por_cen_cos_fecha,cue_id,cue_por_cen_cos_presupuesto)
				  VALUES($cen_costo,'$fecha',$cuenta,$valor)";
		$ejecutar = mssql_query($query);
		if($ejecutar){
			echo "<script>alert('Presupuesto Asignado Correctamente!!!');location.href='../index.php?c=122';</script>";	
			return $ejecutar;
		}
		else{
		echo "<script>alert('Error Al Asiganar El Presupuesto, Intentelo De Nuevo!!!');location.href='../index.php?c=122';</script>";	
		return false;
		}
	}
	
	public function con_cen_cos_con_presupuesto(){
		$query="SELECT DISTINCT cc.cen_cos_id,cc.cen_cos_nombre,cue_por_cen_cos_fecha
				FROM centros_costo cc
				INNER JOIN cuentas_por_centros_costo cpcc ON cc.cen_cos_id=cpcc.cen_cos_id";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function obtener_lista_anios()
        {
            $operacion=date("Y")-2011;
            //echo $operacion;
            $anios = array();
            for($i = date("Y"); $i >= date("Y") - $operacion; $i--)
                $anios[] = array($i);

                    for($a=0;$a<sizeof($anios);$a++)
                            $arreglo[$a]=$anios[$a][0];
            return $arreglo;
        }
	
	public function con_tod_pre_por_cen_cos_fecha($cen_cos_id,$fecha){
		$query="SELECT *
				FROM dbo.cuentas_por_centros_costo cpcc
				INNER JOIN cuentas c ON cpcc.cue_id=c.cue_id
				WHERE cen_cos_id=$cen_cos_id AND cue_por_cen_cos_fecha='$fecha'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	public function mod_presupuesto($valor,$cen_costo,$fecha,$cuenta){
		$query="UPDATE cuentas_por_centros_costo 
				SET cue_por_cen_cos_presupuesto='$valor'
				WHERE cen_cos_id='$cen_costo' AND cue_por_cen_cos_fecha='$fecha' AND cue_id='$cuenta'";
		$ejecutar=mssql_query($query);
		if($query)
			return $ejecutar;
		else
			return false;
	}
	
	public function con_pre_gastado($centro,$cuenta,$fecha){
		$query="SELECT SUM(mov_valor) suma
				FROM movimientos_contables
				WHERE mov_cent_costo='$centro' AND mov_cuent='$cuenta' AND mov_fec_elabo LIKE('%$fecha')";
		$ejecutar=mssql_query($query);
		if($ejecutar){
			$resultado=mssql_fetch_array($ejecutar);
				if($resultado['suma']=="")
					$resultado['suma']=0;
		return $resultado['suma'];
		}
		else
			return false;
	}
}
?>