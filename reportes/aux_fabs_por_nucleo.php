<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
$mes = new mes_contable();
$tod_mes = $mes->mes();
$tod_mes1 = $mes->mes();
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function abreFactura(URL)
 {
   document.aux_tercero.action = URL;
   document.aux_tercero.submit();
 }
</script>

<form name="aux_tercero" id="aux_tercero" method="post">
 <center>
  <table>
   <tr>
    <!--<th>Mes Inicio</th><th>Mes Fin</th>--><th>Documento Inicial</th><th>Documento Final</th>
   </tr>
   <tr>
    <!--<td><select name="cue_ini" id="cue_ini">
          <?php
		while($dat_meses = mssql_fetch_array($tod_mes))
		 echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  ?>  
      </select>
    </td>
    <td><select name="cue_fin" id="cue_fin">
          <?php
		while($dat_meses = mssql_fetch_array($tod_mes1))
		 echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  ?>  
      </select>
  </td>-->
    <td><input type="text" name="doc_ini" id="doc_ini" /></td>
    <td><input type="text" name="doc_fin" id="doc_fin" /></td>
   </tr>
   <tr><td colspan="4"><input type="button" value="PDF" onClick="abreFactura('../reportes_PDF/aux_fabs_por_nucleo.php');"/>
   <input type="button" value="EXCEL" onClick="abreFactura('../reportes_EXCEL/aux_fabs_por_nucleo.php');"/></td></tr>
  </table>
 </center>
</form>