<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('clases/nits.class.php');
  include_once('clases/mes_contable.class.php');
  @include_once('../clases/cuenta.class.php');
  @include_once('clases/cuenta.class.php');
  include_once('clases/pabs.class.php');
  include_once('clases/producto.class.php');
  include_once('clases/factura.class.php');
  @include_once('clases/centro_de_costos.class.php');
  @include_once('../clases/centro_de_costos.class.php');
  $ins_centro=new centro_de_costos();
  $nit = new nits();
  $fac = new factura();
  $mes = new mes_contable();
  $meses = $mes->DatosMesesAniosContables($ano);
  $ins_cuenta = new cuenta();
  $producto = new producto();
  $pabs = new pabs();
  
  $conse = $pabs->obt_consecutivo();
  $_SESSION['conse'] = $conse;

  $principal="1169,";
  $lacadena=$_SESSION['k_cen_costo'];
  $comparacion=strpos($lacadena,$principal);
  if($comparacion===false)
    $list_fabs=0;
  else
    $list_fabs=1;

  $numero=1;
  if (!empty($_REQUEST['numero']))
	 $numero=$_REQUEST['numero'];
  $recor = 0;
?>
<style>
tr:hover td
{
    background-color:#888;
}
</style>
<script src="librerias/js/datetimepicker.js"></script>
<script language="javascript" type="text/javascript" src="librerias/js/validacion_num_letras.js"></script>
<script language="JavaScript" src="librerias/js/jquery-1.5.0.js"></script>
<script src="librerias/js/separador.js"></script>
<script>
function obtenerPabs(idt,id,conce){
   $.ajax({
   type: "POST",
   url: "llamados/trae_lin_telefonicas.php",
   data: "id="+idt+"&id2="+id+"&conce="+conce,
   success: function(msg){
     $("#lineas"+id).html(msg);
   }
 });
}

function ObtenerPlata(nit,id){
          if(nit.length<=5)
          {
            $.ajax({
            type: "POST",
            url: "llamados/trae_plata_fabs_asociado.php",
            data: "id="+nit+"&id2="+id,
            success: function(msg)
            {
                  var resultado=msg.split("#");
                  if(resultado[1]=="")
                  {
                          alert('El afiliado ingresado no se encuentra creado en el sistema.');
                          document.reg_pabs.gua.disabled=true;
                  }
                  else
                  {
                          $("#plata"+id).val(resultado[0]);
                          $("#doc_y_nom_asociado"+id).val(resultado[1]+"-"+resultado[2]);
                          document.reg_pabs.gua.disabled=false;
                  }
            }
            });
          }
          else
          { alert('El afiliado ingresado no se encuentra creado en el sistema'); document.reg_pabs.gua.disabled=true; }
}

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
		 var elhtml= '<tr id="tr'+cuantos+'" class="sobre"><td><input type="text" name="asoc'+cuantos+'" id="asoc'+cuantos+'" list="aso'+cuantos+'" onchange="ObtenerPlata(this.value,'+cuantos+');" size="13" required="required"/><datalist id="aso'+cuantos+'">';
			 <?php
			  while($dat_aso = mssql_fetch_array($asociados)) { ?>
				elhtml+='<option value="<?php echo $dat_aso['nit_id']; ?>" label="<?php echo $dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']; ?>">;';
		 <?php } ?>
		 elhtml+= '</datalist></td><td><input type="text" readonly="readonly" name="doc_y_nom_asociado'+cuantos+'" id="doc_y_nom_asociado'+cuantos+'" size="25"/></td>';
		 //elhtml+='<td><input type="text" name="plata'+cuantos+'" id="plata'+cuantos+'" size="10">';
		 elhtml+= '</td><td><select name="concep_pabs'+cuantos+'" id="concep_pabs'+cuantos+'" onchange="obtenerPabs(asoc'+cuantos+'.value,'+cuantos+',this.value)" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione...</option>';
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
		 elhtml+='<td><input type="text" readonly="readonly" name="doc_y_nom_proveedor'+cuantos+'" id="doc_y_nom_proveedor'+cuantos+'" size="30"/></td>';
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
		/*<input type="checkbox" name="iva'+cuantos+'" id="iva'+cuantos+'" />*/
		elhtml+='<td><input type="hidden" name="cant'+cuantos+'" value="1" id="cant'+cuantos+'" required="requiered"/><input type="text" name="descripcion_pabs'+cuantos+'" id="descripcion_pabs'+cuantos+'" size="15" required="requiered"/></td>';	 
		elhtml+='<td><input pattern="[0-9]+" type="text" name="val'+cuantos+'" id="val'+cuantos+'" onkeypress="return permite(event,"num")" size="8" class="valor" required="required" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>';
		elhtml+='<td><input type="radio" name="eli_fila" id="eli_fila" value="'+cuantos+'" onclick="eliminarFila(this.value);"/></td></tr>';
		$("#tab_pabs").append(elhtml);
		$("#cant_pabs").val(cuantos+1);
}

