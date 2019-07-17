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
include_once('../clases/factura.class.php');

$ins_nits=new nits();
$ins_cuenta=new cuenta();
$ins_com_nomina=new compensacion_nomina();
$ins_factura=new factura();
?>
<script src="../librerias/js/jquery.js"></script>
<script type="text/javascript">
function agregar()
{
	var pos = $("#mitabla>tbody>tr").length-1;
	campo='<tr><th>VALOR</th><td><input required type="text" name="des_nom_valor'+pos+'" id="des_nom_valor'+pos+'" /></td></tr>';
	$("#mitabla").append(campo);
	$("#cuantos").val(pos);
}

function PreguntarEliminar(des_com_id)
{
    var mensaje=confirm("Esta seguro que desea eliminar el descuento seleccionado?");
    if(mensaje)
        location.href='../control/eliminar_descuento_legalizacion_adicional.php?des_com_id='+des_com_id;
}
</script>
</head>
<body>
<?php

$tipo_descuento=11;
$tipo_adicion=1;
$descripcion='IS NOT NULL';
$con_des_aplicados=$ins_com_nomina->ConsultarDescuentosLegalizacionAdicionales($_GET['rec_caj_seleccionado'],$tipo_descuento,$tipo_adicion,$descripcion);
$num_filas=mssql_num_rows($con_des_aplicados);

$dat_fac_rec_caja=$ins_factura->ConsultarDatosFacturaPorReciboCaja($_GET['rec_caj_seleccionado']);
$res_fac_rec_caja=mssql_fetch_array($dat_fac_rec_caja);

?>

<center>
<form>
    <?php
    if($num_filas>0)
    {
    ?>
    <!--INICIO DESCUENTOS QUE SE LE HAN APLICADO HASTA EL MOMENTO PARA ESA NOMINA-->
    <table border="1" bordercolor="#0099CC">
        <tr>
            <th colspan="3">DESCUENTOS DE LEGALIZACI&Oacute; APLICADOS - FACTURA: <?php echo $res_fac_rec_caja['fac_consecutivo']; ?></th>
        </tr>
        <tr>
            <th>VALOR</th>
            <th>ELIMINAR</th>
        </tr>
            <?php
            //while($res_cue_nomina_1=mssql_fetch_array($con_cue_nomina_1))
            //while($res_des_aplicados=mssql_fetch_array($con_des_aplicados))
            while($res_des_aplicados=mssql_fetch_array($con_des_aplicados))
            {
            ?>
                <tr>
                    <td><input type="text" name="des_apli_nom_valor0" id="des_apli_nom_valor0" required="required" value="<?php echo number_format($res_des_aplicados['des_monto'],0) ?>"/></td>
                    <td><input type="radio" name="des_apli_nom_eliminar0" id="des_apli_nom_eliminar0" required="required" value="<?php echo $res_des_aplicados['des_id'] ?>" onclick="PreguntarEliminar(this.value)";/></td>
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
<form method="post" name="des_nomina" id="des_nomina" action="../control/guardar_descuento_legalizacion_adicional.php">
    <table border="1" bordercolor="#0099CC" id="mitabla">
        <tr>
            <th colspan="2">ADICIONAR DESCUENTOS DE LEGALIZACI&Oacute;N - FACTURA: <?php echo $res_fac_rec_caja['fac_consecutivo']; ?></th>
        </tr>
        
      	<tr>
      		<th>VALOR</th>
            <td><input type="text" name="des_nom_valor0" id="des_nom_valor0" required="required"/></td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="2">
            <input type="hidden" name="cuantos" id="cuantos" />
            <input type="hidden" name="des_nom_rec_caja" id="des_nom_rec_caja" value="<?php echo $_GET['rec_caj_seleccionado']; ?>"/>
            <input type="button" class="art-button" value="Agregar Fila" onclick="agregar();"/>
            <input type="submit" class="art-button" value="Guardar"/>
            </td>
        </tr>
    </table>
</form>
</center>
</body>
</html>


