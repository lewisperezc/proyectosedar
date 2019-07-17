<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
$cen_cos = 1;
include_once('../conexion/conexion.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/nits_tipo.class.php');
$centro = new centro_de_costos();
$aso = new tipo_nit();
?>
<script>
	function pulsa(combo1,combo2)
	{
		var j = document.getElementById(combo1);
		var m = document.getElementById(combo2);
		if(j.length>0)
		{
			var k = j.options[j.selectedIndex].value;
			var t = j.options[j.selectedIndex].text;
			var l = k.split(",");
		    borra=l[0];
		    dborra=l[1];
		    var aBorrar=document.forms["for_nitxcen"][combo1].options[j.selectedIndex];
		    aBorrar.parentNode.removeChild(aBorrar);
		    z=m.length;
	 	   m.options[z]=new Option(t,k,0);
		   m.selectedIndex=0;
		   j.selectedIndex=0;
	   }
    }
</script>
<form name = "for_nitxcen" id = "for_nitxcen" action="prueba.php" method="post">
 <center>
  <?php 
    $centro_cos = $centro->buscar_centros($cen_cos);
	$costo = mssql_fetch_array($centro_cos);
  ?>
   <table id="nit_cen">
      <tr>
       <td>Afiliados a Asignar</td>
       <td>&nbsp;</td>
	   <td>A Asignar: </td>
      </tr>
      <tr>
	    <td>
	      <select name="asociados[]" id="asociados[]" size=5 style='width:250px;height:175px;border:solid' multiple="multiple">
	        <?php
		   $asociados = $aso->con_tip_nit(1);
		   $i=1;
		   while($asoci = mssql_fetch_array($asociados))
		   {
			if($i==1)
			 echo "<option value = '".$asoci['nit_id']."' selected>".$asoci['nits_nombres']." ".$asoci['nits_apellidos']."</option>";
			else
			 echo "<option value = '".$asoci['nit_id']."'>".$asoci['nits_nombres']." ".$asoci['nits_apellidos']."</option>";
			 $i++;
			}
			?> 
          </select>
        </td>
      </tr>
   </table>
   <p><input type="submit" class="art-button" name="aso" id="aso" value="Probar"/></p>
 </center>
</form>