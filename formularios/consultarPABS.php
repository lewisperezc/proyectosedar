<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('clases/nits.class.php');
  @include_once('clases/cuenta.class.php');
  @include_once('../clases/cuenta.class.php');
  include_once('clases/pabs.class.php');
  include_once('clases/producto.class.php');
  include_once('clases/mes_contable.class.php');
  @include_once('clases/centro_de_costos.class.php');
  @include_once('../clases/centro_de_costos.class.php');
  
  $nit = new nits();
  $ins_cuenta = new cuenta();
  $producto = new producto();
  $pabs = new pabs();
  $mes = new mes_contable();
  $meses = $mes->DatosMesesAniosContables($ano);
  $ins_centro=new centro_de_costos();
?>
<script language="javascript" type="text/javascript" src="librerias/js/validacion_num_letras.js"></script>
<script language="javascript" type="text/javascript" src="librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/separador.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script language="javascript">
function obtenerFABS(idt,id){
   $.ajax({
   type: "POST",
   url: "llamados/productos.php",
   data: "id="+idt+"&id2="+id,
   success: function(msg){
     $("#pabs2"+id).html(msg);
   }
 });
}
function consultaFabs(conse)
{
	$('#tab_pabs').html('<div><img src="imagenes/loading.gif"/></div>');
	var cuantos = $("#tab_pabs > tbody > tr").length;
	var cadena = $("#mes_sele").val();
	cadena = cadena.split("-");
	for(i=0;i<cuantos;i++)
	  eliminarCon(i);
	var html = '';var html1 = '';
	var cuantos = 0;
	var otro='';
	var nit = '';
	var veces= '';
	$.ajax({
		type: "POST",
		url: "./llamados/consulFabs.php",
		data: "fabs="+conse+"&mes="+cadena[1],	
		success: function(msg){
			html+='<tr align="center" id="0"><td colspan="12"><strong>Causacion FABS</strong></td></tr><tr><td>Asociado</td><td>Documento - Nombres</td><td>Concepto</td><td>Proveedor</td><td>Documento - Nombres</td><td>Tipo producto</td><td>Producto</td><td>Descripcion</td><td>Valor</td></tr>';
			var myObject = eval('(' + msg + ')');
			for (var x = 0 ; x < myObject.length ; x++) 
			{
			  <?php
	 			$asociados = $nit->con_tip_nit(1);
				$tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
				$beneficiarios = $nit->ConProFondo($tipos);
			  ?>
html+='<tr id="tr'+x+'"><td><input type="text" name="asoc'+x+'" id="asoc'+x+'" value="'+myObject[x].nit+'" list="aso'+x+'" size="13" onchange="ObtenerNombresNit(this.value,'+x+');" required="required" /><datalist id="aso'+x+'">';
         <?php
	 	  while($dat_aso = mssql_fetch_array($asociados))
		  { ?>
	   		html+='<option value="<?php echo $dat_aso['nit_id']; ?>" label="<?php echo $dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']; ?>">';
	<?php } ?>
html+='</datalist></td>';
html+='<td><input type="text" name="doc_y_nom_asociado'+x+'" id="doc_y_nom_asociado'+x+'" value="'+myObject[x].doc_nom_asociado+'"/></td>';
html+='<td><select name="concep_pabs'+x+'" id="concep_pabs'+x+'" onchange="obtenerPabs(asoc'+x+'.value,1,this.value);" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione...</option>'+myObject[x].linea+'</select></td>'; 
html+='<td><input type="text" name="prov'+x+'" id="prov'+x+'" value="'+myObject[x].provee+'" size="12" list="pro'+x+'"/><datalist id="pro'+x+'">';
<?php while($res_proveedores=mssql_fetch_array($beneficiarios))
		{ ?>
		  html+='<option value="<?php echo $res_proveedores['nit_id']; ?>" label="<?php echo $res_proveedores['nits_num_documento']." ".$res_proveedores['nits_nombres']." ".$res_proveedores['nits_apellidos']; ?>">;';
<?php	} ?>
html+='</datalist></td></td><td><input type="text" name="nom_prov'+x+'" id="nom_prov'+x+'" value="'+myObject[x].nom_provee+'" /></td><td><select onchange="obtenerFABS(this.value,'+x+')" id="pabs1'+x+'" name="pabs1'+x+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione...</option>'+myObject[x].tip_producto+'</select></td><td><select name="pabs2'+x+'" id="pabs2'+x+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option><option value="'+myObject[x].producto+'" selected="selected">'+myObject[x].nombre_producto+'</option></select></td><td><input type="hidden" name="cant'+x+'" id="cant'+x+'" value="1" size="15"/><input type="text" name="descripcion_pabs'+x+'" id="descripcion_pabs'+x+'" value="'+myObject[x].descripcion+'" size="15"/></td><td><input type="text" name="val'+x+'" id="val'+x+'" value="'+myObject[x].valor+'" size="12"/></td></tr>';
			 veces=myObject[x].veces;
			 cuantos = myObject.length;
			}
			//Preguntar si ya tienen orden de desembolso
			/*if(veces==0){*/
				html1+='<tr><td colspan="6"><input type="button" class="art-button" value="Nuevo registro" onclick="nuevoPABS();"/></td><td colspan="6"><input type="button" class="art-button" name="boton" id="guaReg" onclick="validarMes();" value="Guardar Modificacion"/></td></tr>';
                                
		    /*}*/
			$('#tab_pabs').fadeIn(1000).html(html);
			$('#bot_gua').html(html1);
	    	$("#cant_pabs").val(cuantos);
		}
	});
}

