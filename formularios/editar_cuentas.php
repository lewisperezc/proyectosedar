<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
function genera_cuentas()
{
	$index = 1;
	echo "<select name='select1' id='select1' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>---</option>";
	while($index <= 9){
	    echo "<option value='".$index."'>".$index."</option>";
		$index++;
	}
	echo "</select>";
}
?>

<script>
function valida_blancos(){
	if(document.cuenta.select1.selectedIndex==0)
		{
			alert('seleccione una cuenta ');
			cuenta.select1.focus();
			return false;
		}
	if(document.cuenta.select2.selectedIndex==0)
		{
			alert('seleccione un nombre de s cuenta ');
			cuenta.select2.focus();
			return false;
		}	
	
	if(cuenta.nomb_cue.value==""){
		alert("digite el nombre de la nueva cuenta");
		cuenta.nomb_cue.focus();
		return false;
	}
}
</script>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />
<script language="javascript" type="text/javascript" src="librerias/ajax/select_actu_cuenta.js"></script>
<script>
function modificar()
  {
    for (i=0;i<document.forms[0].elements.length;i++) 
	  {
        if (document.forms[0].elements[i].disabled) 
		{
          document.forms[0].elements[i].disabled = false;
		  cuenta.nomb_cue.focus();
        }
		if (document.forms[0].elements[i].disabled) 
		{
          document.forms[0].elements[i].disabled = false;
		  cuenta.porsetage.focus();
        }
      }
  }
</script>
<form id="cuenta" name="cuenta" onsubmit="return valida_blancos()" action="control/actualiza_cuenta.php" method="post" >
<center>
	<table width="200" border="1">
		<tr>
        	<th colspan="3" align="center" >EDITAR CUENTAS  CONTABLES(nombres)</th>
  		</tr>
       <tr>
    	<th scope="col">cuenta</th>    
   		<th scope="col">nombre</th>
		 </tr>
 		 <tr>
  		 	 <td><?php genera_cuentas(); ?></td>
       	 	<td><select name="select2" size="1" disabled="disabled" id="select2" >
         	  <option value="">Seleccione</option>
       		 </select>
   			 </td>  
  
  		</tr>
   		<tr>
   			<th scope="col">nombre  cuenta</th>
            <th scope="col">Porcentage cuenta</th>
            <tr>
            <td><input type="text" name="nomb_cue"  id="nomb_cue" disabled="disabled" onkeypress="return permite(event, 'car')" value="dddddddddd"/>
    			</td>
                <td><input type="text" name="porsetage"  id="porsetage" disabled="disabled" onkeypress="return permite(event, 'num')" value="ccccccc"/>
    			</td>
            </tr>
   				
  		<tr>
  <tr><td colspan="1"><input type="button" class="art-button" value="regresar" name="regresar"  onClick="location.href='//192.168.0.53/contabilidad/index.php?m=2'"/></td>
  <td colspan="2"><input type="button" class="art-button" name="editar" value="editar" onclick="modificar();"/> <input type="submit" class="art-button" name="grabar" value="grabar" /></td>
  </tr>

</table>
</center>
</form>