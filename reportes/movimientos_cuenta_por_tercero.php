<script src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function traecuentas(nit)
{
   $.ajax({
   type: "POST",
   url: "../llamados/trae_cueNits.php",
   data: "nit="+nit,
   success: function(msg){
     $("#cuen_nit").html(msg);
   }
 });
}
</script>
<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
$ins_mes_contable=new mes_contable();
$tod_mes=$ins_mes_contable->mes();
$ins_nits=new nits();
$con_nom_nits=$ins_nits->ConNomNits();
?>        
<form name="cartera" id="cartera" action="../reportes_EXCEL/movimientos_cuenta_por_tercero.php" method="post">
<center>
	<table border="1" bordercolor="#0099CC">
		<tr>
			<th>NIT:</th>
        	<td><input type="text" name="nit_id" id="nit_id" list="nit" size="50" required onchange="traecuentas(this.value)">
            <datalist id="nit">
            <?php while($res_nom_nits=mssql_fetch_array($con_nom_nits))
				  	echo "<option value='".$res_nom_nits['nit_id']."' label='".$res_nom_nits['nits_nombres']." ".$res_nom_nits['nits_apellidos']." - ".$res_nom_nits['nits_num_documento']."'>";
        	?> 
          	</datalist>
        	</td>
        	<th>CUENTA</th>
        	<td><input type="text" name="cue_nit" id="cue_nit" list="cuen_nit" size="50" required>
            <datalist id="cuen_nit">
            </datalist>
        	</td>
                <th>MES</th>
                <td>
     		<select name="mes_contable" id="mes_contable" required x-moz-errormessage="Seleccione Una Opcion Valida">
      		<option value="">Seleccione...</option>
     		<?php
                    while($row=mssql_fetch_array($tod_mes))
                    echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 	?>
    		</select>
    		</td>
    	</tr>
		<tr>
        	<td colspan="8"><input type="submit" value="EXCEL"/></td>
        </tr>
	</table>
</center>
</form>