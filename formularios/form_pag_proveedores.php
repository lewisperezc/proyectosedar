<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

@include_once('clases/mes_contable.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/transacciones.class.php');
@include_once('../clases/transacciones.class.php');
include_once('../clases/cuenta.class.php');
$cuenta = new cuenta();
$mes = new mes_contable();
$transaccion = new transacciones();
$meses = $mes->DatosMesesAniosContables($ano);
$pagar = $transaccion->documen_pagar($_SESSION["k_nit_id"]);
?>

<link rel="stylesheet" type="text/css" href="../estilos/screen.css"/>
<script src="../librerias/js/separador.js"></script>
<script language="javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script language="javascript">
  function valida()
  {
	  var cantidad = $("#cantidad").val();
	  var mes = $("#mes_contable").val();
	  var ano = $("#estAno").val();
	  var banco = ""; var entra = 0;
	  for(i=0;i<cantidad;i++)
	  {
		 if($("#pagar"+i).attr('checked')==true)
		  {
			 entra = 1;
			 if($("#banco"+i).val()==0)
			 {
				 alert("Debe seleccionar un banco en el pago # "+(i+1));
				 return false;
			 }
		  }
	  }
	  if(mes!=""&&mes!=0)
	  {
	  	  cadena = mes.split("-");
	      if(cadena[0]==1)
			{
		    	alert("Mes de solo lectura.");
				return false;
			}
	      else
		      document.reg_pag_proveedores.submit();
	  }
	  else
	  	{
		   	alert("Debe seleccionar un mes Contable.");
			return false;
		} 
  }
 
  function orden_proveedor(sigla)
  {
  	$('#contenidoAjax').html('<div><img src="../imagenes/loading.gif"/></div>');
	var cuantos = $("#tabla > tbody > tr").length;
	eliminarCon();
	var cadena = '';
	var otro='';
	cuantos = 0;
	$.ajax({
		type: "POST",
		url: "../llamados/trae_doc_pago.php",
		data: "sigla="+sigla,	
		success: function(msg){
			var myObject = eval('(' + msg + ')');
			cuantos = myObject.length;
			cadena+='<table id="tabla"><tr><td>Doc Pago</td><td>Fecha Fac</td><td>Fecha Vencimiento</td><td>Num Factura</td><td>Transaccion</td><td>Cuenta por Pagar</td><td>Valor a Pagar</td><td>Banco</td><td>Num Cheque</td><td>Pagar</td></tr>';
			for (var x = 0 ; x < myObject.length ; x++) 
			{	
		    	cadena+='<tr id="tr'+x+'"><td><input type="text" name="sigla'+x+'" id="sigla'+x+'" size="8" value="'+myObject[x].sigla+'"/></td><td><input type="text" size="10" name="fec_fac'+x+'" id="fec_fac'+x+'" value="'+myObject[x].fec_docu+'"/></td><td><input type="text" name="fec_vencimiento'+x+'" id="fec_vencimiento'+x+'" size="10" value="'+myObject[x].fec_vencimiento+'"/></td><td><input type="text" name="num_fact'+x+'" id="num_fact'+x+'" size="8" value="'+myObject[x].num_factura+'"/></td><td><input type="text" name="causacion'+x+'" id="causacion'+x+'" size="8" value="'+myObject[x].causacion+'"/></td><td><input type="text" name="cuenta'+x+'" id="cuenta'+x+'" size="8" value="23803001"/></td><td><input type="text" name="val_pagar'+x+'" id="val_pagar'+x+'" value="'+myObject[x].valor+'"/><input type="hidden" name="tran'+x+'" id="tran'+x+'" value="'+myObject[x].tran+'"/><input type="hidden" name="nit'+x+'" id="nit'+x+'" value="'+myObject[x].nit+'"/><input type="hidden" name="cen_cos'+x+'" id="cen_cos'+x+'" value="'+myObject[x].centro+'"/> </td><td>';
				cadena+='<select name="banco'+x+'" id="banco'+x+'"><option value="0">Seleccione...</option>';
				<?php
				 $cuentas_banco = $cuenta->cuentas_bancarias();
				 while($row=mssql_fetch_array($cuentas_banco))
				 { ?>
					 cadena+='<option value="<?php echo $row[cue_id]; ?>"><?php echo substr($row[cue_nombre],0,20); ?></option>';
				 <?php } ?>
				cadena+='</select></td><td><input type="text" name="cheque'+x+'" id="cheque'+x+'" size="10" onchange="documento(this.value);" /></td><td><input type="checkbox" name="pagar'+x+'" id="pagar'+x+'" /> </td></tr>';
			}
		}
	});
    alert('Buscando...');
	$('#contenidoAjax').fadeIn(1000).html(cadena);
	$("#cantidad").val(cuantos);
 }
 
 function eliminarCon(){
     $("#tabla").remove();
     return true;
  }
 
 function documento(valor)
 {
 	if(($.trim(valor).length>0))
 		$("#doc_sigla").val(parseInt($("#doc_sigla").val())+1);
 	else
 		$("#doc_sigla").val(parseInt($("#doc_sigla").val())-1);
 }
</script>
<form name="reg_pag_proveedores" method="post" action="../control/pagar_proveedor.php">
<center>
  <table>
   <tr>
    <td>Mes Contable</td>
    <td><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
      <select name="mes_contable" id="mes_contable">
      	<option value="0">Seleccione..</option>
      <?php
	    while($row = mssql_fetch_array($meses))
		  echo "<option value='".$row['mes_estado']."-".$row['mes_id']."'>".$row['mes_nombre']."</option>";
	  ?>  
      </select>
    </td>
   </tr>
   <tr>
    <td>Proveedor por orden de desembolso</td>
    <td>
      <input type="text" size="50" name="prov" id="prov" onchange="orden_proveedor(this.value)" list="ord_desem" /><datalist id="ord_desem">
      <?php
	    while($row = mssql_fetch_array($pagar))
		  echo "<option value='".$row['nits_num_documento']."' label='".$row['nits_num_documento']."-".$row['nits_nombres']." ".$row['nits_apellidos']."'>";
	  ?> 
      </datalist>
    </td>
   </tr>
  </table>
   <div id="contenidoAjax"> 
   </div>
  </table><input type="hidden" name="cantidad" id="cantidad"  /><input type="hidden" name="doc_sigla" id="doc_sigla" value="0" /> 
  <table><tr><td><input type="button" class="art-button" name="boton" value="Pagar Proveedor" onclick="valida()" /></td></tr></table>
</center>
</form>