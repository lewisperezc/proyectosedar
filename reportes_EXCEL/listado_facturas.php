<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=CarteraHospital");
header("Pragma: no-cache");
header("Expires: 0");

include_once('../clases/factura.class.php');
include_once('../clases/recibo_caja.class.php');
$ins_factura=new factura();
$ins_rec_caja=new rec_caja();
$ano=$_GET['elanio'];
//echo "el anio es: ".$ano."<br>";
if($_GET['eltipo']==1)//POR MES
	$con_dat_factura=$ins_factura->RepLisFacturas($_GET['elmes'],$_GET['eltipo'],$ano);
elseif($_GET['eltipo']==2)//TODAS
	$con_dat_factura=$ins_factura->RepLisFacturas(0,$_GET['eltipo'],0);
$filas=mssql_num_rows($con_dat_factura);
?>
<link rel="stylesheet" href="../librerias/jquery-ui-1.10.3.custom/development-bundle/themes/base/jquery.ui.all.css">

<script src="../librerias/jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>

<script type="text/javascript" src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.position.js"></script>
<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../librerias/jquery-ui-1.10.3.custom/development-bundle/ui/jquery.ui.dialog.js"></script>
<link rel="stylesheet" href="../librerias/jquery-ui-1.10.3.custom/development-bundle/demos/demos.css">
<script>
function Error()
{
	$(function(){
	$("#dialog-message" ).dialog({
	modal: true,
	buttons: {
	"Aceptar": function() {
	$( this ).dialog("close");
	window.close();
	}
	}
	});
	});
	//window.close();
}
</script>
<!--MENSAJE DE ERROR-->
<!--
<div id="dialog-message" title="Error" style="display:none;">
	<p><span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>No se encontro informacion relacionada con los datos ingresados</p>
</div>
-->
<?php
if($filas>0)
{
?>
	<table border="1">
		<tr>
			<th colspan="5">LISTADO DE FACTURAS</th>
		</tr>
		<tr>
                    <th>Fecha</th>
                    <th>Factura</th>
                    <th>NIT</th>
                    <th>C. Costo</th>
                    <th>Val. Unitario</th>
                    <th>Mes Servicio</th>
		</tr>
		<?php
		$suma_facturas=0;
		while($res_tod_datos=mssql_fetch_array($con_dat_factura))
		{
		?>
			<tr>
            	<td><?php echo $res_tod_datos['fac_fecha']; ?></td>
                <td><?php echo $res_tod_datos['fac_consecutivo']; ?></td>
                <td><?php echo $res_tod_datos['nits_num_documento']; ?></td>
                <td><?php echo $res_tod_datos['cen_cos_nombre']; ?></td>
                <?php $nota=$ins_rec_caja->saldoNotas($res_tod_datos['fac_id']); ?>
                <td><?php echo $res_tod_datos['fac_val_unitario']+$nota; ?></td>
                <td><?php echo $res_tod_datos['fac_mes_servicio']; ?></td>
			</tr>
		<?php
		}
		?>
	</table>
<?php
}
else
	echo "<script>Error();</script>";
?>