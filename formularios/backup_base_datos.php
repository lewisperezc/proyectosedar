<?php session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>

<form name="vaciar_log" id="vaciar_log" action="control/backup_base_datos.php" method="post">
<center>
 
<table id='botones'>
	
	<tr>
   		<th>REALIZAR BACKUP DE LA BASE DE DATOS</th>
	</tr>
	<tr>
   		<td><input name="guardar" id="guardar" type="submit" class="art-button" value="Realizar Backup"/></td>
	</tr>
</table>
</center>
</form>