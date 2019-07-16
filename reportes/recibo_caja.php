<?php session_start();
$ano = $_SESSION['elaniocontable'];
include_once('../clases/concepto.class.php');
include_once('../clases/factura.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/cuenta.class.php');

$concepto = new concepto();
$factura = new factura();
$cuentas = new cuenta();
$variables = $cuentas->variables();

$grupo = $concepto->gru_notas();
$radio = $_GET['fac'];
$facturas = $factura->datFactura($radio);
$fac = mssql_fetch_array($facturas);
$_SESSION["factura"] = $fac['fac_id'];
$_SESSION["centro"] = $fac['fac_cen_cos'];
$_SESSION["nit"] = $fac['fac_nit'];
////////////////////////////////////////////////////////
?>

<script language="javascript" type="text/javascript" src="../librerias/ajax/select_tipo_concepto.js"></script>
<script language="javascript" type="text/javascript" >
 function validar_vacios() 
 {
	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	 var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	 var CamposVacios = "";
	 //VALIDAMOS EL CAMPO NOMBRE
	 if (document.mod_fac.desc.value == "")  
		 CamposVacios += "* Descripcion\n";
	 if (document.mod_fac.monto_fin.value == "")  
		 CamposVacios += "* Monto a modificar\n";
	 if (document.mod_fac.monto_fac.value < document.mod_fac.monto_fin.value)
		 CamposVacios += "* El monto a modificar no puede ser mayor al monto de la factura\n";
	 if(document.mod_fac.select2.value == 0)
	    CamposVacios += "* Debe seleccionar un concepto\n";
	//SI EN LA VARIABLE CAMPOSVACIONS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	 if (CamposVacios != "")
	 {
 		alert(Mensaje + CamposVacios); 
		return true;
	 }
	document.mod_fac.action = '../control/guardar_recibo.php';
 }

function val_concepto()
 {
	 if(document.mod_fac.select2.selectedItem == 0)  
		{ 
		 alert("Debe seleccionar un concepto!!");
		 return false;
		}
	 else
	   document.mod_fac.submit();	
 }
</script>

<?php
function genera()
{
    include_once('../clases/concepto.class.php');
	$concepto = new concepto();
	$revisar= $concepto->gru_recibos();
	echo "<select name='select1' id='select1' onChange='cargaContenido(this.id)' >";
	echo "<option value='0'>Seleccione Tipo Producto</option>";
	 while($cue= mssql_fetch_array($revisar))
		echo "<option value='".$cue['tip_concep_id']."'>".$cue['tip_concep_concecutipo']."--".$cue['tip_concep_nombre']."</option>";
	echo "</select>";
}

?>
<form name="mod_fac" id="mod_fac" method="post">
 <center>
  <table width="1015" border="1">
   <tr>
    <td colspan="2">Tipo</td>
    <td><?php genera(); ?></td>
    <td colspan="2">Fecha</td>
    <td><input name="fecha" id="fecha" value="<?php echo date('d-m-Y');?>" readonly="readonly" /></td>
   </tr>
   <tr>
    <td>Descripcion</td>
    <td><input type="text" size="50" name="desc" id="desc" /></td>
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
	  $elcentrodecosto = $_SESSION["centro"];
      $centro = new centro_de_costos();
	  $centros = $centro->buscar_centros($elcentrodecosto);
	  $datCentro = mssql_fetch_array($centros);
	  $_SESSION["centro"] = $elcentrodecosto;
	  ?>
     <input type="text" name="cen_cos" id="cen_cos" value="<?php echo $datCentro['cen_cos_nombre']; ?>" readonly="readonly" />      
	</td>
   </tr>
   <tr>
    <td>Concepto Contable</td>
    <td><select name="select2" size="1" disabled="disabled" id="select2" >
      <option value="0" onclick="val_concepto();">Seleccione</option>
    </select></td>
    <td>Monto Factura</td>
    <td><input type="text" name="monto_fac" id="monto_fac" value="<?php echo $fac['fac_val_total']; ?>" readonly="readonly" /></td>
    <td>Monto Recibo caja</td>
    <td><input type="text" name="monto_fin" id="monto_fin" readonly="readonly" /></td>
   </tr>
  </table>
 </center>
</form>

<?php
  $_SESSION['fecha'] = $_POST['fecha'];
  $_SESSION['nit'] = $_POST['nit'];
  $_SESSION['cen_cos'] = $_SESSION["centro"];
  //echo $_SESSION['cen_cos'];
  $_SESSION['concepto'] = $_POST['select2'];
  $_SESSION['des'] = $_POST['desc'];
  $_SESSION['mon_factura'] = $_POST['monto_fac'];
  $_SESSION['mon_recibo'] = $_POST['monto_fin'];
  
  if($concepto)
  {
	  include_once('../clases/moviminetos_contables.class.php');
	  include_once('../clases/cuenta.class.php');
	  $concep = new movimientos_contables();
	  $cuenta = new cuenta();
	  ?>
<form name="form" id="form" action="../control/guardar_recibo.php" method="post">
        <center>
        <?php
		 $form = $concep->consul_formulas($concepto);
		 if($form)
          {
		   $i = 0;	  
           $row = mssql_fetch_array($form);	
           while($i<=21)
            {
		     $palabras=split(",",$sp);
	         $arre = split(",",$row["for_cue_afecta".$i]);
		     $a = $arre[0];
		     $b = $arre[1];
		     $c = $arre[2]; 
		     $d = $arre[3];
		     if($a != "" && $b != "" && $c != "")
		 	  {
			   $matriz[$i][0]= $a;
			   $matriz[$i][1]= $b;
			   $matriz[$i][2]= $c;
			   $matriz[$i][3]= $d;
			  }
		     $i++;	
		    }//cierra el while
	      }//cierra el if
		?>
         <table>
           <?php
		    $i=1;
            while($i<=sizeof($matriz))
			{
			  $cuen = $cuenta->verificar_existe($matriz[$i][1]);
			  $row = mssql_fetch_array($cuen);	
			  echo "<tr>";
			   echo "<td>Cuenta</td>";
 echo "<td><input type='text' name='cuenta[".$i."]' id='cuenta[".$i."]' value='".$row['cue_id']."-".$row['cue_nombre']."' readonly='readonly' size='50'/></td>";
			   echo "<td>Valor</td>";
			   echo "<td><input type='text' name='valor[".$i."]' id='valor[".$i."]'/></td>";
			  echo "</tr>";
			  $i++;
		    }
		  ?>
         </table>
         <table>
          <tr>
           <td><input type="submit" name="guardar" id="guardar" value="Guardar" /></td>
          </tr>
         </table>
        </center>
       </form>
      <p>
         <?php 
  }
?>