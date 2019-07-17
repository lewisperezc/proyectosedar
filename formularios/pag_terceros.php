<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once("clases/nits.class.php");
include_once('clases/mes_contable.class.php');
$nits=new nits();
$mes = new mes_contable();
$tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
$pro = $nits->ConProFondo($tipos);
$meses = $mes->DatosMesesAniosContables($ano);
?>
<script type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>

<script type="text/javascript">
function fac_saldo(prove)
 {
	$('#contenidoAjax').html('<div><img src="imagenes/loading.gif"/></div>');
	var cuantos = $("#facturas > tbody > tr").length;
	var html = '';
	var otro='';
	var mes=$("#mes_sele").val().split('-');
	
	cuantos = 0;
	$.ajax({
		type: "POST",
		url: "./llamados/trae_fac_saldo.php",
		data: "prove="+prove+"&mes="+mes[1],
		success: function(msg){
			//alert(msg);
			var myObject = eval('(' + msg + ')');
			cuantos = myObject.length;
			html+=' <table align="center" id="facturas"><tr><th>Consecutivo</th><th>Factura</th><th>Fecha</th><th>Mes afectado</th><th>Fecha Vencimientos</th><th>Nombre</th><th>Valor total</th><th>Pago Parcial</th><th>Pago Total</th></tr>';
			for (var x = 0 ; x < myObject.length ; x++) 
			{	
		    	html+='<tr id="tr'+x+'"><td><input type="hidden" name="tran_id'+x+'" id="tran_id'+x+'" value="'+myObject[x].tran+'" /><input type="hidden" name="cen_cos'+x+'" id="cen_cos'+x+'" value="'+myObject[x].centro+'" /><input type="text" size="12" name="sigla'+x+'" id="sigla'+x+'" value="'+myObject[x].sigla+'" /></td><td><input type="text" size="6" name="num_factura'+x+'" id="num_factura'+x+'" value="'+myObject[x].num_factura+'" /></td><td>'+myObject[x].fec_docu+'</td><td><input type="hidden" name="elmes'+x+'" id="elmes'+x+'" value='+myObject[x].mes_nombre+'>'+myObject[x].mes_nombre+'</td><td>'+myObject[x].fec_vencimiento+'</td><td>'+myObject[x].nombre+'</td><td><input type="text" size="10" name="val_fac'+x+'" id="val_fac'+x+'" value="'+myObject[x].val_factura+'"/></td><td><input type="text" size="10" name="parcial'+x+'" id="parcial'+x+'"/></td><td><input type="checkbox" name="total'+x+'" id="total'+x+'"/><input type="hidden" size="7" name="cue_pagar'+x+'" id="cue_pagar'+x+'" value="'+myObject[x].cuenta+'"/></td></tr>';
			}
			$('#contenidoAjax').fadeIn(1000).html(html);
			$("#cantidad").val(cuantos);
		}
	});
 }
 
 function eliminarCon(oId){
     $("#elim"+oId).remove();
     return true;
  }
  
  function valida()
  {
	  var cuantos=$("#cantidad").val();
	  if($("#observacion").val()=="")
	  {
	  	alert('El campo observacion es obligatorio.');
		return false;
	  }
	  
	  valido=false;
	  for(j=0;j<cuantos;j++)
	  {
	  	if($("#total"+j).attr('checked')==true)
		{
			valido=true;
			break;
		}
	  }
	  if(!valido)
	  {
	  	alert("Seleccione por lo menos un item.");
		return false;
	  }
	  
	  for(var i=0;i<cuantos;i++)
	  {
		  if($("#parcial"+i).val()!="")
		  {
			if($("#parcial"+i).val()>$("#val_fac"+i).val())
			  {
				  alert("Verifique su abono, es mayor al total de la deuda.");
				  return false;
			  }
		  } 
	  }
	  document.aut_terceros.submit();
  }

</script>

<form name="aut_terceros" method="post" action="control/guardar_pago_proveedor.php">
 <center>
   <table>
   	<tr>
     <td>Mes Contable: </td>
     <td><select name="mes_sele" id="mes_sele">
     	 <option values=''>Seleccione...</option>
         <?php
         while($dat_meses=mssql_fetch_array($meses))
         { echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>"; }
         ?>  
         </select>    
     </td>
     <td>Proveedor</td>
     <td><input type="text" name="prove" id="prove" onchange="fac_saldo(this.value)" list="pro"/>
      <datalist id="pro">
        <?php
		 while($row = mssql_fetch_array($pro))
          echo "<option value='".$row['nits_num_documento']."' label='".$row['nits_num_documento']."-".$row['nits_nombres']." ".$row['nits_apellidos']."'>";
        ?>
      </datalist>
     </td>
   </tr></table>
   <div id="contenidoAjax">
   </div>
   </table> 
   <br />
   <table><tr><td>
   Observacion </td><td><textarea name="observacion" id="observacion"></textarea></td></tr></table>
   <br />
   <input type="hidden" name="cantidad" id="cantidad"/>
   <table><tr><td><input type="button" class="art-button" name="boton" value="Orden de Pago" onclick="valida();" /></td></tr></table>
 </center>
</form>