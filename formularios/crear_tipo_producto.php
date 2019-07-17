<?php
 session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

@include_once('./clases/tipo_producto.class.php');
@include_once('../clases/concepto.class.php');
@include_once('../clases/tipo_producto.class.php');
@include_once('./clases/concepto.class.php');
@include_once('../clases/cuenta.class.php');

$ins_concepto = new concepto();
$filtro = "120,121,122,128";
$no_trae='0';
$con_conceptos = $ins_concepto->conceptos($filtro,$no_trae);
$ins_tip_producto = new tipo_producto();
$cuenta = new cuenta();
$cue_pagar = $cuenta->cue_Pagar(23);
//$con_cue_pago = $ins_tip_producto->con_cue_menores(2335);
?>

<form name="crear_tipo_producto" method="post" action="./control/guardar_tipo_producto.php">
<center>
	<table>
    	<tr>
        	<td>Tipo Producto nombre</td>
        	<td><input name="tipo" id="tipo" type="text" required="required"/></td>
        	<td>Concepto</td>
        	<td>
        		<select name="con" id="con" required x-moz-errormessage="Seleccione Una Opcion Valida">
        		<option value="">Seleccione el Concepto</option>
                <?php
				while($row= mssql_fetch_array($con_conceptos)){
			    ?>
                <option value="<?php echo $row['con_id']?>"><?php echo $row['con_nombre']?></option>
                <?php
				}
				?>
                </select>                   
          	</td>
            <td>Cuenta por pagar</td>
            <td>
            <select name="cuenta" id="cuenta" required x-moz-errormessage="Seleccione Una Opcion Valida">
        		<option value="">Seleccione la cuenta</option>
                <?php
				while($row= mssql_fetch_array($cue_pagar)){
			    ?>
                <option value="<?php echo $row['cue_id']?>"><?php echo substr($row['cue_nombre'],0,30); ?></option>
                <?php
				}
				?>
                </select>
            </td>
            <tr>
            	<td colspan="6">
                <input type="submit" class="art-button" name="guardar" value="guardar"/>
                </td>
            </tr>
    </table>
</center>
</form>


