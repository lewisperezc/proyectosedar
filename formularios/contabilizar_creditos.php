<?php
  session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
  include_once('clases/centro_de_costos.class.php');
  include_once('clases/factura.class.php');
  $centros = new centro_de_costos();
  $factura = new factura();
  $fac_recaudo = $factura->fac_cen_recaudo();
?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script type="text/javascript">
  function recaudo(fac)
  {
    if(fac!=0)
    {
      $('#rec_resultado').html('<div><img src="imagenes/loading.gif"/></div>');    
      $.ajax({
      type: "POST",
      url: "./llamados/recaudo.php",
      data: "factura="+fac,
      success: function(msg){
        $('#rec_resultado').fadeIn(1000).html(msg);
        var cuantos = $("#tab_recaudo > tbody > tr").length;
        $("#cant_credito").val(cuantos-1);
      }
      });
    }
    else
    {
      $('#rec_empleado').html('<div><img src="imagenes/loading.gif"/></div>');    
      $.ajax({
      type: "POST",
      url: "./llamados/recaudo.php",
      data: "factura="+fac,
      success: function(msg){
        $('#rec_empleado').fadeIn(1000).html(msg);
        var cuantos = $("#tab_recaudo > tbody > tr").length;
        $("#cant_crEmpleados").val(cuantos-1);
      }
      });
    }
    $("#factura_recaudo").val(fac);
  }

  function calcular_interes(pos,credito,capital)
  {
     $.ajax({
    type: "POST",
    url: "./llamados/cal_interes.php",
    data: "pos="+pos+"&credito="+credito+"&capital="+capital,
    success: function(msg){
      if(msg==''){
        $("#interes"+pos).val(0);
        $("#cuota"+pos).val($("#capital"+pos).val());
      }
      else{
        $("#interes"+pos).val(msg);
        $("#cuota"+pos).val(parseInt($("#capital"+pos).val())+parseInt(msg));
      }   
    }
    });
  }

  function sum_pagCuota(pos)
    {
      var inter=$("#interes"+pos).val();
      var capi=$("#capital"+pos).val();
      $("#cuota"+pos).val(parseInt(inter)+parseInt(capi));
    }

  function MostrarRecaudo(tipo)
  {
    if(tipo=="")
    {
    	$("#afiliado").css("display","none");
      	$("#empleado").css("display","none");
      	$("#empleado_fecha").css("display","none");      	 
    }
    else
    {
    	if(tipo==1)
      	{
        	$("#afiliado").css("display","block");
        	$("#empleado").css("display","none");
        	$("#empleado_fecha").css("display","none");
        	$("#tipo_nit").val(1);
      	}
      	else
      	{
        	$("#afiliado").css("display","none");
        	$("#empleado_fecha").css("display","block");
        	$("#empleado").css("display","block");
        	$("#tipo_nit").val(2);
      	}
    }
  }    
    
</script>
<form id='form_rec_credito' method='post' action='control/modificar_tabla_amortizacion.php'>
  <center>
    <table>
      <tr>
        <th>Tipo de recaudo
        <input type="hidden" name="tipo_nit" id="tipo_nit" value="0" />
        </th>
        <td><select onclick="MostrarRecaudo(this.value)"><option value="">--Seleccione--</option>
         <option value="1">Afiliado</option>
         <option value="2">Empleado</option>
        </select></td>
    </tr>
    </table>

    <table style='display:none;' id="afiliado">
    <tr>
      <td>Factura a recaudar</td>
      <td><input type="text" name="centro" id="centro" list="cent" onchange="recaudo(this.value);"/>
        <datalist id="cent">
       <?php
        while($dat_centro = mssql_fetch_array($fac_recaudo))
          echo "<option value='".$dat_centro['fac_id']."' label='".$dat_centro['fac_consecutivo']."' />";
       ?>
        </datalist>
      <td/>
    </tr>
    <tr><td colspan=2><div id='rec_resultado'></div></td></tr>
    <tr><input type='hidden' name='cant_credito' id='cant_credito' />
       <td colspan="5"><input type="submit" class="art-button" value="Descontar en Nomina" /></td>
    </tr>
  </table>
  <!--</form>
  <form method='post' action='control/modificar_tabla_amortizacion.php'>-->
  <table style='display:none;' id="empleado_fecha">
  	<tr>
  		<th>Fecha recaudo</th><td><input type="text" name="fec_rec_empleado" id="fec_rec_empleado" value="<?php echo date('d-m-Y'); ?>" required/>
		<a href="javascript:NewCal('fec_rec_empleado','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Seleccione la fecha" /></a>
        </td>
  	</tr>         
  </table>
  <table style='display:none;' id="empleado">
    <tr>
      <td colspan=2><div id='rec_empleado'></div></td>
    </tr>
    <tr>
    <td colspan="4"><input type="button" class="art-button" value="Ver recaudo" onclick="recaudo(0);" /></td></tr>
    <tr><td><input type='hidden' name='cant_crEmpleados' id='cant_crEmpleados' /><input type="submit" class="art-button" value="Descontar en Nomina" /></td></tr>
  </table>
  <input type="hidden" name="factura_recaudo" id="factura_recaudo" value="0" />
  </center>
</form>