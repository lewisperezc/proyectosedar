<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('clases/orden_compra.class.php');
  include_once('clases/factura.class.php');
?>
<script language="javascript">
 function valida()
	{
		if(document.cauFac.legOrd.selectedIndex == 0)
		{
			alert('Seleccione una opcion');
		}
		else
		{
			document.cauFac.submit();
		}
	}
	
function valida1()
	{
		if(document.conOrdCom.select2.selectedIndex == 0)
		{
			alert('Seleccione una opcion');
		}
		else
		{
			document.conOrdCom.submit();
		}
	}
	
</script>
<form name="cauFac" id="cauFac" method="post">
 <center>
   <table>
     <tr>
	  <td>La factura legaliza una orden de compra?</td>
	  <td>
	    <select name="legOrd" id="legOrd">
		  <option value="0" onclick="valida()">Seleccione...</option>
		  <option value="1" onclick="valida()">Si</option>
		  <option value="2" onclick="valida()">No</option>
		</select>
	  </td>
	 </tr>
   </table>
 </center>
</form>
<?php
 $legOrd = $_POST['legOrd'];
 if($legOrd == 1)
 {
	include_once('conte_causar_factura.php');
 }
 else
 {
 	if($legOrd == 2)
	{
 		include_once('conte_transacciones.php');
	}
 }
?>