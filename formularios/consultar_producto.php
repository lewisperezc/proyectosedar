<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('./clases/producto.class.php');
@include_once('../clases/producto.class.php');
$ins_producto=new producto();
$con_tod_productos=$ins_producto->todosProductos();
?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script>
function ConDatProducto(producto)
{
	$.ajax({
   	type: "POST",
   	url: "llamados/trae_datos_producto.php",
   	data: "id="+producto,
   	success: function(msg){
    $("#losdatos").html(msg);
   	}
 	});
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
  	document.consultar_producto.gua.disabled=false;
	document.consultar_producto.mod.disabled=true;
}
</script>
<form name="consultar_producto" id="consultar_producto" method="post" action="./control/modificar_producto.php">
	<center>
    <table>
    	<tr>
        	<th>Producto:</th>
            <td>
            <select name="pro_id" id="pro_id" onchange="ConDatProducto(this.value);"><option value="">--</option>
            <?php
            while($res_tod_productos=mssql_fetch_array($con_tod_productos))
			{
			?>
            	<option value="<?php echo $res_tod_productos['pro_id']; ?>"><?php echo $res_tod_productos['pro_nombre']; ?></option>
            <?php
			}
			?>
            </select>
            </td>
        </tr>
    </table>
    <table id="losdatos">
    </table>
    </center>
</form>