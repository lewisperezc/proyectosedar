<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); } 
$ano = $_SESSION['elaniocontable'];

include_once('clases/orden_compra.class.php');
include_once('clases/nits.class.php');
include_once('clases/centro_de_costos.class.php');
include_once('clases/producto.class.php');
include_once('clases/tipo_producto.class.php');
include_once('clases/moviminetos_contables.class.php');
include_once('clases/cuenta.class.php');
include_once('clases/comprobante.class.php');

$comprobante= new comprobante();
$nits=new nits();
$centro=new centro_de_costos();
$producto=new producto();
$form=new movimientos_contables();
$tipo_producto=new tipo_producto();
$cuenta=new cuenta();
$proveedor=$nits->get_proveedores();
$centros=$centro->cen_cos_sec();

?>
<script language="javascript" src="librerias/ajax/select_cueIca.js"></script>
<script language="javascript" type="text/javascript" src="librerias/js/validacion_num_letras.js"></script>
<script language="JavaScript" src ="librerias/js/jquery.js"></script>
<script language="JavaScript" src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/separador.js"></script>
<script src="librerias/js/separador.js"></script>
<script>
function valida_producto(id)
  {
	quitarPuntos();
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	for(var i=0;i<id;i++)
	  { 
	   if(document.productos.select2+i.selectedIndex == 0)
			CamposVacios += "* Producto "+i+"\n";
	   if(document.productos.cantidad+i.selectedIndex == 0)
			CamposVacios += "* cantidad "+i+"\n";
	   if(document.productos.valor+i.selectedIndex == 0)
			CamposVacios += "* valor "+i+"\n";
	  }
	 if (CamposVacios != "")
	     {
		   alert(Mensaje + CamposVacios);
		   return true;
	     }
	   else
	     document.productos.submit(); 	  
}
</script>
<script>
function obtenerP(idt,id){
   $.ajax({
   type: "POST",
   url: "./llamados/productos.php",
   data: "id="+idt+"&id2="+id,
   success: function(msg){
     $("#select2"+id).html(msg);
   }
 });
}
</script>
<script>
function obtenerP1(idt,id){
   $.ajax({
   type: "POST",
   url: "./llamados/productos.php",
   data: "id="+idt+"&id2="+id,
   success: function(msg){
     $("#select3"+id).html(msg);
   }
 });
}
</script>
<script>
function Agregar()
{
	var pos = $("#productos>tbody>tr").length-1;
	<?php
	$tip_pro=$tipo_producto->cons_tipo_producto(); 
	?>
	campo='<tr><td><input type="text" name="ref'+pos+'" id="ref'+pos+'" size="5" value="N/A"/></td>';
	campo+='<td><select onchange="obtenerP(this.value,'+pos+')" id="select1'+pos+'" name="select1'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option>';
	<?php
	while($tip_prod=mssql_fetch_array($tip_pro))
	{
	?>
	campo+='<option value="<?php echo $tip_prod['tip_pro_id'] ?>"><?php echo $tip_prod['tip_pro_nombre']; ?></option>';
	<?php
	}
	?>
	campo+='</select></td>';
	campo+='<td><select name="select2'+pos+'" size="1" id="select2'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option></select></td>';
	campo+='<td><textarea cols="10" name="descr'+pos+'" id="descr'+pos+'" required="required" /></textarea></td>';
	campo+='<td><input type="radio" name="iva'+pos+'" id="iva'+pos+'" /></td>';
	campo+='<td><input type="text" name="cantidad'+pos+'" id="cantidad'+pos+'" size="5" required="required"/></td>';
	campo+='<td><input type="text" name="valor'+pos+'" id="valor'+pos+'" size="5" required="required" onkeypress="mascara(this,cpf);" onpaste="return false"/></td></tr>';
	$("#productos").append(campo);
	$("#can_filas").val(pos);
}
</script>
<form name="crear_orden_de_compra" id="crear_orden_de_compra" method="post" action="control/guardar_orden_compra.php">
 <center>
  <table id="enc_pro_cen" align="center">
    <tr>
	 <td>Centro costo</td>
	 <td>Nombre</td>
	 <td>Proveedor</td>
	 <td>Nombre</td>
     <td>Cuenta Ica</td>
	</tr>
	<tr>
	  <td>
       <input type="text" name="centro" id="centro" list="centro0" required="required" onchange="ValidaIdCentro(this.value,'centro','nombre_centro_costo','guardar')">
          <datalist id="centro0">
          <?php
           while($cen_cos = mssql_fetch_array($centros))
              echo "<option value='".$cen_cos['cen_cos_id']."' label='".$cen_cos['cen_cos_nombre']."'>";
          ?>
          </datalist>
     </td>
     <td><input type="text" name="nombre_centro_costo" id="nombre_centro_costo" readonly="readonly" /></td>
     
     <td>
       <input type="text" name="proveedor" id="proveedor" list="proveedor0" required="required" onchange="ValidaIdTercero(this.value,'proveedor','nombre_proveedor','guardar')">
          <datalist id="proveedor0">
          <?php
           while($proveedores=mssql_fetch_array($proveedor))
              echo "<option value='".$proveedores['nit_id']."' label='".$proveedores['nits_num_documento']." - ".$proveedores['nits_nombres']."'>";
          ?>
          </datalist>
     </td>
     <td><input type="text" name="nombre_proveedor" id="nombre_proveedor" readonly="readonly" /></td>
  
      <td>
      	<select name="select2" size="1" disabled="disabled" id="select2" required x-moz-errormessage="Seleccione Una Opcion Valida">
        	<option value="">--Seleccione--</option>
        </select>
      </td>
	 </tr>
	</table>
	<table id="productos">
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
	     <td><input type="text" name="ref0" id="ref0" size="5" value="N/A"/></td>
         <td>
		 <?php
		   $tip_pro = $tipo_producto->cons_tipo_producto(); 
		   $html = "";
           $html .= "<select onchange='obtenerP(this.value,0)' id='select10' name='select10' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
		   $html .= "<option value=''>--Seleccione--</option>";
           while($tip_prod=mssql_fetch_array($tip_pro))       
               $html .= '<option value="'.$tip_prod['tip_pro_id'].'">'.$tip_prod['tip_pro_nombre'].'</option>'; 
           $html .="</select>";
		   echo $html;
		  ?>
         </td>
	     <td>
           <select name="select20" id="select20" required x-moz-errormessage='Seleccione Una Opcion Valida'>
		     <option value="">--Seleccione--</option>
		   </select>
	     </td>
		 <td><textarea cols="10" name="descr0" id="descr0" required="required" /></textarea></td>
	     <td><input type="radio" name="iva0" id="iva0" /></td>
         <td><input type="text" name="cantidad0" id="cantidad0" size="5" onkeypress="return permite(event,'num')" required="required"/></td>
         <td><input type="text" name="valor0" id="valor0" size="5" onkeypress="return permite(event,'num')" required="required" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
	 	</tr>
     </table>
     <table id="guardar">
  	 	<tr>
   			<td>
            <input type="button" class="art-button" name="agr" id="agr" value="Agregar" onclick="Agregar();"/>
            <input type="hidden" name="can_filas" id="can_filas"/>
            <input type="submit" class="art-button" name="guardar" id="guardar" value="Guardar"/>
            </td>
   		</tr>
  	</table>
  </center>
</form>