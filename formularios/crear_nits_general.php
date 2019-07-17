<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$tip_id_nit = $_SESSION['sel_tip_nit'];
$nit= new nits();
function genera_departamentos()
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido_1(this.id)'>";
	echo "<option value='0'onclick='validar_vacios();'>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
	echo "</select>";
}
?>
<script>
function validar(){
var mensaje="Estos campos son obligatorios:\n";
campos="";
if(document.crear_nits_general.raz.value=="")
	campos+="* El campo Razon Social no debe ser vacio\n";
if(document.crear_nits_general.nit.value=="")
	campos+="* El campo NIT no debe ser vacio\n";
if(document.crear_nits_general.select1.selectedIndex==0)
	campos+="* El campo Departamento no debe ser vacio\n";
if(document.crear_nits_general.select2.selectedIndex==0)
	campos+="* El campo Ciudad no debe ser vacio\n";
if(campos!="")
	alert(mensaje+campos);
else
	document.crear_nits_general.submit();
}


function ValidarDocumentoTercero(documento,nom_cam_documento,nom_btn_guardar)
{
	$.ajax({
    type: "GET",
    url: "../llamados/valida_documento.php",
    data: "docum="+documento,
	    success: function(msg)
	    {
	    	if(msg==1)
	        {
	        	//alert('Existe');
	        	$("#"+nom_cam_documento).val('');
	            alert("El nit ingresado ya se encuentra creado en el sistema.");
	        	$("input[type=submit]").attr("disabled", "disabled");
	        }
	        else
	        {
	            $("input[type=submit]").removeAttr("disabled");
	        }
	   }
   });
}


</script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript" language="javascript"></script>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<form name="crear_nits_general" action="../control/guardar_nits_general.php" method="post">
<center>
	<table>
    <tr>
    	<td colspan="4" align="center">
    	<?php
	
    		switch ($tip_id_nit)
			{
    			case 5:?><h3>Crear ARL</h3> 
    			<?php
                break;
				case 6:?><h3>Crear IPS</h3><?php
				break;
    			case 7:?><h3>Crear EPS</h3>
				<?php
    			break;
    			case 9:?><h3>Crear Aseguradora</h3>
				<?php
				break;
				case 10:?><h3>Crear Alcaldia &oacute; Gobernaci&oacute;n</h3>
				<?php
				break;
				case 11:?><h3>Crear Fondo de Pensiones</h3>
				<?php
				break;
				case 12:?><h3>Crear Fondo de Cesantias</h3>
				<?php
				break;
				case 13:?><h3>Crear Empresa</h3>
				<?php
				break;
				case 14:?><h3>Crear Caja de Compensaci&oacute;n</h3>
				<?php
				break;
				case 15:?><h3>Crear Otro tipo de NIT</h3>
				<?php
				break;
    		}
        ?>
    	</td>	
    </tr>
    <tr>
    	<td>Raz&oacute;n Social</td>
    	<td><input name="raz" type="text"></td>
    	<td>NIT</td>
    	<td><input name="nit" id="nit" type="text" onchange="ValidarDocumentoTercero(this.value,'nit','btn_gua_nit');"></td>
    </tr>
    <tr>
            <td>R&eacute;gimen</td>
            <td><select name="regimen">
            	<option value="0">Seleccione el R&eacute;gimen </option>
               	<?php
				$regimenes=$nit->cons_regimen();
				while($row=mssql_fetch_array($regimenes)){
				?>
            	<option value="<?php echo $row['reg_id'];?>"><?php echo $row['reg_nombre'];?></option>
                <?php
				}
                ?>
                </select>
                </td>
                <td>Tipo R&eacute;gimen</td>
                <td><select name="tipo_regimen">
                	<option value="0">Seleccione el Tipo de R&eacute;gimen</option>
                    <?php
					$tipo_regimen=$nit->cons_tipo_regimen();
					while($row=mssql_fetch_array($tipo_regimen)){
					?>
                    <option value="<?php echo $row['tip_reg_id'];?>"><?php echo $row['tip_reg_nombre'];?></option>
                    <?php
					}
					?>
           			</select>
                </td>
            </tr>
    <tr>
    	<td>Departamento</td>
    	<td><?php genera_departamentos();?></td>
        <td>Ciudad</td>
    	<td><select name="select2" id="select2">
        	<option value="0">--Seleccione una Ciudad</option>
        	</select>
        </td>
    </tr>
    <tr>
    	<td>Direccion</td>
        <td><input type="text" name="dir" ></td>
        <td>Tel&eacute;fono</td>
        <td><input name="tel" type="text"></td>   
    </tr>
    <tr>
    	<td>Contacto</td>
        <td><input type="text" name="contacto"></td>
        <td>Tel&eacute;fono o Celular</td>
        <td><input name="cel" type="text"></td>
    </tr>
    <tr>
    	<td>Correo</td><td><input type="text" name="correo"/></td>
    </tr>
    <tr>
    	<td><input type="button" class="art-button" name="btn_gua_nit" id="btn_gua_nit" value="Guardar" onClick="validar();"></td>
        <td><input type="button" class="art-button" name="cancelar" value="Cancelar"></td>
    </tr>
    </table>
</center>
</form>