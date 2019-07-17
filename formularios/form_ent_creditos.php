<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/credito.class.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/cuenta.class.php');

$inst_credito = new credito();
$mes = new mes_contable();
$ins_cuenta=new cuenta();

$meses = $mes->DatosMesesAniosContables($ano);

?>
<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script src="../librerias/js/separador.js"></script>
<script>
	  function traeCreditos(nit,pos)
    {
      $.ajax({
      type: "POST",
      url: "../formularios/archivo.php?opc=1",
      data: "id="+nit,
      success: function(msg){
        $("#fabs"+pos).removeAttr('disabled');
        $("#ent_credito"+pos).html(msg); 
      }
      });
    }		

function validarMes()
{
	
	$("#reg_ent_credito").submit(function(){return false;});
	var cadena = document.reg_ent_credito.mes_sele.value;
  	var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
    {
    	alert("No se puede ingresar mas datos en este mes.");
    	$("#reg_ent_credito").submit(function(){return false;});	
    }
	else
	{  
		if(document.reg_ent_credito.value==0)
	  	{
			alert("Debe seleccionar una cuenta bancaria.");
		  	$("#reg_ent_credito").submit(function(){return false;});	
	  	}
	  	else
			document.reg_ent_credito.submit();
	}
}

 function bus_fabs(tercero,mes,pos){
 	var dat_mes=mes.split("-");
 	if($("#fabs"+pos).attr("checked")==false)
 	{
 		$("#cre_fabs"+pos).css('display','none');
		$("#credito"+pos).css('display','block');
 	}
 	else
 	{
 		$.ajax({
			type: "POST",
			url: "../llamados/fabs_tercero.php",
			data: "tercero="+tercero+"&mes="+dat_mes[1],
			success: function(msg){
				$("#cre_fabs"+pos).css('display','block');
				$("#credito"+pos).css('display','none');
				$("#ent_cuentaFabs"+pos).html(msg);
			}
		});
 	}
 }

function sal_credito(val,fecha,pos)
{
	$("#diaInteres"+pos).val(0);
	if(val!='')
	{
		$.ajax({
				type: "POST",
				url: "../llamados/sal_credito.php",
				data: "credito="+val,
				success: function(msg){
					var res = msg.split("-");
					$("#saldo"+pos).val(formatNumber.new(res[0]));
					$("#ent_val_int"+pos).val(calcular_interes(pos,val,$('#saldo'+pos).val(),fecha));
					if($("#ent_fec_pago"+pos).val()!='')
					{
						$("#diaInteres"+pos).val(res[1]);	
					}
				}
		});
		/*
	   	$.ajax({
	      type: "POST",
	      url: "../formularios/archivo.php?opc=2",
	      data: "id="+val,
	      success: function(msg){
	        $("#cen_costo"+pos).html(msg);
	      }
	    });
	    */
   }
}

function calcular_interes(pos,credito,capital,fecha)
{
	$.ajax({
    type: "POST",
    url: "../llamados/cal_interes.php",
    data: "pos="+pos+"&credito="+credito+"&capital="+capital+"&fecha="+fecha,
    success: function(msg)
    {
    	if(msg=='')
    	{
        	$("#ent_val_int"+pos).val(0);
        	$("#cuota"+pos).val($("#capital"+pos).val());
      	}
      	else
      	{
        	var res = msg.split("-");
        	$("#ent_val_int"+pos).val(res[1]);
        
        	if($("#ent_fec_pago"+pos).val()!='')
			{
				$("#diaInteres"+pos).val(res[0]);
			}
        	$("#cuota"+pos).val(parseInt($("#capital"+pos).val())+parseInt(msg));
      	}
    }
    });
}