function eliminarCon(oId)
{
     $("#tr"+oId).remove();
     return true;
}

function ObtenerNombresNit(nit,id)
{
	 $.ajax({
   	  type: "POST",
   	  url: "llamados/trae_nombres_nit.php",
   	  data: "id="+nit,
   	  success: function(msg)
	  {
	  	if(msg==" - ")
		{
			alert('El afiliado ingresado no se encuentra creado en el sistema!!!');
			document.dat_pabs.guaReg.disabled=true;
		}
	  	else
		{
      		$("#doc_y_nom_asociado"+id).val(msg);
			document.dat_pabs.guaReg.disabled=false;
		}
	  }
	  });
}
function nuevoPABS(){
	var cuantos = $("#tab_pabs > tbody > tr").length-2;
		<?php 
		  $asociados = $nit->con_tip_nit(1);
		  $tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
		  $beneficiarios = $nit->ConProFondo($tipos);
		  $centro=$ins_centro->con_cen_cos_pabs(1164);
		  $res_centro=mssql_fetch_array($centro);
		  $productos = $producto->todosProductos();
		  $lin_pabs = $pabs->lineasPABS();
		  $tip_pago = $pabs->tip_pago(); ?>
		 var elhtml= '<tr id="'+cuantos+'"><td><input type="text" name="asoc'+cuantos+'" id="asoc'+cuantos+'" list="aso'+cuantos+'" size="13" onchange="ObtenerNombresNit(this.value,'+cuantos+');" required="required"/><datalist id="aso'+cuantos+'">';
			 <?php
			  while($dat_aso = mssql_fetch_array($asociados)) { ?>
				elhtml+='<option value="<?php echo $dat_aso['nit_id']; ?>" label="<?php echo $dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']; ?>">;';
		 <?php } ?>
		 elhtml+='</datalist></td>';
		 elhtml+='<td><input type="text" name="doc_y_nom_asociado'+cuantos+'" id="doc_y_nom_asociado'+cuantos+'"/></td>';
		 elhtml+='<td><select name="concep_pabs'+cuantos+'" id="concep_pabs'+cuantos+'" onchange="obtenerPabs(asoc'+cuantos+'.value,'+cuantos+',this.value)" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione...</option>';
		 <?php
		 while($dat_concep = mssql_fetch_array($lin_pabs)){ 
	      if($list_fabs==0 && $dat_concep['pabs_id']!=9){?>
	          elhtml+='<option value="<?php echo $dat_concep['pabs_id']; ?>"><?php echo substr($dat_concep['pabs_nombre'],0,16); ?></option>';
	      <?php }
	      elseif($list_fabs==1){ ?>
	            elhtml+='<option value="<?php echo $dat_concep['pabs_id']; ?>"><?php echo substr($dat_concep['pabs_nombre'],0,16); ?></option>';
	      <?php }?>
		  <?php } ?>		 
		 elhtml+='</select></td>';
		 elhtml+='<td><input type="text" name="prov'+cuantos+'" id="prov'+cuantos+'" list="pro'+cuantos+'" size="13" required="required" onchange="ValidaId(this.value,'+cuantos+')"/><datalist id="pro'+cuantos+'">';
		 <?php 
		   while($res_proveedores=mssql_fetch_array($beneficiarios)) { ?>
			elhtml+='<option value="<?php echo $res_proveedores['nit_id']; ?>" label="<?php echo $res_proveedores['nits_num_documento']." ".$res_proveedores['nits_nombres']." ".$res_proveedores['nits_apellidos']; ?>">'; <?php } ?>
		 elhtml+='</datalist></td>';
		 elhtml+='<td><input type="text" name="doc_y_nom_proveedor'+cuantos+'" id="doc_y_nom_proveedor'+cuantos+'" size="13" required="required"/></td>';
		 elhtml+='<td><select onchange="obtenerFABS(this.value,'+cuantos+')" id="pabs1'+cuantos+'" name="pabs1'+cuantos+'" required x-moz-errormessage="Seleccione Una Opcion Valida">';
		 elhtml+='<option value="">--Seleccione--</option>';
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
		elhtml+='<td><select name="pabs2'+cuantos+'" id="pabs2'+cuantos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option></select></td>';
		elhtml+='<td><input type="hidden" name="cant'+cuantos+'" id="cant'+cuantos+'" value = "1" required="required"/><input type="text" name="descripcion_pabs'+cuantos+'" id="descripcion_pabs'+cuantos+'" size="15" required="requiered"/></td>';	 
			elhtml+= '<td><input type="text" name="val'+cuantos+'" id="val'+cuantos+'" onkeypress="return permite(event,"num")" size="12" class="valor" onChange = "puntitos(this,this.value.charAt(this.value.length-1),\'llamados/retMoneda.php\');" required="required"/></td></tr>';
		$("#tab_pabs").append(elhtml);
		$("#cant_pabs").val(cuantos+1);
 }

