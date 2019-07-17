<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('./clases/ciudades.class.php');
$ciudad =new ciudades();
function genera_cuentas()
{
	$index = 1;
	echo "<select name='select1' id='select1' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>---</option>";
	while($index <= 9){
	    echo "<option value='".$index."'>".substr($index,0,20)."</option>";
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
			document.cuenta.select1.focus();
			return false;
		}
	if(document.cuenta.select2.selectedIndex==0)
		{
			alert('seleccione una cuenta ');
			document.cuenta.select2.focus();
			return false;
		}	
	
	if(document.cuenta.sub_cue.value==""){
		alert("digite la subcuenta");
		document.cuenta.sub_cue.focus();
		
		return false;
	}
	if(document.cuenta.nomb_cue.value==""){
		alert("digite la descrpcion de la cuenta");
		document.cuenta.nomb_cue.focus();
		
		return false;
	
		}
		if(document.cuenta.cue_may.selectedIndex==0)
		{
			alert('seleccione Si o NO esta cuenta ES MAYOR');
			document.cuenta.cue_may.focus();
			return false;
		}	
		if(document.cuenta.sel_div.selectedIndex==0)
		{
			alert('seleccione si hay subdivisiones ');
			document.cuenta.sel_div.focus();
			return false;
		}
		if(document.cuenta.por_iva.value=="") 
		{
			alert('digite el iva a cobrar en esta cuenta ');
			document.cuenta.por_iva.focus();
			return false;
		}
		
		if(document.cuenta.cue_nomina.selectedIndex==0) 
		{
			alert('Especifique si la cuenta es de nomina o no');
			document.cuenta.cue_nomina.focus();
			return false;
		}
		else
			document.cuenta.submit();

}
</script>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />
<script language="javascript" type="text/javascript" src="librerias/ajax/select_cuenta.js"></script>
<form id="cuenta" name="cuenta" action="control/Guardar_CuePrueba.php" method="post" >
<center>
	<table width="200" border="1">
		<tr>
        	<th colspan="5" align="center" >CREAR CUENTAS CONTABLES|</th>
  		</tr>
       <tr>
        <th scope="col">Clase</th>
        <th scope="col">Cuenta</th>
        <th scope="col">Subcuenta</th>
     </tr>
  <tr>
    <td><?php genera_cuentas(); ?></td>     
    <td><select name="select2" size="1" disabled="disabled" id="select2" >
           <option value="">Seleccione</option>
        </select></td>
    <td><input type="text" name="sub_cue" id="sub_cue" size="5"onkeypress="return permite(event,'num')" value=""/></td>
     <tr><th scope="col">Nombre</th>
        <th scope="col">Es Mayor</th>
        <th scope="col">Subdivision</th>
       </tr>
       <td><input type="text" name="nomb_cue" value="" /></td>
    <td><select name="cue_may"><option value="">--</option><option value="si">SI</option><option value="no">NO</option></select></td>    
    <td><select name="sel_div"><option value="">--</option><option value="si">SI</option><option value="no">NO</option></select></td>
    
     <tr>

    <th scope="col">Porcentaje</th>
    <th scope="col">Ciudad</th>
    <th scope="col">Nomina</th>
    </tr>
    
      <td><input type="text" name="por_iva" onkeypress="return permite(event, 'num')"/></td>
      <td>
      <select name="ciudad">
        	 <option value="0" >--selecione...--</option>
			 <?php
				$ciudad=$ciudad->consultar_ciudades();
	           while($cue= mssql_fetch_array($ciudad))
	           {
	            ?>
         <option value="<?php echo $cue['ciu_id']; ?>"><?php echo $cue['ciu_nombre']; ?></option>
             <?php 
	           }          ?>
            </select>
      </td>
      <td><select name="cue_nomina" id="cue_nomina">
      <option value="0">Seleccione</option>
      <option value="SI">SI</option>
      <option value="NO">NO</option>
      </select></td>
    </tr>
  <tr><td colspan="4"><input type="button" class="art-button" value="Guardar" name="guardar" onclick="valida_blancos();" /> || <input type="button" class="art-button" value="Regresar" name="regresar"/></td></tr>
</table>
</center>
</form>