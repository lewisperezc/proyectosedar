<?php session_start();
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/nits.class.php');
$ins_nit=new nits();
$mes = new mes_contable();
$tod_mes = $mes->mes();
$tod_mes_2 = $mes->mes();
$asociados = $ins_nit->con_tip_nit('1,2,3');
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
/*
function abreFactura(URL)
 {
	 var nit = $("#nit").val();
	 var mes_inicial = $("#mes_inicial").val();
	 var mes_final = $("#mes_final").val();
	 var ano_contable = $("#ano_contable").val();
     day = new Date();
	 id = day.getTime();
	 URL = URL+'?mes_inicial='+mes_inicial+'&nit='+nit+'&mes_final='+mes_final+'&ano_contable='+ano_contable;
	 eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
 }
 */
</script>

<form name="bal_tercero" id="bal_tercero" method="post" action="../reportes_PDF/estado_cuenta.php">
 <center>
  <table>
   <tr><th>Mes inicial</th><th>Mes final</th><th>Tercero</th></tr>
   <tr>
    <td>
	  <select required name="mes_inicial" id="mes_inicial">
      <option value="">--</option>
     <?php
  	  while($row = mssql_fetch_array($tod_mes))
  		  echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	   ?>
    </select>
    </td>
    <td>
	  <select required name="mes_final" id="mes_final">
      <option value="">--</option>
     <?php
  	  while($row = mssql_fetch_array($tod_mes_2))
  		  echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	   ?>
    </select>
    </td>
    <td>
      <input required type='text' name='nit' id='nit' value='' list='nits'>
      <datalist id="nits">
         <?php
          while($dat_aso = mssql_fetch_array($asociados))
            echo "<option value='".$dat_aso['nit_id']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      </datalist>
    </td>
   </tr>
   <tr><input type="hidden" name="ano_contable" id="ano_contable" value="<?php echo $_SESSION['elaniocontable']; ?>"/>
   <td colspan="3"><input type="submit" value="PDF"/></td></tr>
   <!--onclick="javascript:abreFactura('../reportes_PDF/estado_cuenta.php')"-->
  </table>
 </center>
</form>