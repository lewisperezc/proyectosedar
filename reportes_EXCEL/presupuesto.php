<?php
@include_once('../clases/centro_de_costos.class.php');
@include_once('../clases/presupuesto.class.php');
@include_once('clases/centro_de_costos.class.php');
@include_once('clases/presupuesto.class.php');
$ins_cen_costo=new centro_de_costos();
$ins_presupuesto=new presupuesto();

$variables=$_GET['variables'];
$partir=split("-",$variables,2);
$cen_cos_id=$partir[0];
$fecha=$partir[1];

$con_nom_cen_cos=$ins_cen_costo->con_cen_cos_pabs($cen_cos_id);
$res_nom_cen_cos=mssql_fetch_array($con_nom_cen_cos);
$con_tod_datos=$ins_presupuesto->con_tod_pre_por_cen_cos_fecha($cen_cos_id,$fecha);
$filas=mssql_num_rows($con_tod_datos);
if($filas>0){
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=RepPresupuesto");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
	<tr>
    	<th colspan="4">PRESUPUESTO ASIGNADO EN EL <?php echo $pre_fecha; ?> AL CENTRO DE COSTO <?php echo $res_nom_cen_cos['cen_cos_nombre']; ?></th>
    </tr>
    <tr>
    	<th>Cuenta</th>
        <th>Presupuesto</th>
        <th>Gastado</th>
        <th>Diferencia</th>
    </tr>
    <?php
	$i=0;
    while($res_tod_datos=mssql_fetch_array($con_tod_datos)){
	//$diferencia=0;
	?>
    <tr>
    	<td><?php echo $res_tod_datos['cue_nombre']; ?></td>
        <td><?php echo number_format($res_tod_datos['cue_por_cen_cos_presupuesto']); ?></td>
        <?php
        $res_gastado=$ins_presupuesto->con_pre_gastado($centro,$pre_cuenta[$i],$pre_fecha);
		?>
        <td><?php echo $res_gastado; ?></td>
        <?php
        $diferencia=$res_tod_datos['cue_por_cen_cos_presupuesto']-$res_gastado;
		?>
        <td><?php echo number_format($diferencia); ?></td>
    </tr>
    <?php
	$i++;
	}
	?>
</table>
<?php
}
else{
	echo "<script>
			alert('No se encontro presupuesto asignado al centro de costos en el año Seleccionado.');
			window.history.back(1);
		  </script>";
}
?>