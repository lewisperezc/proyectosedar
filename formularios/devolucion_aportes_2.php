<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

$_SESSION['regimen_empresa'];
@include_once('../clases/transacciones.class.php');@include_once('clases/transacciones.class.php');
@include_once('../clases/nits.class.php');@include_once('clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');@include_once('clases/mes_contable.class.php');
@include_once('../clases/tipo_producto.class.php');@include_once('clases/tipo_producto.class.php');
@include_once('../clases/producto.class.php');@include_once('clases/producto.class.php');
@include_once('../clases/cuenta.class.php');@include_once('clases/cuenta.class.php');
@include_once('../clases/pabs.class.php');@include_once('clases/pabs.class.php');
@include_once('../clases/concepto.class.php');@include_once('clases/concepto.class.php');
@include_once('../clases/recibo_caja.class.php');@include_once('clases/recibo_caja.class.php');
@include_once('../clases/regimenes.class.php');@include_once('clases/regimenes.class.php');
@include_once('../clases/comprobante.class.php');@include_once('clases/comprobante.class.php');

$regimen = new regimenes();
$ins_rec_caja = new rec_caja();
$ins_cuenta = new cuenta();
$mes = new mes_contable();
$cent =new transacciones();
$nits = new nits();
$ins_concepto=new concepto();
$cuenta = new cuenta();
$comprobante= new comprobante();

$accion = "control/guardar_devolucion_aportes.php";
unset($_SESSION['trans']);

$afecto = $regimen->afec_impuesto(1);
$meses = $mes->DatosMesesAniosContables($ano);
$trans =$cent->con_cen_cos_ord_por_hospital();
$ejecutar =$cent->obtener_concecutivo();
$con_concepto=$ins_concepto->verificar_existe(106);
$res_concepto=mssql_fetch_array($con_concepto);
$cue = mssql_fetch_array($ejecutar);
$ejemplo = $cons_conse;//$cue['max_id'] + 1;//tre el ultimo numro insertado en los consecutivos y suma 1
$_SESSION['tra'] = $ejemplo;
?>
<script language="javascript" src="librerias/js/datetimepicker.js"></script><script language="javascript" src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script><script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/validacion_num_letras.js"></script><script src="librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/separador.js"></script><script src="librerias/js/separador.js"></script>
<script language="javascript">
function ValidaMesContable()
{
	
	mes=$("#mes_sele").val().split('-');
	if(mes[0]==1)
    {
   	  $("#transaccion").submit(function(){return false;});
      //$('#gua').attr('disabled', true);
      alert("Mes de solo lectura");
    }
    else
    {
    	document.transaccion.submit();
    }
	
}
function nuevaGasto()
{
	var cuantos = $("#cuentas > tbody > tr").length-1;
	var temp;
	var elhtml='<tr id="'+cuantos+'"><td><input type="text" name="cuenta'+cuantos+'" id="cuenta'+cuantos+'" list="cue'+cuantos+'" required="required" size="12" onkeypress="return permite(event,num)" onchange="diferido(this.value);"><datalist id="cue'+cuantos+'">';
	<?php
	 $cuen_cau=$cuenta->busqueda('no');
     while($dat_cuentas = mssql_fetch_array($cuen_cau)){ ?>
		  elhtml+='<option value="<?php echo $dat_cuentas['cue_id']; ?>" label="<?php echo $dat_cuentas['cue_id']." ".$dat_cuentas['cue_nombre']; ?> ">'; <?php }?>
     elhtml+='</datalist></td><td><input type="text" name="desc'+cuantos+'" id="desc'+cuantos+'" required="required" /></td><td><input type="text" name="prove'+cuantos+'" id="prove'+cuantos+'" list="prov'+cuantos+'" onchange="ValIca('+cuantos+',this.value,<?php echo $afecto; ?>);" required="required" size="15"><datalist id="prov'+cuantos+'">';
<?php
  $tipos="1,3,11,2,8";
  $pro = $nits->ConProFondo($tipos);
  while($proveedor = mssql_fetch_array($pro)){ ?>
  	elhtml+='<option value="<?php echo $proveedor['nit_id']; ?>" label="<?php echo $proveedor['nits_num_documento']." ".$proveedor['nits_nombres']." ".$proveedor['nits_apellidos']; ?>">';
<?php } ?>
    elhtml+='</datalist></td><td><select name="ica'+cuantos+'" id="ica'+cuantos+'" ><option value="0">Seleccione...</option></select></td><td><input type="text" name="debito'+cuantos+'" id="debito'+cuantos+'" value="0" onchange="sum_total();" size="10" /></td><td><input type="text" name="credito'+cuantos+'" id="credito'+cuantos+'" value="0" onchange="sum_total();" required="required" size="10"/></td></tr>';
	$("#cuentas").append(elhtml);
	$("#cant_gasto").val(cuantos);
 }
</script>
<script language="javascript" type="text/javascript">
function ValIca(pos,prove,tipo,tipo1)
{
   
   $.ajax({
   type: "GET",
   url: "llamados/valida_nit.php",
   data: "nit_id="+prove,
   success: function(msg)
   {
   	  if(msg==0)
	  {
	  	 alert("El proveedor ingresado no se encuentra creado en el sistema!!!");
		 document.transaccion.gua.disabled=true;
	  }
	  else
	  {
		document.transaccion.gua.disabled=false;
   		$.ajax({
   		type: "POST",
   		url: "llamados/ica.php",
   		data: "prove="+prove+"&tipo="+tipo+"&tipo1="+tipo1,
   		success: function(msg){$("#ica"+pos).html(msg);}
   	    });
        }
      }
   });
}
function sum_total()
{	
	var debito=0,credito=0;
	for(i=0;i<=$("#cant_gasto").val();i++)
	{
		debito+=parseFloat($("#debito"+i).val());
		credito+=parseFloat($("#credito"+i).val());
	}
	$("#tot_deb").val(debito);
	$("#tot_cre").val(credito);
}

