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
 include_once('../clases/reporte_jornadas.class.php');
 $mes = new mes_contable();
 $ins_factura = new factura();
 $contrato = new contrato();
 $rep_jornadas = new reporte_jornadas();
 $ins_rec_caja = new rec_caja();
 $ins_concepto = new concepto();
 $meses=$mes->DatosMesesAniosContables($ano);
 $facturas = $ins_factura->cons_fac_no_pagadas();
 //Obtengo El Consecutivo Del recibo De Caja
  $cons_conse = $ins_rec_caja->obt_consecutivo(15);
 //Obtengo El id y nombre Del Concepto
 $no_trae="0";
 $con_recibo = $ins_concepto->conceptos(127,$no_trae);
 //Obtengo El Saldo A La Fecha De La Factura
 $ins_transaccion = new transacciones();
 $ins_mov_contable = new movimientos_contables();
 $rep_jornadas = new reporte_jornadas();
 //$act_rec_caja = $ins_rec_caja->act_consecutivo(15);
?>
<script src="../librerias/js/datetimepicker.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
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

function mostDistri(val)
{
  if(val==0){
    $("#igual").css("display", "none");
    $("#asociados").css("display","none");
  }
  if(val==1){
    $("#igual").css("display", "block");
    $("#asociados").css("display","none");
  }
  if(val==2){
    $("#igual").css("display", "none");
    $("#asociados").css("display","block");
  }
}

function mostDistri_pen(val)
{
  if(val==0){
    $("#igual_pen").css("display", "none");
    $("#asociados_pen").css("display","none");
  }
  if(val==1){
    $("#igual_pen").css("display", "block");
    $("#asociados_pen").css("display","none");
  }
  if(val==2){
    $("#igual_pen").css("display", "none");
    $("#asociados_pen").css("display","block");
  }
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
  once = document.rec_caja.glo_ace_rec_caja1.value;
  doce = document.rec_caja.glo_pen_ace_rec_caja1.value;
  document.rec_caja.val_net_rec_caja.value = (uno * 1) + (dos * 1) + (tres * 1) + (cuatro * 1) + (cinco * 1) + (seis * 1) + (siete * 1) + (ocho * 1) + (nueve * 1) + (diez * 1) + (once * 1) + (doce * 1);
}
function NoSumar()
{
  clearInterval(interval);
}

