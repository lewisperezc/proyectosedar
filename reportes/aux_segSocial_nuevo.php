<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
$mes = new mes_contable();
$tod_mes = $mes->mes();
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function TipoReporte(tipo)
 {
	 if($("#documento").val()=="")
	 	$("#aux_tercero").submit(function(){return false;});
	 else
	 {
	 	if(tipo==1)
	 		document.aux_tercero.action="../reportes_PDF/aux_segSocial_nuevo.php" 
	 	else
	 	{
	 		if(tipo==2)
	 			document.aux_tercero.action="../reportes_EXCEL/aux_segSocial_nuevo.php" 
	 		else
	 			$("#aux_tercero").submit(function(){return false;});
	 	}
	 }
 }
</script>

<form name="aux_tercero" id="aux_tercero"method="post">
 <center>
  <table border="1">
   <tr>
    <th colspan="2">Documento Inicial</th>
   </tr>
   <tr>
    <td colspan="2"><input type="text" pattern="[0-9]+" required name="documento" id="documento" /></td>
   </tr>
   <tr><td><input type="submit" onclick="TipoReporte(1);" value="PDF"/></td>
   <td><input type="submit" onclick="TipoReporte(2);" value="EXCEL"/></td></tr>
  </table>
 </center>
</form>