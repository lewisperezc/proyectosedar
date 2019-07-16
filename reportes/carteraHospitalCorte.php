<script>
function enviar(eltipo)
{
	var form=document.cartera;
	if(eltipo==1)
		form.action='../reportes_PDF/carteraHospitalCorte.php';
	else
	{
		if(eltipo==2)
			form.action='../reportes_EXCEL/carteraHospitalCorte.php';
	}
	form.submit();
}
</script>
<?php
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$mes = new mes_contable();
$anios=$ins_presupuesto->obtener_lista_anios();
$tod_mes=$mes->mes();
$tod_mes_2=$mes->mes();
?>
<form name="cartera" id="cartera" action="../reportes_PDF/carteraHospitalCorte.php" method="post">
<center>
	<table border="1" bordercolor="#0099CC">
    	<tr>
        	<th>DESDE:</th>
            <td>Mes</td>
            <td>A&ntilde;o</td>
            <th>HASTA:</th>
            <td>Mes</td>
            <td>A&ntilde;o</td>
            <td>Documento Inicial</td>
            <td>Documento Final</td>
   		<tr>
        	<td>&nbsp;</td>
     		<td>
     		<select name="mes_ini" id="mes_ini" required x-moz-errormessage="Seleccione Una Opcion Valida">
      			<option value="0">Seleccione...</option>
     			<?php
	  			while($row = mssql_fetch_array($tod_mes))
		  			echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 			?>
    		</select>
    		</td>
            <td>
            <select name="ano_ini" id="ano_ini">
            <option value="0">Seleccione</option>
            <?php
		    for($a=0;$a<sizeof($anios);$a++)
				echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
		    ?>
            </select>
       		</td>
            <td>&nbsp;</td>
     		<td>
     		<select name="mes_fin" id="mes_fin" required x-moz-errormessage="Seleccione Una Opcion Valida">
      			<option value="0">Seleccione...</option>
     			<?php
	  			while($row = mssql_fetch_array($tod_mes_2))
		  			echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 			?>
    		</select>
    		</td>
            <td>
     		<select name="ano_fin" id="ano_fin">
            <option value="0">Seleccione</option>
            <?php
		    for($a=0;$a<sizeof($anios);$a++)
				echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
		    ?>
            </select>
    		</td>
    		<td><input type="text" name="doc_ini" id="doc_ini" required="required"/></td>
    		<td><input type="text" name="doc_fin" id="doc_fin" required="required"/></td>
   		</tr>
   		<tr>
        	<td colspan="8"><input type="button" onclick="enviar(1);" value="PDF"/>
            <!--||<input type="button" onclick="enviar(2);" value="EXCEL"/>--></td>
        </tr>
  </table>
 </center>
</form>