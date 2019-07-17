<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/producto.class.php');
@include_once('../clases/cuenta.class.php');
@include_once('./clases/producto.class.php');
@include_once('./clases/cuenta.class.php');
$ins_cuenta=new cuenta();
$ins_producto=new producto();
//$con_cuentas = $ins_cuenta->con_cue_menores(2408);
?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="./librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="./librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript">
function Agregar()
{
	var pos = $("#productos>tbody>tr").length-1;
	<?php
	$con_tip_producto=$ins_producto->tipo_producto();
	$con_retencion=$ins_cuenta->con_cue_menores(2365);
	?>
	campo='<tr><td><select name="tip_pro_id'+pos+'" id="tip_pro_id'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option>';
	<?php
	while($res_tip_producto=mssql_fetch_array($con_tip_producto))
	{ ?>
    campo+='<option value="<?php echo $res_tip_producto['tip_pro_id']; ?>"><?php echo $res_tip_producto['tip_pro_nombre']; ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td>';
	campo+='<td><input type="text" name="pro_descripcion'+pos+'" id="pro_descripcion'+pos+'" required="required"/></td>';
	campo+='<td><input type="text" name="pro_iva'+pos+'" id="pro_iva'+pos+'" onKeyPress="return permite(event,num)" required="required"  maxlength="3" size="3"/></td>';
    campo+='<td><select name="pro_retencion'+pos+'" id="pro_retencion'+pos+'"><option value="NULL">--Seleccione--</option>';
	<?php
	while($res_retencion=mssql_fetch_array($con_retencion))
	{ ?>
    campo+='<option value="<?php echo $res_retencion['cue_id']; ?>"><?php echo $res_retencion['cue_id']."-".$res_retencion['cue_nombre']; ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td></tr>';
	$("#productos").append(campo);
	$("#can_filas").val(pos);
}
</script>
<?php
$con_tip_producto=$ins_producto->tipo_producto();
$con_retencion=$ins_cuenta->con_cue_menores(2365);
?>
<form name="crear_producto" method="post"  action="control/guardar_producto.php" >
<center>
	<table id="productos">
    	<tr>
        	<th>Tipo Producto</th>
            <th>Descripcion</th>
            <th>Iva</th>
            <th>Cuenta Retenci&oacute;n</th>
        </tr>
            <td>
            <select name="tip_pro_id0" id="tip_pro_id0" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">--Seleccione--</option>
            <?php
			while($res_tip_producto=mssql_fetch_array($con_tip_producto)){
			?>
            <option value="<?php echo $res_tip_producto['tip_pro_id']; ?>" ><?php echo $res_tip_producto['tip_pro_nombre']; ?></option>
            <?php
			 }
			?>	
            </select>
            </td>
            <td><input type="text" name="pro_descripcion0" id="pro_descripcion0" required="required"></td>
            <td>
              <input type="text" name="pro_iva0" id="pro_iva0" onkeypress="return permite(event,'num')" maxlength="3" size="3" required="required" />
            </td>    
            <td><select name="pro_retencion0" id="pro_retencion0">
            <option value="NULL">--Seleccione--</option>
            <?php while($res_retencion=mssql_fetch_array($con_retencion)){ ?>
            <option value="<?php echo $res_retencion['cue_id']; ?>">
				<?php echo $res_retencion['cue_id']."-".$res_retencion['cue_nombre']; ?>
            </option>
            <?php } ?>
            </select>
            </td>           
    	</tr>
</table>
<table>
        <tr>
        	<td colspan="8">
            <input type="button" class="art-button" name="agr" id="agr" value="Agregar" onclick="Agregar();"/>
            <input type="hidden" name="can_filas" id="can_filas"/>
            <input type="submit" class="art-button" name="guardar" value="Guardar">
            </td>
        </tr>
    </table>
 </center> 
</form>