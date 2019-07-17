<?php
session_start();
$_SESSION['regimen_empresa'];
$ano = $_SESSION['elaniocontable'];
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
@include_once('../clases/centro_de_costos.class.php');@include_once('clases/centro_de_costos.class.php');
@include_once('../clases/tipo_comprobante.class.php');@include_once('clases/tipo_comprobante.class.php');
@include_once('../clases/cuenta.class.php');@include_once('clases/cuenta.class.php');


$ins_tip_comprobante=new tipo_comprobantes();
$regimen = new regimenes();$ins_rec_caja = new rec_caja();
$ins_cuenta = new cuenta();$mes = new mes_contable();
$cent =new transacciones();$nits = new nits();
$centro = new centro_de_costos();$ins_concepto=new concepto();
$cuenta = new cuenta();

if($_SESSION['trans']==1)
{
  $notrae="2,4,7,9,11,13,15,17,18,19,20,21,23,30,32,33,34,35,36,37,38,40";
  $submit="../control/guardar_cauConcepto.php";
}
else
{
  $notrae="0";
  $submit="control/guardar_cauConcepto.php";
}

$con_tip_comprobante=$ins_tip_comprobante->ConTipComprobante($notrae);
$afecto = $regimen->afec_impuesto(1);
$meses = $mes->DatosMesesAniosContables($ano);
$trans =$centro->con_cen_por_usuario();
$ejecutar =$cent->obtener_concecutivo();
$con_concepto=$ins_concepto->verificar_existe(106);
$res_concepto=mssql_fetch_array($con_concepto);
$cue = mssql_fetch_array($ejecutar);
?>

<script language="javascript" src="librerias/js/datetimepicker.js"></script><script language="javascript" src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script><script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/validacion_num_letras.js"></script><script src="librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/separador.js"></script><script src="librerias/js/separador.js"></script>

<script language="javascript">
function nuevaGasto()
{
  var ruta="llamados/retMoneda.php";
  var cuantos = $("#cuentas > tbody > tr").length-1;
  var temp;
  var elhtml='<tr id="'+cuantos+'"><td><input type="text" name="cuenta'+cuantos+'" id="cuenta'+cuantos+'" list="cue'+cuantos+'" required="required" size="12" onkeypress="return permite(event,num)"><datalist id="cue'+cuantos+'">';
  <?php
   $cuen_cau=$cuenta->busqueda('no');
     while($dat_cuentas = mssql_fetch_array($cuen_cau)){ ?>
      elhtml+='<option value="<?php echo $dat_cuentas['cue_id']; ?>" label="<?php echo $dat_cuentas['cue_id']." ".$dat_cuentas['cue_nombre']; ?> ">'; <?php }?>
     elhtml+='</datalist></td><td><input type="text" name="desc'+cuantos+'" id="desc'+cuantos+'" required="required" /></td><td><input type="text" name="prove'+cuantos+'" id="prove'+cuantos+'" list="prov'+cuantos+'" onchange="ValIca('+cuantos+',this.value,<?php echo $afecto; ?>);" required="required" size="15"><datalist id="prov'+cuantos+'">';
<?php
  $tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
  $pro = $nits->ConProFondo($tipos);
  while($proveedor = mssql_fetch_array($pro)){ ?>
    elhtml+='<option value="<?php echo $proveedor['nit_id']; ?>" label="<?php echo $proveedor['nits_num_documento']." ".$proveedor['nits_nombres']." ".$proveedor['nits_apellidos']; ?>">';
<?php } ?>
    elhtml+='</datalist></td><td><input required type="text" name="debito'+cuantos+'" id="debito'+cuantos+'" value="0" onblur="sum_total();" size="10" onkeypress="mascara(this,cpf);"  onpaste="return false" /></td><td><input required type="text" name="credito'+cuantos+'" id="credito'+cuantos+'" value="0" onblur="sum_total();" required="required" size="10" onkeypress="mascara(this,cpf);"  onpaste="return false"/></td>';
    elhtml+='<td><input required type="text" name="bas_retencion'+cuantos+'" id="bas_retencion'+cuantos+'" value="0" onblur="sum_total();" required="required" size="10" onkeypress="mascara(this,cpf);"  onpaste="return false"/></td></tr>';
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
       alert("El proveedor ingresado no se encuentra creado en el sistema.");
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
  var debSin=0,creSin=0;
  for(i=0;i<=$("#cant_gasto").val();i++)
  {
    deb=$("#debito"+i).val();
    cre=$("#credito"+i).val();
    deb=deb.replace(/,/g , "");deb=deb.replace('.',',');
    cre=cre.replace(/,/g , "");cre=cre.replace('.',',');
    debSin=elvalor(deb.replace('$',''),1);
    creSin=elvalor(cre.replace('$',''),1);
    debSin=debSin.replace(',','.');
    creSin=creSin.replace(',','.');
    debito+=parseFloat(debSin);
    credito+=parseFloat(creSin);
  }
  $("#tot_deb").val(debito);
  $("#tot_cre").val(credito);
}


