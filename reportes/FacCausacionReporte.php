<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/presupuesto.class.php');
$mes=new mes_contable();
$tod_mes=$mes->mes();
$presupuesto=new presupuesto();
$anos = $presupuesto->obtener_lista_anios();
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function validar()
{
	form=document.aux_tercero;
	form.action = '../reportes_PDF/facCausacionReporte1.php';
	form.submit();
}
  </script>
<form name="aux_tercero" id="aux_tercero" method="post">
 <center>
  <table border="1" bordercolor="#0099CC">
   <tr>
    <td>
     <select name="ano" id="ano" >
      <option value="0">Seleccione...</option>
      <?php
		for($a=0;$a<sizeof($anos);$a++)
		  echo "<option value='".$anos[$a]."'>".$anos[$a]."</option>";
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
   <tr align="center"><td colspan="4"><input type="button" value="Ver" onclick="validar();"/></td></tr>
  </table>
 </center>
</form>