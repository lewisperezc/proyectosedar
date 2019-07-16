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
 function validar(opcion)
  {
	form=document.aux_tercero;
	if(opcion==1)
	  form.action = '../reportes_PDF/aux_cuenTerceros.php';
	else
	  form.action = '../reportes_EXCEL/aux_cuenTerceros.php'; 
	form.submit();
  }
  </script>
<form name="aux_tercero" id="aux_tercero" method="post">
 <center>
  <table border="1" bordercolor="#0099CC">
   <tr>
    <td>Año</td><td>Mes</td><td>Cuenta Inicial</td><td>Cuenta Final</td><td>Documento Inicial</td><td>Documento Final</td>
   </tr>
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
    <td><input type="text" name="cue_ini" id="cue_ini" /></td>
    <td><input type="text" name="cue_fin" id="cue_fin" /></td>
    <td><input type="text" name="doc_ini" id="doc_ini" /></td>
    <td><input type="text" name="doc_fin" id="doc_fin" /></td>
   </tr>
   <tr align="center"><td colspan="6"><input type="button" value="PDF" onclick="validar(1);"/><input type="button" value="Excel" onclick="validar(2);"/></td></tr>
  </table>
 </center>
</form>