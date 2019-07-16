<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
include_once('../clases/cuenta.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/mes_contable.class.php');


$ins_credito=new credito();
$nits = new nits();
$cuenta = new cuenta();
$mes = new mes_contable();
$cons_nit_credito = $ins_credito->con_aso_emp_credito();
$cons_cue_bancarias = $ins_credito->cuentas_bancarias();
$meses = $mes->DatosMesesAniosContables($ano);


$cre_unificar=$_POST['cre_id'];
$ced_tercero=$_POST['cedula'];
//echo "los datos: ".$cre_unificar."___".$ced_tercero."<br>";


$con_credito=$ins_credito->ConsultarCreditosConSaldoYContabilizados($cre_unificar,$ced_tercero);
$num_filas=mssql_num_rows($con_credito);
$dat_credito=mssql_fetch_array($con_credito);
//echo "informacion: ".$dat_credito['cre_id']."___".$dat_credito['nit_id']."<br>";
$res_sal_credito=$ins_credito->ConsultarSaldoCreditoRecaudo($cre_unificar);
?>

<link rel="stylesheet" href="../estilos/limpiador.css" media="screen" type="text/css" />
<script language="javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script language="javascript" src="librerias/js/validacion_num_letras.js"></script>
<script language="javascript" src="../librerias/js/datetimepicker.js"></script>
<script language="javascript" src="librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/separador.js"></script>
<script src="librerias/js/separador.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>

<script language="javascript">
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

function nuevaGasto()
{
	var cuantos = $("#cuentas > tbody > tr").length-1;
	var temp;
	var elhtml='<tr id="'+cuantos+'"><td><input type="text" name="cuenta'+cuantos+'" id="cuenta'+cuantos+'" list="cue'+cuantos+'" required="required" size="12" onkeypress="return permite(event,num)"><datalist id="cue'+cuantos+'">';
	<?php
	 $cuen_cau=$cuenta->busqueda('no');
     while($dat_cuentas = mssql_fetch_array($cuen_cau)){ ?>
		  elhtml+='<option value="<?php echo $dat_cuentas['cue_id']; ?>" label="<?php echo $dat_cuentas['cue_id']." ".$dat_cuentas['cue_nombre']; ?> ">'; <?php }?>
     elhtml+='</datalist></td><td><input type="text" name="desc'+cuantos+'" id="desc'+cuantos+'" required="required" /></td><td><input type="text" name="prove'+cuantos+'" id="prove'+cuantos+'" list="prov'+cuantos+'" required="required" size="15"><datalist id="prov'+cuantos+'">';
	<?php
	  $tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
	  $pro = $nits->ConProFondo($tipos);
	  while($proveedor = mssql_fetch_array($pro)){ ?>
	  	elhtml+='<option value="<?php echo $proveedor['nit_id']; ?>" label="<?php echo $proveedor['nits_num_documento']." ".$proveedor['nits_nombres']." ".$proveedor['nits_apellidos']; ?>">';
	<?php } ?>
    elhtml+='</datalist></td><td><select name="pagare'+cuantos+'" id="pagare'+cuantos+'" onChange="conceCredito(this.value,'+cuantos+');"><option value="0">Seleccione...</option>';
    <?php
		$cre_nits=$ins_credito->cre_salNits($dat_credito['nit_id'],$dat_credito['cre_id']);
    	while($row = mssql_fetch_array($cre_nits)) { ?>
    		elhtml+='<option value=<?php echo $row[cre_id]; ?>><?php echo $row[cre_id]; ?></option>"';
    <?php } ?>
    elhtml+='<td><input type="text" id="con_credito'+cuantos+'" name="con_credito'+cuantos+'" readonly/></td>';
    elhtml+='<td><input type="text" id="saldo_credito'+cuantos+'" name="saldo_credito'+cuantos+'" readonly/></td>';
    elhtml+='</select></td><td><input type="text" name="debito'+cuantos+'" id="debito'+cuantos+'" value="0" onblur="sum_total();" size="10" onkeypress="mascara(this,cpf);" onpaste="return false" /></td><td><input type="text" name="credito'+cuantos+'" id="credito'+cuantos+'" value="0" onblur="sum_total();" required="required" size="10" onkeypress="mascara(this,cpf);" onpaste="return false"/></td></tr>';
	$("#cuentas").append(elhtml);
	$("#cant_gasto").val(cuantos);
 }

