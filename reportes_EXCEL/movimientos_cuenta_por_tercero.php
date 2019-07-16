<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Movimientosportercero");
header("Pragma: no-cache");
header("Expires: 0");
include_once('../clases/saldos_cuentas.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/nits.class.php');
$ins_nits=new nits();
$ins_cuentas=new cuenta();
$ins_sal_cuentas= new insercion();
$lacuenta=$_POST['cue_nit'];
$elnit=$_POST['nit_id'];
$mes_contable=$_POST['mes_contable'];
//POR NIT
$con_mov_cuentas=$ins_sal_cuentas->ConMovCueNit($lacuenta,$elnit,$mes_contable,$_SESSION['elaniocontable']);
?>
<table border="1">
	<tr>
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
    while($resultado=mssql_fetch_array($con_mov_cuentas))
	{
	?>
    <tr>
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
</table>