<form action="../control/guardar_novedad_administrativa.php" name="frm_novedades_administrativas" id="frm_novedades_administrativas" method="post" enctype="multipart/form-data">
<center>
<table border="1" bordercolor="#0099CC">
	<tr>
		<input type="hidden" name="nombres_cedula" id="nombres_cedula" value="<?php echo $_GET['nombres']; ?>" />
		<input type="hidden" name="nit_id" id="nit_id" value="<?php echo $_GET['nit_id']; ?>" />
    	<th colspan="2"><?php echo $_GET['nombres']; ?></th>
   	</tr>
   	<tr>
   		<th colspan="2">Observaciones</th>
   	</tr>
   	<tr>
   		<td colspan="2"><textarea cols="100" required name="nov_observacion" id="nov_observacion"></textarea></td>
   	</tr>
   	<tr>
   		<th>Adjuntar archivo</th>
   		<td><input type="file" name="arc_plano" id="arc_plano" /></td>
   	</tr>
   	<tr>
   		<td colspan="2"><input type="submit" value="Guardar" /></td>
   	</tr>
</table>
</center>
</form>