<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../clases/factura.class.php');
$instancia_factura = new factura();
$con_tip_facturacion = $instancia_factura->con_tipo_facturacion();

?>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<script>
function enviando()
{
	document.f1.submit();
}
	
function validar_campos()
{	  
	var suma_jornadas = 0;
	var bandera = 0;
	for(i=0;i<document.f1.num_jornadas.length;i++)
	{
		if(document.f1.num_jornadas[i].value == '' || document.f1.num_jornadas[i].value >120)
	    {
	    	alert("Debe escribir un numero >= 0 y < que 120 en el numero de jornadas "+ (i+1));
			bandera = 1;
		    f1.num_jornadas.focus();
	    }
	}
	if(bandera==0)
		document.f1.action = 'control/guardar_reporte_jornadas.php';
}
	
</script>

<form method="post" name="f1" action="trae_form_tipo_facturacion.php" target="frame2">
  <center>
   <table>
    <tr>
      <td>
       <select name="tip_fac" id="tip_fac" required x-moz-errormessage="Seleccione Una Opcion Valida">
        <option value="">Seleccione...</option>
        <?php
        	while($row = mssql_fetch_array($con_tip_facturacion))
			{
		?>
        		<option value="<?php echo $row['tip_fac_id']; ?>" onclick="enviando();"><?php echo $row['tip_fac_nombre']; ?></option>
       <?php
			}
	   ?>
       </select>
      </td>
    </tr>
   </table>
  </center>
</form>