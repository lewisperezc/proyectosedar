<?php session_start();
include_once('../clases/moviminetos_contables.class.php');
$ins_mov_contable=new movimientos_contables();
$con_dat_movimiento=$ins_mov_contable->con_mov_con_por_sig_mes_anio($_GET['sigla'],$_GET['mes'],$_GET['ano_contable']);

$filas=mssql_num_rows($con_dat_movimiento);
if($filas>0)
{
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=RepPresupuesto");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
	<table border="1">
        <tr>
            <th>CUENTA</th>
            <th>NOMBRE CUENTA</th>
            <th>NIT</th>
            <th>NOMBRE TERCERO</th>
            <th>DOCUMENTO</th>
            <th>FECHA</th>
            <th>MES CONTABLE</th>
            <th>A&Ntilde;O CONTABLE</th>
            <th>CENTRO DE COSTOS</th>
            <th>NATURALEZA</th>
            <th>VALOR</th>
        </tr>
	<?php
	while($res_dat_movimiento=mssql_fetch_array($con_dat_movimiento))
	{
    ?>
    	<tr>
        	<td><?php echo $res_dat_movimiento['mov_cuent']; ?></td>
            <td><?php echo $res_dat_movimiento['cue_nombre']; ?></td>
            <td><?php echo $res_dat_movimiento['mov_nit_tercero']; ?></td>
            <td><?php echo $res_dat_movimiento['nits_apellidos']." ".$res_dat_movimiento['nits_nombres']; ?></td>
            <td><?php echo $res_dat_movimiento['mov_compro']; ?></td>
            <td><?php echo $res_dat_movimiento['mov_fec_elabo']; ?></td>
            <td><?php echo $res_dat_movimiento['mov_mes_contable']; ?></td>
            <td><?php echo $res_dat_movimiento['mov_ano_contable']; ?></td>
            <td><?php echo $res_dat_movimiento['cen_cos_nombre']; ?></td>
            <td><?php echo $res_dat_movimiento['mov_tipo']; ?></td>
            <td><?php echo $res_dat_movimiento['mov_valor']; ?></td>
        </tr>
	<?php
	}
	?>
	</table>
<?php
}
else
	echo "<script>alert('No se encontraron movimientos para este documento.');window.history.back(1);</script>";
?>