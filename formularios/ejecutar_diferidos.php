<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Documento sin título</title>
</head>
<body>
<?php
include_once('clases/moviminetos_contables.class.php');
include_once('clases/mes_contable.class.php');
include_once('clases/cuenta.class.php');
$ins_mov_contable = new movimientos_contables();
$ins_mes = new mes_contable();
$ins_cuenta = new cuenta();
$meses = $ins_mes->DatosMesesAniosContables($ano);
$gasto = $ins_cuenta->cue_gasto();

?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" src="librerias/js/datetimepicker.js"></script>
<script language="javascript">
 function diferidos(mes,tipo)
 {
	 $.ajax({
   	 type: "POST",
	 url: "llamados/diferidos.php",
	 data: "mes="+mes+"&tipo="+1,
	 success: function(msg){
	   $("#contrato").append(msg);
	    $("#contratos").css("display","block");
       }
   })
   
    $.ajax({
   	 type: "POST",
	 url: "llamados/diferidos.php",
	 data: "mes="+mes+"&tipo="+2,
	 success: function(msg){
	   $("#causaciones").append(msg);
	    $("#causacion").css("display","block");
       }
   })
 }

 function calcular(valor,mes,pos)
 {
	 var res = parseFloat(valor/mes);
	 $("#val_dif_cau"+pos).val(res);
 }
 
</script>
<form name="selecciona_persona" method="post" action="control/guardar_diferido.php">
<center>
 <table>
 <tr><td align="center">Mes Contable: <select name="mes_sele" id="mes_sele" onchange="diferidos(this.value);">
       <option value="0" selected="selected">Seleccione...</option>
     <?php 
	  while($dat_meses = mssql_fetch_array($meses))
		echo "<option value='".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	 ?>  
      </select>
     </td>   
  </tr>
  </table>
 <div id="contratos" style="display:none"> 
  <table id="contrato"><tr><td>Documento</td><td>Cuenta diferido</td><td>Cuenta gasto</td><td>Valor total</td><td>Tot Cuotas</td><td>Valor a diferir</td><td>Fecha Inicio</td><td>Fecha Fin</td></tr>
  </table> 
 </div>
 <br /><br />
 <div id="causacion" style="display:none">
  <table id="causaciones">
   <tr><td>Documento</td><td>Cuenta diferido</td><td>Cuenta gasto</td><td>Valor</td><td>Cuotas</td>
  	  <td>Valor a diferir</td><td>Fecha incio</td><td>Fecha fin</td></tr>
  </table>
  <table>
   <tr><td><input type="submit" class="art-button" value="Guardar documentos a Diferir" /></td></tr>
  </table>
 </div>
</center>
</form>
</body>
</html>