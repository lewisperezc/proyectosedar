<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('clases/tipo_hospital.class.php');
include_once('clases/cuenta.class.php');
$cuenta = new cuenta();
$cuentas = $cuenta->busqueda("no");
?>  
<form name="crear_hospital2" method="post" action="./control/guardar_tip_hospital.php">
 <center>
  <table>
   <tr>
    <td>Nombre tipo de hospital</td>
    <td><input name="tip_hos" id="tip_hos" type="text" required="required"/></td>
   </tr>
   <tr>
    <td>Cuenta por cobrar</td>
    <td>
      <select name="cue_cob" id="cue_cob" required x-moz-errormessage="Seleccione Una Opcion Valida">
       <option value="">Seleccione...</option>
       <?php
	    while($row = mssql_fetch_array($cuentas))
		  echo "<option value='".$row["cue_id"]."'>".$row["cue_id"]."-".$row["cue_nombre"]."</option>";
	   ?>
      </select>
    </td>
   </tr>
   <tr>
    <td colspan="2"><input type="submit" class="art-button" id="boton" name="boton" value="Guardar"/></td>
   </tr>
  </table>
 </center>
</form>