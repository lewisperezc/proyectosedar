<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script language="javascript" type="text/javascript">
function enviar()
{
		document.con_centros.submit();
} 
function modificar()
{
    for (i=0;i<document.forms[1].elements.length;i++) 
	{
    	if (document.forms[1].elements[i].disabled) 
		{
          document.forms[1].elements[i].disabled = false;
        }
     }
}
</script>
<?php
include_once('clases/centro_de_costos.class.php');
include_once('clases/ciudades.class.php');
$cen_cos = new centro_de_costos();
$ciudad = new ciudades();
$tod_cen_cos = $cen_cos->cons_centro_costos();
$ciudades = $ciudad->consultar_ciudades();
?>
<td>
<form name="con_centros" method="post">
 <center>
  <table>
   <tr>
     <td>Seleccione:</td>
     <td>
       <select name="centros_costo" id="centros_costo" required x-moz-errormessage="Seleccione Una Opcion Valida">
        <option value="">Centros de Costo</option>
       <?php 
         while($row = mssql_fetch_array($tod_cen_cos))
		 { ?>
	     <option value="<?php echo $row['cen_cos_id'] ?>" onClick="enviar();"><?php echo $row['cen_cos_codigo']."--".$row['cen_cos_nombre'] ?></option>
      <?php
         }?>
        </select>
     </td>
    </tr>
   </table>
 </center>
</form>
<form name="datos_linea" method="post" action="control/modificar_centro_costo.php">
<?php
$_SESSION['centros_costo'] = $_POST['centros_costo'];
$cen_costo = $_SESSION['centros_costo'];
if($cen_costo)
{
	$centro_costo = $cen_cos->buscar_centros($cen_costo);
?>
<center>
	<table>
		<tr>
        	<td>Nombre Centro de costo:</td>
        	<td>Ciudad</td>
        	<td>Codigo centro de costo</td>
    	</tr>
	<?php
    while($rows = mssql_fetch_array($centro_costo))
    {
    ?>
    <tr>
        <td><input type="text" name="nom_cen" disabled="disabled" value="<?php echo $rows['cen_cos_nombre'];?>" required="required"/></td>
        <td>
        <select id="ciudad" name="ciudad" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
        <option value="">--Seleccione--</option>
        <?php 
        $centros = $cen_cos->cons_centro_costos();
        while($row = mssql_fetch_array($centros))
        {
            while($dat_ciudades = mssql_fetch_array($ciudades))
            {
                if($row['ciud_ciu_id'] == $dat_ciudades['ciu_id'])
                    echo "<option value='".$dat_ciudades['ciu_id']."' selected = 'selected'>".$dat_ciudades['ciu_nombre']."</option>";
                else
                    echo "<option value='".$dat_ciudades['ciu_id']."'>".$dat_ciudades['ciu_nombre']."</option>";
            }	  
        }	
         ?>
       </select>
        </td>
         <td><input type="text" name="cod_cen" disabled="disabled" value="<?php echo $rows['cen_cos_codigo']; ?>" required="required"/></td>
        </tr>
        <tr>
         <td colspan="3">
           <input type="button" class="art-button" name="editar" value="Modificar" onclick="modificar();"/>
           <input type="submit" class="art-button" name="guardar" value="Guardar" disabled="disabled"/>
         </td>
        </tr>
        <?php
    }
    ?>
    </table>
<?php
} 
?>
</center>
</form>