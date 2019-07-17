<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('clases/mes_contable.class.php');
$mes = new mes_contable();
$con_meses = $mes->DatosMesesAniosContables($ano);
?>

<script>
function ValidaMes()
{
	var cadena = $("#mes_pag_seg_social").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
	{
		alert("Mes de solo lectura");
		$("#frm_pag_seg_social").submit(function(){return false;});
	}
	else
	{
		var mensaje=confirm("Esta seguro que desea volver a generar el reporte de seguridad social?, Se reemplazara el anterior.");
		if(mensaje)
		{
			document.frm_pag_seg_social.submit();
		}
		else
		{
			$("#frm_pag_seg_social").submit(function(){return false;});
		}
	}	
}
</script>

<center>
<form method="post" name="frm_pag_seg_social" id="frm_pag_seg_social" action="reportes_excel/seg_social.php">
	<table>
		<tr>
			<th colspan="4">PAGO SEGURIDAD SOCIAL</th>
		</tr>
		<tr>
			<td>Mes de pago</td>
			<td><input type="hidden" name="consulta_seg_social" id="consulta_seg_social" value="1">
			<select name="mes_pag_seg_social" id="mes_pag_seg_social" required>
				<option value="">--</option>
				<?php
				while($res_meses=mssql_fetch_array($con_meses))
					echo "<option value='".$res_meses['mes_estado']."-".$res_meses['mes_id']."'>".$res_meses['mes_nombre']."</option>";
				
				?>
			</select></td>

			<td>A&ntilde;o de pago</td>
			<td><input type="hidden" name="anio_pag_seg_social" id="anio_pag_seg_social" value="<?php echo $ano; ?>"><b><?php echo $ano; ?></b></td>
		</tr>
		<tr>
			<td colspan="4"><input onclick="ValidaMes();" type="submit" value="Generar"></td>
		</tr>
	</table>
</form>
</center>