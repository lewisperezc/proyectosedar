<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
$ins_presupuesto=new presupuesto();
$mes = new mes_contable();
$tod_mes = $mes->mes();
$anios=$ins_presupuesto->obtener_lista_anios();
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
	 alert(URL);
	 eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
 }
</script>

<form name="bal_tercero" id="bal_tercero" action="../reportes_PDF/bal_terceros.php" method="post">
 <center>
  <table>
   <tr>
    <td>Año</td><td>Mes</td>
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
     <select name="mes" id="mes">
      <option value="0">Seleccione...</option>
     <?php
	  while($row = mssql_fetch_array($tod_mes))
		  echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	 ?>
    </select>
    </td>
   </tr>
   <tr><input type="button" value="PDF" onclick="javascript:abreFactura('../reportes_PDF/bal_terceros.php')" /></tr>
   <tr><input type="button" value="EXCEL" onclick="javascript:abreFactura('../reportes_EXCEL/balance_de_prueba_terceros.php')" /></tr>
  </table>
 </center>
</form>