<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script>
	function valida()
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
<?php
 include('../clases/moviminetos_contables.class.php');
 include('../clases/orden_compra.class.php');
 $orden = new orden_compra();
 $form =new movimientos_contables();
 function generaOpciones()
 {
   echo "<select name='conPor' id='select1' onChange='cargaContenido(this.id)'>
           <option value='0'>Seleccione...</option>
		   <option value='1'>Centro de Costo</option>
	       <option value='2'>Proveedor</option>
	       <option value='3'>Consecutivo</option>
         </select>";
 }
?>
<script language="javascript" src="../librerias/ajax/frame_opcOrd.js"></script>
<form id="conOrdCom" name="conOrdCom" method="post">
 <center>
    <table id="conOrd">
     <tr>
      <td>Consultar por: </td>
      <td><?php generaOpciones(); ?></td>
     </tr>
	 <tr>
	   <td>Numero: </td>
	   <td><select name="dato" size="1" disabled="disabled" id="select2">
            <option value="0" onclick="valida();">Selecciona opci&oacute;n...</option>
          </select></td>
	 </tr>
   </table>
 </center> 
</form>
<?php
	$consulta = $_POST['conPor'];
	$captura = $_POST['select2'];
    if($captura)
	{
?>
<form id="proOrdCom" method="post" action="leg_orden.php">
 <center>
   <?php
     if($consulta == 1)
	   {
	     $ord_com = $orden->bus_ordCen($captura);
		 if($ord_com)
		  {
		    echo "<table id='ordCom'>";
			 while($row = mssql_fetch_array($ord_com))
			  {
			   echo "<tr>";
			     echo "<td>Orden compra numero: </td>";
				 echo "<td><input name='orden_compra' id='orden_compra' type='text' value='".$row['orden_compra']."' /></td>";
				 echo "<td><input name='cen_cos' id='cen_cos' type='text' value='".$row['nombre']."' /></td>";
				 echo "<td><input name='est' id='est' type='text' value='".$row['estado']."' /></td>";
				 echo "<td><input name='total' id='total' type='text' value='".$row['ord_com_val_total']."' /></td>";
				 echo "<td>Legalizar</td>";
			 	 echo "<td> <input type='radio' name='radio' id='radio' value='".$row['orden_compra']."' /></td>";
			   echo "</tr>";
			  } 
			echo "</table>";
		  }
		 else
	        echo "<script type=\"text/javascript\">alert(\"No se pudo traer las ordenes de compra del centro de costo, intentelo de nuevo!!\");</script>";  
	   }
	 elseif( $consulta == 2 ) 
	 {
		$ord_com = $orden->bus_ordPro($captura);
		 if($ord_com)
		  {
		    echo "<table id='ordCom'>";
			 while($row = mssql_fetch_array($ord_com))
			  {
			   echo "<tr>";
			     echo "<td>Numero: </td>";
				 echo "<td><input name='orden_compra' id='orden_compra' type='text' value='".$row['orden_compra']."' /></td>";
				 echo "<td><input name='cen_cos' id='cen_cos' type='text' value='".$row['nombre']."' /></td>";
				 echo "<td><input name='est' id='est' type='text' value='".$row['estado']."' /></td>";
				 echo "<td><input name='total' id='total' type='text' value='".$row['ord_com_val_total']."' /></td>";
				 	 echo "<td>Legalizar</td>";
			 echo "<td> <input type='radio' name='radio' id='radio' value='".$row['orden_compra']."' /></td>";
			
			   echo "</tr>";
			  } 
			echo "</table>";
		  }
		 else
	        echo "<script type=\"text/javascript\">alert(\"No se pudo traer las ordenes de compra del centro de costo, intentelo de nuevo!!\");</script>";    
	 } 
	 elseif( $consulta == 3 ) 
	 {
	  $i=1;
	 } 
   ?>
 </center>
 <center> 
   <table id="completar">
    <tr>
     <td colspan="2"><input type="submit" class="art-button" name="guardar" id="guardar" value="Enviar" /></td>
    </tr>
   </table>
   </center>
</form>
<?php
   }
?>