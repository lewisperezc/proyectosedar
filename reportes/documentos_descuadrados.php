<?php session_start();
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$anios=$ins_presupuesto->obtener_lista_anios();
$mes = new mes_contable();
$tod_mes = $mes->mes();
?>
<form name="documentos_descuadrados" id="documentos_descuadrados" method="post">
<center>
    <table border="1" bordercolor="#0099CC">
        <tr>
            <th>MES</th>
            <th>A&Ntilde;O</th>
        </tr>
   	<tr>
            <td>
            <select name="mes" id="mes" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione...</option>
            <?php
            while($row = mssql_fetch_array($tod_mes))
            echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
            ?>
            </select>
            </td>
            <td>
            <select name="anio" id="anio" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Seleccione</option>
            <?php
		    for($a=0;$a<sizeof($anios);$a++)
				echo "<option value='".$anios[$a]."'>".$anios[$a]."</option>";
		    ?>
            </select>
            </td>
   	</tr>
   	<tr>
            <td colspan="4">
            <input type="submit" onclick="document.documentos_descuadrados.action='../reportes_EXCEL/documentos_descuadrados_excel.php'" value="EXCEL"/> ||
            <input type="submit" value="PDF"/>
            </td>
        </tr>
  </table>
 </center>
</form>