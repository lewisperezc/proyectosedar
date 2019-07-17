<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }

$id_asociado = $_SESSION['aso_id'];
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();

$con_cen_cos_asociado = $instancia_nits->con_cen_cos_asociado($id_asociado);
//$dat_cen_cos_asociado = mssql_fetch_array($con_cen_cos_asociado);

//INICIO CAPTURO TODO LO QUE HAY EN LOS CAMPOS PARA MANTENERLO EN LA SESSION AL MOMENTO DE DAR ATRAS
$_SESSION['aso_uni_pregrado'] = $_POST['aso_uni_pregrado'];
$_SESSION['aso_fec_pregrado'] = $_POST['aso_fec_pregrado'];
$_SESSION['aso_tit_gra_obtenido'] = $_POST['aso_tit_gra_obtenido'];
$_SESSION['aso_ciu_pregrado'] = $_POST['aso_ciu_pregrado'];

$_SESSION['aso_uni_posgrado'] = $_POST['aso_uni_posgrado'];
$_SESSION['aso_fec_posgrado'] = $_POST['aso_fec_posgrado'];
$_SESSION['aso_tit_pos_obtenido'] = $_POST['aso_tit_pos_obtenido'];
$_SESSION['ciu_posgrado'] = $_POST['ciu_posgrado'];

$_SESSION['aso_uni_otros'] = $_POST['aso_uni_otros'];
$_SESSION['aso_fec_otros'] = $_POST['aso_fec_otros'];
$_SESSION['aso_tit_otr_obtenido'] = $_POST['aso_tit_otr_obtenido'];
$_SESSION['aso_ciu_otr_obtenido'] = $_POST['aso_ciu_otr_obtenido'];
//FIN CAPTURO TODO LO QUE HAY EN LOS CAMPOS PARA MANTENERLO EN LA SESSION AL MOMENTO DE DAR ATRAS
?>

<script language="JavaScript" src="librerias/js/jquery-1.5.0.js"></script>
<script language="JavaScript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function habilitar()
  {
    for (i=0;i<document.forms[0].elements.length;i++) 
	  {
        if (document.forms[0].elements[i].disabled) 
		{
          document.forms[0].elements[i].disabled = false;
		}
      }
	  	  document.aso_cen_costos.guardar.disabled=false;
  }
</script>
<script>
function pulsa(combo1,combo2)
{
	var j = document.getElementById(combo1);
	var m = document.getElementById(combo2);
	if(j.length>0)
	{
		var k = j.options[j.selectedIndex].value;
		var t = j.options[j.selectedIndex].text;
		var l = k.split(",");
		borra=l[0];
		dborra=l[1];
		var aBorrar=document.forms["aso_cen_costos"][combo1].options[j.selectedIndex];
		aBorrar.parentNode.removeChild(aBorrar);
		z=m.length;
	 	m.options[z]=new Option(t,k,0);
		m.selectedIndex=0;
		j.selectedIndex=0;
	}
}
</script>


<table align="left">
	<tr>
    	<td><b>Centros De Costo A Los Que Pertenece:</b></td>
    </tr>
    <tr>
    <td colspan="6" ><hr /></td>
  </tr>
    <?php
		$h = 0;
		while($row = mssql_fetch_array($con_cen_cos_asociado))
		{
			//INICIO CAPTURO EN UN ARREGLO EL ID DE LA TABLA nits_por_cen_costo
			echo "<input type='hidden' name='valor[$h]' value='$row[id_nit_por_cen]'/>";
			//FIN CAPTURO EN UN ARREGLO EL ID DE LA TABLA nits_por_cen_costo
			
			$con_cen_costos = $instancia_nits->cen_cos_contrato();
			
	?>
    <tr>
    	<td>
	        <select name="cen_cos_asociado[<?php echo $h; ?>]" id="cen_cos_asociado[<?php echo $h; ?>]" disabled="disabled">
    <?php
			  		while($rows = mssql_fetch_array($con_cen_costos))
			  		{
				  		if($row['cen_cos_id'] == $rows['cc_id'])
				  		{
	?>
      				<option value="<?php echo $rows['cc_id']; ?>" selected="selected">
					<?php echo $rows['cc_nombre']; ?></option>";
    <?php
                  		}
				  		else
                  		{
	?>
				     <option value="<?php echo $rows['cc_id']; ?>"><?php echo $rows['cc_nombre']; ?></option>
    <?php
				  		}
		  			}
	?>
			</select>
            <!--<a href="../control/eliminar_centro_costo_asociado.php?nit_por_cen_cos_id=<?php echo base64_encode($row['id_nit_por_cen']); ?>" title="Eliminar">Eliminar</a></td>-->
    </tr>
   <?php
			$h++;
		}
		$_SESSION['nit_por_cen_cos_id'] = $nit_por_cen_cos_id;
	?>
			
    
</table>


<form name="aso_cen_costos" id="aso_cen_costos" method="post" target="frame2" action="../control/guardar_asociado.php">
<center>
	
<table align="center">
    <tr>
        <td><b>Agregar centro de costos</b></td>
        <td>&nbsp;</td>
    </tr>
    <?php
    $con_cen_costos_2 = $instancia_nits->cen_cos_con($id_asociado);
	?>
    <tr>
	    <td>
	      <select name="aso_cen_costos[]" id="aso_cen_costos[]" size=5 style='width:500px;height:300px;border:solid' multiple="multiple" disabled="disabled">
	      <?php
		   $i=0;
		   while($row = mssql_fetch_array($con_cen_costos_2))
		   {
			?>
				<option value="<?php echo $row['cc_id']; ?>"><?php echo $row['cc_nombre']; ?></option>	
			<?php
			 $i++;
			}
			?> 
          </select>
        </td>
      </tr>
      
      <tr>
    	
       <td><input type="submit" class="art-button" onClick="document.aso_cen_costos.action = 'consultar_asociado_6.php'" value="<< Atras" target="frame2"/>
       <input type="submit" class="art-button" onClick="document.aso_cen_costos.action = 'consultar_asociado_2.php'" value="<< Volver al Inicio" target="frame2"/>
       <?PHP
       if($_SESSION['k_perfil']==13 || $_SESSION['k_perfil']==16)//JEFE DE CONTRATACIÓN Y DIRECTOR EJECUTIVO
       {
       ?>
       <input type="button" class="art-button" value="Modificar" name="modificar" onclick="habilitar();"/>
       <input type="submit" class="art-button" value="Guardar" name="guardar" onclick="document.aso_cen_costos.action = '../control/actualizar_asociado_6.php'" disabled="disabled"/></td>
       <?PHP
	   }
       ?>
    </tr>
   </table>
</center>
</form>