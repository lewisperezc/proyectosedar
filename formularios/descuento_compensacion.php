<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Descuento De Nomina</title>
<?php
include_once('../clases/cuenta.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/compensacion_nomina.class.php');

$ins_nits=new nits();
$ins_cuenta=new cuenta();
$ins_com_nomina=new compensacion_nomina();
?>
<script src="../librerias/js/jquery.js"></script>
<script type="text/javascript">
function agregar()
{
	var pos = $("#mitabla>tbody>tr").length-1;
	<?php
	$valor="SI";
	$con_cue_nomina=$ins_cuenta->con_cue_nomina($valor);
	?>
 campo='<tr><td><select name="des_nom_cuenta'+pos+'" id="des_nom_cuenta'+pos+'"><option value="NULL">Seleccione</option>';
	<?php
	while($res_cue_nomina=mssql_fetch_array($con_cue_nomina))
	{ ?>
 campo+='<option value="<?php echo $res_cue_nomina['cue_id']; ?>"><?php echo $res_cue_nomina['cue_id']." - ".$res_cue_nomina['cue_nombre']; ?></option>"';
	<?php 
	}
	?>
	campo+='</select></td>';
	campo+='<td><input type="text" name="des_nom_valor'+pos+'" id="des_nom_valor'+pos+'" /></td></tr>';
	$("#mitabla").append(campo);
	$("#cuantos").val(pos);
}

function PreguntarEliminar(des_com_id)
{
    var mensaje=confirm("Esta seguro que desea eliminar el descuento seleccionado?");
    if(mensaje)
        location.href='../control/eliminar_descuento_compensacion.php?des_com_id='+des_com_id;
}
</script>
</head>
<body>
<?php
$valor="SI";
$con_cue_nomina=$ins_cuenta->con_cue_nomina($valor);
$con_cue_nomina_1=$ins_cuenta->con_cue_nomina($valor);
$des_nomina=$_GET['desc'];
$datos=split("-",$des_nomina,3);
//Posic 0:nit_id - Posic 1:fac_id - Pos 2: rec_caj_id
$con_nom_nit=$ins_nits->cons_nombres_nit($datos[0]);
$res_nom_nit=mssql_fetch_array($con_nom_nit);
$con_des_aplicados=$ins_com_nomina->ConsultarDescuentosCompensacion($datos[0],$datos[1],$datos[2]);
$num_filas=mssql_num_rows($con_des_aplicados);
?>
<center>
<form>
    <table border="1" bordercolor="#0099CC">
        <tr>
            <th colspan="2"><?php echo $res_nom_nit['nombres']; ?></th>
        </tr>
    </table>
    <?php
    if($num_filas>0)
    {
    ?>
    <!--INICIO DESCUENTOS QUE SE LE HAN APLICADO HASTA EL MOMENTO PARA ESA NOMINA-->
    <table border="1" bordercolor="#0099CC">
        <tr>
            <th colspan="3">DESCUENTOS APLICADOS</th>
        </tr>
        <tr>
            <th>CUENTA</th>
            <th>VALOR</th>
            <th>ELIMINAR</th>
        </tr>
            <?php
            //while($res_cue_nomina_1=mssql_fetch_array($con_cue_nomina_1))
            //while($res_des_aplicados=mssql_fetch_array($con_des_aplicados))
            while($res_des_aplicados=mssql_fetch_array($con_des_aplicados))
            {
                $con_cue_nomina_1=$ins_cuenta->con_cue_nomina($valor);
            ?>
                <tr>
                    <td><select name="des_apli_nom_cuenta0" id="des_apli_nom_cuenta0">
                    <option value="">Seleccione</option>
            <?php
                    while($res_cue_nomina_1=mssql_fetch_array($con_cue_nomina_1))
                    {
                        if($res_cue_nomina_1['cue_id']==$res_des_aplicados['des_nom_cuenta'])
                        { echo '<option value="'.$res_cue_nomina_1['cue_id'].'" selected>'.$res_cue_nomina_1['cue_id']." - ".$res_cue_nomina_1['cue_nombre'].'</option>'; }
                        else
                        { echo '<option value="'.$res_cue_nomina_1['cue_id'].'">'.$res_cue_nomina_1['cue_id']." - ".$res_cue_nomina_1['cue_nombre'].'</option>'; }
                    }
            ?>
                    </select></td>
                    <td><input type="text" name="des_apli_nom_valor0" id="des_apli_nom_valor0" required="required" value="<?php echo $res_des_aplicados['des_nom_valor'] ?>"/></td>
                    <td><input type="radio" name="des_apli_nom_eliminar0" id="des_apli_nom_eliminar0" required="required" value="<?php echo $res_des_aplicados['des_nom_id'] ?>" onclick="PreguntarEliminar(this.value)";/></td>
                </tr>
            <?php
            }
            ?>
    </table>
    </br>
    <?php
    }
    ?>
    <!--FIN DESCUENTOS QUE SE LE HAN APLICADO HASTA EL MOMENTO PARA ESA NOMINA-->
</form>
<form method="post" name="des_nomina" id="des_nomina" action="../control/guardar_descuento_compensacion.php">
    <table border="1" bordercolor="#0099CC" id="mitabla">
        <tr>
            <th colspan="3">ADICIONAR DESCUENTOS</th>
        </tr>
    	<tr>
            <th>CUENTA</th>
            <th>VALOR</th>
        </tr>
        <tr>
            <td><select name="des_nom_cuenta0" id="des_nom_cuenta0" required x-moz-errormessage="Seleccione Una Cuenta">
            <option value="">Seleccione</option>
            <?php
            while($res_cue_nomina=mssql_fetch_array($con_cue_nomina))
            {
            ?>
            <option value="<?php echo $res_cue_nomina['cue_id']; ?>"><?php echo $res_cue_nomina['cue_id']." - ".$res_cue_nomina['cue_nombre']; ?></option>
            <?php
            }
            ?>
            </select></td>
            <td><input type="text" name="des_nom_valor0" id="des_nom_valor0" required="required"/></td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="2">
            <input type="hidden" name="cuantos" id="cuantos" />
            <input type="hidden" name="des_nom_asociado" id="des_nom_asociado" value="<?php echo $datos[0]; ?>"/>
            <input type="hidden" name="des_nom_factura" id="des_nom_factura" value="<?php echo $datos[1]; ?>"/>
            <input type="hidden" name="des_nom_rec_caja" id="des_nom_rec_caja" value="<?php echo $datos[2]; ?>"/>
            <input type="button" class="art-button" value="Agregar Fila" onclick="agregar();"/>
            <input type="submit" class="art-button" value="Guardar"/>
            </td>
        </tr>
    </table>
</form>
</center>
</body>
</html>