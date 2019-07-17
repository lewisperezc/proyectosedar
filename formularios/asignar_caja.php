<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
include_once('clases/centro_de_costos.class.php');
include_once('clases/cuenta.class.php');
$ano = $_SESSION['elaniocontable'];
$centro = new centro_de_costos();
$cen_costos = $centro->cons_centro_costos();
?>
<script language="javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script language="javascript" src="librerias/ajax/select_deptos.js"></script>
<form name= "caj_menor" method="post" action="control/guardar_caj_men.php">
	<center>
		<table>
			<tr>
            	<td>Centro de costo</td>
            	<td>
              	<select name="cen_cos" id="cen_cos" required x-moz-errormessage="Seleccione Una Opcion Valida">
                	<option value="">Seleccione...</option>
               	<?php
		    	while($row = mssql_fetch_array($cen_costos))
			  		echo "<option value='".$row['cen_cos_id']."'>".$row['cen_cos_codigo']."--".$row['cen_cos_nombre']."</option>";
			   	?>   
              	</select>
            	</td>
           	</tr>
           	<tr>
            	<td>Monto de caja menor</td>
            	<td><input name="mon_caj" id="mon_caj" type="text" onkeypress="return permite(event,'num')" required="required"/></td>
           	</tr>
           	<tr>
            	<td colspan="2"><input name="boton" id="boton" type="submit" class="art-button" /></td></tr>
    	</table>
	</center>
</form>