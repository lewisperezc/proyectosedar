<?php
@include_once('../conexion/conexion.php');

class orden_desembolso
{
	function GuardarOrdenDesembolso($ord_de_des_fec_cre_sistema,$ord_de_des_fec_cre_usuario,$ord_de_des_causacion,$ord_de_des_orden,$ord_de_des_val_neto,$ord_de_des_val_iva,$ord_de_des_val_retefuente,$ord_de_des_val_reteica,$ord_de_des_val_anticipos,$ord_de_des_val_por_pagar,$ord_de_des_nit,$ord_de_des_cen_costo,$ord_de_des_descripcion,$ord_de_des_observacion,$ord_de_des_mes_contable,$ord_de_des_ano_contable,$ord_de_des_cuenta,$ord_de_des_anticipo)
	{
		$query="INSERT INTO ordenes_de_desembolso(ord_de_des_fec_cre_sistema,ord_de_des_fec_cre_usuario,ord_de_des_causacion,ord_de_des_orden,ord_de_des_val_neto,ord_de_des_val_iva,ord_de_des_val_retefuente,ord_de_des_val_reteica,ord_de_des_val_anticipos,ord_de_des_val_por_pagar,ord_de_des_nit,ord_de_des_cen_costo,ord_de_des_descripcion,ord_de_des_observacion,ord_de_des_mes_contable,ord_de_des_ano_contable,ord_de_des_cuenta,ord_de_des_anticipo)
				VALUES('$ord_de_des_fec_cre_sistema','$ord_de_des_fec_cre_usuario','$ord_de_des_causacion','$ord_de_des_orden','$ord_de_des_val_neto','$ord_de_des_val_iva','$ord_de_des_val_retefuente','$ord_de_des_val_reteica','$ord_de_des_val_anticipos','$ord_de_des_val_por_pagar','$ord_de_des_nit','$ord_de_des_cen_costo','$ord_de_des_descripcion','$ord_de_des_observacion','$ord_de_des_mes_contable','$ord_de_des_ano_contable','$ord_de_des_cuenta','$ord_de_des_anticipo')";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
	
	function ActualizarCampoValor($nom_columna,$valor,$sigla,$mes_contable,$ano_contable)
	{
		$query="UPDATE ordenes_de_desembolso SET $nom_columna='$valor' WHERE ord_de_des_causacion='$sigla' AND ord_de_des_mes_contable='$mes_contable' AND ord_de_des_ano_contable='$ano_contable'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
			return $ejecutar;
		else
			return false;
	}
}
?>