function agrCredito()
{
	<?php
   	$cons_nit_credito = $inst_credito->con_aso_emp_credito();
   	$cons_cue_bancarias = $inst_credito->cuentas_bancarias();
   
   	$con_cue_balance=$ins_cuenta->busqueda_T('no');
  	?>
  	var cuantos = $("#tab_credito > tbody > tr").length-1;
  	//alert(cuantos);
  	var elhtml= "<tr><td><input required size='30' name='ent_nit"+cuantos+"' id='ent_nit"+cuantos+"' list='tercero"+cuantos+"' onChange='traeCreditos(this.value,"+cuantos+")'><datalist id='tercero"+cuantos+"'>";
  	<?php while($nits = mssql_fetch_array($cons_nit_credito))
  	{
  	?> 
    	elhtml+="<option value='<?php echo $nits['nit_id']; ?>' label='<?php echo $nits['nits_num_documento']." ".$nits['nit_nombres']; ?>'></option>";
    <?php
    }
    ?>
    elhtml+="</datalist></td><td><select name='ent_credito"+cuantos+"' id='ent_credito"+cuantos+"' onchange='sal_credito(this.value,0,"+cuantos+");'></select></td>";
    elhtml+="<td><input name='saldo"+cuantos+"' id='saldo"+cuantos+"' size='10'><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/></td>";
    /*elhtml+="<td><input type='checkbox' disabled name='fabs"+cuantos+"' id='fabs"+cuantos+"' onchange='bus_fabs(ent_nit"+cuantos+".value,mes_sele.value,"+cuantos+")'></td>";*/
    elhtml+="<td><input type='text' name='ent_fec_pago"+cuantos+"' id='ent_fec_pago"+cuantos+"' onblur='sal_credito(ent_credito"+cuantos+".value,this.value,"+cuantos+");' onchange='sal_credito(ent_credito"+cuantos+".value,this.value,"+cuantos+");' onClick='sal_credito(ent_credito"+cuantos+".value,this.value,"+cuantos+");'/>";
    elhtml+="<a href='Javascript:NewCal('ent_fec_pago"+cuantos+"','ddmmyyyy')'><img src='../imagenes/cal.gif' width='16' height='16' border='0' alt='Pick a date'></a></td>";
    elhtml+="<td><input type='text' name='ent_val_pago"+cuantos+"' id='ent_val_pago"+cuantos+"' onkeypress='return permite(event,'num')' size='10'/></td>";
    elhtml+="<td><input readonly type='text' name='diaInteres"+cuantos+"' id='diaInteres"+cuantos+"' size='4'></td>";
    elhtml+="<td><input type='text' name='ent_val_int"+cuantos+"' id='ent_val_int"+cuantos+"' onkeypress='return permite(event,'num')' size='10'/></td>";
	/*elhtml+="<td><select name='cen_costo"+cuantos+"' id='cen_costo"+cuantos+"'></select></td>";*/
    elhtml+="<td><textarea type='text' name='ent_nota"+cuantos+"' id='ent_nota"+cuantos+"'></textarea></td>";
    elhtml+="<td id='credito"+cuantos+"'>";
	elhtml+="<input style='width:300px;' type='text' name='ent_cuenta"+cuantos+"' id='ent_cuenta"+cuantos+"' list='cuent"+cuantos+"'>";
    elhtml+="<datalist id='cuent"+cuantos+"'>";
    <?php while($cuentas = mssql_fetch_array($con_cue_balance)){ ?>
    elhtml+="<option value='<?php echo $cuentas['cue_id']; ?>' label='<?php echo $cuentas['cue_id']." - ".$cuentas['cue_nombre']; ?>'>";
    <?php } ?>
    elhtml+="</datalist>";
    elhtml+="</td><td id='cre_fabs"+cuantos+"' style='display:none'><select name='ent_cuentaFabs"+cuantos+"' id='ent_cuentaFabs"+cuantos+"'></select></td></tr>";
  
  	$("#tab_credito").append(elhtml);
  	$("#cuantos").val(cuantos+1);
}
</script>

<?php
$cons_nit_credito = $inst_credito->con_aso_emp_credito();
$cons_cue_bancarias = $inst_credito->cuentas_bancarias();
$con_cue_balance=$ins_cuenta->busqueda_T('no');
?>

