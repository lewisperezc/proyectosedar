<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('clases/mes_contable.class.php');
  $mes = new mes_contable();
  $meses = $mes->DatosMesesAniosContables($ano);
?>
<script src="librerias/js/jquery-1.5.0.js" type="text/javascript"></script>
<script type="text/javascript">
 function cierre(valor)
 {
	$.ajax({
		type: "POST",
		url: "./llamados/cierre.php",
		data: "mes="+valor,	
		success: function(msg){
		  if(msg)
		  {
		    alert("Saldos actualizados");
			cambiar(valor);
		  }
		  else
		    alert("No se pudieron actualizar los saldos");
		}
	});
 }

 function cambiar(valor)
 {
 	$("#boton").removeAttr("disabled");
 	$.ajax({
		type: "POST",
		url: "./llamados/col_comprobante.php",
		data: "mes="+valor,
		success: function(msg){
		  var mensaje="Columna creada.";
		}
	});	
 }
</script>

<form name="mes_con" id="mes_con" method="post" action="control/guardar_act_mesContable.php">
 <center>
  <table border="1">
   <tr>
   	<th colspan="2">A&Ntilde;O DE TRABAJO: <?php echo $ano; ?></th>
   </tr>
   <tr>
    <th>Mes contable</th>
    <th>Estado mes</th>
   </tr>
  <?php
   $i=0;
   while($row=mssql_fetch_array($meses))
   {
	   echo "<tr>";
	    echo "<input type='hidden' name='mes_por_ano_con_id[$i]' id='mes_por_ano_con_id[$i]' value='".$row['mes_por_ano_con_id']."' />";
	    echo "<input type='hidden' name='mes[$i]' id='mes[$i]' value='".$row['mes_id']."' />";
		echo "<input type='hidden' name='ano_con_id[$i]' id='ano_con_id[$i]' value='".$row['ano_con_id']."' />";
 echo "<td><input type='text' name='mes_nombre' id='mes_nombre' readonly='readonly' value='".$row['mes_nombre']."' /></td>";
        echo "<td><select name='estado[$i]' id='estado[$i]' onchange='cambiar($i);'>";
		if($row['mes_estado']==1)
		{
		  echo "<option value='".$row['mes_estado']."' selected='selected'>Cerrado</option>";
		  echo "<option value='2'>Abierto</option>";
		}
		else
		{
		  echo "<option value='1' onclick='cierre($i);'>Cerrado</option>";
		  echo "<option value='".$row['mes_estado']."' selected='selected'>Abierto</option>";  
		}
        echo "</select></td>";
       echo "</tr>";
	   $i++;
   }
  ?>
  <tr><td colspan="2"><input type="submit" class="art-button" name="boton" id="boton" value="Actualizar" disabled="disabled" /></td></tr>
  </table>
 </center>
</form>