<?php 
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
$ins_nit=new nits();
$afiliados_1=$ins_nit->con_tip_nit('1');
$afiliados_2=$ins_nit->con_tip_nit('1');
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<form name="aux_tercero" id="aux_tercero" method="post" action="../reportes_pdf/balance_detallado_creditos.php">
 <center>
  <table border="1" bordercolor="#0099CC">
   <tr>
   	<th colspan="2">BALANCE DETALLADO DE CREDITOS POR NIT</th>
   </tr>
   <tr>
    <th>Documento inicial</th>
    <th>Documento final</th>
   </tr>
   <tr>
    <td>
    	<input type='text' name='nit_inicio' id='nit_inicio' value='' list='nits_inicio' required="">
      	<datalist id="nits_inicio">
        <?php
        	while($dat_aso = mssql_fetch_array($afiliados_1))
            echo "<option value='".$dat_aso['nits_num_documento']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      	</datalist>
    </td>
    <td>
    	<input type='text' name='nit_fin' id='nit_fin' value='' list='nits_fin' required="">
      	<datalist id="nits_fin">
        <?php
        	while($dat_aso = mssql_fetch_array($afiliados_2))
            echo "<option value='".$dat_aso['nits_num_documento']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      	</datalist>
    </td>
   </tr>
   <tr align="center">
   	<td colspan="2"><input type="submit" value="Ver"/></td>
   </tr>
  </table>
 </center>
</form>