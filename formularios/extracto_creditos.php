<?php session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
  
  @include_once('../clases/nits.class.php');
  @include_once('clases/nits.class.php');
  $ins_nits = new nits();
  $tipos='1,2';
  $asociados=$ins_nits->con_tip_nit($tipos);
  
  
?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>

<script>
function ObtenerCreditos(nit_documento){
   $.ajax({
   type: "POST",
   url: "llamados/trae_extracto_credito_por_documento.php",
   data: "nit_documento="+nit_documento,
   success: function(msg){
     $("#list_creditos").html(msg);
   }
 });
}
</script>

<form name="vaciar_log" id="vaciar_log" action="reportes_pdf/extracto_credito.php" method="post">
<center>
 
<table id='botones'>
	
	<tr>
   		<th colspan="2">EXTRACTO DE CREDITOS</th>
	</tr>
	
	<tr>
   		<th>Documento</th>
   		<th>Credito</th>
	</tr>
	<tr>
		<td>
        <input type="text" pattern="[0-9]+" name="nit_num_documento" id="nit_num_documento" list="list_nits" onchange='ObtenerCreditos(this.value,0);' size="50" required="required"/>
        <datalist id="list_nits">
         <?php
	 	  while($dat_aso = mssql_fetch_array($asociados))
		  {
		  	echo "<option value='".$dat_aso['nits_num_documento']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>";
		  }
		  ?>
	   	  </datalist>
	   </td>
	   <td>
        <input type="text" pattern="[0-9]+" name="nit_credito" id="nit_credito" list="list_creditos" size="13" required="required"/>
        <datalist id="list_creditos">
        </datalist>
	   </td>
	   
	</tr>

	<tr>
   		<td colspan="2"><input name="guardar" id="guardar" type="submit" class="art-button" value="Consultar extracto"/></td>
	</tr>
</table>
</center>
</form>