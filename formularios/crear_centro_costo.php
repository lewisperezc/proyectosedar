<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('clases/centro_de_costos.class.php');
include_once('clases/cuenta.class.php');
include_once('clases/nits.class.php');

$nits = new nits();
$emple = $nits->con_tip_nit(2);

function generaDeptos_paises()
{
    include_once('clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>Seleccione Depto</option>";
	while($row = mssql_fetch_array($list_deptos))
		echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
	echo "</select>";
}

?>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<script>
function validar()
{
	if(document.vali.cen_prin.selectedIndex == 0)
	{
	  alert("Debe seleccionar una opcion");
	  return false; 
	}
	else
	{
		document.vali.submit();
	}
}

function validar_campos(formulario)
{
  if(formulario.cod_txt.value == '')
   {
    alert("Debe escribir el codigo para el centro de costo");
	formulario.cod_txt.focus();
	return (false);
   }
  
  if(formulario.nom_txt.selectedIndex == 0)
   {
     alert("Debe seleccionar el hospital para el centro de costo");
     formulario.nom_txt.focus();
	 return (false);
   }
   
   if(formulario.select1.value == 0)
   {
     alert("Debe seleccionar un depto para el centro de costo");
     formulario.select1.focus();
	 return (false);
   }
   
   if(formulario.select2.value == 0)
   {
     alert("Debe seleccionar a que Ciudad pertence el centro de costo");
     formulario.select2.focus();
	 return (false);
   }
}
</script>
<script language="javascript" src="librerias/ajax/select_deptos.js"></script>
     <form name="vali" id="val" method="post">
      <center>
      <table>
       <tr>
        <td>Es centro de costos Ppal?</td>
        <td>
          <select name="cen_prin" id="cen_prin">
           <option value="0" onclick="validar();">Seleccione...</option>
           <option value="1" onclick="validar();">Si</option>
           <option value="2" onclick="validar();">No</option>
          </select>
        </td>
       </tr>
      </table>
      </center>
     </form> 
      
     <?php
	   $cen_prin = $_POST["cen_prin"];
	   $_SESSION["cen_prin"] = $cen_prin; 
	   $centro= new centro_de_costos();
       $centrociu = $centro->cen_cos_prin();
	   $sincentro = $centro->hos_sin_centro();
	   ?>
	    <form name= "crear_centro_de_costo"  method="post" onSubmit="return validar_campos(this)" action="control/guardar_centro_de_costos.php">
		<center>
		<?php
	   if($cen_prin == 1)
	   {?>
		    
            <table>
	   <tr>
		<td>Código:</td>
        <td><input type="text" name="cod_txt" onkeypress="return permite(event, 'num')"/></td>
		<td>Nombre:</td>
        <td><input type="text" name="nom_txt" onkeypress="return permite(event, 'car')"/></td>
	   </tr>
       <tr>
        <td>Departamantos:</td>
		<td><?php generaDeptos_paises(); ?></td>
		<td>Ciudad</td>
        <td><select name="ciudad" size="1" disabled="disabled" id="select2">
             <option value="0">Selecciona opci&oacute;n...</option>
          </select>
        </td>	
       </tr>
   	   <tr>
   	     <td colspan="2">Empleado encargado</td>
         <td colspan="2">
          <select name="emp" id="emp" >
           <option value="0">Seleccione...</option>
           <?php
		    while($row = mssql_fetch_array($emple))
			  echo "<option value='".$row['nit_id']."'>".$row['nits_nombres']."".$row['nits_apellidos']."</option>";
		   ?>
          </select>
         </td>
 	   </tr>
   	   <tr>
   	     <td colspan="4"><input type="submit" class="art-button" value= "Crear Centro de Costo" /></td>
 	     </tr>
          </table>
          <?php
	   }
	  elseif($cen_prin == 2)
	   {
		   ?>
		    <table>
	   <tr>
		<td>Código:</td>
        <td><input type="text" name="cod_txt" onkeypress="return permite(event, 'num')"/></td>
		<td>Nombre:</td>
        <td>
          <select name="nom_txt" id="nom_txt">
           <option value="0">Seleccione...</option>
        <?php
        	while($row = mssql_fetch_array($sincentro))
			{
				echo "<option value='".$row['id']."'>".$row['nombre']."</option>";
			}
		?>
          </select>
	   </tr>
	   <tr>
		<td colspan="2">Centro costo Principal</td>
		<td colspan="2">
          <select id="ppal" name="ppal">
           <?php
		 while($row = mssql_fetch_array($centrociu))
		  echo "<option value='".$row['cen_cos_id']."'>".$row['cen_cos_codigo']."---".$row['cen_cos_nombre']."</option>";
		   ?>
          </select>
        </td>
        </td>	
	   </tr>	
   	   <tr>
   		<td colspan="4"><input type="submit" class="art-button" value= "Crear Centro de Costo" /></td>
       </tr>
      </table><?php
	   }  
	 ?>
     </center>
</form>