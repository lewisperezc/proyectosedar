<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
$caso = $_GET['c'];
$_SESSION['caso'] = $caso;
//include_once('./clases/transacciones.class.php');//para funcionamiento en angelical_pc
@include_once('../clases/transacciones.class.php');
@include_once('../clases/nits.class.php');
@include_once('clases/transacciones.class.php');
@include_once('clases/nits.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');
@include_once('../clases/tipo_producto.class.php');
@include_once('clases/tipo_producto.class.php');

$tipo_producto = new tipo_producto();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
$cent =new transacciones();
$nits = new nits();
$pro = $nits->get_proveedores();
$trans =$cent->buscar_centro_costos();
$ejecutar =$cent->obtener_concecutivo();
$cue = mssql_fetch_array($ejecutar);
$ejemplo = $cue['max_id'] + 1;//tre el ultimo numro insertado en los consecutivos y suma 1
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
<script language="javascript" src="librerias/js/datetimepicker.js"></script>
<script language="javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>

function valida_blancos()
{
    var cadena = $("#mes_sele").val();
    var ano = $("#estAno").val();
    cadena = cadena.split("-");
    if(cadena[0]==1)
	{
	  alert("Mes de solo lectura.");
 		transaccion.mes_sele.focus();
		return false;
	}
if(document.transaccion.sigla.selectedIndex==0)
 			{
 			alert('seleccione el tipo de documento para latransaccion ');
 			transaccion.sigla.focus();
			return false;
			}
if(document.transaccion.fecha_fact.value==0)
			{
			alert('digite la fecha de la factura');
			transaccion.fecha_fact.focus();
			return false;
			}	

if(document.transaccion.centro_cost.selectedIndex==0)
			{
			alert('seleccione el centro de costos');
			transaccion.centro_cost.focus();
			return false;
			}

			if(document.transaccion.num_oc_fa.value==0)
			{
			alert('DIGITE EL NUMERO DEL DOCUMENTO SELECCIONADO ');
			transaccion.num_oc_fa.focus();
			return false;
			}
			if(document.transaccion.fec_ven.value==0)
			{
			alert('digite la fecha de vencimiento del documento ');
			transaccion.fecha_venc.focus();
			return false;
			}
			if(document.transaccion.num_det.value==0)
			{
			alert('digite el numero de items para hacer el detalle  de la factura ');
			transaccion.num_det.focus();
			return false;
			}	
			else
			document.transaccion.submit();
}
function validar(cant)
  {
	 for(var i=0;i<cant;i++)
	  {
	     if(document.det_tran.ref+i.value == "")
		  { 
			det_tran.ref+i.focus();
			return false;
		  }	
		 else
			document.det_tran.submit()	
	  }
  }

function ica(prove)
 {
   $.ajax({
   type: "POST",
   url: "../llamados/ica.php",
   data: "prove="+prove,
   success: function(msg){$("#select2").html(msg);}
   });
 }
 
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
</script>
<form id="transaccion" name="transaccion" onsubmit="return valida_blancos()" action="" method="post" >
      <center>
      <table>
       <tr>
        <td align="center"><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
        Mes Contable: 
          <select name="mes_sele" id="mes_sele">
          <?php
			  while($dat_meses = mssql_fetch_array($meses))
			    echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
		  ?>  
      </select>    
	  </td>
       </tr>
      </table>
      <table width="792" border="1" align="center">
		<tr>
        	<td colspan="4" align="center"><strong>TELEFONIA</strong></td>
  		</tr>
       <tr align="center">
         <td width="165">Transaccion numero</td> 
         <td width="144">&nbsp;</td>
         <td colspan="2">Proveedor</td>
       </tr>
       <tr align="center">  
         <td>
             <input type="text" size="5" name="trans_id"  value="TRA <?php echo $ejemplo;?>" readonly="readonly" />
         </td>
         <td>&nbsp;</td> 
          <td width="144">
           <select name="prov" id="prov">
            <option value="0">Seleccione...</option>
             <?php
			  while($row = mssql_fetch_array($pro))
                echo "<option value='".$row['nit_id']."' onclick='ica(this.value)'>".$row['nits_nombres']." ".$row['nits_apellidos']."</option>";
            ?>
           </select>
         </td> 
          <td width="143">
            <select name="select2" id="select2">
              <option value="0" onclick="valida();">Selecciona opci&oacute;n...</option>
            </select>
          </td> 
        </tr>
	    <tr align="center">
          <td height="23">Fecha expedicion factura</td>
          <td>Numero de Documento </td>
          <td colspan="2">Centro de costo</td>
       </tr>
       <tr align="center">
         <td>
           <p>
             <input type="text" name="fecha_fact" id="fecha_fact" onkeypress="return permite(event,'car')"   readonly="readonly"  />
             <a href="javascript:NewCal('fecha_fact','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a> <br /> 
             
             <label></label>
           </p></td>
    <td><input type="text" name="num_oc_fa" onkeypress="return permite(event, 'num')" value="" /></td>    
   <td colspan="2"><select name="centro_cost" id="centro_cost">
     <option value="0">Seleccione centro</option>
     <?php
	           while($cue = mssql_fetch_array($trans))
	           {
                 echo "<option value='".$cue['cen_cos_id']."'>".$cue['cen_cos_nombre']."</option>";
	           } 
			   ?>
   </select></td></tr>
     <tr align="center">
      <td>Fecha Vencimiento</td>
      <td>cuatos items desea registrar en esta transaccion</td>
      <td colspan="2">FECHA DE GRABACION</td>
     <tr align="center">
     <td><input type="text" name="fec_ven" id="fec_ven" onkeypress="return permite(event, 'car')" readonly="readonly" />
       <a href="javascript:NewCal('fec_ven','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
    <td><input type="text"  size="4"name="num_det" onkeypress="return permite(event, 'num')" value="" /></td>
    <td colspan="2"><input type="text" name="fech_venc" value="<?php echo date('d/m/y')?>" disabled="disabled"/></td>
      </tr>
  <tr align="center">
    <td colspan="2">
      <input type="button" class="art-button"  value="detalle transaccion" name="guardar2" onclick="valida_blancos();" />
   </td>
    <td colspan="2">
      <input type="button" class="art-button" value="regresar" name="regresar2"  onclick="location.href='//192.168.0.53/contabilidad/index.php?m=2'"/>
   </td>
  </tr>