function balance()
{
	if($("#tot_deb").val()==$("#tot_cre").val(credito))
	  document.transaccion.submit();
	else
	{
	  alert("El documento no esta balanceado, revise los valores");
	  return false;
	}
}
function diferido(valor)
{
	$.ajax({
    type: "GET",
    url: "llamados/valida_cuenta.php",
    data: "cue_id="+valor,
    success: function(msg)
    {
		if(msg==0)
	  	{
			alert("la cuenta ingresada no se encuentra creada en el sistema!!!");
			document.transaccion.gua.disabled=true;
		}
		else
		{
			document.transaccion.gua.disabled=false;
			if(valor.substring(0,2)==17)
			{
				var a = confirm('La desea diferir?');
				if(a)
				{
		  			var fecha=prompt('Cantidad en meses del diferido','Cantidad');
		  			var cuenta=prompt('Cuenta del gasto','');
		  			$("#can_diferido").val(fecha);
		  			$("#cuenta_gasto").val(cuenta);
	 			}
			}
		}
	}
	});
}

</script>
<form id="transaccion" name="transaccion" onsubmit="return valida_blancos()" action="<?php echo $accion; ?>" method="post" >
 <center>
  <table bordercolor="#000000" border="1">
   <tr>
    <td>Mes Contable</td><td>
          <select name="mes_sele" id="mes_sele" onchange="consecutivo(this.value,42,'conce','llamados/inic_mes.php');">
          <?php
        while($dat_meses = mssql_fetch_array($meses))
          echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
      ?>  
      </select>    
    </td>
     <input type="hidden" name="acutaliza" id="actualiza" value="<?php echo $acutaliza; ?>" /></td>
     <td>Fecha Causacion</td><td><input type="text" name="cau_fecha" id="cau_fecha" required="required"/> <a href="javascript:NewCal('cau_fecha','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Calendario" /></a></td>
<td>Fecha Cracion</td><td><input type="text" name="cau_fecCreacion" id="cau_fecCreacion" value="<?php echo date('d-m-Y'); ?>"/></td>
</tr>
   <tr>
     <td>Centro de Costo</td>
     <td>
       <input type="text" name="centro" id="centro" list="centro0" required="required">
          <datalist id="centro0">
          <?php
           while($row = mssql_fetch_array($trans))
              echo "<option value='".$row['cen_cos_id']."' label='".$row['cen_cos_nombre']."'>";
          ?>
          </datalist>
     </td>
     <td>Numero Documento</td><td><input type="text" name="num_doc" id="num_doc" value="" required /></td>
   </tr>
   <tr><td>Descripcion</td><td colspan="5"><textarea cols="100" name="desc" id="desc" required></textarea></td></tr>
  </table><br />
  
  <table id="cuentas" border="1">
   <center>
    <tr><td>Cuenta</td><td>Descripcion</td><td>Proveedor</td><td>ICA</td><td>Debito</td><td>Credito</td></tr>
    <tr>
     <td>
  <input type="text" name="cuenta0" id="cuenta0" list="cue0" required="required" size="12" onchange="diferido(this.value);" onkeypress="return permite(event,'num')"/>
      <datalist id="cue0">
      <?php
	   $cuen_cau = $cuenta->busqueda('no');
       while($dat_cuentas = mssql_fetch_array($cuen_cau))
	   	  echo "<option value='".$dat_cuentas['cue_id']."' label='".$dat_cuentas['cue_id']." ".$dat_cuentas['cue_nombre']."'>";
	  ?>
      </datalist>
     </td>
     <td><input type="text" name="desc0" id="desc0" required="required" /></td>
     <td><input type="text" name="prove0" id="prove0'" list="prov0" onchange="ValIca(0,this.value,<?php echo $afecto; ?>);" required="required" size="15">
     <datalist id="prov0">
	  <?php
	  $tipos="1,3,11,2,8";
	  $pro = $nits->ConProFondo($tipos);
  		while($proveedor = mssql_fetch_array($pro))
  		  echo "<option value='".$proveedor['nit_id']."' label='".$proveedor['nits_num_documento']." ".$proveedor['nits_nombres']." ".$proveedor['nits_apellidos']."'>";
	  ?>
     </datalist></td>
     <td><select name="ica0" id="ica0" ><option value="0">Seleccione...</option></select></td>
     <td><input type="text" name="debito0" id="debito0" value="0" required="required" onchange="sum_total();" size="10" /></td>
     <td><input type="text" name="credito0" id="credito0" value="0" onchange="sum_total();" size="10" required /></td>
     </tr>
    </center>
    </table>
    <table id="total" border="1">
     <tr><td colspan="2">Totales</td><td><input type="text" name="tot_deb" id="tot_deb" readonly="readonly" /></td><td><input type="text" name="tot_cre" id="tot_cre" readonly="readonly" /><input type="hidden" name="cant_gasto" id="cant_gasto" /><input type="hidden" name="can_diferido" id="can_diferido" /><input type="hidden" name="cuenta_gasto" id="cuenta_gasto" /></td></tr>
    </table>
  </table>
  <input type="button" class="art-button" name="boton" value="Nuevo Registro" onclick="nuevaGasto();" /><br /><br />
  <input type="submit" onclick="ValidaMesContable();" class="art-button" name="gua" id="gua" value="Guardar Causacion" />
  </center>
</form>