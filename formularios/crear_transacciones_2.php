<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
$caso = $_GET['c'];
$_SESSION['caso'] = $caso;
$_SESSION['regimen_empresa'];
@include_once('../clases/transacciones.class.php');
@include_once('../clases/nits.class.php');
@include_once('clases/transacciones.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('../clases/tipo_producto.class.php');
@include_once('clases/tipo_producto.class.php');
@include_once('../clases/producto.class.php');
@include_once('clases/producto.class.php');
@include_once('../clases/cuenta.class.php');
@include_once('clases/cuenta.class.php');
@include_once('clases/pabs.class.php');
@include_once('../clases/pabs.class.php');
@include_once('clases/concepto.class.php');
@include_once('../clases/concepto.class.php');
@include_once('../clases/recibo_caja.class.php');
@include_once('clases/recibo_caja.class.php');
include_once('../clases/regimenes.class.php');
include_once('../clases/centro_de_costos.class.php');

$regimen = new regimenes();
$ins_rec_caja = new rec_caja();
$tipo_producto = new tipo_producto();
$producto = new producto();
$ins_cuenta = new cuenta();
$pabs = new pabs();
$mes = new mes_contable();
$cent =new transacciones();
$nits = new nits();
$ins_concepto=new concepto();
$centro=new centro_de_costos();

$cons_conse = $ins_rec_caja->obt_consecutivo(20);
$afecto = $regimen->afec_impuesto($_SESSION['regimen_empresa']);
$meses = $mes->DatosMesesAniosContables($ano);
$tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
$pro = $nits->ConProFondo($tipos);
$trans =$centro->cen_cos_sec();
$ejecutar =$cent->obtener_concecutivo();
$con_concepto=$ins_concepto->verificar_existe(106);
$res_concepto=mssql_fetch_array($con_concepto);
$cue = mssql_fetch_array($ejecutar);
$ejemplo = $cons_conse;//$cue['max_id'] + 1;//tre el ultimo numro insertado en los consecutivos y suma 1
$_SESSION['tra'] = $ejemplo;

function generaProductos($i)
  {
    @include_once('../clases/producto.class.php');
	@include_once('clases/producto.class.php');
    $producto = new producto();
	$tip_pro = $producto->todosProductos();
	echo "<select name='select2[".$i."]' id='select2[".$i."]'>";
	echo "<option value='0'>Seleccione Articulo</option>";
	while($tip_prod = mssql_fetch_array($tip_pro))
		echo "<option value='".$tip_prod['pro_id']."'>".$tip_prod['pro_nombre']."</option>";
	echo "</select>";
  }
?>

<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<script language="javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script language="javascript" src="../librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/separador.js"></script>
<script src="librerias/js/separador.js"></script>
<script src="../librerias/js/jquery-1.5.0.js"></script>

<script language="javascript">
function validaPeriodo()
{
	var cadena = $("#mes_sele").val();
	if(cadena=='')
	{
		alert('Seleccione el mes contable.');
		return false;
	}
	else
	{
		cadena = cadena.split("-");
		if(cadena[0]==1)
		{
			alert("Mes de solo lectura");
			return false;	
		}
		else
			document.transaccion.submit();
	}
}

function ica(prove,tipo,tipo1)
 {
   $.ajax({
   type: "POST",
   url: "../llamados/ica.php",
   data: "prove="+prove+"&tipo="+tipo+"&tipo1="+tipo1,
   success: function(msg){$("#select2").html(msg);}
   });
 }

function nuevaGasto()
 {
	var cuantos = $("#productos > tbody > tr").length-1;
	var temp;
	var elhtml='<tr id="'+cuantos+'"><td><input type="text" name="ref'+cuantos+'" id="ref'+cuantos+'" size="5" value="N/A"/></td>';
	elhtml+='<td><select onchange="obtenerP(this.value,'+cuantos+')" id="select1'+cuantos+'" name="select1'+cuantos+'">';
	elhtml+='<option value="0">--Seleccione--</option>';
	<?php
	  $tipo_producto = new tipo_producto();
	  $tip_pro = $tipo_producto->cons_tipo_producto();
	  while($tip_prod = mssql_fetch_array($tip_pro))
	  { ?>
	    elhtml+='<option value="<?php echo $tip_prod['tip_pro_id'];?>"><?php echo $tip_prod['tip_pro_nombre']; ?></option>"';
	  <?php 
	  }
	?>
	elhtml+='</select></td>';
	elhtml+='<td><select name="select2'+cuantos+'" id="select2'+cuantos+'"><option value="0">--Seleccione--</option></select></td>';
	elhtml+='<td><textarea cols="10" name="descr'+cuantos+'" id="descr'+cuantos+'"></textarea></td><td><input type="checkbox" name="iva'+cuantos+'" id="iva'+cuantos+'" /></td><td><input type="text" name="cantidad'+cuantos+'" id="cantidad'+cuantos+'" size="5" onkeypress="return permite(event,"num")" /></td><td><input type="text" name="valor'+cuantos+'" id="valor'+cuantos+'" size="5" onkeypress="return permite(event,"num")"/></td></tr>';
	$("#productos").append(elhtml);
	$("#cant_gasto").val(cuantos);
 }

function nuevoPABS(){
	var cuantos = $("#tab_pabs > tbody > tr").length-1;
	<?php 
	  $asociados = $nits->con_tip_nit(1);
	  $beneficiarios = $nits->con_tip_nit(3);
	  $productos = $producto->todosProductos();
	  $lin_pabs = $pabs->lineasPABS();
	  $tip_pago = $pabs->tip_pago(); ?>
	 var elhtml= '<tr id="'+cuantos+'"><td><select name="asoc'+cuantos+'" id="asoc'+cuantos+'" class="nombre"><option value="0">Seleccione...</option>'; 
	 
	 <?php
	 while($dat_aso = mssql_fetch_array($asociados)){ ?>
	   	elhtml+='<option value="<?php echo $dat_aso['nit_id']; ?>"><?php echo substr($dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos'],0,20); ?></option>'; 
	 <?php } ?>
	 elhtml+= '</select></td><td><select name="concep_pabs'+cuantos+'" id="concep_pabs'+cuantos+'" onchange="obtenerPabs(asoc'+cuantos+'.value,'+cuantos+',this.value)"><option value="0">Seleccione...</option>';
	 
	 <?php
	 while($dat_concep = mssql_fetch_array($lin_pabs)){ ?>
		elhtml+= '<option value="<?php echo $dat_concep['pabs_id']; ?>"><?php echo substr($dat_concep['pabs_nombre'],0,15); ?></option>';
	  <?php } ?>		 
	 elhtml+='</select></td><td><select name="lineas'+cuantos+'" id="lineas'+cuantos+'"></select></td>';
	 elhtml+='<td><select onchange="obtenerFABS(this.value,'+cuantos+')" id="pabs1'+cuantos+'" name="pabs1'+cuantos+'">';
	 elhtml+='<option value="0">--Seleccione--</option>';
	<?php
	  $tipo_producto = new tipo_producto();
	  $tip_pro = $tipo_producto->cons_tipo_producto();
	  while($tip_prod = mssql_fetch_array($tip_pro))
	  { ?>
	   elhtml+='<option value="<?php echo $tip_prod['tip_pro_id'];?>"><?php echo substr($tip_prod['tip_pro_nombre'],0,15); ?></option>"';
	  <?php 
	  }
	?>
	elhtml+='</select></td>';
	elhtml+='<td><select name="pabs2'+cuantos+'" id="pabs2'+cuantos+'"><option value="0">--Seleccione--</option></select></td>';
	elhtml+='<td><input type="checkbox" name="iva'+cuantos+'" id="iva'+cuantos+'" /></td>';
	elhtml+='<td><input type="text" name="cant'+cuantos+'" id="cant'+cuantos+'" size="2" onkeypress="return permite(event,"num")" /></td><td><input type="text" name="descripcion_pabs'+cuantos+'" id="descripcion_pabs'+cuantos+'" size="15"/></td><td><select name="tip_pag'+cuantos+'" id="tip_pag'+cuantos+'" ><option value="0">Seleccione...</option>';
	 <?php
	    while($dat_tip = mssql_fetch_array($tip_pago)) { ?>
		     elhtml += '<option value="<?php echo $dat_tip['tip_pag_id']; ?>"><?php echo substr($dat_tip['tip_pag_nombre'],0,10) ?></option>';
		<?php } ?>	 
	    elhtml+= '</select></td><td><input type="text" name="val'+cuantos+'" id="val'+cuantos+'" onkeypress="return permite(event,"num")" size="8" class="valor" /></td></tr>';
	$("#tab_pabs").append(elhtml);
	$("#cant_pabs").val(cuantos);
 }
 
