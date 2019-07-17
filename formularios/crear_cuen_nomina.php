<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 @include_once('clases/concepto.class.php');
 @include_once('../clases/concepto.class.php');
 if(isset( $_POST['compensacion']))
 {
 	$compensacion = $_POST['compensacion'];
 	$aportes = $_POST['aportes'];
 	$legalizacion = $_POST['legalizacion'];
 	$gastos = $_POST['gastos'];
 	$educacion = $_POST['educacion'];
 	$cant_compensacion = $_POST['cantidad'];
 	if($aportes&&$legalizacion&&$gastos)
  	{	  
   	$guarCon_nomina = $conceptos->ins_por_nomina($compensacion,$aportes,$legalizacion,$gastos,$educacion,$cant_compensacion);
   	if($guarCon_nomina)
     	echo "<script>alert('Se guardaron satisfactoriamente los porcentajes.');</script>";  
  	}
 }
 $conceptos = new concepto();
 $dat_nomina = $conceptos->con_por_nomina();
 $por_descuentos = mssql_fetch_array($dat_nomina);
?>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<script language="javascript">
 function validar_vacios(form)
  {
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	if (document.form.compensacion.value == "")
	{ CamposVacios += "* Compensacion\n"; }
	if(document.form.aportes.value == "")
	{ CamposVacios += "* Aportes sociales\n"; }
	if(document.form.legalizacion.value == "")
	{ CamposVacios += "* Legalizacion y Polizas\n"; }
	if(document.form.gastos.selectedIndex == 0)
	{ CamposVacios += "* Gastos Administrativos\n"; }
	if(document.form.educacion.value == "")
	{ CamposVacios += "* Educacion\n"; }
	if (CamposVacios != "")
	{
		alert(Mensaje + CamposVacios);
		return true;
	}
		document.form.submit();
  }
</script>

<form name="form" id="form" method="post" action="#">
 <center>
  <table border="1">
   <tr>
    <td>Auxilio Inmobiliario</td>
    <td><input type="text" name="compensacion" id="compensacion" onkeypress="return permite(event,'num')" 
         value="<?php echo $por_descuentos['dat_nom_compensacion'];  ?>" />
    </td>
    <td><input type="text" value="" disabled="disabled" /></td>
   </tr>
   <tr>
    <td>Fondo de Retiro</td>
    <td><input type="text" name="aportes" id="aportes" onkeypress="return permite(event,'num')"
    value="<?php echo $por_descuentos['dat_nom_aportes'];  ?>"  /></td>
    <td><input type="text" name="cantidad" id="cantidad" value="<?php echo $por_descuentos['dat_can_nom_compensacion'];  ?>" /></td>
   </tr>
   <tr>
    <td>Legalizacion de contratos y Polizas</td>
    <td><input type="text" name="legalizacion" id="legalizacion" onkeypress="return permite(event,'num')"
    value="<?php echo $por_descuentos['dat_nom_legalizacion'];  ?>"  /></td>
    <td><input type="text" value="" disabled="disabled"/></td>
   </tr>
   <tr>
    <td>Gastos Administracion</td>
    <td><input type="text" name="gastos" id="gastos" onkeypress="return permite(event,'num')" 
    value="<?php echo $por_descuentos['dat_nom_gastos'];  ?>" /></td>
    <td><input type="text" value="" disabled="disabled"/></td>
   </tr>
   <tr>
    <td>Educacion</td>
    <td><input type="text" name="educacion" id="educacion" onkeypress="return permite(event,'num')" 
    value="<?php echo $por_descuentos['dat_nom_educacion'];  ?>" /></td>
    <td><input type="text" value="" disabled="disabled"/></td>
   </tr>
   <tr>
    <td>Descuento SS compensacion</td>
    <td><input type="text" name="ss_compe" id="ss_compe" onkeypress="return permite(event,'num')" 
    value="<?php echo $por_descuentos['dat_por_segSocial'];  ?>" /></td>
    <td><input type="text" value="" disabled="disabled"/></td>
   </tr>
   <tr>
   	<td colspan="3"><input type="button" class="art-button" name="enviar" id="enviar" value="Guardar" onclick=" validar_vacios(this)"/></td>
   </tr>
  </table>
 </center>
</form>