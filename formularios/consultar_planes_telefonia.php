<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<body>
<?php
include_once('clases/plan_telefonia.class.php');
$ins_plan_telefonia = new plan_telefonia();
$con_planes = $ins_plan_telefonia->cons_tod_pla_telefonia();

@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$ins_nits = new nits();
$con_tod_proveedores = $ins_nits->con_tip_nit(3);
?>
<script>
function enviar()
{
	document.form_planes.submit();
}
function habilitar()
{
   for (i=0;i<document.forms[0].elements.length;i++) 
   {
      if (document.forms[0].elements[i].disabled) 
      {
         document.forms[0].elements[i].disabled = false;
	  }
   }
	  	  document.form_planes.guardar.disabled=false;
}
function validar_vacios(form_planes)
{
	document.form_planes.submit();
}
</script>
<form name="form_planes" method="post" action="#">
	<center>
        <table>
        <tr>
            <th colspan="6">Consultar Planes Telefonia</th>
        </tr>
        <tr>
        <td colspan="6">Planes
            <select name="planes" id="planes" required x-moz-errormessage='Seleccione Una Opcion Valida'>
                <option value="">Seleccione</option>
           <?php while($row=mssql_fetch_array($con_planes)){ ?>
                <option value="<?php echo $row['pla_tel_id']; ?>" onclick="enviar();"><?php echo $row['pla_tel_nombre']; ?></option>
            <?php } ?>
            </select></td>
    </tr>
    </table>
    </center>
</form>
<?php 
if(isset($_POST['planes']))
{
	$_SESSION['planes'] = $_POST['planes'];
	$plan_id = $_SESSION['planes'];
	$cons_dat_linea = $ins_plan_telefonia->cons_pla_por_id($plan_id);
	$result = mssql_fetch_array($cons_dat_linea);
?>
		<form name="datos_plan" id="datos_plan" action="../control/actualizar_plan_telefonia.php">
            <center>
                <table>
        <tr>
            <td>Nombre</td><td><input type="text" name="cre_pla_tel_nombre" value="<?php echo $result['pla_tel_nombre'] ?>" disabled="disabled" required="required"/></td>
            <td>Valor</td><td><input type="text" name="cre_pla_tel_valor" value="<?php echo $result['pla_tel_valor'] ?>" disabled="disabled" required="required"/></td>
            <td>Proveedor</td><td><select name="cre_pla_tel_proveedor" disabled="disabled" required x-moz-errormessage='Seleccione Una Opcion Valida'>
            <option value="">--Seleccione--</option>
            <?php while($res_tod_proveedores = mssql_fetch_array($con_tod_proveedores)){
                  if($result['nit_id'] == $res_tod_proveedores['nit_id']){
            ?>
            <option value="<?php echo $res_tod_proveedores['nit_id']; ?>" selected="selected">
            <?php echo $res_tod_proveedores['nits_nombres']; ?>
            </option>
            <?php }
                  else{
            ?>
            <option value="<?php echo $res_tod_proveedores['nit_id']; ?>">
            <?php echo $res_tod_proveedores['nits_nombres']; ?>
            </option>
            <?php
                  }
            }
            ?>
            </select></td>
        </tr>
        <tr>
            <td colspan="6">
            <input type="button" class="art-button" name="modificar" value="Modificar" onclick="habilitar();"/>
            <input type="submit" class="art-button" name="guardar" id="guardar" value="Guardar" disabled="disabled"/>
            </td>
        </tr>
    </table>
            </center>
        </form>
<?php
}
?>
</body>
</html>