function nuevoCredito()
{
	var cuantos = $("#cre_cau > tbody > tr").length-1;
	<?php $asociados = $nits->con_tip_nit(1);
	      $con_nit_por_lin_tel=$nits->con_nit_por_lin_tel(2);
	 ?>
	 var elhtml= '<tr id="'+cuantos+'"><td><select name="asoc_cre'+cuantos+'" id="asoc_cre'+cuantos+'" onchange="obtenerLinTel(this.value,1,'+cuantos+')">	<option value="0">Seleccione...</option>'; 
	 <?php
	 while($dat_aso = mssql_fetch_array($con_nit_por_lin_tel)){ ?>
	   	elhtml+='<option value="<?php echo $dat_aso['nit_id']; ?>"><?php echo substr($dat_aso['nombres'],0,20); ?></option>'; 
	 <?php } ?>
	 elhtml+= '</select></td><td><select name="concep_cre'+cuantos+'" id="concep_cre'+cuantos+'"><option value="106">CREDIYA Y/O TELEFONIA</option>';
	 elhtml+= '</select></td><td><select name="lin_cre'+cuantos+'" id="lin_cre'+cuantos+'"></select></td>';
	 elhtml+= '<td><input type="text" name="descripcion'+cuantos+'" id="descripcion'+cuantos+'" size="15"/></td>';
	 elhtml+= '<td><input type="text" name="val['+cuantos+']" id="val['+cuantos+']" onkeypress="return permite(event,"num")" size="8" class="valor" /></td></tr>';
	$("#cre_cau").append(elhtml);
	$("#cantidad_credito").val(cuantos);
}