</table>
</center>
</form>
<!--aca ya se ha efectuado la insercion de trensaccion general,
y hacemos la consulta del max id de la transaccion para que quede la misma identificacion para los detalles de cada transacion
...... -->
  <?php
  $_SESSION["cen_cos"]= $_POST['centro_cost'];
  $_SESSION["num_tra"] = $_POST['trans_id']; 
  $_SESSION["sigla"] = $_POST['sigla']; 
  $_SESSION["prov"] = $_POST['prov']; 
  $_SESSION["fecha_fact"] = $_POST['fecha_fact']; 
  $_SESSION["val_total"] = $_POST['val_total']; 
  $_SESSION["iva"] = $_POST['iva']; 
  $_SESSION["num_oc_fa"] = $_POST['num_oc_fa']; 
  $_SESSION["fec_ven"] = $_POST['fec_ven']; 
  $_SESSION["fech_venc"] = $_POST['fech_venc'];
  $num=$_POST['num_det'];
  $_SESSION['mes_sele'] = $_POST['mes_sele'];
  $_SESSION['me'] = $_POST['mes_sele'];
  if($num)
   {
     ?>
<form id="det_tran" name="det_tran" action="../control/guardar_leg_tran.php" method="post" >
 <center>
    <table width="604" id="productos" border="1">
	  <tr>
	     <td width="30">Ref</td>
	     <td width="85">Tipo de articulo</td>
	     <td width="119">Articulo</td>
         <td width="72">Descripcion</td>
         <td width="20">Iva</td>
         <td width="55">Cantidad</td>
	     <td width="76">Valor Unidad</td>   
	   </tr>
	  <?php 
	  $i=0;
	  $_SESSION["cant"] = $num;
	  while($i<$_SESSION["cant"])
	  {?>  
	   <tr>
	     <td><input type="text" name="ref<?php echo $i ?>" id="ref<?php echo $i ?>" size="5" value="N/A"/></td>
         <td>
		 <?php
		   $tip_pro = $tipo_producto->cons_tipo_producto(); 
		   $html = "";
           $html .= "<select onchange='obtenerP(this.value,".$i.")' id='select1".$i."' name='select1".$i."'>";
		   $html .= "<option value='0'>Seleccione...</option>";
           while($tip_prod = mssql_fetch_array($tip_pro))       
               $html .= '<option value="'.$tip_prod['tip_pro_id'].'">'.$tip_prod['tip_pro_nombre'].'</option>'; 
           $html .="</select><br><br>";
		   echo $html;
		  ?>
         </td>
	     <td>
           <select name="select2<?php echo $i;?>" id="select2<?php echo $i;?>">
		     <option value="0">--Seleccione--</option>
		   </select>
	     </td>
		 <td><textarea cols="10" name="descr<?php echo $i ?>" id="descr<?php echo $i ?>" /></textarea></td>
	     <td><input type="radio" name="iva<?php echo $i ?>" id="iva<?php echo $i ?>" /></td>
         <td><input type="text" name="cantidad<?php echo $i ?>" id="cantidad<?php echo $i ?>" size="5" onkeypress="return permite(event,'num')" /></td>
         <td><input type="text" name="valor<?php echo $i ?>" id="valor<?php echo $i ?>" size="5" onkeypress="return permite(event,'num')"/></td>
	   </tr>
	   <?php 
	   $i++;
	  } 
	  ?>
     </table>
   <table id="guardar">
  <tr>
   <td><input type="button" class="art-button" name="guardar" id="guardar" value="Guardar" onclick="validar(<?php echo $_SESSION["cant"]; ?>)"/></td>
   </tr>
  </table>
  </center>
</form>
  
<?php
   }//fin if
?>