<form name="reg_ent_credito" id="reg_ent_credito" method="post" action="../control/guardar_entrada_credito.php">
	<table border="1">
    	<tr>
        	<td>Mes Contable:
            <select name="mes_sele" id="mes_sele">
        		<?php
					while($dat_meses = mssql_fetch_array($meses))
				    	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  		?>
			</select>
            <input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
            </td>
		</tr>
    </table>
    <table border="1" id='tab_credito' name='tab_credito'>
    	<tr>
			<td><b>Nombre</b></td>
      		<td><b>Pagare Num.</b></td>
      		<td><b>Saldo</b></td>
      		<!--<td><b>Descuento otro concepto</b></td>-->
      		<td><b>Fecha Pago</b></td>
      		<td><b>Valor a Capital</b></td>
      		<td><b>Num dias interes</b></td>
      		<td><b>Valor Intereses</b></td>
            <!--<td><b>Centro de Costo</b></td>-->
      		<td><b>Nota</b></td>
            <td><b>Cuenta contable</b></td>
        </tr>
		<tr>
			<td><input size="30" name="ent_nit0" id="ent_nit0" onChange='traeCreditos(this.value,0)' list='tercero0' required="required">
        		<datalist id='tercero0'>
        		<?php while($nits = mssql_fetch_array($cons_nit_credito)){
                		echo "<option value='".$nits['nit_id']."' label='".$nits['nits_num_documento']." ".$nits['nit_nombres']."'>";
				}
				?>
        		</datalist>
            </td>
            
            <td><select value="" onchange="sal_credito(this.value,'',0);" name="ent_credito0" id="ent_credito0" required="required"></select></td>
            <td><input name="saldo0" id="saldo0" value='' required="required" size='10'><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/></td>
            <!--<td><input type='checkbox' disabled name='fabs0' id='fabs0' onchange='bus_fabs(ent_nit0.value,mes_sele.value,0)'></td>-->
            <td><input type="text" name="ent_fec_pago0" id="ent_fec_pago0" required="required" onselect="sal_credito(ent_credito0.value,this.value,0);" onblur="sal_credito(ent_credito0.value,this.value,0);" onchange="sal_credito(ent_credito0.value,this.value,0);" onClick='sal_credito(ent_credito0.value,this.value,0);' />
            <a href="javascript:NewCal('ent_fec_pago0','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
            <td><input type="text" name="ent_val_pago0" id="ent_val_pago0" required="required" onkeypress="return permite(event,'num')" size='10' onselect="sal_credito(ent_credito0.value,ent_fec_pago0.value,0);" onblur="sal_credito(ent_credito0.value,ent_fec_pago0.value,0);" onchange="sal_credito(ent_credito0.value,ent_fec_pago0.value,0);" onClick='sal_credito(ent_credito0.value,ent_fec_pago0.value,0);'/></td>
            <td><input type='text' readonly name='diaInteres0' id='diaInteres0' required="required" size='4'></td>
            <td><input type="text" name="ent_val_int0" id="ent_val_int0" required="required" onkeypress="return permite(event,'num')" size='10'/></td>
            <!--<td><select name="cen_costo0" value="" required="required" id="cen_costo0" required="required"></select></td>-->
            <td><textarea type="text" name="ent_nota0" required="required"></textarea></td>
            <td id='ent_credito0'>
            <input style="width:300px;" type="text" required="required" name="ent_cuenta0" id="ent_cuenta0" list="cuent0">
            <datalist id="cuent0">
            <?php while($cuentas = mssql_fetch_array($con_cue_balance)){ ?>
            <option value="<?php echo $cuentas['cue_id']; ?>" label="<?php echo $cuentas['cue_id']." - ".$cuentas['cue_nombre']; ?>">
            <?php } ?>
            </datalist></td>
            <td id='cre_fabs0' style='display:none'>
              <select name="ent_cuentaFabs0" id="ent_cuentaFabs0"></select>
            </td>
            <input type='hidden' name='cuantos' id='cuantos' value=1>
        </tr>
      </table>
	<table>
      	<tr>
        	<td colspan="6" align="center"><input type="button" class="art-button" name="enviar" value="Nuevo Registro" onClick="agrCredito();"/></td>
          	<td colspan="5" align="center"><input type="submit" class="art-button" name="enviar" value="Guardar" onClick="validarMes();"/></td>
        </tr>
    </table>
</form>