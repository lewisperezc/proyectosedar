<?php 
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/nits.class.php');
$ins_nit=new nits();
$mes = new mes_contable();
$tod_mes = $mes->mes();
$asociados = $ins_nit->con_tip_nit(1);
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function abreFactura(URL)
 {
	 var nit = $("#nit").val();
	 var credito = $("#list_credito").val();
     day = new Date();
	 id = day.getTime();
	 URL = URL+'?mes_sele='+mes+'&nit='+nit;
	 alert(URL);
	 eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
 }

function list_creditos(tercero)
{
  $.ajax({
   type: "POST",
   url: "../llamados/list_creditos.php",
   data: "nit="+tercero,
   success: function(msg){
     $("#creditos").html(msg);
   }
 });
  cre_salNits($nit,0)
}
</script>

<form name="bal_tercero" id="bal_tercero" method="post">
 <center>
  <table>
   <tr><td>Tercero</td><td>Credito</td></tr>
   <tr>
    <td>
      <input type='text' name='nit' id='nit' value='' list='nits' onChange='list_creditos(this.value);'>
      <datalist id="nits">
         <?php
          while($dat_aso = mssql_fetch_array($asociados))
            echo "<option value='".$dat_aso['nit_id']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?>
      </datalist>
    </td>
    <td><input type='text' name='list_credito' id='list_credito' list='creditos'><datalist id='creditos'></datalist></td>
   </tr>
   <tr><input type="button" value="PDF" onclick="javascript:abreFactura('../reportes_PDF/estado_cuenta.php')" /></tr>
  </table>
 </center>
</form>