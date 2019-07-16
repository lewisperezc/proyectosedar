<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<?php
@include_once('../clases/cuenta.class.php');
@include_once('clases/cuenta.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
$ins_cuenta=new cuenta();
$ins_mes=new mes_contable();
$con_cuentas=$ins_cuenta->busqueda_T('no');
$tod_mes=$ins_mes->mes();
?>
<form name="cartera" id="cartera" action="../reportes_EXCEL/movimientos_cuenta.php" method="post">
<center>
	<table border="1" bordercolor="#0099CC">
		<tr>
			<th>CUENTA INICIAL:</th>
        	<td><input type="text" name="cue_ini" id="cue_ini" list="cuenta" size="50" required>
            <datalist id="cuenta">
            <?php while($res_cuentas=mssql_fetch_array($con_cuentas))
				  	echo "<option value='".$res_cuentas['cue_id']."' label='".$res_cuentas['cue_id']." ".$res_cuentas['cue_nombre']."'>";
        	?> 
          	</datalist>
        	</td>
          <th>CUENTA FINAL:</th>
          <td><input type="text" name="cue_fin" id="cue_fin" list="cuenta" size="50" required>
            <datalist id="cuenta">
            <?php while($res_cuentas=mssql_fetch_array($con_cuentas))
            echo "<option value='".$res_cuentas['cue_id']."' label='".$res_cuentas['cue_id']." ".$res_cuentas['cue_nombre']."'>";
          ?> 
            </datalist>
          </td>
      
            <th>MES</th>
            <td>
     		<select name="mes" id="mes" required x-moz-errormessage="Seleccione Una Opcion Valida">
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