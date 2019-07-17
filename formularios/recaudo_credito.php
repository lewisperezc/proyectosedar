<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/credito.class.php');
include_once('../clases/nits.class.php');
$credito = new credito();
$nit = new nits();
?>
<script type="text/javascript" src="../librerias/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function enviar(val)
{
	if(val==0)
		alert("Debe seleccionar un tipo");
	else
	    document.creditos.submit();
}

function traeCreditos(id){
	var sel = $("#nom").val();
	var html = ''; 
	$.ajax({
		type: "POST",
		url: "../llamados/trae_creditos.php",
		data: "id="+sel,
		success: function(msg){
		var myObject = eval('(' + msg + ')');
		for (var x = 0 ; x < myObject.length ; x++)
		 {
			html += '<div id="div_'+x+'">';
			var res = myObject[x];
			html += '<table><tr>';
			html += '<th>'+res[0].nit+'</th></tr>';
			for(var y=0; y < res.length;y++)
			{
		   html += '<tr><td>Prestamo</td><td>Ult. Pago</td><td>Capital</td><td>Intereses</td><td>Total Descuento</td><td>Descontar?</td></tr>';
  html += '<tr align="center"><td><input type="text" name="presta_'+y+'_'+x+'" id="presta_'+y+'_'+x+'" value="'+res[y].credito+'"/></td>';
 html += '<td><input type="text" name="ult_pag_'+y+'_'+x+'" id="ult_pag_'+y+'_'+x+'" value="'+res[y].pago+'"/></td>';
 html += '<td><input type="text" name="capital_'+y+'_'+x+'" id="capital_'+y+'_'+x+'" value="'+res[y].valor+'"/></td>';
  html += '<td><input type="text" name="interes_'+x+'" id="interes_'+x+'" value="'+res[y].interes+'"/></td>';
  html += '<td><input type="text" name="total_'+x+'" id="total_'+x+'" value="'+(res[y].valor+res[y].interes)+'"/></td>';
  html += '<td><input type="checkbox" name="pagar" id="pagar" value="'+res[0].nit+'_'+res[y].credito+'_'+res[y].valor+'" /></td></tr>';
			}
		   html += '</table></div>';
		 }
	html += '<table><tr><td colspan="6" align="center"><input type="button" class="art-button" value="Descontar" /></td></tr></table>';
	$("#lineas").html(html);
		}
	});
}
</script>

<form name="creditos" action="#" method="post">
<center>
 <table border="1">
  <tr>
   <td>Tercero</td>
   <td>
    <select name="ter" id="ter">
     <option value="0" onclick="enviar(this.value);">--Seleccione--</option>
     <option value="1" onclick="enviar(this.value);">Afiliado</option>
     <option value="2" onclick="enviar(this.value);">Empleado</option>
    </select>
   </td>
  </tr>
 </table> 
  <?php
   if(isset($_POST['ter']))
   { 
   	if($_POST['ter']==1){
     $con_nominas = $credito->nominas(2);
    ?>
       <table>
       	<tr>
       <div id="default_0" style="height:150px">
		<select name="nom" id="nom">
         <option value="0" onclick="traeCreditos(this.value);">--Seleccione--</option>
          <?php while($res_nominas = mssql_fetch_array($con_nominas))
                 echo "<option value='".$res_nominas['nom_consecutivo']."' onclick='traeCreditos(this.value);'>".$res_nominas['nom_consecutivo']."</option>";
			 ?>
            </select>
			<div id="lineas">
			</div> 
		</div>
	    </tr>
       </table>
 <?php
	  }
   }
  ?>  
</center>
</form>