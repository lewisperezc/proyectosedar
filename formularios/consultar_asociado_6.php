<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script src="librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<?php
//INICIO CAPTURO TODO LO QUE HAY EN LOS CAMPOS PARA MANTENERLO EN LA SESSION AL MOMENTO DE DAR ATRAS
$_SESSION['aso_ape_beneficiario'] = $_POST['aso_ape_beneficiario'];
$_SESSION['aso_nom_beneficiario'] = $_POST['aso_nom_beneficiario'];
$_SESSION['aso_tip_doc_beneficiario'] = $_POST['aso_tip_doc_beneficiario'];
$_SESSION['aso_num_doc_beneficiario'] = $_POST['aso_num_doc_beneficiario'];
$_SESSION['aso_por_ben_beneficiario'] = $_POST['aso_por_ben_beneficiario'];
$_SESSION['aso_ben_parentezco'] = $_POST['aso_ben_parentezco'];
//FIN CAPTURO TODO LO QUE HAY EN LOS CAMPOS PARA MANTENERLO EN LA SESSION AL MOMENTO DE DAR ATRAS
?>

<?php
$id_asociado = $_SESSION['aso_id'];
include_once('../clases/nits.class.php');
include_once('../clases/pais.class.php');
$instancia_nits = new nits();
$pais = new pais();

$con_est_asociado_1 = $instancia_nits->con_est_asociado_1($id_asociado);
$dat_est_asociado_1 = mssql_fetch_array($con_est_asociado_1);
$con_ciudades_1 = $pais->paises();

$con_est_asociado_2 = $instancia_nits->con_est_asociado_2($id_asociado);
$dat_est_asociado_2 = mssql_fetch_array($con_est_asociado_2);
$con_ciudades_2 = $pais->paises();

$con_est_asociado_3 = $instancia_nits->con_est_asociado_3($id_asociado);
$dat_est_asociado_3 = mssql_fetch_array($con_est_asociado_3);
$con_ciudades_3 = $pais->paises();
?>
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
	  	  document.aso_edu_superior.guardar.disabled=false;
  }
