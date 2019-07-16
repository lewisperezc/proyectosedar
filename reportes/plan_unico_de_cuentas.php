<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Plan Unico De Cuentas PUC</title>
<script>
 function validar_vacios(puc)
  {
	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	var form = document.puc;
	//VALIDAMOS EL CAMPO NOMBRE
	if(form.desde.value == "")
	{ CamposVacios += "* Desde\n"; }
	if(form.hasta.value == "")
	{ CamposVacios += "* Hasta\n"; }
    //SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	if (CamposVacios != "")
	{
		alert(Mensaje + CamposVacios);
		return true;
	}
		form.action = '../reportes_PDF/plan_unico_de_cuentas.php'
		form.submit();
  }</script>
</head>
<body>
<?php
@include_once('../clases/cuenta.class.php');
$ins_cuenta=new cuenta();
$con_tod_cue_1=$ins_cuenta->todCuentas();
$con_tod_cue_2=$ins_cuenta->todCuentas();
?>
<form name="puc" id="puc" method="post">
	<table border="2" bordercolor="#0099CC">
    	<tr>
        	<th colspan="4">Rango De Cuentas</th>
        </tr>
        <tr>
        	<th>Desde</th><td>
            <input type="number" name="desde" id="desde" list="lisdesde" size="40" required pattern="[0-9]+">
        	<datalist id="lisdesde">
        	<?php
			while($res_tod_cue_1=mssql_fetch_array($con_tod_cue_1))
			{
			?>
        	<option value="<?php echo $res_tod_cue_1['cue_id']; ?>" label="<?php echo $res_tod_cue_1['cue_id']." ".$res_tod_cue_1['cue_nombre']; ?>"/>
			<?php } ?>
        	</datalist>
            </td>
            <th>Hasta</th><td>
            <input type="number" name="hasta" id="hasta" list="lishasta" size="40" required pattern="[0-9]+">
        	<datalist id="lishasta">
        	<?php
			while($res_tod_cue_2=mssql_fetch_array($con_tod_cue_2))
			{
			?>
        	<option value="<?php echo $res_tod_cue_2['cue_id']; ?>" label="<?php echo $res_tod_cue_2['cue_id']." ".$res_tod_cue_2['cue_nombre']; ?>"/>
			<?php } ?>
        	</datalist>
            </td>
        </tr>
        <tr>
        	<td><input type="button" name="consultar" value="Consultar" onclick="validar_vacios();"/></td>
        </tr>
    </table>
</form>
</body>
</html>