function probando(val)
{
	if(val==''){
		$("#gasto").css("display", "none");
		$("#pabs").css("display","none");
		$("#credito").css("display","none");
		$("#btn1").css("display","none");
		$("#btn2").css("display","none");
		$("#btn3").css("display","none");
	}
	if(val==1){
		$("#gasto").css("display", "block");
		$("#pabs").css("display","none");
		$("#credito").css("display","none");
		$("#btn1").css("display","block");
		$("#btn2").css("display","none");
		$("#btn3").css("display","none");
	}
	if(val==2){
		$("#gasto").css("display", "none");
		$("#pabs").css("display","block");
		$("#credito").css("display","none");
		$("#btn1").css("display","none");
		$("#btn2").css("display","block");
		$("#btn3").css("display","none");
	}
	if(val==3){
		$("#gasto").css("display", "none");
		$("#pabs").css("display","none");
		$("#credito").css("display","block");
		$("#btn1").css("display","none");
		$("#btn2").css("display","none");
		$("#btn3").css("display","block");
	}
}

 //Este es para cuando sale unicamente el gasto!!!
 function obtenerP(idt,id){
   $.ajax({
   type: "POST",
   url: "../llamados/productos.php",
   data: "id="+idt+"&id2="+id,
   success: function(msg){
     $("#select2"+id).html(msg);
   }
 });
}

function obtenerFABS(idt,id){
   $.ajax({
   type: "POST",
   url: "../llamados/productos.php",
   data: "id="+idt+"&id2="+id,
   success: function(msg){
     $("#pabs2"+id).html(msg);
   }
 });
}

function obtenerPabs(idt,id,conce){
   $.ajax({
   type: "POST",
   url: "../llamados/trae_lin_telefonicas.php",
   data: "id="+idt+"&id2="+id+"&conce="+conce,
   success: function(msg){
     $("#lineas"+id).html(msg);
   }
 });
}

function obtenerLinTel(nit,credito,res){
   $.ajax({
   type: "POST",
   url: "../llamados/trae_lin_telefonicas.php",
   data: "id="+nit+"&cred="+credito,
   success: function(msg){
     $("#lin_cre"+res).html(msg);
   }
 });
}

function val_retencion(valor,pos)
{
 if(valor==28)
 {
	var val_descontar=prompt('Valor retencion','$$');
	$("#val_reteVaca").val(val_descontar);
 }
}

