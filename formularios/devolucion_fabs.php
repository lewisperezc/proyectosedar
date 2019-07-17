<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="../librerias/js/jquery-1.5.0.js"></script><script src="librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/separador.js"></script><script src="librerias/js/separador.js"></script>
<script src="../librerias/js/datetimepicker.js" language="javascript" type="text/javascript"></script>
<script src="librerias/js/datetimepicker.js" language="javascript" type="text/javascript"></script>
<script>
function ObtenerNombres(elnit)
{
	$.ajax({
   	type: "POST",
   	url: "llamados/trae_plata_fabs_asociado.php",
   	data: "id="+elnit,
   	success: function(msg)
	{
		var resultado=msg.split("#");
	  	if(resultado[0]=="")
		{
			alert('El afiliado ingresado no se encuentra creado en el sistema!!!');
			document.ladevolucion.gua_devolucion.disabled=true;
		}
	  	else
		{
			$("#doc_y_nom_afiliado").val(resultado[1]+"-"+resultado[2]);
			document.ladevolucion.gua_devolucion.disabled=false;
			$("#los_datos").css("display", "block");
		}
	}
	});
}
</script>

<script language="javascript">
$(document).ready(function(){
   $("#gua_devolucion").click(function(evento)
   {
	    quitarPuntos();
      var cadena = $("#mes_contable").val();
      var ano = $("#estAno").val();
      cadena=cadena.split("-");
    	if(cadena[0]==1)
		{
			alert("Mes de solo lectura.");
			return false;
		}
		else
		{ 
			quitarPuntos();
	  		document.ladevolucion.submit();
		}
   });
});

$(document).ready(function(){
	$("#tie_retencion").click(function(evento)
	{
		if($("#tie_retencion").attr("checked"))
		{
        	$("#val_retencion").attr("disabled",false);
			$("#cue_retencion").attr("disabled",false);
      	}
		else
		{
        	$("#val_retencion").attr("disabled",true);
			$("#cue_retencion").attr("disabled",true);
      	}
   	});
});
</script>
</head>
<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/cuenta.class.php');
@include_once('clases/cuenta.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
$ins_cuenta=new cuenta();
$con_cuentas=$ins_cuenta->busqueda('no');
$ins_nits=new nits();
$con_afiliados=$ins_nits->con_tip_nit(1);
$ins_mes_contable=new mes_contable();
$con_meses=$ins_mes_contable->DatosMesesAniosContables($ano);
$con_cue_retencion=$ins_cuenta->con_cue_pabs(2365);
?>
<body>
<form name="ladevolucion" id="ladevolucion" method="post" action="control/guardar_devolucion_fabs.php">
	<center>
    <table>
    	<tr><th colspan="4">DEVOLUCI&Oacute;N FABS</th></tr>
      <tr>
          <td>Fecha</td>
          <td><input required type="text" name="dev_fab_fecha" id="dev_fab_fecha" placeholder="DD-MM-YYYY"/>
          <a href="javascript:NewCal('dev_fab_fecha','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
          </td>
          <td>Mes Contable</td><td><select name="mes_contable" id="mes_contable">
            <?php
      	while($dat_meses = mssql_fetch_array($con_meses))
        echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
        ?>  
          </select>    
        </td>
        </tr>
        <tr>
          <td>Afiliado<input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes_contable->conAno($ano); ?>'/></td>
          <td><input type="text" name="nit_id" id="nit_id" list="afiliado" size="50" required onChange="ObtenerNombres(this.value);" pattern="[0-9]+" title="Solo se permiten numeros"/><datalist id="afiliado">
         <?php
      while($res_afiliados=mssql_fetch_array($con_afiliados))
          echo "<option value='".$res_afiliados['nit_id']."' label='".$res_afiliados['nits_num_documento']." ".$res_afiliados['nits_nombres']." ".$res_afiliados['nits_apellidos']."'>"; ?></datalist>
           </td>
           <td>Documento - Nombres</td>
           <td><input type="text" name="doc_y_nom_afiliado" id="doc_y_nom_afiliado" readonly size="50"/></td>
        </tr>
      <tr>
          <td>Valor</td><td><input type="text" name="val_devolucion" id="val_devolucion" pattern="[0-9]+" title="Solo se permiten numeros" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
            <td>Cuenta</td><td><input type="text" name="cue_devolucion" id="cue_devolucion" list="cuenta" size="50" required pattern="[0-9]+" title="Solo se permiten numeros"/><datalist id="cuenta">
         <?php
      while($res_cuentas=mssql_fetch_array($con_cuentas))
          echo "<option value='".$res_cuentas['cue_id']."' label='".$res_cuentas['cue_id']." ".$res_cuentas['cue_nombre']."'>"; ?></datalist></td>
        </tr>
        <tr>
        <td colspan="2">Retenci&oacute;n</td><td colspan="2"><input type="checkbox" name="tie_retencion" id="tie_retencion"/></td>
        </tr>
        
        <!---INICIO RETENCION-->
        <tr>
        	<td>Valor retenci&oacute;n</td>
            <td><input type="text" value="0" name="val_retencion" id="val_retencion" pattern="[0-9]+" disabled onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
        	<td>Cuenta</td>
            <td><input type="text" name="cue_retencion" id="cue_retencion" list="con_cuentas" pattern="[0-9]+" disabled/>
            <datalist id="con_cuentas">
            <?php
            while($res_cue_retencion=mssql_fetch_array($con_cue_retencion))
			{
			?>
            <option value="<?php echo $res_cue_retencion['cue_id']; ?>" label="<?php echo $res_cue_retencion['cue_id']." - ".$res_cue_retencion['cue_nombre']; ?>"></option>
            <?php
			}
			?>
            </datalist>
            </td>
        </tr>
        <!---FIN RETENCION-->
        
        <tr>
          <th colspan="4">Observaciones</th>
        </tr>
        <tr>
          <td colspan="4"><textarea name="obs_devolucion" id="obs_devolucion" placeholder="Escriba aqui..." cols="50" required></textarea></td>
        </tr>
        <tr>
          <td colspan="4"><input type="submit" class="art-button" name="gua_devolucion" id="gua_devolucion" value="Guardar Devoluci&oacute;n"/></td>
        </tr>
    </table>
  </center>
</form>
</body>
</html>