</script>
<form id="aso_edu_superior" name="aso_edu_superior" action="consultar_asociado_7.php" method="post">
<center>
	<table>
  <tr>
       <td colspan="6"><h4>Datos Educaci&oacute;n Superior</h4></th>
  </tr>	
  <tr>
    <td colspan="6" ><hr /></td>
  </tr>	
	   <td> Universidad Pregrado Medicina</td>
	   <td><input  name="aso_uni_pregrado" type="text" onKeyPress="return permite(event,'car')" value="<?php echo $dat_est_asociado_1['est_nom_uni_pregrado']; ?>" disabled="disabled" required="required"/></td>
	   <td> A&ntilde;o Grado</td>
	   <td><input type="text" name="aso_fec_pregrado" id="aso_fec_pregrado" value="<?php echo $dat_est_asociado_1['est_fec_pregrado']; ?>" disabled="disabled" required="required"/>
       <a href="javascript:NewCal('aso_fec_pregrado','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
       </td>
  </tr>
  <tr>
       <td>Titulo Obtenido</td>
       <td><input  name="aso_tit_gra_obtenido"type="text" onKeyPress="return permite(event,'car')" value="<?php echo $dat_est_asociado_1['est_tit_obt_pregrado']; ?>" disabled="disabled" required="required"/></td>
	   <td>Pais Pregrado</td>
       <td><select name="aso_ciu_pregrado" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
					<option  value="">--Seleccione--</option>
                    <?php
				    while($row = mssql_fetch_array($con_ciudades_1))
				    {
						if($dat_est_asociado_1['pai_id'] == $row['pai_id'])
						{
				    ?>
                	   <option value="<?php echo $row['pai_id'] ?>" selected>
					   <?php echo $row['pai_nombre']; ?>
                       </option>
                    <?php
						}
						else
						{
					?>
                    		<option value="<?php echo $row['pai_id'] ?>"><?php echo $row['pai_nombre']; ?></option>
                    <?php
						}
				    }
				    ?>
		   </select>
       </td>
  </tr>
  <tr>
	   <td> Universidad Posgrado Anestesiologia</td>
	   <td><input  name="aso_uni_posgrado"type="text" onKeyPress="return permite(event,'car')" value="<?php echo $dat_est_asociado_2['est_nom_uni_posgrado']; ?>" disabled="disabled" required="required"/></td>
	   <td> A&ntilde;o Posgrado</td>
	   <td><input type="text" name="aso_fec_posgrado" id="aso_fec_posgrado" value="<?php echo $dat_est_asociado_2['est_fec_posgrado']; ?>" disabled="disabled" required="required"/>
       <a href="javascript:NewCal('aso_fec_posgrado','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
         </td>
  </tr>
  <tr>
         <td>Titulo Obtenido</td>
         <td><input  name="aso_tit_pos_obtenido" type="text" onKeyPress="return permite(event,'car')" value="<?php echo $dat_est_asociado_2['est_tit_obt_posgrado']; ?>" disabled="disabled" required="required"/></td>
	     <td>Pais Posgrado</td>
         <td><select  name="ciu_posgrado" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
			    <option  value="">--Seleccione--</option>
                <?php
				while($row = mssql_fetch_array($con_ciudades_2))
				{
					if($dat_est_asociado_2['pai_id'] == $row['pai_id'])
					{
				?>
                	<option value="<?php echo $row['pai_id'] ?>" selected><?php echo $row['pai_nombre']; ?></option>
                <?php
					}
					else
					{
				?>
                		<option value="<?php echo $row['pai_id'] ?>"><?php echo $row['pai_nombre']; ?></option>
                <?php
					}
				}
				?>
		     </select>
         </td>
  </tr>
  <tr>                
         <td>Universidad otros</td>
	     <td><input  name="aso_uni_otros" type="text" onKeyPress="return permite(event,'car')" value="<?php echo $dat_est_asociado_3['est_nom_uni_otros']; ?>" disabled="disabled"/></td>
         <td> A&ntilde;o otros </td>
		 <td>
         <input type="text" name="aso_fec_otros" id="aso_fec_otros" value="<?php echo $dat_est_asociado_3['est_fec_otros']; ?>" disabled="disabled"/>
       <a href="javascript:NewCal('aso_fec_otros','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
         </td>
  </tr>
  <tr>
			<td>Titulo Obtenido</td>
            <td><input  name="aso_tit_otr_obtenido"type="text" onKeyPress="return permite(event,'car')" value="<?php echo $dat_est_asociado_3['est_tit_obt_otros']; ?>" disabled="disabled"/></td>
			<td>Pais Otros Estudios</td>
            <td><select name="aso_ciu_otr_obtenido" disabled="disabled">
					<option  value="NULL">--Seleccione--</option>
                    <?php
				    while($row = mssql_fetch_array($con_ciudades_3))
				    {
						if($dat_est_asociado_3['pai_id'] == $row['pai_id'])
						{
				    ?>
                	   <option value="<?php echo $row['pai_id'] ?>" selected><?php echo $row['pai_nombre']; ?></option>
                    <?php
						}
						else
						{
					?>
                    		<option value="<?php echo $row['pai_id'] ?>"><?php echo $row['pai_nombre']; ?></option>
                    <?php
						}
				    }
				    ?>
				</select>
            </td>
	<tr>
     <tr>
	    <td colspan="4">
		<input type="submit" class="art-button" value="Siguiente >>" target="frame2"/>
		<input type="submit" class="art-button" onClick="document.aso_edu_superior.action='consultar_asociado_5.php'" value="<< Atras" target"frame2"/>
		<?PHP
       	if($_SESSION['k_perfil']==13 || $_SESSION['k_perfil']==16)//JEFE DE CONTRATACIÓN Y DIRECTOR EJECUTIVO
       	{
       	?>
        <input type="button" class="art-button" value="Modificar" name="modificar" onclick="habilitar();"/>
        <input type="submit" class="art-button" value="Guardar" name="guardar" onclick="document.aso_edu_superior.action = '../control/actualizar_asociado_5.php'" disabled="disabled"/>
        <?PHP
		}
        ?>
	    </td>
  </tr>
</table>
</center>
</form>