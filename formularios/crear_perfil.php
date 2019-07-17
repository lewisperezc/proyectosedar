<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  @include_once('clases/usuario.class.php');
  @include_once('../clases/usuario.class.php');
  $usuario = new usuario();
  $casos = $usuario->get_casosUso();
  $reportes=$usuario->con_men_por_modulo(5);
?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="librerias/js/validacion_email.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<form name="cre_perfil" id="cre_perfil" action="control/guardar_perfil.php" method="post">
 <center>
  <table>
    <tr>
    	<th colspan="2">Nombre del perfil</th>
    	<td colspan="2"><input type="text" name="nom_perfil" id="nom_perfil" required="required"/></td>
    </tr>
    <?php
    $i=0;$j=0;
    echo "<tr>";
	$_SESSION['cant'] = mssql_num_rows($casos);
	while($dat_casos = mssql_fetch_array($casos))
	{
	  if($i%4==0)
	  {
		echo "</tr>";
		echo "<tr>";
	  }
	   echo "<td colspan='3'>".$dat_casos['cas_uso_nombre']." </td>
	   <td><input type='checkbox' name='cas_uso[$j]' id='cas_uso[$j]' value='".$dat_casos['cas_uso_id']."' /></td>";
	  $j++; 
	}
	echo "</tr>";
    ?>
    <tr><th colspan="4">REPORTES</th></tr>
    <?php
    $k=0;$l=0;
    echo "<tr>";
	$_SESSION['cantmenus']=mssql_num_rows($reportes);
	while($dat_menus = mssql_fetch_array($reportes))
	{
	  if($i%4==0)
	  {
	  	echo "</tr>";
		echo "<tr>";
	  }
	    echo "<td colspan='3'>".$dat_menus['men_nombre']." </td>
	    <td><input type='checkbox' name='men[$l]' id='men[$l]' value='".$dat_menus['men_id']."' /></td>";
	    $l++;
	}
	echo "</tr>";
   ?>
    <tr>
        <td colspan="4"><input type="submit" class="art-button" name="boton" value="Crear perfil"/></td>
    </tr>
  </table>
 </center>
</form>