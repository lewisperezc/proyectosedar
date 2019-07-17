<?php session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>

<form name="vaciar_log" id="vaciar_log" action="control/vaciar_log.php" method="post">
<center>
 
<table id='botones'>
	
	<tr>
   		<th>VACIAR LOG DE TRANSACCIONES DE LA BASE DE DATOS</th>
	</tr>
	<tr>
   		<td><input name="guardar" id="guardar" type="submit" class="art-button" value="Vaciar Log"/></td>
	</tr>
</table>
</center>
</form>