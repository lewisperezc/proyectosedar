<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('clases/centro_de_costos.class.php');
include_once('clases/cuenta.class.php');
include_once('clases/nits.class.php');
$nit = new nits();
$dat_seg = $nit->consultar_segSocial();
?>
<form name="porSeg" id="porSeg" method="post" action="./control/guarPorcentajes.php">
 <center>
  <table>
  <?php
   $i=1;
   while($row = mssql_fetch_array($dat_seg))
   {
     echo "<tr>
      <td>".$row['tip_segSoc_nombre']."</td>
      <td><input type='hidden' name='seg_soc_id[$i]' id='seg_soc_id[$i]' value='".$row['tip_segSoc_id']."'/>
      <input type='text' name='segSocial[$i]' id='segSocial[$i]' value='".$row['tip_segSoc_porcentaje']."' /></td>
     </tr>";
	 $i++;
   }
   ?> 
   <tr>
    <td colspan="2">
     <input type="submit" class="art-button" name="boton" id="boton" value="Aceptar" />
    </td>
   </tr>
  </table>
 </center>
</form>