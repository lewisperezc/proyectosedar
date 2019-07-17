<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript">
 function cierre_ano(tipo)
 {
   $('#encabezado').html('<div><img src="imagenes/loading.gif"/></div>');
   $.ajax({
   		type: "POST",url: "llamados/cierre_ano.php",
		data: "tipo="+tipo,
   		success: function(msg){	
		    alert("Cierre Realizado");
	 		dat_tablas = msg.split('/-/');
	 		$('#encabezado').fadeIn(1000).html('');
   		}
   }); 
 }
</script>
<form name="cierre" id="cierre" method="post">
  <center>
   <table id="encabezado">
    <tr>
      <td>Cierre</td>
      <td>
       <select name="tipo" id="tipo" onChange="cierre_ano(this.value)">
        <option value="0">Seleccione</option>
        <!--<option value="1">Cuentas 1-2-3</option>-->
        <option value="2">Cuentas 4-5-6</option>
        <option value="3">Retencion en la fuente</option>
        <!--<option value="1">Ingresos, Gastos y Costos</option>-->
        <!--<option value="2">Retencion en la fuente</option>-->
       </select>
      </td>
    </tr>
   </table>
  </center>
</form>