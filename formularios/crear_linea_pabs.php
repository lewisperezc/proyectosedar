<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script src="librerias/js/validacion_num_letras.js"></script>
<?php
if(!$agregar)
   $_SESSION["aso_per_cargo"] = $_POST['aso_per_cargo'];
?>
<?php
@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');
$instancia_nits = new nits();
?>
<form id="aso_lin_pabs" name="aso_lin_pabs" method="post">
<center>
  <?php 
//INICIO CAPTURO LOS DATOS DEL MISMO FORMULARIO PARA QUE LOS DEJE CADA VEZ QUE AGREGUEN UN ITEM
$_SESSION['aso_lin_pab_nombre'] = $_POST['aso_lin_pab_nombre'];
$_SESSION['aso_lin_pab_porcentaje'] = $_POST['aso_lin_pab_porcentaje'];
//FIN CAPTURO LOS DATOS DEL MISMO FORMULARIO PARA QUE LOS DEJE CADA VEZ QUE AGREGUEN UN ITEM
$numero=1;
if (!empty($_REQUEST['numero'])){
$numero=$_REQUEST['numero'];
}
?>
<table>
  <tr>
       <td colspan="6" ><h4>Crear Linea PABS</h4></td>
  </tr>
  <tr>
     <td>Nombre Linea</td>
     <td>Porcentaje</td>
  </tr>
  <?php
    $recor = 0;
    while($recor < $numero)
  {
  ?>
  <tr>
       <td>
         <input type="text" name="aso_lin_pab_nombre[<?php echo $recor; ?>]" onKeyPress="return permite(event,'car')" value="<?php echo $_SESSION['aso_lin_pab_nombre'][$recor]; ?>"/>
         </td> 
     <td>
         <input type="text" name="aso_lin_pab_porcentaje[<?php echo $recor; ?>]" onKeyPress="return permite(event,'num')" value="<?php echo $_SESSION['aso_lin_pab_porcentaje'][$recor]; ?>"/>
         </td>
  </tr>
  <?php
     $recor++;   
     }
    $numero++;
  ?>
  <tr>
   <td colspan="4">
      <input type="submit" class="art-button" name="agregar" value="Agregar"/>
      <input type="hidden" name="numero" id="numero" value="<?php echo $numero; ?>">
      <input type="submit" class="art-button" value="Guardar" onClick="document.aso_lin_pabs.action='control/guardar_linea_pabs.php'"/>
     </td>
   </tr>           
</table>
</center>
</form>