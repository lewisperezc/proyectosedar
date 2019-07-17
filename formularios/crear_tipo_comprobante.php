<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); 
$ano = $_SESSION['elaniocontable'];}
?>
<form id="tip_com" name="tip_com" method="post" action="control/guardar_tipComprobante.php">
  <center>
    <table id="dat">
	  <tr>
	   <td>Nombre comprobante: </td>
	   <td><input name="nomCom" id="nomCom" type="text" required="required"/>
	  </tr>
	  <tr>
	    <td>
		  Codigo comprobante: 
		</td>
		<td>
		 <input name="codCom" id="codCom" type="text" required="required"/>
		</td>
	  </tr>
	  <tr>
	   <td colspan="2">
		  <input type="submit" class="art-button" name="botCom" id="botCom" value="Guardar" />
	   </td>
	  </tr>
	</table>
  </center>
</form>