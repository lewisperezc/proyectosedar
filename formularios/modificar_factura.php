<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/concepto.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/mes_contable.class.php');

$concepto = new concepto();
$factura = new factura();
$grupo = $concepto->gru_notas();
$mes = new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
$radio = $_POST['rad_mod'];

$facturas = $factura->datFactura($radio);
$fac = mssql_fetch_array($facturas);
$_SESSION["factura"] = $fac['fac_id']; 
?>
<script language="javascript" type="text/javascript" src="../librerias/ajax/select_tipo_concepto.js"></script>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script language="javascript" type="text/javascript" >
 function validar_vacios() 
 {
	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	 var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	 var CamposVacios = "";
	 var monto_fin = document.mod_fac.monto_fin.value;
	 var monto_fac = document.mod_fac.monto_fac.value;
	 var cadena = document.mod_fac.mes_sele.value;
   	 var ano = $("#estAno").val();
     cadena = cadena.split("-");
     if(cadena[0]==1)
	    CamposVacios += "* Mes de solo lectura\n";	 
	 if (document.mod_fac.desc.value == "")
		 CamposVacios += "* Descripcion\n";
	 if (document.mod_fac.monto_fin.value == "")
		 CamposVacios += "* Monto a modificar\n";
	 if ( monto_fac-monto_fin <0 )
		 CamposVacios += "* El monto a modificar no puede ser mayor al monto de la factura\n";
	 if(document.mod_fac.select2.value == 0)
	    CamposVacios += "* Debe seleccionar un concepto\n";
	//SI EN LA VARIABLE CAMPOSVACIONS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	 if (CamposVacios != "")
	 {
 		alert(Mensaje + CamposVacios); 
		return true;
	 }
	document.mod_fac.submit();
 }
</script>

<?php
function genera()
{
    include_once('../clases/concepto.class.php');
	$concepto = new concepto();
	$revisar= $concepto->gru_notas();
	echo "<select name='select1' id='select1' onChange='cargaContenido1(this.id)' >";
	echo "<option value='0'>Seleccione Tipo Concepto</option>";
	 while($cue= mssql_fetch_array($revisar))
		echo "<option value='".$cue['tip_concep_id']."'>".$cue['tip_concep_concecutipo']."--".$cue['tip_concep_nombre']."</option>";
	echo "</select>";
}
?>
<form name="mod_fac" id="mod_fac" method="post" action="../control/modificar_factura.php">
 <center>
  <table>
   <tr>
    <td>Mes Contable: 
      <select name="mes_sele" id="mes_sele">
       <?php
		while($dat_meses = mssql_fetch_array($meses))
		 echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
	  ?>  
      </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
    </td>
   </tr>
  </table>
  <table border="1">
   <tr>
    <td colspan="2">Tipo</td>
    <td><?php genera(); ?></td>
    <td colspan="2">Fecha</td>
    <td><input name="fecha" id="fecha" value="<?php echo date('d-m-Y');?>" readonly="readonly" /></td>
   </tr>
   <tr>
    <td>Concepto Contable</td>
    <td>
      <select name="select2" size="1" disabled="disabled" id="select2" >
        <option value="">Seleccione</option>
       </select>
    </td>
    <td>NIT</td>
    <td> 
	 <?php
	  $nit = new nits();
	  $nits = $nit->consul_nits($fac['fac_nit']);
	  $datNit = mssql_fetch_array($nits);
	 ?>
     <input type="text" name="nit" id="nit" value="<?php echo $datNit['nits_num_documento']; ?>" readonly="readonly" />
    </td>
    <td>Centro de costo</td>
    <td> 
      <?php 
      $centro = new centro_de_costos();
	  $centros = $centro->buscar_centros($fac['fac_cen_cos']);
	  $datCentro = mssql_fetch_array($centros);
	  ?>
     <input type="text" name="nit" id="nit" value="<?php echo $datCentro['cen_cos_nombre']; ?>" readonly="readonly" />      
	</td>
   </tr>
   <tr>
    <td>Descripcion</td>
    <td><input type="text" size="50" name="desc" id="desc" /></td>
    <td>Monto Factura</td>
    <td><input type="text" name="monto_fac" id="monto_fac" value="<?php echo $fac['fac_val_total']; ?>" readonly="readonly" /></td>
    <td>Monto a modificar</td>
    <td><input type="text" name="monto_fin" id="monto_fin" /></td>
   </tr>
  </table>
  <table>
   <tr>
    <td>
     <input type="button" class="art-button" name="imprimir" id="imprimir" value="Guardar" onclick="validar_vacios();" />
    </td>
   </tr>
  </table>
 </center>
</form>