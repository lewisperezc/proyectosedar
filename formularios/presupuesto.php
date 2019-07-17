<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script>
function validar_vacios(reg_presupuesto)
  {
	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	var form = document.reg_presupuesto;
	//VALIDAMOS EL CAMPO NOMBRE
	if (form.pre_cen_costo.value == "NULL")
	{ CamposVacios += "* Centro De Costo\n"; }
    //SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	if (CamposVacios != "")
	{
		alert(Mensaje + CamposVacios);
		return true;
	}
		//alert('Se Cumple Too!!!');
		form.submit();
  }
</script>
<title>Presupuesto</title>
</head>
<body>
<?php
@include_once('../clases/centro_de_costos.class.php');
@include_once('clases/centro_de_costos.class.php');
@include_once('../clases/cuenta.class.php');
@include_once('clases/cuenta.class.php');
@include_once('../clases/presupuesto.class.php');
@include_once('clases/presupuesto.class.php');

$ins_presupuesto=new presupuesto();
$ins_cuenta=new cuenta();
$con_cuentas=$ins_cuenta->con_cue_menores(5);
$ins_cen_costo=new centro_de_costos();
$con_cen_cos_nit=$ins_cen_costo->con_cen_cos_es_nit();
$res_anios=$ins_presupuesto->obtener_lista_anios();
?>
	<form method="post" name="reg_presupuesto" id="reg_presupuesto" action="../contabilidad/control/guardar_presupuesto.php">
    	<center>
            <table border="1">
            <tr>
                <th colspan="2">Presupuesto Por Centro De Costo</th>
            </tr>
            <tr>
                <th>Centro De Costo</th>
                <th>Año</th>
            </tr>
            <tr>
                <td>
                <select name="pre_cen_costo" id="pre_cen_costo"><option value="NULL">--Seleccione--</option>
            <?php
                while($res_cen_cos_nit=mssql_fetch_array($con_cen_cos_nit)){
            ?>
                    <option value="<?php echo $res_cen_cos_nit['cen_cos_id']; ?>"><?php echo $res_cen_cos_nit['cen_cos_nombre']; ?>
                    </option>
            <?php
                }
            ?>              
                </select>
                </td>
                <td><select id="pre_fecha" name="pre_fecha">
                <?php
                for($i=0;$i<sizeof($res_anios);$i++){
                ?>
                    <option value="<?php echo $res_anios[$i]; ?>"><?php echo $res_anios[$i]; ?></option>
                <?php 
                }
                ?>
                </select></td>
            </tr>
            <tr>
                <th>Cuenta</th>
                <th>Presupuesto</th>
            </tr>
            <?php
            while($res_cuentas=mssql_fetch_array($con_cuentas)){
            ?>
            <tr>
                <td><?php echo $res_cuentas['cue_id']." - ".$res_cuentas['cue_nombre']; ?>
                <input type="hidden" name="pre_cuenta[]" id="pre_cuenta[]" value="<?php echo $res_cuentas['cue_id']; ?>"/>
                </td>
                <td><input type="text" name="pre_valor[]" id="pre_valor[]" value="0" /></td>
            </tr>
           <?php } ?>
           <tr>
            <td colspan="2"><input type="button" class="art-button" onclick="validar_vacios();" name="guardar" value="Guardar Presupuesto"/></td>
           </tr>
        </table>
        </center>
    </form>
</body>
</html>