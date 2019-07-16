<?php
include_once('../clases/moviminetos_contables.class.php');
$ins_mov_contable = new movimientos_contables();
$i=1;
?>
<table border="2">
	<tr>
		<td><b>CUENTA</b></td>
        <td><b>DEBITO</b></td>
        <td><b>CREDITO</b></td>
        <td><b>SALDO</b></td>
	</tr>
<?php
while($i<=9){
	
	$con_sal_cue_debito=$ins_mov_contable->con_sal_cuentas($i,1);
	$con_sal_cue_credito=$ins_mov_contable->con_sal_cuentas($i,2);
	
	$resultado = $con_sal_cue_debito-$con_sal_cue_credito;
	
	$con_nom_cuenta = $ins_mov_contable->con_nom_cuenta($i);
	$res_nom_cuenta=mssql_fetch_array($con_nom_cuenta);
	?>
    <tr>
    	<td><?php echo $res_nom_cuenta['cue_nombre']; ?></td>
    	<td><?php echo number_format($con_sal_cue_debito); ?></td>
        <td><?php echo number_format($con_sal_cue_credito); ?></td>
        <td><?php echo number_format($resultado); ?></td>
    </tr>
	<?php
	$con_cue_debito = $ins_mov_contable->con_sal_cue_debito($i,1);
	$con_cue_credito = $ins_mov_contable->con_sal_cue_debito($i,2);
	while($res=mssql_fetch_array($con_cue_debito))
	{
		while($res_2=mssql_fetch_array($con_cue_credito))
		{
			if($res['mov_cuent'])
			$suma_debito=$suma_debito+$res['mov_valor'];
			$suma_credito=$suma_credito+$res_2['mov_valor'];
			$res_operacion=$suma_debito-$suma_credito;
		?>
        <tr>
        	<td><?php echo $res_2['mov_cuent']; ?></td>
            <td><?php echo $suma_debito; ?></td>
            <td><?php echo $suma_credito; ?></td>
            <td><?php echo $res_operacion; ?></td>
        </tr>
        <?php
		}
	}
	$i++;
}
?>
</table>