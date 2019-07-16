<script>
function enviar(eltipo)
{
	var form=document.cartera;
	if(eltipo==1)
		form.action='../reportes_PDF/carteraHospital.php';
	else
	{
		if(eltipo==2)
			form.action='../reportes_EXCEL/carteraHospital.php';
	}
	form.submit();
}
</script>
<?php
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
include_once('../clases/nits.class.php');
$ins_presupuesto=new presupuesto();
$anios=$ins_presupuesto->obtener_lista_anios();
$mes = new mes_contable();
$ins_nits=new nits();
$tod_mes = $mes->mes();
$tipos="1,2,3,5,6,7,8,9,10,11,13,14";
$con_tod_nits_1=$ins_nits->ConProFondo($tipos);
$con_tod_nits_2=$ins_nits->ConProFondo($tipos);
?>
<form name="cartera" id="cartera" action="../reportes_PDF/carteraHospital.php" method="post">
<center>
	<table border="1" bordercolor="#0099CC">
    	<tr>
    		<td>A&ntilde;o</td>
            <td>Mes</td>
            <td>Documento Inicial</td>
            <td>Documento Final</td>
   		</tr>
   		<tr>
    		<td>
            <select name="ano" id="ano">
            <option value="0">Seleccione</option>
            <?php
		    for($a=0;$a<sizeof($anios);$a++)
				echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
		    ?>
            </select>
    		</td>
     		<td>
     		<select name="mes" id="mes" required x-moz-errormessage="Seleccione Una Opcion Valida">
      			<option value="0">Seleccione...</option>
     			<?php
	  			while($row = mssql_fetch_array($tod_mes))
		  			echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 			?>
    		</select>
    		</td>
            <td>
          	<input type="text" name="doc_ini" id="doc_ini" list="tercero_1" size="40" required="required">
          	<datalist id="tercero_1">
          	<?php
            while($res_tod_nits_1=mssql_fetch_array($con_tod_nits_1))
			{
			?>
			<option value="<?php echo $res_tod_nits_1['nits_num_documento']; ?>" label="<?php echo $res_tod_nits_1['nits_num_documento']." ".$res_tod_nits_1['nits_nombres']." ".$res_tod_nits_1['nits_apellidos']; ?>">
            <?php
			}
			?>
          	</datalist>
          	</td>
            
            <td>
          	<input type="text" name="doc_fin" id="doc_fin" list="tercero_2" size="40" required="required">
          	<datalist id="tercero_2">
          	<?php
            while($res_tod_nits_2=mssql_fetch_array($con_tod_nits_2))
			{
			?>
			<option value="<?php echo $res_tod_nits_2['nits_num_documento']; ?>" label="<?php echo $res_tod_nits_2['nits_num_documento']." ".$res_tod_nits_2['nits_nombres']." ".$res_tod_nits_2['nits_apellidos']; ?>">
            <?php
			}
			?>
          	</datalist>
          	</td>
   		</tr>
   		<tr>
        	<td colspan="4">
                <input type="button" onclick="enviar(1);" value="PDF"/> || 
                <!--<input type="button" onclick="enviar(2);" value="EXCEL"/> ||-->
                <input type="button" onclick="enviar(3);" value="Reporte General"/>
                </td>
        </tr>
  </table>
 </center>
</form>