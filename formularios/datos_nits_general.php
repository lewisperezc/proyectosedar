<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$tip_id_nit = $_GET['tip_nit_id'];
$nit= new nits();
//echo "el tipo de nit es: ".$tip_id_nit;
//echo "el nit seleccionado es: ".$_GET['nit_id'];
$des_ubicacion="1";
$con_dat_nit=$nit->ConsultarDatosNitGeneralPorId($_GET['nit_id'],$des_ubicacion);
$res_dat_nit=mssql_fetch_array($con_dat_nit);

$ciu_1 = $nit->con_ciu_dep_asociado($des_ubicacion,$_GET['nit_id']);

$ciudades=$nit->consultar_ciudades();


function genera_departamentos($departamento_anterior)
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select required disabled name='select1' id='select1' onChange='cargaContenido_1(this.id)'>";
	echo "<option value='' onclick='validar_vacios();'>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
	{
		if($row['dep_id']==$departamento_anterior)
		{
			echo "<option selected value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
		}
		else
		{
			echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
		}
	}
		
	echo "</select>";
}
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
	document.datos_aso_1.guardar.disabled=false;
}

function iratras()
{
	history.back(-1);
}
</script>
<script type="text/javascript" language="javascript"></script>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<!--../control/guardar_nits_general.php-->
<form name="crear_nits_general" action="../control/modificar_nit_general.php" method="post">
<center>
	<table>
    <tr>
    	<td>Raz&oacute;n Social</td>
    	<td><input name="raz" disabled type="text" required value="<?php echo $res_dat_nit['nits_nombres']; ?>"></td>
    	<td>NIT</td>
    	<td><input name="nit" disabled type="text" required value="<?php echo $res_dat_nit['nits_num_documento']; ?>"></td>
    </tr>
    <tr>
            <td>R&eacute;gimen</td>
            <td><select name="regimen" required disabled>
            	<option value="">Seleccione el R&eacute;gimen </option>
               	<?php
				$regimenes=$nit->cons_regimen();
				while($row=mssql_fetch_array($regimenes))
				{
					if($row['reg_id']==$res_dat_nit['reg_id'])
					{
				?>
            			<option selected value="<?php echo $row['reg_id'];?>"><?php echo $row['reg_nombre'];?></option>
                <?php
					}
					else
					{
				?>
						<option value="<?php echo $row['reg_id'];?>"><?php echo $row['reg_nombre'];?></option>
				<?php
					}
				}
                ?>
                </select>
                </td>
                <td>Tipo R&eacute;gimen</td>
                <td><select name="tipo_regimen" required disabled>
                	<option value="">Seleccione el Tipo de R&eacute;gimen</option>
                    <?php
					$tipo_regimen=$nit->cons_tipo_regimen();
					while($row=mssql_fetch_array($tipo_regimen))
					{
						if($row['tip_reg_id']==$res_dat_nit['tip_reg_id'])
						{
					?>
                    	<option selected value="<?php echo $row['tip_reg_id'];?>"><?php echo $row['tip_reg_nombre'];?></option>
                    <?php
						}
						else
						{
					?>
							<option value="<?php echo $row['tip_reg_id'];?>"><?php echo $row['tip_reg_nombre'];?></option>
					<?php
						}
					}
					?>
           			</select>
                </td>
            </tr>
    <tr>
    	<td>Departamento</td>
    	<td><?php genera_departamentos($res_dat_nit['depa_dep_id']);?></td>
        <td>Ciudad</td>
    	<td><select name="select2" id="select2" required disabled>
           <?php
		   		while($row = mssql_fetch_array($ciu_1))
				{
					if($ciudades['ciu_id'] == $row['ciu_id'])
					{
			?>
            			<option value="<?php echo $row['ciu_id']; ?>" selected="selected"/><?php echo $row['ciu_nombre']; ?></option>
            <?php
					}
					else
					{
			?>
         				<option value="<?php echo $row['ciu_id']; ?>"/><?php echo $row['ciu_nombre']; ?></option>   			
            <?php
					}
				}
		    ?>
		   </select>
       </td>
    </tr>
    <tr>
    	<td>Direccion</td>
        <td><input type="text" name="dir" value="<?php echo $res_dat_nit['nits_dir_residencia']; ?>" required disabled></td>
        <td>Tel&eacute;fono</td>
        <td><input name="tel" type="text" value="<?php echo $res_dat_nit['nits_tel_residencia']; ?>" required disabled></td>   
    </tr>
    <tr>
    	<td>Contacto</td>
        <td><input type="text" name="contacto" value="<?php echo $res_dat_nit['nits_contacto']; ?>" required disabled></td>
        <td>Tel&eacute;fono o Celular</td>
        <td><input name="cel" type="text" value="<?php echo $res_dat_nit['nits_num_celular']; ?>" required disabled></td>
    </tr>
    <tr>
    	<td>Correo</td><td><input type="text" name="correo" value="<?php echo $res_dat_nit['nits_cor_electronico']; ?>" required disabled/>
    	<input type="hidden" name="nit_ant" id="nit_ant" value="<?php echo $_GET['nit_id']; ?>" />
    	<input type="hidden" name="tipo_nit_seleccionado" id="tipo_nit_seleccionado" value="<?php echo $tip_id_nit; ?>" />
    	</td>
    </tr>
    <tr>
    	<td><input type="button" class="art-button" name="atras" value="&larr; Atras" onClick="iratras();">
    	<input type="button" onclick="habilitar();" class="art-button" name="modificar" value="Modificar">
    	<input type="submit" class="art-button" name="guardar" value="Guardar">
    </tr>
    </table>
</center>
</form>