function exis_factura(doc,tercero)
{
	$.ajax({
		type:"POST",
		url: "../llamados/doc_registrado.php",
	    data: "tercer="+tercero+"&documento="+doc,
	    success: function(msg){
	    	if(msg==1){
	    		alert("El documento digitado ya registrado");
	    		$("#num_oc_fa").val(0);	
	    	}
		}
	});
}

</script>
<form id="transaccion" name="transaccion" onsubmit="return valida_blancos()" action="../control/guardar_leg_tran.php" method="post" >
      <center>
      <table>
       <tr>
       	<input type='hidden' name="validaCampo" id="validaCampo" />
        <td align="center"><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
        Mes Contable: 
          <select name="mes_sele" id="mes_sele" onchange="consecutivo(this.value,20,'trans_id','../llamados/inic_mes.php');">
          	<option value=''>Seleccione...</option>
          <?php
			  while($dat_meses = mssql_fetch_array($meses))
			    echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  ?>  
      </select>    
	  </td>
       </tr>
      </table>
      <table width="764" border="1" align="center">
		<tr>
        	<td colspan="4" align="center"><strong>CAUSACIONES SEDAR</strong></td>
  		</tr>
       <tr align="center">
         <td width="164">Transaccion numero</td> 
         <td width="293">Documento para Transaccion</td>
         <td colspan="2">Proveedor</td>
       </tr>
       <tr align="center">  
         <td>
             <input type="text" size="5" name="trans_id" id="trans_id" readonly="readonly" value='' />
         </td>
         <td>
           <select name="sigla" id="sigla" required x-moz-errormessage="Seleccione Una Opcion Valida">
             <option value="">Seleccione</option> 
             <option value="1">Gasto Sedar</option>
             <option value="2">FABS</option>
             <option value="3">Credito</option>
           </select></td> 
          <td width="129">
     <input size="20" type="text" name="prov" id="prov" onchange='ica(this.value,<?php echo $afecto; ?>)' list="pro" required="required"/>
           <datalist id="pro">
            <?php
			  while($row = mssql_fetch_array($pro))
                echo "<option value='".$row['nit_id']."' label='".$row['nits_num_documento']."-".$row['nits_nombres']." ".$row['nits_apellidos']."'>";
            ?>
           </datalist>
         </td> 
          <td width="150">
            <select name="select2" id="select2" required x-moz-errormessage="Seleccione Una Opcion Valida">
              <option value="" onclick="valida();">Selecciona opci&oacute;n...</option>
            </select>
          </td> 
        </tr>
	    <tr align="center">
          <td height="23">Fecha expedicion factura</td>
          <td>Fecha vencimiento</td>
          <td>Centro de costo</td>
          <td>Porcentaje CREE</td>
       </tr>
       <tr align="center">
         <td>
           <p>
             <input type="text" name="fecha_fact" id="fecha_fact" onkeypress="return permite(event,'car')" required="required"/>
             <a href="javascript:NewCal('fecha_fact','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a> <br /> 
             
             <label></label>
           </p></td>
    <td><input type="text" name="fec_ven" id="fec_ven" onkeypress="return permite(event, 'car')" required="required"/>
      <a href="javascript:NewCal('fec_ven','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>    
    <td><input name="centro_cost" id="centro_cost" list='cen' required x-moz-errormessage="Digite Centro">
   	<datalist id='cen'>
     <?php
	   while($cue = mssql_fetch_array($trans))
	       echo "<option value='".$cue['cen_cos_id']."' label='".$cue['cen_cos_nombre']."'>"; ?>
	 ?>
   </datalist></td>
  <td><input type='text' name='cree' id='cree' value='0'></td>
  </tr>
     <tr align="center">
      <td>Numero de Documento</td>
      <td>Fecha Grabacion</td>
      <td colspan="2">Descripcion</td>
     <tr align="center">
     <td><input type="text" name="num_oc_fa" id="num_oc_fa" onkeypress="return permite(event, 'num')" value="" required="required" onchange="exis_factura(this.value,prov.value);"/></td>
    <td><input type="text" name="fech_venc" value="<?php echo date('d/m/y')?>" disabled="disabled"/></td>
    <td colspan="2"><textarea name="descripcion" id="descripcion" required="required"></textarea>
    
    </td>
      </tr>
  <tr align="center">
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr align="center">
    <td colspan="4"><input type="button" class="art-button" value="Aceptar" name="aceptar"  onclick="probando(sigla.value)"/></td>
  </tr>
      </table>
