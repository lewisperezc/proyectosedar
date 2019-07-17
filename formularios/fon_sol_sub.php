<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
 include_once('conexion/conexion.php');
 include_once('clases/nomina.class.php');
 $ano = $_SESSION['elaniocontable'];
 $nomina = new nomina();
 $consul_dat = $nomina->consul_fondos();
 $consulta = mssql_fetch_array($consul_dat);
?>
<form name="porSeg" id="porSeg" method="post" action="#">
 <center>
  <table id="solidaridad">
   <tr><td colspan="2">Subcuenta de solidaridad</td></tr>
   <tr><td>Mayor a 4 SMLV</td><td><input type="text" name="sol4" id="sol4" value="<?php echo $consulta['adic_solidaridad']; ?>" /></td></tr>
   </tr>
  </table>
  <br />
  <table id="subsistencia">
   <tr><td>Subcuenta de subsistencia</td></tr>
   <tr><td>Entre 16 y 17 SMLVG</td><td><input type="text" name="subsi16" id="subsi16" value="<?php echo $consulta['adic_subsis17']; ?>" /></td></tr>
   <tr><td>Entre 17 y 18 SMLVG</td><td><input type="text" name="subsi17" id="subsi17" value="<?php echo $consulta['adic_subsis18']; ?>" /></td></tr>
   <tr><td>Entre 18 y 19 SMLVG</td><td><input type="text" name="subsi18" id="subsi18" value="<?php echo $consulta['adic_subsis19']; ?>" /></td></tr>
   <tr><td>Entre 19 y 20 SMLVG</td><td><input type="text" name="subsi18" id="subsi18" value="<?php echo $consulta['adic_subsis20']; ?>" /></td></tr>
   <tr><td>Mayor a 20 SMLVG</td><td><input type="text" name="subsi20" id="subsi20" value="<?php echo $consulta['adic_subsismayor']; ?>" /></td></tr>
  </table>
 </center>
 <table align="center"><tr><td><input type="submit" class="art-button" name="boton" id="boton" value="Guardar Cambio" /></td></tr></table>
</form>

<?php
 if(isset($boton))
   { 
	$guardar = $nomina->gua_fondos($soli,$subsis17,$subsis18,$subsis19,$subsis20,$subsismayor);
	if($guardar)
	  echo "<script>alert('Se guardaron los fondos satisfactoriamente.');</script>";
	else
	  echo "<script>alert('No se guardaron los fondos, intente nuevamente.');</script>";
   }
?>