function conceCredito(credito,id)
{
	$.ajax({
		type: "POST",
   		url: "llamados/trae_datCreditos.php",
	    data: "cre_id="+credito,
	    success: function(msg){
	    	var resultado=msg.split("#");
	     	$("#con_credito"+id).val(resultado[0]);
	     	$("#saldo_credito"+id).val(resultado[1]);
	     }
	})
}

function balance()
{
  quitarPuntos();
  mes=$("#mes_sele").val().split('-');
  ano=$("#estAno").val();
    if(mes[0]==1)
    {
      alert("Mes de solo lectura");
      $("#mov_credito").submit(function(){return false;});
    }
    else
    {
      if($("#tot_deb").val()==$("#tot_cre").val())
      document.mov_credito.submit();
      else
      {
        alert("El documento no esta balanceado, revise los valores");
        return false;
      }
    }
}

</script>

<?php
if($num_filas>0)
{
?>
<center>
	<table border="1">
		<tr><input type='hidden' name='cre_conta' id='cre_conta' value='<?php echo $cre_unificar; ?>'/>
		<th colspan='9'><?php echo "Credito a unificar: ".$cre_unificar;?>
        <input type="hidden" name="credito_contabilizar" id="credito_contabilizar" value="<?php echo $cre_unificar; ?>"
        </th>
        </tr>
        
        <tr>
		<th colspan='9'><?php echo "Saldo credito: ".number_format($res_sal_credito);?></th>
        </tr>
        
        
		<tr><td>Mes Contable</td><td>
          <select name="mes_sele" id="mes_sele">
          <?php
			  while($dat_meses = mssql_fetch_array($meses))
			    echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  ?>  
      </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/> 
	  </td>
	  <td>Fecha desembolso</td>
	  <td><input type="text" name="fec_des_credito" id="fec_des_credito" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" placeholder="DD-MM-AAAA" required/>
      <a href="javascript:NewCal('fec_des_credito','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
      </td>
      <td>Fecha contabilizaci&oacute;n</td>
	  <td><input type="text" name="fec_con_credito" id="fec_con_credito" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" placeholder="DD-MM-AAAA" required/>
      <a href="javascript:NewCal('fec_con_credito','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
      </td>
	  </tr>
		<input type='hidden' name='num_doc' id='num_doc' value='<?php echo $dat_credito['cre_id']; ?>'>
	</table>
 	<table id="cuentas" border="1">
    <tr><td>Cuenta</td><td>Descripcion</td><td>Proveedor</td><td>Pagare</td><td>Concepto</td><td>Saldo</td><td>Debito</td><td>Credito</td></tr>
    <tr><td><input type="text" name="cuenta0" id="cuenta0" list="cue0" required="required" size="12"/>
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
    <td><select name="pagare0" id="pagare0" onChange='conceCredito(this.value,0);'><option value="0">Seleccione...</option>
    	<?php
			$cre_nits=$ins_credito->cre_salNits($dat_credito['nit_id'],$dat_credito['cre_id']);
    		while($row = mssql_fetch_array($cre_nits))
    			echo "<option value=".$row['cre_id'].">".$row['cre_id']."</option>"; ?>
    </select></td>
    <td><input type="text" id="con_credito0" name="con_credito0" readonly/></td>
    <td><input type="text" id="saldo_credito0" name="saldo_credito0" readonly/></td>
    <td><input type="text" name="debito0" id="debito0" value="0" required="required" onblur="sum_total();" size="10" onkeypress="mascara(this,cpf);" onpaste="return false" /></td>
    <td><input type="text" name="credito0" id="credito0" value="0" onblur="sum_total();" size="10" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
    </tr>
    </table>
    <table id="total" border="1">
     <tr><td colspan="2">Totales</td><td><input type="text" name="tot_deb" id="tot_deb" readonly="readonly" /></td><td><input type="text" name="tot_cre" id="tot_cre" readonly="readonly" /><input type="hidden" name="cant_gasto" id="cant_gasto" /><input type="hidden" name="can_diferido" id="can_diferido" /><input type="hidden" name="cuenta_gasto" id="cuenta_gasto" /></td></tr>
    </table>
    <input type="button" class="art-button" name="boton" value="Nuevo Registro" onclick="nuevaGasto();" /><br /><br />
    <input type="submit" class="art-button" name="boton" onclick="balance();" id="gua" value="Guardar Causacion"/>
    </center>
 
<?php
}
else
{
	echo "<table><tr><th>No se encontraron datos para mostrar.</th></tr></table>";
}
?>