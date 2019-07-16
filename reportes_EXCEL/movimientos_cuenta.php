<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=MovimientosCuenta");
header("Pragma: no-cache");
header("Expires: 0");
include_once('../clases/saldos_cuentas.class.php');
$ins_sal_cuentas= new insercion();
$cue_id=$_POST['cue_ini'];
$cue_fin=$_POST['cue_fin'];
$mes=$_POST['mes'];
$ano = $_SESSION['elaniocontable'];
$con_sal_cuentas=$ins_sal_cuentas->ConSalCuenta($cue_id,$cue_fin,$mes,$ano)
?>
<table border="1">
	<tr>
        <th>ID MOV</th>
    	<th>MES CONTABLE</th>
        <th>DOCUMENTO</th>
        <th>CUENTA</th>
        <th>VALOR</th>
        <th>NATURALEZA</th>
        <th>NIT</th>
        <th>NOMBRE</th>
        <th>APELLIDO</th>
    </tr>
    <?php
	$total=0;
    while($resultado=mssql_fetch_array($con_sal_cuentas))
	{
		$total+=$resultado['mov_valor'];
	?>
    <tr>
        <td><?php echo $resultado['id_mov']; ?></td>
    	<td><?php echo $resultado['mov_mes_contable']; ?></td>
        <td><?php echo $resultado['mov_compro']; ?></td>
        <td><?php echo $resultado['mov_cuent']; ?></td>
        <td><?php echo $resultado['mov_valor']; ?></td>
        <td><?php echo $resultado['mov_tipo']; ?></td>
        <td><?php echo $resultado['nits_num_documento']; ?></td>
        <td><?php echo $resultado['nits_nombres']; ?></td>
        <td><?php echo $resultado['nits_apellidos']; ?></td>
    </tr>
    <?php
	}
	?>
    <tr>
    	<th colspan="3" align="right">TOTAL:</th><th><?php echo $total; ?></th>
        <th colspan="4">&nbsp;</th>
    </tr>
</table>