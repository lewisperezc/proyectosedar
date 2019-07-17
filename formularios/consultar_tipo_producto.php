<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script>
function validar_vacios()
{
	document.consultar_tipo_producto.submit();
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
  	document.modificar_tipo_producto.guardar.disabled=false;
}
</script>
<?php
@include_once('./clases/tipo_producto.class.php');
@include_once('../clases/tipo_producto.class.php');
@include_once('./clases/concepto.class.php');
@include_once('../clases/concepto.class.php');
@include_once('./clases/cuenta.class.php');
@include_once('../clases/cuenta.class.php');
$tip=new tipo_producto();
$ins_concepto=new concepto();
$con_tod_conceptos=$ins_concepto->consulta_concepto();
$ins_cuenta=new cuenta();
$con_tod_cuenta=$ins_cuenta->cue_Pagar(23);
?>
<form name="consultar_tipo_producto" method="post" >
	<center>
    	<table>
        	<tr>
            	<td>Tipo Producto</td>
                <td>
                	<select name="tipoproducto" onchange="validar_vacios();">
                    	<option value="">Seleccione un tipo de producto</option>
                        <?php		
						$tipo=$tip->cons_tipo_producto();
						while($row= mssql_fetch_array($tipo)){
						?>
                        <option value="<?php echo $row ['tip_pro_id'];?>"><?php echo $row ['tip_pro_nombre'];?></option>
                        <?php
					     }											 
						?>
                    </select>
                </td>
             </tr>
           </table>
	</center>
</form>
<?php
 session_start();
 $sel=$_POST[tipoproducto];
  $_SESSION["tipo"]=$sel;
 
 if($sel)
 {
	 $_SESSION["tipo"]=$sel;
	 $descripcion=$tip->cons_descripcion($sel);
	 $consulta=mssql_fetch_array($descripcion);
 		?>                   
<form name="modificar_tipo_producto" method="post" action="./control/modificar_tipo_producto.php">
<center>
<table>
	<tr>    	
    	<th>Tipo producto:</th>
        <td colspan="3"><input name="cambio" type="text" value="<?php  echo $consulta['tip_pro_nombre'];?>" disabled="disabled" required="required"/></td>
    	<th>Cuenta:</th>
        <td>
        <select name="cue_id" id="cue_id" disabled="disabled"><option value="">--</option>
        <?php
        while($res_tod_cuenta=mssql_fetch_array($con_tod_cuenta))
		{
			if($res_tod_cuenta['cue_id']==$consulta['cue_id'])
			{
		?>
      		<option value="<?php echo $res_tod_cuenta['cue_id']; ?>" selected="selected">
			<?php echo substr($res_tod_cuenta['cue_nombre'],0,35); ?></option>
        <?php
			}
			else
			{
		?>
        		<option value="<?php echo $res_tod_cuenta['cue_id']; ?>">
			<?php echo substr($res_tod_cuenta['cue_nombre'],0,35); ?></option>
        <?php
			}
		}
		?>
        </select>
        </td>
    	<th>Concepto:</th>
        <td><select name="con_id" id="con_id" disabled="disabled"><option value="">--</option>
        <?php
        while($res_tod_conceptos=mssql_fetch_array($con_tod_conceptos))
		{
			if($res_tod_conceptos['con_id']==$consulta['con_id'])
			{
		?>
      		<option value="<?php echo $res_tod_conceptos['con_id']; ?>" selected="selected">
			<?php echo substr($res_tod_conceptos['con_nombre'],0,35); ?></option>
        <?php
			}
			else
			{
		?>
        		<option value="<?php echo $res_tod_conceptos['con_id']; ?>">
			<?php echo substr($res_tod_conceptos['con_nombre'],0,35); ?></option>
        <?php
			}
		}
		?>
        </select>
        </td>
    </tr>    
    <tr>
        <td colspan="8"><input type="button" class="art-button" name="editar" value="Modificar"  onclick="habilitar();"/>
        <input type="submit" class="art-button" name="guardar" id="guardar" value="Guardar" disabled="disabled"/></td>
    </tr>
</table>
</center>
</form>
<?php
  }
?>