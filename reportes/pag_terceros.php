<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
$mes = new mes_contable();
$tod_mes = $mes->mes();
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function abreFactura(URL)
 {
	 var ano = $("#ano").val();
	 var mes = $("#mes").val();
     day = new Date();
	 id = day.getTime();
	 URL = URL+'?mes_sele='+mes+'&ano_sele='+ano;
	 eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
 }
</script>

<form name="sal_cueMes" id="sal_cueMes" action="" method="post">
 <center>
  <table>
   <tr>
    <td>Año</td><td>Mes</td></tr>
   <tr>
    <td>
     <select name="ano" id="ano" >
      <option value="0">Seleccione...</option>
     </select>
    </td>
     <td>
     <select name="mes" id="mes">
      <option value="0">Seleccione...</option>
     <?php
	  while($row = mssql_fetch_array($tod_mes))
		  echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 ?>
    </select>
    </td>
   </tr>
   <tr>
    <td><input type="button" value="PDF" onclick="javascript:abreFactura('../reportes_PDF/pag_terceros.php')"/></td>
    <td><input type="button" value="EXCEL" onclick="javascript:abreFactura('../reportes_EXCEL/pag_terceros.php')"/></td></tr>
  </table>
 </center>
</form>