<?php
@include_once('../conexion/conexion.php');

class Liquidacion_empleado
{
	public function ins_liq_empleado($reg_liq_emp_fec_retiro,$reg_liq_emp_observaciones,$nit_id,$creditos,$nominas)
	{
		$query = "INSERT INTO dbo.registro_liquidacion_empleado VALUES('$reg_liq_emp_fec_retiro','$reg_liq_emp_observaciones',$nit_id,'$creditos','$nominas')";
		$ejecutar = mssql_query($query);
		if($ejecutar)
		   return $ejecutar;
		else
		  return false;
	}
}
?>