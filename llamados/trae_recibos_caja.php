<?php
include_once('../clases/recibo_caja.class.php');
$ins_rec_caja = new rec_caja();
$datos=$_POST['fac_seleccionada'];
$par_datos=explode("-",$datos,4);
$fac_id=$par_datos[3];
$con_rec_caj_factura=$ins_rec_caja->ConRecCajPorFacEstado($fac_id,'0,2');
$numero_filas=mssql_num_rows($con_rec_caj_factura);
$res="";
if($numero_filas>0)
{
	$res.="<option value=''>Seleccione</option>";
	while($res_rec_caj_factura=mssql_fetch_array($con_rec_caj_factura))
	{
		$res.="<option value='".$res_rec_caj_factura['rec_caj_id']."-".$res_rec_caj_factura['rec_caj_monto']."-".$res_rec_caj_factura['rec_caj_consecutivo']."-".$fac_id."' onclick='ValFormulario(this.value-".$datos[0].");'>".$res_rec_caj_factura['rec_caj_id']." - ".$res_rec_caj_factura['rec_caj_monto']."</option>";
	}
}
else
{
	$res.="<script>ValFormulario('$datos');</script>";
}
echo $res;
?>