<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/contrato.class.php');
$instancia_contrato = new contrato();
$con_tip_nit = $instancia_contrato->con_tip_nit(10);
$con_tip_concepto = $instancia_contrato->con_tip_concepto(9);
?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<?php
//INICIO CAPTURO LOS DATOS QUE DIGITAN EN EL FORMULARIO ANTERIOR
if(!$agregar)
{
	$_SESSION['con_adm_ips_nom_pol_aseguradora'] = $_POST['con_adm_ips_nom_pol_aseguradora'];
	$_SESSION['con_adm_ips_con_pol_nombre'] = $_POST['con_adm_ips_con_pol_nombre'];
	$_SESSION['con_adm_ips_pol_porcentaje'] = $_POST['con_adm_ips_pol_porcentaje'];
}
//FIN CAPTURO LOS DATOS QUE DIGITAN EN EL FORMULARIO ANTERIOR
?>
<form name="con_adm_ips_impuesto" method="post">
<center>
  <?php
  $_SESSION['con_adm_ips_nom_imp_aseguradora'] = $_POST['con_adm_ips_nom_imp_aseguradora'];
  $_SESSION['con_adm_ips_imp_nombre'] = $_POST['con_adm_ips_imp_nombre'];
  $_SESSION['con_adm_ips_imp_porcentaje'] = $_POST['con_adm_ips_imp_porcentaje'];
?>
<table>
  <tr>
      <td colspan="4"><b>Impuestos Contrato Administraci&oacute;n IPS</b></td>
    </tr>
    <tr>
      <td><b>Aseguradora</b></td>
        <td><b>Impuesto</b></td>
        <td><b>Porcentaje</b></td>
    </tr>
    <?php 
$numero_dos=1;
if (!empty($_REQUEST['numero_dos'])){
$numero_dos=$_REQUEST['numero_dos'];
}
     $recor_dos = 0;
     while($recor_dos < $numero_dos)
   {
     $con_tip_nit = $instancia_contrato->con_tip_nit(10);
     $con_tip_concepto = $instancia_contrato->con_tip_concepto(9);
   ?>
      <tr>
          <td>
            <select name="con_adm_ips_nom_imp_aseguradora[<?php echo $recor_dos; ?>]">
              <option value="NULL">--Seleccione--</option>
            <?php
            while($row = mssql_fetch_array($con_tip_nit))
      {
        if($_SESSION['con_adm_ips_nom_imp_aseguradora'][$recor_dos] == $row['nit_id'])
        {
      ?>
              <option value="<?php echo $row['nit_id']; ?>" selected><?php echo $row['nits_nombres']; ?></option>
            <?php
        }
        else
        {
        ?>
          <option value="<?php echo $row['nit_id']; ?>"><?php echo $row['nits_nombres']; ?></option>
            <?php
        }
      }
      ?>
            </select>
            </td>
            <td>
            <select name="con_adm_ips_imp_nombre[<?php echo $recor_dos; ?>]">
              <option value="NULL">--Seleccione--</option>
            <?php
            while($row = mssql_fetch_array($con_tip_concepto))
      {
        if($_SESSION['con_adm_ips_imp_nombre'][$recor_dos] == $row['con_id'])
        {
      ?>
              <option value="<?php echo $row['con_id']; ?>" selected><?php echo $row['con_nombre']; ?></option>
            <?php
        }
        else
        {
        ?>
          <option value="<?php echo $row['con_id']; ?>"><?php echo $row['con_nombre']; ?></option>
            <?php
        }
      }
      ?>
            </select>
            </td>
            <td>
            <input type="text" name="con_adm_ips_imp_porcentaje[<?php echo $recor_dos; ?>]" value="<?php echo $_SESSION['con_adm_ips_imp_porcentaje'][$recor_dos]; ?>" onkeypress="return permite(event,'num')"/>
        </td>
        </tr>
         <?php
      $recor_dos++;   
    }
      $numero_dos++;
    ?>
     <tr>
      <td colspan="4"><input type="submit" class="art-button" name="agregar" value="Agregar"/>
            <input type="hidden" name="numero_dos" id="numero_dos" value="<?php echo $numero_dos; ?>">
          <input type="submit" class="art-button" name="atras" onclick="document.con_adm_ips_impuesto.action = 'crear_contrato_administracion_ips_2.php'" value="<< Atras">
             <input type="submit" class="art-button" name="guardar" onclick="document.con_adm_ips_impuesto.action = '../control/guardar_contrato_administracion_ips.php'" value="Crear Contrato">
        </td>
    </tr>
</table>
</center>
</form>