function balance()
{
	$("#transaccion").submit(function(){return false;});
	
	quitarPuntos();
	
  	mes=$("#mes_sele").val().split('-');
  	ano=$("#estAno").val();
    if(mes[0]==1)
    {
		alert("Mes de solo lectura.");
      	$("#transaccion").submit(function(){return false;});
    }
    else
    {
		if($("#tot_deb").val()==$("#tot_cre").val())
		{
			if($("#conce").val()!="" || $("#conce").val()!=0)
      			document.transaccion.submit();
      		else
      		{
      			alert('Seleccione el tipo de documento que va a registrar.');
      			$("#transaccion").submit(function(){return false;});
      		}
      	}
      	else
      	{
        	alert("El documento no esta balanceado, revise los valores.");
        	$("#transaccion").submit(function(){return false;});
      	}
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
        var a = confirm('La desea diferirla?');
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
<form id="transaccion" name="transaccion" action="<?php echo $submit; ?>" method="post" >
 <center>
  <table bordercolor="#000000" border="1">
   <tr>
     <td>Consecutivo</td>
     <td>
     	<select name="conce" id="conce"><option value="" required="required">--</option>
     <?php
        while($res_tip_comprobante=mssql_fetch_array($con_tip_comprobante))
		{
	 ?>
         <option required="required" value="<?php echo $res_tip_comprobante['tip_com_id']; ?>"><?php echo $res_tip_comprobante['tip_com_nombre']; ?></option>
     <?php
		}
     ?>
     </select>
     </td>
     <td>Fecha Causaci&oacute;n</td><td><input type="text" name="cau_fecha" id="cau_fecha" required="required"/> <a href="javascript:NewCal('cau_fecha','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Calendario" /></a></td>
<td>Fecha Creaci&oacute;n</td><td><input type="text" name="cau_fecCreacion" id="cau_fecCreacion" value="<?php echo date('d-m-Y'); ?>"/></td>
</tr>
   <tr>
     <td>C. Costo</td>
     <td>
       <input type="text" name="centro" id="centro" list="centro0" required="required">
          <datalist id="centro0">
          <?php
           while($row = mssql_fetch_array($trans))
              echo "<option value='".$row['cen_cos_id']."' label='".$row['cen_cos_nombre']."'>";
          ?>
          </datalist>
     </td>
     <td>Numero Documento</td><td><input type="text" name="num_doc" id="num_doc" required="required" pattern="[0-9]+" title="ingrese solo numeros" /></td>
     <td>Mes Contable</td><td>
          <select name="mes_sele" id="mes_sele">
          <?php
        while($dat_meses = mssql_fetch_array($meses))
          echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
      ?>  
      </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/> 
    </td>
   </tr>
   <tr><td>Descripci&oacute;n:</td><td colspan="6"><textarea required name="desc" id="desc" cols="100"></textarea></td></tr>
  </table><br />
  
  <table id="cuentas" border="1">
   <center>
    <tr><td>Cuenta</td><td>Descripci&oacute;n</td><td>Proveedor</td><td>D&eacute;bito</td><td>Cr&eacute;dito</td><td>Base de retencion</td></tr>
    <tr>
     <td>
  <input type="text" name="cuenta0" id="cuenta0" list="cue0" required="required" size="12"/>
      <datalist id="cue0">
      <?php
     $cuen_cau = $cuenta->busqueda('no');
       while($dat_cuentas = mssql_fetch_array($cuen_cau))
        echo "<option value='".$dat_cuentas['cue_id']."' label='".$dat_cuentas['cue_id']." ".$dat_cuentas['cue_nombre']."'>";
    ?>
      </datalist>
     </td>
     <td><input type="text" name="desc0" id="desc0" required="required" /></td>
     <td><input type="text" name="prove0" id="prove0'" list="prov0" required="required" size="15">
     <datalist id="prov0">
      <?php
    $tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
      $pro = $nits->ConProFondo($tipos);
    while($proveedor = mssql_fetch_array($pro))
        echo "<option value='".$proveedor['nit_id']."' label='".$proveedor['nits_num_documento']." ".$proveedor['nits_nombres']." ".$proveedor['nits_apellidos']."'>";
    ?>
     </datalist></td>
     <td><input required type="text" name="debito0" id="debito0" value="0" onblur="sum_total();" onkeypress="mascara(this,cpf);"  onpaste="return false"  /></td>
     <td><input required type="text" name="credito0" id="credito0" value="0" onblur="sum_total();" size="10" onkeypress="mascara(this,cpf);"  onpaste="return false"/></td>
     <td><input required type="text" name="bas_retencion0" id="bas_retencion0" value="0" onkeypress="mascara(this,cpf);"  onpaste="return false"  /></td>
     </tr>
    </center>
    </table>
    <table id="total" border="1">
     <tr><td colspan="2">Totales</td><td>Debito:<input type="text" name="tot_deb" id="tot_deb" readonly="readonly" /></td><td>Credito:<input type="text" name="tot_cre" id="tot_cre" readonly="readonly" /><input type="hidden" name="cant_gasto" id="cant_gasto" /><input type="hidden" name="can_diferido" id="can_diferido" /><input type="hidden" name="cuenta_gasto" id="cuenta_gasto" /></td></tr>
    </table>
  </table>
  <input type="button" class="art-button" name="boton" value="Nuevo Registro" onclick="nuevaGasto();" /><br /><br />
  <input type="submit" class="art-button" name="boton" onclick="balance();" id="gua" value="Guardar Causacion"/>
  </center>
</form>