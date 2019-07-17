<script>
function valida()
{
	document.conOrdCom.submit();
}	
function valida_radio()
{
	var sel = 0;
	if(document.proOrdCom.legal.length)
	{
		for(i=0;i<=document.proOrdCom.legal.length;i++)
	 	{
	  		if(document.proOrdCom.legal[i].checked)
        	{
		  	sel = 1;
		  	location.href = 'formularios/leg_orden.php?legalizar='+document.proOrdCom.legal[i].value;
		  	return true; 
        	}
	 	}
	 }
	 else
	 {
		if(document.proOrdCom.legal.checked)
		{
		  	sel = 1;
		  	location.href = 'formularios/leg_orden.php?legalizar='+document.proOrdCom.legal.value;
		  	return true; 
        }
		else
	    {
			alert("Debe seleccionar una opcion");
			return false;
		}
	}
}	
</script>
<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 include('clases/orden_compra.class.php');
 $orden = new orden_compra();
 $prov = new nits();
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
<script language="javascript" src="librerias/ajax/select_opcOrd.js"></script>
<form id="conOrdCom" name="conOrdCom" method="post">
 <center>
    <table id="conOrd">
     <tr>
      <td>Consultar por: </td>
      <td><?php generaOpciones(); ?></td>
     </tr>
	 <tr>
	   <td>Numero: </td>
	   <td><select name="dato" size="1" disabled="disabled" id="select2" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">Selecciona opci&oacute;n...</option>
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
		/*echo "<script type=\"text/javascript\">alert(\"el dato capturado es ".$captura."!!\");</script>";  */
?>
<form id="proOrdCom" name="proOrdCom" method="post">
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
			     echo "<td>Numero: </td>";
			   echo "<td><input name='orden_compra' id='orden_compra' type='text' value='".$row['orden_compra']."' /></td>";
				 echo "<td><input name='cen_cos' id='cen_cos' type='text' value='".$row['nombre']."' /></td>";
				 echo "<td><input name='est' id='est' type='text' value='".$row['estado']."' /></td>";
				 echo "<td><input name='total' id='total' type='text' value='".$row['ord_com_val_total']."' /></td>";?>
    <td>Legalizar<input type="radio" name="legal" id="legal" value="<?php echo $row['orden_compra'] ?>"/></td>
                 <?php
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
				 echo "<td><input name='total' id='total' type='text' value='".$row['ord_com_val_total']."' /></td>";?>
     <td>Legalizar<input type="radio" name="legal" id="legal" value="<?php echo $row['orden_compra']; ?>"/></td>
<?php
			   echo "</tr>";
			  } 
			echo "</table>";
		  }
		 else
	        echo "<script type=\"text/javascript\">alert(\"No se pudo traer las ordenes de compra del centro de costo, intentelo de nuevo!!\");</script>";    
	 } 
	 elseif( $consulta == 3 ) 
	 {
		 $ord_com = $orden->bus_Ord($captura);
		 $row = mssql_fetch_array($ord_com);
		    echo "<table id='ordCom'>";
			 echo "<tr>";
			  echo "<td>Numero: </td>";
			  echo "<td><input name='orden_compra' id='orden_compra' type='text' value='".$row['ord_com_id']."' /></td>";
			  echo "<td><input name='cen_cos' id='cen_cos' type='text' value='".$row['cen_cos_id']."' /></td>";
			  echo "<td><input name='est' id='est' type='text' value='".$row['est_ord_com_id']."' /></td>";?>
	 <td>Legalizar<input type="radio" name="legal" id="legal" value="<?php echo $row['orden_compra']; ?>"/></td>
            </tr>
			</table>
<?php
			 
	 } 
   ?>
<table id="completar">
 <tr>
  <td colspan="2"><input type="button" class="art-button" name="guardar" id="guardar" value="Completar orden" onclick="valida_radio();" /></td>
 </tr>
</table>
 </center> 
</form>
<?php
   }
?>