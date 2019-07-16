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

<form name="aux_tercero" id="aux_tercero" action="../reportes_PDF/aux_segSocial.php" method="post">
 <center>
  <table>
   <tr>
    <td>Documento Inicial</td><td>Documento Final</td>
   </tr>
   <tr>
    <td><input type="text" name="doc_ini" id="doc_ini" /></td>
    <td><input type="text" name="doc_fin" id="doc_fin" /></td>
   </tr>
   <tr><input type="submit" value="PDF"/></tr>
  </table>
 </center>
</form>