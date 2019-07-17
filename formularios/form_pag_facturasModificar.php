<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 include_once('../clases/factura.class.php');
 include_once('../clases/recibo_caja.class.php');
 include_once('../clases/concepto.class.php');
 include_once('../clases/moviminetos_contables.class.php');
 include_once('../clases/transacciones.class.php');
 include_once('../clases/mes_contable.class.php');
 include_once('../clases/contrato.class.php');
 $mes = new mes_contable();
 $meses = $mes->DatosMesesAniosContables($ano);
 $ins_factura = new factura();
 $contrato = new contrato();
 $facturas = $ins_factura->cons_fac_no_pagadas();
 //Obtengo El Consecutivo Del recibo De Caja
  $ins_rec_caja = new rec_caja();
  $cons_conse = $ins_rec_caja->obt_consecutivo(15);
 //Obtengo El id y nombre Del Concepto
 $ins_concepto = new concepto();
 $no_trae="0";
 $con_recibo = $ins_concepto->conceptos(127,$no_trae);
 //Obtengo El Saldo A La Fecha De La Factura
 $ins_transaccion = new transacciones();
 $ins_mov_contable = new movimientos_contables();
 //$act_rec_caja = $ins_rec_caja->act_consecutivo(15);
?>
<script src="../librerias/js/datetimepicker.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
 $(function() 
  {
   $("#calcular").click(function() {
   var add = 0;
   $(".sumar").each(function() {
   add += Number($(this).val());
   });
   $("#val_net_rec_caja").html(add);
  });
 });
</script> 

<script>
	function seleccion()
	{
		if(document.for_rec_cliente.sel_factura.selectedIndex == 0)
			alert('Seleccione Una Factura');
		else
			document.for_rec_cliente.submit();
	}
	
	function distGlosa(hospital)
	{
		
	}
</script>
<script>

function Sumar()
{
	interval = setInterval("calcular()",1);
}
function calcular()
{
	uno = document.rec_caja.glo_ace_rec_caja.value;
	dos = document.rec_caja.adm_rec_caja.value; 
	tres = document.rec_caja.glo_pen_ace_rec_caja.value;
	cuatro = document.rec_caja.des_rec_caja.value;
	cinco = document.rec_caja.imp_tim_rec_caja.value;
	seis = document.rec_caja.ret_en_la_fue_rec_caja.value;
	siete = document.rec_caja.pro_hos_rec_caja.value;
	ocho = document.rec_caja.ica_rec_caja.value;
	nueve = document.rec_caja.pro_des_rec_caja.value;
	diez = document.rec_caja.otr_des_rec_caja.value;
	document.rec_caja.val_net_rec_caja.value = (uno * 1) + (dos * 1) + (tres * 1) + (cuatro * 1) + (cinco * 1) + (seis * 1) + (siete * 1) + (ocho * 1) + (nueve * 1) + (diez * 1);
}
function NoSumar()
{
clearInterval(interval);
}
/*FIN SUMAR*/
</script>
<script>
function validar_vacios()
  {
	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	var CamposVacios = "";
	var Mensaje = "Modifique los siguientes campos: \n\n";
	var valor = parseInt(document.rec_caja.val_abo_rec_caja.value);
	if(document.rec_caja.fec_rec_caja.value=="")
	CamposVacios += "* Fecha \n";
	if(document.rec_caja.not_rec_caja.value=="")
	CamposVacios += "* Nota \n";
	
	if(document.rec_caja.val_abo_rec_caja.value == "" && document.rec_caja.abono.checked)
	{
		alert("Debe colocar algun valor en el abono");
		return true;
	}
	else
	{
		if(document.rec_caja.val_abo_rec_caja.value != "" && document.rec_caja.abono.checked == false)
		{
			alert("Debido a que hay valores en el campo de abono, debe seleccionar el check que dice que es un abono");
		}
	}
	  var cadena = $("#mes_sele").val();
    var ano = $("#estAno").val();
      cadena = cadena.split("-");
      if(cadena[0]==1)
	    CamposVacios += "* No se puede ingresar mas datos en este mes\n";
	  //VALIDAMOS EL CAMPO NOMBRE
	  if(document.rec_caja.abono.checked)
	   {
		var saldo = parseInt(document.rec_caja.sal_abo.value);
		var total = parseInt(document.rec_caja.val_abo_rec_caja.value);
		if(total > saldo)
		   CamposVacios +="El Valor Del Abono No Puede Ser Mayor Al Saldo";
	  }
	  else
	  {	
	   if(document.rec_caja.val_abo_rec_caja.value > document.rec_caja.val_tot_factura.value)
	   { 
		  CamposVacios += "* Valor Abono \n"; }	
	   }
	   
    //SI EN LA VARIABLE CAMPOSVACIOS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	   if (CamposVacios != "")
	    {
	     alert(Mensaje + CamposVacios);
	     return true;
	    }
	   else
	    document.rec_caja.submit();
  }
