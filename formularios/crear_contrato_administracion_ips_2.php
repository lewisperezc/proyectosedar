<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/contrato.class.php');
$instancia_contrato = new contrato();
$con_tip_nit = $instancia_contrato->con_tip_nit(9);
$con_tip_concepto = $instancia_contrato->con_tip_concepto(8);
?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<?php
//INICIO CAPTURO LOS DATOS QUE DIGITAN EN EL FORMULARIO ANTERIOR
if(!$agregar)
 {
  $_SESSION['con_adm_ips_num_consecutivo'] = $_POST['con_adm_ips_num_consecutivo'];
  $_SESSION['con_adm_ips_hospital'] = $_POST['con_adm_ips_hospital'];
  $_SESSION['con_adm_ips_vigencia'] = $_POST['con_adm_ips_vigencia'];
  $_SESSION['con_adm_ips_valor'] = $_POST['con_adm_ips_valor'];
  $_SESSION['con_adm_ips_cuo_mensual'] = $_POST['con_adm_ips_cuo_mensual'];
  $_SESSION['con_adm_ips_fec_inicial'] = $_POST['con_adm_ips_fec_inicial'];
  $_SESSION['con_adm_ips_fec_fin'] = $_POST['con_adm_ips_fec_fin'];
  $_SESSION['con_adm_ips_estado'] = $_POST['con_adm_ips_estado'];
  $_SESSION['con_adm_ips_est_legalizado'] = $_POST['con_adm_ips_est_legalizado'];
 }
//FIN CAPTURO LOS DATOS QUE DIGITAN EN EL FORMULARIO ANTERIOR
?>
<form name="con_adm_ips_poliza" method="post">
<center>
  <?php
  $_SESSION['con_adm_ips_nom_pol_aseguradora'] = $_POST['con_adm_ips_nom_pol_aseguradora'];
  $_SESSION['con_adm_ips_con_pol_nombre'] = $_POST['con_adm_ips_con_pol_nombre'];
  $_SESSION['con_adm_ips_pol_porcentaje'] = $_POST['con_adm_ips_pol_porcentaje'];
?>
<table>
    <tr>
        <td colspan="4"><b>Polizas Contrato Administraci&oacute;n IPS</b></td>
    </tr>
    <tr>
      <td><b>Aseguradora</b></td>
        <td><b>Poliza</b></td>
        <td><b>Porcentaje</b></td>   
    </tr>
<?php 
$numero=1;
if (!empty($_REQUEST['numero'])){
$numero=$_REQUEST['numero'];
}
     $recor = 0;
     while($recor < $numero)
   {
     $con_tip_nit = $instancia_contrato->con_tip_nit(9);
     $con_tip_concepto = $instancia_contrato->con_tip_concepto(8);
?>
      <tr>
      <td>
           <select name="con_adm_ips_nom_pol_aseguradora[<?php echo $recor; ?>]">             
           <option value="NULL">--Seleccione--</option>
       <?php
           while($row = mssql_fetch_array($con_tip_nit))
           {
           if($_SESSION['con_adm_ips_nom_pol_aseguradora'][$recor] == $row['nit_id'])
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
        <select name="con_adm_ips_con_pol_nombre[<?php echo $recor; ?>]">
        <option value="NULL" onClick="ver_tipo_poliza">--Seleccione--</option>
        <?php
           while($row = mssql_fetch_array($con_tip_concepto))
           {
         if($_SESSION['con_adm_ips_con_pol_nombre'][$recor] == $row['con_id'])
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
        <input type="text" name="con_adm_ips_pol_porcentaje[<?php echo $recor; ?>]" value="<?php echo $_SESSION['con_adm_ips_pol_porcentaje'][$recor]; ?>" onkeypress="return permite(event,'num')"/>
        </td>
    </tr>
    <?php
      $recor++;   
    }
      $numero++;
    ?>
    <tr>
      <td colspan="4"><input type="submit" class="art-button" name="agregar" value="Agregar"/>
            <input type="hidden" name="numero" id="numero" value="<?php echo $numero; ?>">
      <input type="submit" class="art-button" name="atras" value="<< Atras" onClick="document.con_adm_ips_poliza.action='crear_contrato_administracion_ips.php'"/>
      <input type="submit" class="art-button" name="sigiente" value="Sigiente >>" onClick="document.con_adm_ips_poliza.action='crear_contrato_administracion_ips_3.php'"/></td>
    </tr>
</table>
</center>
</form>