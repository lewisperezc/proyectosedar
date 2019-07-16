<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
$mes = new mes_contable();
$tod_mes = $mes->mes();
?>
<form name="cartera" id="cartera" action="../reportes_PDF/carteraHospital.php" method="post">
 <center>
  <table>
   <tr>
    <td>Año</td><td>Mes</td><td>Cuenta Inicial</td><td>Cuenta Final</td><td>Documento Inicial</td><td>Documento Final</td>
   </tr>
   <tr>
    <td>
     <select name="ano" id="ano" >
      <option value="0">Seleccione...</option>
      <option value="2011">2011</option>
      <option value="2012">2012</option>
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
    <td><input type="text" name="doc_ini" id="doc_ini" /></td>
    <td><input type="text" name="doc_fin" id="doc_fin" /></td>
   </tr>
   <tr><input type="submit" value="PDF"/></tr>
  </table>
 </center>
</form>