function sumaGlosa(valor)
 {
  var suma=0;
  for(i=0;i<valor;i++)
  {
    if($('#jorna'+i).val()!="")
      suma += parseInt($('#jorna'+i).val());
  }
  $('#glo_ace_rec_caja1').val(suma);
 }
 
 function sumaGlosa_pen(valor)
 {
  var suma=0;
  for(i=0;i<valor;i++)
  {
    if($('#jorna_pen'+i).val()!="")
      suma += parseInt($('#jorna_pen'+i).val());
  }
  $('#glo_pen_ace_rec_caja1').val(suma);
 }
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
  if(document.rec_caja.concep_rec_caja.value==0)
  CamposVacios += "* Concepto \n";
  
  if(document.rec_caja.val_abo_rec_caja.value == "" && document.rec_caja.abono.checked)
  {
    alert("El valor del abono debe ser mayor a cero.");
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
  function val_descuento(abono,recibo,mes,val_rec_anterior)
  {
  	/*alert(abono);
  	alert(recibo);
  	alert(mes);
  	alert(val_rec_anterior);*/
  	
    var impuestos = $("#can_impu").val();
    var val_factura = $("#val_tot_factura").val();
    for(i=1;i<=impuestos;i++)
    {
    valor = $("#val"+i).val();
    val_cal = (abono*valor)/val_factura;
    $("#val"+i).val(parseInt(val_cal,0))
    }
    if(val_rec_anterior>0)
      AbrPopUp('crear_reporte_jornadas_por_abono.php?valor_del_abono='+abono+'&recibo_consecutivo='+recibo+'&mes='+mes);
  }
  function AbrPopUp(URL)
  {
  var ValAbonado=document.rec_caja.abon.value;
  var EsAbono=document.rec_caja.abono;
  if(EsAbono.checked&&ValAbonado>0)
  {
    day=new Date();
    id=day.getTime();
    eval("page"+id+"=window.open(URL,'"+id+"','toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=900,left=240,top=112');");
  }
}
</script>

<?php
$f=0;
while($res_fac=mssql_fetch_array($facturas))
{
	$val_factura=$ins_rec_caja->ValorTotalFactura($res_fac['fac_id']);
	$total_abonos = $ins_rec_caja->ValorTotalAbonosFactura($res_fac['fac_id']);
	
	if($val_factura>$total_abonos)
	{
		$lista_facturas_id[$f]=$res_fac['fac_id'];
		$lista_facturas_consecutivo[$f]=$res_fac['fac_consecutivo'];
		$f++;
	}
}
?>

<form name="for_rec_cliente" id="for_rec_cliente" method="post" action="#">
 <center>
    <table border="1">
        <tr>
            <td><select name="sel_factura">
            <option value="0" onclick="seleccion();">--Factura--</option>
            <?php
            $cantidad=0;
            while($cantidad<sizeof($lista_facturas_id))
            {
            ?>
            <option value="<?php echo $lista_facturas_id[$cantidad]; ?>" onclick="seleccion();"><?php echo $lista_facturas_consecutivo[$cantidad]; ?></option>
			<?php	
				$cantidad++;
			}
            ?>
           	<input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
            </td>
        </tr>
    </table>
</form>
<form name="rec_caja" id="rec_caja"  method="post" action="../control/guardar_recibo_caja_clientes.php">
 <?php
 $fac_seleccionada = $_POST['sel_factura'];
 $_SESSION['factura'] = $fac_seleccionada;
 $_SESSION['fac_seleccionada']=$fac_seleccionada;
 if($fac_seleccionada)
 {
   $dat_factura = $ins_factura->dat_fac_seleccionada($fac_seleccionada);
   $res_dat_factura = mssql_fetch_array($dat_factura);
   $impu_contratos = $contrato->desc_contrato($fac_seleccionada);
   $impu_adiciones = $contrato->desc_adicion($fac_seleccionada);
   
   $con_val_recibo=$ins_rec_caja->SumValRecPorFactura($fac_seleccionada);
   $val_factura=$ins_rec_caja->ValorTotalFactura($fac_seleccionada);
 ?>
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
            <input type="hidden" name="nit" value="<?php echo $res_dat_factura['nit_id']; ?>"/><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
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
            <td><?php echo number_format($res_dat_factura['fac_val_total']); ?></td>
            <th>Valor Total</th>
            <td><?php echo number_format($res_dat_factura['fac_val_total']); ?></td>
        <input type="hidden" name="val_tot_factura" id="val_tot_factura" value="<?php echo $res_dat_factura['fac_val_total']; ?>"/>
        </tr>
    </table>
  <?php
  $res_num_factura = $ins_transaccion->num_tran($fac_seleccionada);
  $recibo = $ins_rec_caja->ValorTotalAbonosFactura($fac_seleccionada);
  $sigla_debito='NOT-DEB_';
  $sigla_credito='NOT-CRE_';
  $notas_debito=$ins_rec_caja->ValorNotaDebitoOCredito($fac_seleccionada,$sigla_debito);
  $notas_credito=$ins_rec_caja->ValorNotaDebitoOCredito($fac_seleccionada,$sigla_credito);
  $nota = $ins_rec_caja->saldoNotas($fac_seleccionada);
  /*$tipo="11,12";
  $descuento = $ins_rec_caja->saldoDescuentos($fac_seleccionada,$tipo);*/
  
  
  /*$valor_not_debito=$ins_rec_caja->ValorTotalFactura($row['fac_id']);
  $valor_factura=$row['fac_val_unitario']+$valor_not_debito;
  $valor_abonos=$ins_rec_caja->ValorTotalAbonosFactura($row['fac_id']);*/
  $valor_saldo=$val_factura-$recibo;
  
  ?>
    <table border="1">
        <tr>
            <th>Valor Abonado</th>
            <td><input readonly="readonly" type="text" name="abon" id="abon" value="<?php echo $recibo; ?>"/></td>
            <!--<th>Valor en descuentos</th>
            <td><input disabled="disabled" type="text" name="des" id="des" value="<?php echo $descuento; ?>" /></td>
            -->
            <th>Notas debito</th>
            <td><input disabled="disabled" type="text" name="not" id="not" value="<?php echo $notas_debito; ?>"  /></td>
            <th>Notas credito</th>
            <td><input disabled="disabled" type="text" name="not" id="not" value="<?php echo $notas_credito; ?>"  /></td>
            <th>Saldo A La Fecha</th>
            
            <td><input disabled="disabled" type="text" name="sal_abo" id="sal_abo" value="<?php echo $valor_saldo; ?>"  /></td>
         <input type="hidden" name="sal_fecha" id="sal_fecha" value="<?php echo $valor_saldo; ?>"/>
        </tr>
    </table>
    <table border="1">
        <tr>
      <?php
        $i=1;$j=1;
        $impuestos = "";
    	$num_impuestos = mssql_num_rows($impu_contratos);
        while($des_contrato = mssql_fetch_array($impu_contratos))
        {
            if($i%3==0)
               echo "</tr><tr>";
            $valor = $des_contrato['con_por_con_porcentaje']/$des_contrato['con_vigencia'];
            echo "<td>Nombre Impuesto</td><td><input type='text' name='impu$j' id='impu$j' value='".$des_contrato['con_nombre']."' readonly='readonly' /></td><td>Valor a descontar</td><td><input type='text' name='val$j' id='val$j' value='".$valor."' readonly='readonly' /></td>";
            echo "<input type='hidden' name='cuenta$j' id='cuenta$j' value='".$des_contrato['for_cue_afecta2']."' />";
            $impuestos.= $des_contrato['con_por_con_id']."-";
            $i++;$j++;
        }
        $num_adi_impuestos = mssql_num_rows($impu_adiciones);
    	if($num_adi_impuestos>0)
    	{
      		$i=$i-2;
      		while($des_contrato = mssql_fetch_array($impu_adiciones))
          	{
        		if($i%3==0)
           		echo "</tr><tr>";
		        $valor = $des_contrato['con_por_con_porcentaje']/$des_contrato['con_vigencia'];
		        echo "<td>Nombre Impuesto</td><td><input type='text' name='impu$j' id='impu$j' value='".$des_contrato['con_nombre']."' readonly='readonly' /></td><td>Valor a descontar</td><td><input type='text' name='val$j' id='val$j' value='".$valor."' readonly='readonly' /></td>";
		        echo "<input type='hidden' name='cuenta$j' id='cuenta$j' value='".$des_contrato['for_cue_afecta2']."' />";
		        $impuestos.= $des_contrato['con_por_con_id']."-";
		        $i++;$j++;
          	}
    }
        $_SESSION['des_impuestos'] = $j;
    $num_impuestos = $num_impuestos+$num_adi_impuestos;
      ?>
      <input type="hidden" name="impu_id" id="impu_id" value="<?php echo $impuestos; ?>" />
      <input type="hidden" name="can_impu" id="can_impu" value="<?php echo $num_impuestos; ?>" />
      <input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
        </tr>
    </table>
    <table border="1">
       <tr><th colspan="4">Recibo De Caja</th></tr>
       <tr>
           <th colspan="2">Es Abono<input type="checkbox" name="abono" id="abono"/></th>
           <th>Valor Abono</th>
           <td><input type="text" name="val_abo_rec_caja" id="val_abo_rec_caja" onchange="val_descuento(this.value,<?php echo $cons_conse; ?>,mes_sele.value,<?php echo $con_val_recibo ?>);"/></td>
       </tr>
       <tr>
        <td colspan="4"><hr /></td>
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
                <?php
         while($row=mssql_fetch_array($con_recibo))
           echo "<option value='".$row['con_id']."'>".$row['con_nombre']."</option>";
        ?>
               </select>
           </td>
       </tr>
       <tr>
           <td>Glosa Aceptada</td>
           <td>
            <select name="glo_ace" id="glo_ace" onchange="mostDistri(this.value);">
             <option value="0">Seleccione...</option><option value="1">Distribucion Proporcional</option><option value="2">Distribucion Afiliados</option>
            </select>
            <div id="igual" style="display: none;">
             <input type="text" class="sumar" name="glo_ace_rec_caja" id="glo_ace_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0" onchange="distGlosa(<?php echo $_SESSION['factura']; ?>);"/>
            </div>
            <div id="asociados" style="display: none;">
             <table id="asoFac">
              <?php
         $reporte = $rep_jornadas->buscarReporteJornadas_Factura($_SESSION['factura']);
               echo "<tr><td>Cedula</td><td>Nombre Afiliado</td><td>Valor Glosa</td></tr>";
         $i=0;
         $_SESSION['tot_asociados'] = mssql_num_rows($reporte);
         while($row = mssql_fetch_array($reporte))
            {
          echo "<tr>";
           echo "<td>";
           echo "<input type='hidden' name='repJor$i' id='repJor$i' value='".$row['rep_jor_id']."'/>";
           echo "<input type='hidden' name='nit$i' id='nit$i' value='".$row['nit_id']."'/>";
             echo "<input type='text' name='identi$i' id='identi$i' disabled='disabled' value='".$row['nits_num_documento']."' size='9'/>";
           echo "</td>";
           echo "<td>";
           echo "<input type='text' name='nombre$i' id='nombre$i' disabled='disabled' value='".$row['nits_nombres']." ".$row['nits_apellidos']."' size='30'/>"; 
           echo "</td>";
           echo "<td>";
           echo "<input type='text' name='jorna$i' id='jorna$i' value='".$row['rep_jor_num_jornada']."' size='10' onchange='sumaGlosa(".$_SESSION['tot_asociados'].");'/>";
           echo "</td></tr>";
           $i++;
            } ?>
      <tr><td colspan=2>Total Glosa</td>
            <td><input type='text' class='sumar' name='glo_ace_rec_caja1' id='glo_ace_rec_caja1' onFocus='NoSumar();' onBlur='Sumar();' value='0' /></td></tr>
             </table>
            </div>
           </td>
           <td>Estampillas</td>
           <td><input type="text" class="sumar" name="adm_rec_caja" id="adm_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0"/></td>
       </tr>
       <tr>
           <td>Glosa Pdte Aceptaci&oacute;n</td>
           <td>
           <select name="glo_pen" id="glo_pen" onchange="mostDistri_pen(this.value);">
             <option value="0">Seleccione...</option><option value="1">Distribucion Proporcional</option><option value="2">Distribucion Afiliados</option>
            </select>
             <div id="igual_pen" style="display: none;">
             <input type="text" class="sumar" name="glo_pen_ace_rec_caja" id="glo_pen_ace_rec_caja" onFocus="NoSumar();" onBlur="Sumar();" value="0" onchange="distGlosa(<?php echo $_SESSION['factura']; ?>);"/>
            </div>
            <div id="asociados_pen" style="display: none;">
             <table id="asoFac">
              <?php
         $reporte = $rep_jornadas->buscarReporteJornadas_Factura($_SESSION['factura']);
               echo "<tr><td>Cedula</td><td>Nombre Afiliado</td><td>Valor Glosa</td></tr>";
         $i=0;
         $_SESSION['tot_asociados'] = mssql_num_rows($reporte);
         while($row = mssql_fetch_array($reporte))
            {
          echo "<tr>";
           echo "<td>";
           echo "<input type='hidden' name='repJor_pen$i' id='repJor_pen$i' value='".$row['rep_jor_id']."'/>";
           echo "<input type='hidden' name='nit_pen$i' id='nit_pen$i' value='".$row['nit_id']."'/>";
             echo "<input type='text' name='identi_pen$i' id='identi_pen$i' disabled='disabled' value='".$row['nits_num_documento']."' size='9'/>";
           echo "</td>";
           echo "<td>";
           echo "<input type='text' name='nombre_pen$i' id='nombre_pen$i' disabled='disabled' value='".$row['nits_nombres']." ".$row['nits_apellidos']."' size='30'/>"; 
           echo "</td>";
           echo "<td>";
           echo "<input type='text' name='jorna_pen$i' id='jorna_pen$i' value='' size='10' onchange='sumaGlosa_pen(".$_SESSION['tot_asociados'].");'/>";
           echo "</td></tr>";
           $i++;
            } ?>
      <tr><td colspan=2>Total Glosa</td>
            <td><input type='text' class='sumar' name='glo_pen_ace_rec_caja1' id='glo_pen_ace_rec_caja1' onFocus='NoSumar();' onBlur='Sumar();' value='0' /></td></tr>
             </table>
            </div>
           </td>
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