<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/transacciones.class.php');//para funcionamiento local
$cent =new transacciones();
$trans =$cent->buscar_centro_costos();
$ejecutar =$cent->obtener_concecutivo();
$cue = mssql_fetch_array($ejecutar);
$ejemplo = $cue['max_id'] + 1;//tre el ultimo numro insertado en los consecutivos y suma 1
$doctrans=$cent->doc_transa();
?>
<script>
function valida_blancos()
{
if(document.transaccion.sigla.selectedIndex==0)
 {
 alert('seleccione el tipo de documento para latransaccion ');
 transaccion.sigla.focus();
return false;
	}else {
	  		if(transaccion.nit.value=="")
			 {
			 alert("digite  el nit del tercero");
			 transaccion.nit.focus();		
			 return false;
			}
			else{
				if(transaccion.fecha.value=="")
					{
					alert("digite la fecha de la transaccion");
					transaccion.fecha.focus();		
					return false;
					}
					else{
						if(document.transaccion.centro_cost.selectedIndex==0)
							{
							alert('seleccione el centro de costos');
							transaccion.centro_cost.focus();
							return false;
							}
							if(document.transaccion.val_totaal.value==0)
								{
								alert('digite el valor de la factura ');
								transaccion.val_totaal.focus();
								return false;
								}						
							if(document.transaccion.iva.value==0)
								{
								alert('digite el iva de la factura ');
								transaccion.iva.focus();
								return false;
								}
						}
				}
		}
}
</script>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />
<script language="javascript" type="text/javascript" src="librerias/ajax/select_cuenta.js"></script>
action="../control/guardar_transaccion.php"
<form id="transaccion" name="transaccion"   action="" method="post" >
<center>
	<table width="859" border="1">
		<tr>
        	<th colspan="4" align="center" >DETALLE POR TRANSACCIONES</th>
  		</tr>
     <tr>
       <th>det_tra_trans_id</th> 
       <th>det_tra_fech</th>
       <th>det_tra_prod</th>
       <th>det_tra_cant</th>
     </tr>
  <tr>  
     <td><input type="text" size="" name="trans_id"  value=""  /></td>
       <td>*dd/mm/aa<input type="text" size="" name="fech_det_trans" onkeypress="return permite(event, 'num')" value=""  /></td>
    <td>*<input type="text" size="" name="det_prod" onkeypress="return permite(event, 'num')" value=""  /></td>
    <td>*<input type="text" size="" name="det_cant"  value=""  /></td>
     <tr>
         <th>det_tra_val_uni </th>
         <th>det_tra_iva_prod</th>
         <th>det_tracen_cos </th>
     </tr>
         <td>*$
             <input type="text" name="vdet_val_pro" onkeypress="return permite(event, 'num')" value="" /></td>
      
   <td>*
     <input type="text"  size="7"name="iva" onkeypress="return permite(event, 'num')" value="" />%</td>
    <td>*
      <input type="text" name="det_cen_cos" onkeypress="return permite(event, 'num')" value="" /></td>
    
     <tr>
  <tr><td colspan="2"><input type="button" class="art-button" onclick="valida_blancos();" value="guardar" name="guardar" /></td></tr>
</table>
</center>
</form>