</script>
<form name="for_rec_cliente" id="for_rec_cliente" method="post" action="#">
 <center>
    <table>
        <tr>
            <td>Mes Contable:
            <select name="mes_sele" id="mes_sele">
            <?php
            while($dat_meses = mssql_fetch_array($meses))
            	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
            ?>  
            </select> 
            </td>
        </tr>
    </table>
    <table border="1">
        <tr>
            <td><select name="sel_factura">
                <option value="0" onclick="seleccion();">--Factura--</option>
            <?php while($res_fac = mssql_fetch_array($facturas)){ ?>
                <option value="<?php echo $res_fac['fac_id']; ?>" onclick="seleccion();"><?php echo $res_fac['fac_consecutivo']; ?></option>
            <?php } ?>
                </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
            </td>
        </tr>
    </table>
</form>
<form name="rec_caja" id="rec_caja"  method="post" action="../control/guardar_recibo_caja_clientes.php">
 <?php
 $fac_seleccionada = $_POST['sel_factura'];
 $_SESSION['factura'] = $fac_seleccionada;
 $_SESSION['mes'] = $_POST['mes_sele'];
 if($fac_seleccionada)
 {
	 $dat_factura = $ins_factura->dat_fac_seleccionada($fac_seleccionada);
	 $res_dat_factura = mssql_fetch_array($dat_factura);
	 $impu_contratos = $contrato->desc_contrato($fac_seleccionada);
 ?>
    <table border="1">
        <tr>
            <th colspan="4">Datos Factura</th>
        </tr>
        <tr>
            <th>Consecutivo</th>
            <td><?php echo $res_dat_factura['fac_consecutivo']; ?></td>
            <input type="hidden" name="fac_id" value="<?php echo $res_dat_factura['fac_id']; ?>"/>
            <th>Fecha</th>
            <td><?php echo $res_dat_factura['fac_fecha']; ?></td>
        </tr>
        <tr>
            <th>Nit</th>
            <td><?php echo $res_dat_factura['nits_nombres']; ?></td>
            <input type="hidden" name="nit" value="<?php echo $res_dat_factura['nit_id']; ?>"/>
            <th>C.Costo</th>
            <td><?php echo $res_dat_factura['cen_cos_nombre']; ?></td>
            <input type="hidden" name="cen_costo" value="<?php echo $res_dat_factura['cen_cos_id']; ?>"/>
        </tr>
        <tr>
           <th>Descripci&oacute;n</th>
           <td colspan="3"><?php echo $res_dat_factura['fac_descripcion']; ?></td>
        </tr>
        <tr>
            <th>Valor Unitario</th>
            <td><?php echo $res_dat_factura['fac_val_unitario']; ?></td>
            <th>Valor Total</th>
            <td><?php echo $res_dat_factura['fac_val_total']; ?></td>
        <input type="hidden" name="val_tot_factura" value="<?php echo $res_dat_factura['fac_val_total']; ?>"/>
        </tr>
    </table>
  <?php
  $res_num_factura = $ins_transaccion->num_tran($fac_seleccionada);
  $recibo = $ins_rec_caja->saldoRecibos($fac_seleccionada);
  $nota = $ins_rec_caja->saldoNotas($fac_seleccionada);
  $descuento = $ins_rec_caja->saldoDescuentos($fac_seleccionada,11);
  ?>
    <table border="1">
        <tr>
            <th>Valor Abonado</th>
            <td><input disabled="disabled" type="text" name="abon" id="abon" value="<?php echo $recibo+$descuento; ?>"/></td>
            <th>Valor en descuentos</th>
            <td><input disabled="disabled" type="text" name="des" id="des" value="<?php echo $descuento; ?>" /></td>
            <th>Valor en notas</th>
            <td><input disabled="disabled" type="text" name="not" id="not" value="<?php echo $nota; ?>"  /></td>
            <th>Saldo A La Fecha</th>
            <td><input disabled="disabled" type="text" name="sal_abo" id="sal_abo" value="<?php echo $res_dat_factura['fac_val_total']-($recibo+$nota+$descuento); ?>"  /></td>
         <input type="hidden" name="sal_fecha" id="sal_fecha" value="<?php echo $res_dat_factura['fac_val_total']-($recibo+$nota+$descuento); ?>"/>
        </tr>
    </table>
    <table border="1">
        <tr>
      <?php
        $i=1;
        $impuestos = "";
        while($des_contrato = mssql_fetch_array($impu_contratos))
        {
            if($i%3==0)
               echo "</tr><tr>";
            $valor = $des_contrato['con_por_con_porcentaje']/$des_contrato['con_vigencia'];
            echo "<td>Nombre Impuesto</td><td><input type='text' name='impu$i' id='impu$i' value='".$des_contrato['con_nombre']."' readonly='readonly' /></td><td>Valor a descontar</td><td><input type='text' name='val$i' id='val$i' value='".$valor."' readonly='readonly' /></td>";
            echo "<input type='hidden' name='cuenta$i' id='cuenta$i' value='".$des_contrato['for_cue_afecta2']."' />";
            $impuestos.= $des_contrato['con_por_con_id']."-";
            $i++;
        }
        $_SESSION['des_impuestos'] = $i;
      ?>
      <input type="hidden" name="impu_id" id="impu_id" value="<?php echo $impuestos; ?>" />
        </tr>
    </table>
    <table border="1">
       <tr><th colspan="4">Recibo De Caja</th></tr>
       <tr>
           <th colspan="2">Es Abono  <input type="checkbox" name="abono" id="abono" /></th>
           <th>Valor Abono</th><td><input type="text" name="val_abo_rec_caja" id="val_abo_rec_caja"/></td>
       </tr>
       <tr>
           <td>Consecutivo</td>
           <td><input type="text" name="conse_rec_caja" id="conse_rec_caja" readonly="readonly" value="<?php echo $cons_conse; ?>" /></td>
           <td>Fecha</td>
           <td><input type="text" name="fec_rec_caja" id="fec_rec_caja"/>
                <a href="javascript:NewCal('fec_rec_caja','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
       </tr>
       <tr>
           <td>Nota</td>
           <td><textarea cols="20" rows="1" name="not_rec_caja"></textarea></td>
           <td>Concepto</td>
           <td><select name="concep_rec_caja" id="concep_rec_caja">
                <option value="0">Seleccione...</option>
                <?php
				 while($row=mssql_fetch_array($con_recibo))
				   echo "<option value='".$row['con_id']."'>".$row['con_nombre']."</option>";
				?>
               </select>
           </td>
       </tr>
       <tr>
           <td>Glosa Aceptada</td>
           <td><input type="text" class="sumar" name="glo_ace_rec_caja" id="glo_ace_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0" onchange="distGlosa()"/></td>
           <td>Estampillas</td>
           <td><input type="text" class="sumar" name="adm_rec_caja" id="adm_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
       </tr>
       <tr>
           <td>Glosa Pdte Aceptaci&oacute;n</td>
           <td><input type="text" class="sumar" name="glo_pen_ace_rec_caja" id="glo_pen_ace_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0" onchange=""/></td>
           <td>Descuento</td>
           <td><input type="text" class="sumar" name="des_rec_caja" id="des_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
       </tr>
       <tr>
           <td>Impuesto De Timbre</td>
           <td><input type="text" class="sumar" name="imp_tim_rec_caja" id="imp_tim_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
           <td>Retenci&oacute;n En La Fuente</td>
           <td><input type="text" class="sumar" name="ret_en_la_fue_rec_caja" id="ret_en_la_fue_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
       </tr>
       <tr>
           <td>ProHospital</td>
           <td><input type="text" class="sumar" name="pro_hos_rec_caja" id="pro_hos_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
           <td>Ica</td>
           <td><input type="text" class="sumar" name="ica_rec_caja" id="ica_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
       </tr>
       <tr>
           <td>Prodesarrollo</td>
           <td><input type="text" class="sumar" name="pro_des_rec_caja" id="pro_des_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
           <td>Otros Dtos</td>
           <td><input type="text" class="sumar" name="otr_des_rec_caja" id="otr_des_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
       </tr>
       <tr>
           <td>&nbsp;</td>
           <td>&nbsp;</td>
           <td>Valor Neto</td>
           <td><input type="text" name="val_net_rec_caja" id="val_net_rec_caja" value="0" readonly="readonly"/></td>
           <!--<td><input type="button" class="art-button" name="calcular" id="calcular" value="Calc"/>
           <p id="val_net_rec_caja" />
           </td>-->
       </tr>
       <tr>
            <td colspan="4"><input type="button" class="art-button" name="guardar" value="Guardar" onclick="validar_vacios();"/></td>
       </tr>
    </table>
<?php
}
?>
</form>