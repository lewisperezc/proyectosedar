<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/orden_compra.class.php');
include_once('../clases/transacciones.class.php');
include_once('../clases/concepto.class.php');
include_once('../clases/mes_contable.class.php');

if($_GET['legalizar'])
	$orden = $_GET['legalizar'];  
else
   $orden = $_POST['radio'];	

$ord_com = new orden_compra();
$tran = new transacciones();
$conce = new concepto();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
$orden_compra = $ord_com->bus_Ord($orden);
$productos = $ord_com->bus_ProOrd($orden);
$row = mssql_fetch_array($orden_compra);
$cen =$tran->obtener_concecutivo();
$cue = mssql_fetch_array($cen);
$ejemplo = $cue['max_id'] + 1;//tre el ultimo numro insertado en los consecutivos y suma 1
$_SESSION['ejemplo']=$ejemplo;
$_SESSION['orden']=$orden;
?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../estilos/limpiador.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
<script language="javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script language="javascript" src="../librerias/js/datetimepicker.js"></script>
<script language="javascript">
  function validar()
  {
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	if (document.transaccion.sigla.selectedIndex == 0) 
	  { CamposVacios += "* Documento para transaccion\n"; }
    if (document.transaccion.prov.selectedIndex == 0) 
	  { CamposVacios += "* Proveedor\n"; }
    if (CamposVacios != "")
	  {
		alert(Mensaje + CamposVacios);
		return true;
	  }
	var a = confirm("Est� Seguro que legalizar la orden de compra?");
	if(a)
	{
		var cadena = document.transaccion.mes_sele.value;
    	cadena = cadena.split("-");
	    if(cadena[0]==1)
		{
			$("#transaccion").submit(function(){return false;});
			alert('Mes de solo lectura.');
		}
		else
			document.transaccion.submit();
	}
  }
</script>
<form id="transaccion" name="transaccion" action="../control/guardar_leg_tran.php" method="post" >
   <center>
      <br />
      <table>
       <tr>
        <td>
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
     <table width="624" border="1">
<tr>
        	<td colspan="5"><strong>TRANSACCIONES</strong></td>
  		</tr>
       <tr>
         <td width="177">Transaccion numero</td> 
         <td width="198">Documento para Transaccion</td>
         <td width="112">Proveedor</td>
         <td width="113">Cuenta Ica</td>
       </tr>
       <tr>  
         <td>
             <input type="text" size="5" name="trans_id"  value="TRA <?php echo $ejemplo;?>" readonly="readonly" />         
          </td>
         <td>
           <select name="sigla">
             <option value="0">Seleccione</option> 
             <option value="1">Factura</option> 
           </select>
         </td> 
          <td>
              <select name="prov" id="prov">
                <option value="0">Seleccione...</option>
                <option value="<?php echo $row['nit_id'];?>" carga><?php echo $row['nit_id'];?></option>
              </select>
	     </td>
          <td>
            <select name="select2" size="1" id="select2">
              <option value="<?php echo $row['ord_com_ica']; ?>"><?php echo $row['ord_com_ica']; ?></option>
            </select>
          </td> 
        </tr>
	    <tr>
          <td>Fecha expedicion factura</td>
          <td>Centro de costo</td>
          <td colspan="2">Numero de Documento</td>
       </tr>
         <tr>
           <td>
           <input type="text" name="fecha_fact" id="fecha_fact" value="<?php echo $row['ord_com_fecha']; ?>" readonly="readonly"/>
         </td>
    <td>
      <select name="centro_cost">
        <option value="<?php echo $row['cen_cos_id']; ?>"><?php echo $row['cen_cos_id']; ?></option>
      </select>
    </td>    
   <td colspan="2"><input type="text" name="num_oc_fa" onkeypress="return permite(event, 'num')" value="" /></td>
     <tr>
      <td colspan="4">Fecha Vencimiento</td>
     <tr>
    <td colspan="4">
      <input type="text" name="fec_ven" id="fec_ven" onkeypress="return permite(event, 'car')" readonly="readonly" />
       <a href="javascript:NewCal('fec_ven','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
       </td>
      </tr>
</table>

         <table width="626" align="center" id="productos">
          <tr>
           <td width="100">Numero</td>
           <td width="224">Articulo</td>
           <td width="96"><p>Concepto</p></td>
           <td width="91">Cantidad</td>
           <td width="91">Valor Unidad</td>
          </tr>
     <?php
	 $i=1;
	 while($prod = mssql_fetch_array($productos))
	  {?>
    <tr>
      <td>
        <input type="text" name="ref[<?php echo $i ?>]" id="ref[<?php echo $i ?>]" size="5" value="<?php echo $prod['referencia'] ?>"/>
      </td>
      <td>
      <input type="text" name="productos[<?php echo $i ?>]" id="productos[<?php echo $i ?>]" size="15" value="<?php echo $prod['pro_nombre'] ?>"/>
      <?php
	   $_SESSION['prod'][$i] = $prod['pro_id'];
	  ?>
      <input type="hidden" name="producto[<?php echo $i ?>]" id="producto[<?php echo $i ?>]" value="<?php echo $prod['pro_id'] ?>" />
      </td>
      <td>
       <select name="unidad[<?php echo $i ?>]" id="unidad[<?php echo $i ?>]">
         <?php
           $no_trae="0";
		   $conceptos = $conce->conceptos(121,$no_trae);
		   $concep_pro = $conce->conceProducto($prod['pro_id']);
		   while($row = mssql_fetch_array($conceptos))
		   {
			   if($row['con_id']==$concep_pro)
			     echo "<option value='".$row['con_id']."' selected='selected'>".$row['con_nombre']."</option>";
			   else
			     echo "<option value='".$row['con_id']."'>".$row['con_nombre']."</option>";	 
		   }
		  ?>
       </select>
      </td>
      <td>
        <input type="text" name="cantidad[<?php echo $i ?>]" id="cantidad[<?php echo $i ?>]" size="5" value="<?php echo $prod['cantidad_producto']; ?>"/>
      </td>
      <td>
        <input type="text" name="valor[<?php echo $i ?>]" id="valor[<?php echo $i ?>]" size="5" value="<?php echo $prod['valor_unitario']; ?>"/>
      </td>
     </tr>
    <?php
	$_SESSION['iva_pro'][$i] = $prod['iva'];
	$_SESSION['retencion_pro'][$i] = $prod['retencion'];
	$tot_iva = $tot_iva + $prod['iva'];
	$tot_retencion = $tot_retencion + $prod['retencion'];
	$i++;
	}
	$_SESSION['tot_iva'] = $tot_iva;
	$_SESSION['tot_retencion'] = $tot_retencion;
	  ?>
          </table>
          <table id="guardar">
           <tr>
             <td><input type="hidden" name="cant_gasto" id="cant_gasto" value="<?php echo $i; ?>"/><input type="hidden" name="orden_compra" id="orden_compra" value="1"/><input type="button" class="art-button" name="guardar" id="guardar" value="Guardar"  onclick="validar();"/></td>
           </tr>
         </table>
       </center>
      </form>