function validarMes()
{
    quitarPuntos();
    var ano = $("#estAno").val();
    var cadena = $("#mes_sele").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
    {
    	$("#dat_pabs").submit(function(){return false;});
    	alert("Mes de solo lectura.");
    }
    else
    { document.dat_pabs.submit(); }
}
 
function VentanaEmergente()
{
	var cadena = $("#mes_sele").val();
    cadena = cadena.split("-");
	URL='formularios/causaciones_fabs_por_nucleo.php?mes='+cadena[1];
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=900,height=500,left=240,top=112');");
}

function ValidaId(id,pos)
{
		$.ajax({
    	type: "GET",
    	url: "llamados/valida_nit.php",
    	data: "nit_id="+id,
    	success: function(msg)
    	{
			if(msg==0)
	  		{
	  			alert("El proveedor ingresado no se encuentra creado en el sistema!!!");
				document.reg_pabs.gua.disabled=true;
	  		}
			else
			{
                            $.ajax({
                            type: "POST",
                            url: "llamados/trae_nombres_nit.php",
                            data: "id="+id,
                            success: function(msg)
                            {
                                $("#doc_y_nom_proveedor"+pos).val(msg);
                                document.reg_pabs.gua.disabled=false;
                            }
                            });
			}
    	}
   		});
}
</script>

<form name="dat_pabs" id="dat_pabs" method="post" action="control/guardar_leg_tran.php?modificar=1" >
<center>
  <table id="contenedor" border="1">
    <tr>
     <td colspan="2">
      <table id="cab">
        <tr>
            <td>Tipo<input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/></td>
            <td><strong><input type="text" readonly="readonly" value=" Causacion FABS" size="20"/></strong></td>
            <td align="center">
            Mes Contable: <select name="mes_sele" id="mes_sele">
            <?php
            while($dat_meses=mssql_fetch_array($meses))
            { echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>"; }
            ?>  
            </select>    
            </td>
            <td>Causaci&oacute;n</td>
            <td><input type="text" name="conse" id="conse" onblur="consultaFabs(this.value);" ondblclick="VentanaEmergente();" size="10"  />
            <input type="hidden" name="num_oc_fa" id="num_oc_fa" value="<?php echo $conse; ?>" /></td>
            <!--<td>Fecha</td>
            <td>
            <input type="text" name="fec_ven" id="fec_ven" required="required"/>
            <a href="javascript:NewCal('fec_ven','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>-->
        </tr>
      </table>
     </td>
    </tr>
    <tr>
        <td>Centro Costo<input type="text" name="centro" id="centro" value="<?php echo $res_centro['cen_cos_nombre']; ?>"/>
           <input type="hidden" name="centro_cost" id="centro_cost" value="<?php echo $res_centro['cen_cos_id']; ?>"/></td>
    </tr>
   </table>
  <table id="tab_pabs" border="1">
  </table>
  <table id="bot_gua" border="1">
  </table>
  <input type="hidden" name="cant_pabs" id="cant_pabs" value="1"/>
  
</center>
</form>