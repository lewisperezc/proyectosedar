<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('clases/usuario.class.php');
  $usuario = new usuario();
  $perfiles = $usuario->get_perfiles();
  $casos_uso = $usuario->get_casosUso();
  $_SESSION['perfil'] = $_POST['nom_perfil'];
?>

<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="librerias/js/validacion_email.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script language="javascript" type="text/javascript">
function enviar()
{
	var form = document.cre_perfil;
	if(form.nom_perfil.value == 0)
	 {
	   alert('Debe escribir el nombre del perfil');
	   form.nom_perfil.focus();
	 }
	else
	 form.submit();     
}
function habilitar()
  {
    for (i=0;i<document.forms[1].elements.length;i++) 
	  {
        if (document.forms[1].elements[i].disabled) 
		{
          document.forms[1].elements[i].disabled = false;
		}
      }
	  	  document.casos.gua.disabled=false;
  }
</script>

<form name="cre_perfil" id="cre_perfil" action="#" method="post">
 <center>
  <table>
   <tr>
    <td colspan="2">Nombre del perfil</td>
    <td colspan="2">
      <select name="nom_perfil" id="nom_perfil" required x-moz-errormessage="Seleccione Una Opcion Valida">
       <?php
	    echo "<option value=''>Seleccione...</option>";	
	    while($dat_perfil = mssql_fetch_array($perfiles))
		 {
			 if($perfil[0]==$dat_perfil['per_id'])
		       echo "<option value='".$dat_perfil['per_id']."-".$dat_perfil['per_nombre']."' selected='selected' onclick='enviar();'>".$dat_perfil['per_nombre']."</option>";
			 else
			   echo "<option value='".$dat_perfil['per_id']."-".$dat_perfil['per_nombre']."' onclick='enviar();'>".$dat_perfil['per_nombre']."</option>";	
		 }
	   ?>
      </select>
    </td>
   </tr>
  </table>
  <br /><br />
 </center>
</form>

<?php

  if($_SESSION['perfil']!='')
  { 
    $perfil = split("-",$_SESSION['perfil'],2);
	$_SESSION['perfil'] = $perfil;
    ?>
    <form name="casos" id="casos" method="post" action="control/guardar_perfil.php?modificar=1">
     <center>
      <table>
       <tr>
        <td>Caso de Uso</td>
        <td>Seleccion</td>
       </tr>
       <tr>
        <?php 
       $i=0;$j=0;
	   while($dat_casos = mssql_fetch_array($casos_uso))
	    {
		  $_SESSION['cant'] = mssql_num_rows($casos_uso);
		  $casos = $usuario->casos_perfil($perfil[0]);
		  $temp = 0;
		  while($dat_perfil = mssql_fetch_array($casos))
		  {
			if($dat_perfil['per_por_cas_cas_id']==$dat_casos['cas_uso_id'])
			 {
			   $temp = 1;
			   echo "<td>".$dat_casos['cas_uso_nombre']." </td>";
echo "<td><input type='checkbox' name='cas_uso[$j]' id='cas_uso[$j]' checked='checked' disabled value='".$dat_casos['cas_uso_id']."' /></td>";
			   break;
			 }
		  }
		  if($temp == 0)
		     {
			   echo "<td>".$dat_casos['cas_uso_nombre']." </td>";
			   echo "<td><input type='checkbox' name='cas_uso[$j]' id='cas_uso[$j]' disabled value='".$dat_casos['cas_uso_id']."'/></td>";
			 }
	      if($i%2==0)
	      {
		    echo "</tr>";
		    echo "<tr>";
	      }
	     $j++; 
	    }  ?>
	  </tr>
     </table><br />
     <table><tr><td><input type="button" class="art-button" name="mod" id="mod" value="Modificar" onclick="habilitar();" />
     <input type="submit" class="art-button" name="gua" id="gua" value="Guardar" />
     </td></tr></table>
     </center>
    </form>
 <?php }
?>