<div id="gasto" style="display: none;">
 <br /><br />
  <table width="604" id="productos" border="1">
	 <tr align="center"><td colspan="7"><strong>Causacion del gasto</strong></td></tr>
      <tr>
	     <td width="30">Ref</td>
	     <td width="85">Tipo de articulo</td>
	     <td width="119">Articulo</td>
         <td width="72">Descripcion</td>
         <td width="20">Iva</td>
         <td width="55">Cantidad</td>
	     <td width="76">Valor Unidad</td>   
	  </tr>
    <tr>
	 <td><input type="text" name="ref1" id="ref1" size="5" value="N/A"/></td>
     <td>
	  <?php
		$tip_pro = $tipo_producto->cons_tipo_producto(); 
		$html = "";
        $html .= "<select onchange='obtenerP(this.value,1)' id='select11' name='select11'>";
		$html .= "<option value='0'>Seleccione...</option>";
        while($tip_prod = mssql_fetch_array($tip_pro))       
            $html .= '<option value="'.$tip_prod['tip_pro_id'].'">'.$tip_prod['tip_pro_nombre'].'</option>'; 
        $html .="</select><br><br>";
		echo $html;
	  ?>
      </td>
	  <td>
       <select name="select21" id="select21" onchange="val_retencion(this.value);">
		 <option value="0">--Seleccione--</option>
	   </select>
	   </td>
	   <td><textarea cols="10" name="descr1" id="descr1" /></textarea></td>
	   <td><input type="checkbox" name="iva1" id="iva1" /></td>
       <td><input type="text" name="cantidad1" id="cantidad1" size="5" onkeypress="return permite(event,'num')" /></td>
       <td><input type="text" name="valor1" id="valor1" size="5" onkeypress="return permite(event,'num')"/></td>
	</tr>
   </table>
</div>
<div id="btn1" style="display:none;">
<table>
    <tr>
        <td><input type="button" class="art-button" value="Nuevo registro" onclick="nuevaGasto()"/></td>
        <td><input type="hidden" value="1" name="cant_gasto" id="cant_gasto"  /><input type="hidden" name="val_reteVaca" id="val_reteVaca" /></td>
        <td><input type="button" class="art-button" id="guar" name="guar" onclick="validaPeriodo();" value="Registrar causacion"/></td>
    </tr>