function ValidaId(id,pos)
{
    if(id.length<=5)
    {
	$.ajax({
            type: "GET",
            url: "llamados/valida_nit.php",
            data: "nit_id="+id,
            success: function(msg)
            {
                if(msg==0)
	  	            {
                    alert("El proveedor ingresado no se encuentra creado en el sistema.");
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
    else
    { alert("El proveedor ingresado no se encuentra creado en el sistema.");document.reg_pabs.gua.disabled=true; }
}

function eliminarFila(cuantos)
{
	$("#tr"+cuantos).remove();
	$("#loseliminados").val(parseInt($("#loseliminados").val())+1);
	
	return false;
}
</script>
<script>
$(document).ready(function(){
   $("#gua").click(function(evento)
   {
	    quitarPuntos();
     	var ano = $("#estAno").val();
        var cadena = document.reg_pabs.mes_sele.value;
    	cadena = cadena.split("-");
    	if(cadena[0]==1)
		{
			$("#reg_pabs").submit(function(){return false;});
			alert("Mes de solo lectura");
		}
		else
		{ 
			/*document.reg_pabs.action = "control/registrarPabs.php";*/
	  		document.reg_pabs.submit();
		}
   });
});
</script>
<form name="reg_pabs" id="reg_pabs" method="post" action="control/guardar_leg_tran.php">
<?php
	$_SESSION['asoc'] = $_POST['asoc'];
	$_SESSION['concep'] = $_POST['concep'];
	$_SESSION['desc'] = $_POST['desc'];
	$_SESSION['bene'] = $_POST['bene'];
	$_SESSION['tip_pag'] = $_POST['tip_pag'];
	$_SESSION['val'] = $_POST['val'];
	$_SESSION['prod'] = $_POST['prod'];
	$_SESSION['cant'] = $_POST['cant'];
?>
  <center>
   <table id="contenedor" border="1">
    <tr>
     <td colspan="2">
      <table id="cab">
       <tr>
        <td>Tipo</td>
        <td><strong><input type="text" readonly="readonly" value="Causacion FABS" size="20"/></strong></td>
        <td align="center">Mes Contable: </td>
        <td><select required="required" name="mes_sele" id="mes_sele" onchange="consecutivo(this.value,11,'trans_id','llamados/inic_mes.php');">
          <option value=''>Seleccione...</option>
          <?php
          while($dat_meses = mssql_fetch_array($meses))
              echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
          ?>  
            </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>  
        </td>
        <td>Fecha</td>
        <td>
        	<input type="text" value="<?php echo date('d-m-Y'); ?>" name="fec_ven" readonly="readonly" required="required" id="fec_ven"/>
        	<a href="javascript:NewCal('fec_ven','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
      	</td>
        
       </tr>
      </table>
     </td>
    </tr>
    <tr>
      <td>Centro Costo<input type="text" name="centro" id="centro" value="<?php echo $res_centro['cen_cos_nombre']; ?>"/>
         <input type="hidden" name="centro_cost" id="centro_cost" value="<?php echo $res_centro['cen_cos_id']; ?>"/></td>
      <td>Consecutivo <input type="text" name="trans_id" id="trans_id" value="" readonly="readonly"  required="required"/><input type="hidden" name="num_oc_fa" id="num_oc_fa" value="<?php echo $conse; ?>" /></td>   
    </tr>
   </table>
 <br /><br />
  <table id="tab_pabs" border="1">
   <tr align="center" id="0"><td colspan="15"><strong>Causacion FABS</strong></td></tr>
   <tr id="1">
     <td>Afiliado</td>
     <td>Documento-Nombres</td>
     <!--<td>Saldo</td>-->
     <td>Concepto</td>
     <td>Proveedor</td>
     <td>Documento-Nombres</td>
     <td>Tipo producto</td>
     <td>Producto</td>
     <!--<td>Iva</td>-->
     <td>Descripcion</td>
     <td>___Valor___</td>
     <td>Eliminar Fila</td>
    </tr>
    <?php 
	
	  $asociados = $nit->con_tip_nit(1);
	  $tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
	  $beneficiarios = $nit->ConProFondo($tipos);
	  $productos = $producto->todosProductos();
	  $lin_pabs = $pabs->lineasPABS();
	  $tip_pago = $pabs->tip_pago(); 
	  $tip_pro = $tipo_producto->cons_tipo_producto(); ?>
       <tr id="tr0" class="sobre">
       <td>
        <input type="text" name="asoc0" id="asoc0" list="aso0" onchange='ObtenerPlata(this.value,0);' size="13" required="required"/><datalist id="aso0">
         <?php
	 	  while($dat_aso = mssql_fetch_array($asociados))
	   			echo "<option value='".$dat_aso['nit_id']."' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."'>"; ?></datalist>
	   </td>
       <td><input type="text" readonly="readonly" name="doc_y_nom_asociado0" id="doc_y_nom_asociado0" size="25"/></td>
       <!--<td><input type="text" name="plata0" id="plata0" size="10"/></td>-->
	   <td>
	       <select name='concep_pabs0' id='concep_pabs0' onchange='obtenerPabs(asoc0.value,1,this.value);' required x-moz-errormessage="Seleccione Una Opcion Valida">
			 <option value=''>Seleccione...</option> <?php
			 while($dat_concep = mssql_fetch_array($lin_pabs))
       {
          if($list_fabs==0 && $dat_concep['pabs_id']!=9)
             echo "<option value='".$dat_concep['pabs_id']."'>".substr($dat_concep['pabs_nombre'],0,16)."</option>";
          elseif($list_fabs==1)
            echo "<option value='".$dat_concep['pabs_id']."'>".substr($dat_concep['pabs_nombre'],0,16)."</option>";
       }
       ?>
		   </select></td>
         <td>
          <input type="text" name="prov0" id="prov0" list="pro0" size="13" required="required" onchange="ValidaId(this.value,0)"><datalist id="pro0">
           <?php while($res_proveedores=mssql_fetch_array($beneficiarios))
				echo "<option value='".$res_proveedores['nit_id']."' label='".$res_proveedores['nits_num_documento']." ".$res_proveedores['nits_nombres']." ".$res_proveedores['nits_apellidos']."'>";
        	?>
          </datalist>
         </td>
         <td><input type="text" readonly="readonly" name="doc_y_nom_proveedor0" id="doc_y_nom_proveedor0" size="30"/></td>
         <td>
		 <?php 
		$html = "";
        $html .= "<select onchange='obtenerFABS(this.value,0)' id='pabs10' name='pabs10' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
		$html .= "<option value=''>Seleccione...</option>";
        while($tip_prod = mssql_fetch_array($tip_pro))       
            $html .= '<option value="'.$tip_prod['tip_pro_id'].'">'.substr($tip_prod['tip_pro_nombre'],0,15).'</option>'; 
        $html .="</select><br><br>";
		echo $html;
	  ?>
      </td>
	  <td><select name="pabs20" id="pabs20" required x-moz-errormessage="Seleccione Una Opcion Valida">
      <option value="">--Seleccione--</option></select><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/></td>
      <td>
      	<input type="hidden" name="cant0" id="cant0" value = "1" required="required"/>
      	<input type='text' name='descripcion_pabs0' id='descripcion_pabs0' size='15' value='' required="required"/></td>
	  <td><input type="text" pattern="[0-9]+" name="val0" id="val0" onkeypress="return permite(event,'num')" size="15" class="valor" required="required" onkeypress="mascara(this,cpf);" onpaste="return false"/>
     </td>
     <td>
     <input type="radio" name="eli_fila" id="eli_fila" value="0" onclick="eliminarFila(this.value);"/>
     </td>
     </tr>
  </table>
  <input type="hidden" name="loseliminados" id="loseliminados" value="0"/>
  <input type="button" class="art-button" value="Nuevo registro" onclick="nuevoPABS()"/>
  <input type="hidden" name="cant_pabs" id="cant_pabs" value="1" />
  <br /><br /><input type="submit" class="art-button" name="gua" id="gua" value="Guardar FABS"  />
  </center>
</form>