</table>
</div>
<div id="pabs" style="display: none;">
 <br /><br />
  <table id="tab_pabs" border="1">
   <tr align="center" id="0"><td colspan="10"><strong>Causacion FABS</strong></td></tr>
   <tr id="1">
     <td>Afiliado</td>
     <td>Concepto</td>
     <td>Lineas Telefonicas</td>
     <td>Tipo producto</td>
     <td>Producto</td>
     <td>Iva</td>
     <td>Cantidad</td>
     <td>Descripcion</td>
     <td>Medio de Pago</td>
     <td>Valor</td>
    </tr>
    <?php 
	  $asociados = $nits->con_tip_nit(1);
	  $beneficiarios = $nits->con_tip_nit(3);
	  $productos = $producto->todosProductos();
	  $lin_pabs = $pabs->lineasPABS();
	  $tip_pago = $pabs->tip_pago(); 
	  $tip_pro = $tipo_producto->cons_tipo_producto(); ?>
	  <tr id="2">
        <td>
          <?php
	         echo "<select name='asoc1' id='asoc1' class='nombre'>
			  <option value='0'>Seleccione...</option>";
	 		  while($dat_aso = mssql_fetch_array($asociados))
	   			echo "<option value='".$dat_aso['nit_id']."'>".substr($dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos'],0,20)."</option>";
			 echo "</select>"; ?>
	     </td>
		 <td>
	       <select name='concep_pabs1' id='concep_pabs1' onchange='obtenerPabs(asoc1.value,1,this.value);'>
			 <option value='0'>Seleccione...</option> <?php
			 while($dat_concep = mssql_fetch_array($lin_pabs))
			     echo "<option value='".$dat_concep['pabs_id']."'>".substr($dat_concep['pabs_nombre'],0,15)."</option>"; ?>
		   </select></td>
		 <td><select name='lineas1' id='lineas1'></select></td>
         <td>
		 <?php 
		$html = "";
        $html .= "<select onchange='obtenerFABS(this.value,1)' id='pabs11' name='pabs11'>";
		$html .= "<option value='0'>Seleccione...</option>";
        while($tip_prod = mssql_fetch_array($tip_pro))       
            $html .= '<option value="'.$tip_prod['tip_pro_id'].'">'.substr($tip_prod['tip_pro_nombre'],0,15).'</option>'; 
        $html .="</select><br><br>";
		echo $html;
	  ?>
      </td>
	  <td><select name="pabs21" id="pabs21">
      <option value="0">--Seleccione--</option></select></td>
      <td><input type="radio" name="iva1" id="iva1"  /></td>
		 <td><input type="text" name="cant1" id="cant1" size="2" value = "" onkeypress="return permite(event,'num')" /></td>				 	     <td><input type='text' name='descripcion_pabs1' id='descripcion_pabs1' size='15' value = '' /></td>
	     <td><select name='tip_pag1' id='tip_pag1' ><option value='0'>Seleccione...</option> <?php
			  while($dat_tip = mssql_fetch_array($tip_pago))
			      echo "<option value='".$dat_tip['tip_pag_id']."'>".substr($dat_tip['tip_pag_nombre'],0,10)."</option>";
			 echo "</select>"; ?>
	     </select></td>
	     <td><input type="text" name="val1" id="val1" onkeypress="return permite(event,'num')" size="8" class="valor" />
     </td></tr>
  </table>
</div>
<div id="btn2" style="display:none;">
<table>
	<tr>
    	<td><input type="button" class="art-button" value="Nuevo registro" onclick="nuevoPABS()"/></td>
  		<td><input type="hidden" name="cantidad_pabs" id="cantidad_pabs" value="0" /></td>
        <td><input type="button" class="art-button" id="guar" name="guar" onclick="validaPeriodo();" value="Registrar causacion"/></td>
    </tr>
</table>
</div>
<div id="credito" style="display: none;">
 <br /><br />
  <table width="604" id="cre_cau" border="1">
   <?php $con_nit_por_lin_tel=$nits->con_nit_por_lin_tel(2); ?>
	 <tr align="center"><td colspan="7"><strong>Causacion del gasto</strong></td></tr>
      <tr>
	     <td width="30">Asociado</td>
	     <td width="85">Concepto</td>
	     <td width="119">Linas Telefonicas</td>
         <td width="72">Descripcion</td>
         <td width="20">Valor</td>
	  </tr>
      <tr id="0">
        <td><select name="asoc_cre1" id="asoc_cre1" onchange="obtenerLinTel(this.value,1,1)">	
      		<option value="0">Seleccione...</option>
	 		<?php
	 			while($dat_aso = mssql_fetch_array($con_nit_por_lin_tel)){ ?>
	   				<option value="<?php echo $dat_aso['nit_id']; ?>"><?php echo substr($dat_aso['nombres'],0,20); ?></option> 
	 		<?php } ?>
	 		</select>
         </td>
         <td>
          <select name="concep_cre1" id="concep_cre1">
           <option value="106">CREDIYA Y/O TELEFONIA</option>
	   	  </select>
         </td>
         <td><select name="lin_cre1" id="lin_cre1"></select></td>
	 	 <td><input type="text" name="descripcion1" id="descripcion1" size="15"/></td>
	 	 <td><input type="text" name="val[1]" id="val[1]" onkeypress="return permite(event,'num')" size="8" class="valor" /></td></tr>     
   </table>
</div>
<div id="btn3" style="display: none;">
<table>
	<tr>
		<td><input type="button" class="art-button" value="Nuevo registro" onclick="nuevoCredito()"/></td>
		<td><input type="hidden" value="1" name="cant_pabs" id="cant_pabs"  /><input type="hidden" value="1" name="cantidad_credito" id="cantidad_credito"  />
		</td>
		<td><input type="button" class="art-button" id="guar" name="guar" onclick="validaPeriodo();" value="Registrar causacion"/></td>
	</tr>
</table>
</div>
</center>
</form>