<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<script src="librerias/ajax/select_productos.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
<script language="javascript">
function modificar()
{
	var a = confirm("Esta seguro que desea modificar la factura?");
	if(a)
	{
		document.mos_fac.submit();
	}
}
function imprimir(contrato)
{
	document.mos_fac.action ="./reportes/reimprimir_factura.php?con_id="+contrato;
	document.mos_fac.submit();
}
function recCaja(val)
{
	setTimeout("location.href='./reportes/recibo_caja.php?fac="+val.value+"'", 50);
}
function radicado()
{
	var a = confirm("Esta seguro que desea radicar la factura?");
	if(a)
	{
		var fecha = $("#fec_radicado").val();
		document.mos_fac.action ="./control/guardar_factura.php?radicado=1";
		document.mos_fac.submit();
	}
}

function abreVentana(URL)
{
  day = new Date();
  id = day.getTime();
  eval("page" + id + " = window.open(URL,'"+id+"','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1100,height=500,left = 150,top = 100');");
}

function adelantar(valor)
{
  
	var a = confirm("Esta seguro que desea adelantar la factura?");
		if(a)
		{
		  $.ajax({
			type: "POST",
			url: "./llamados/adelantar.php",
			data: "val="+valor+"&total="+$("#val_factura").val()+"&fac_conse="+$("#fac_conse").val()+"&nit="+$("#fac_nit").val()+"&centro="+$("#fac_centro").val(),
			   success: function(msg){
          
          if(msg==1)
          {
            alert('Factura marcada como anticipo.');

            var mensaje_descuento = confirm("La factura tiene descuentos de glosa?");
            if(mensaje_descuento)
            {
              abreVentana('formularios/agregar_otro_descuento_compensacion.php?factura='+valor);
            }
          }
          else
          {
            if(msg==2)
              var mensaje_descuento = confirm("La factura ya tiene un recibo de caja o ya fue marcada como adelanto, desea ingresar un descuento?");
              if(mensaje_descuento)
              {
                abreVentana('formularios/agregar_otro_descuento_compensacion.php?factura='+valor);
              }
          }
          
			}
		});

	}


}
function anular()
{
	var a = confirm("Esta seguro que desea anular la factura?");
	if(a)
	{
    	document.mos_fac.action ="./control/anular_factura.php";
  		document.mos_fac.submit();
	}
}
</script>
<script type="text/javascript" language="javascript">
 function abreFacturas()
    {
	   $.ajax({
	   type: "POST",
	   url: "llamados/facturasCentro.php",
	   data: "centro="+$("#con_cen_cos").val()+"&mes="+$("#mes_ser").val(),
	   success: function(msg){
       $("#consecutivo").append(msg);}
 		});
    }
</script>
<?php
include_once('clases/centro_de_costos.class.php');
include_once('clases/factura.class.php');
include_once('clases/mes_contable.class.php');
include_once('clases/recibo_caja.class.php');
$ins_rec_caja=new rec_caja();
$centro = new centro_de_costos();
$nucleos = $centro->nucleo();
$cen_costo = $centro->con_cen_por_con_estado(1);
$mes=new mes_contable();
$meses = $mes->DatosMesesAniosContables($ano);
?>
<form name="consultar_tipo_producto" method="post">
	<center>
    	<table>
       <tr>
        <td colspan="4">Consecutivo</td><td><input name="con_fac" id="con_fac" type="text" size="7" list="consecutivo" />
         <datalist id="consecutivo">
         </datalist>
        </td>
       </tr>
       <tr><td colspan="5"><input name="boton" type="submit" class="art-button" value="Consultar factura" /></td></tr>
       </table>	
	</center>
  <br /><br />  
</form>
<?php
$nucleo=$_POST['nucleo'];
$conse = $_POST['con_fac'];
$_SESSION["consecu"] = $conse;
$_SESSION["nucleo"] = $nucleo;

$fac = new factura();
if($conse)
{ 
	$dat_fac = $fac->bus_factura($conse);
	$row = mssql_fetch_array($dat_fac);
	if($row)
	{
		$valor_total_factura=$ins_rec_caja->ValorTotalFactura($row['fac_id']);
		$valor_abonos=$ins_rec_caja->ValorTotalAbonosFactura($row['fac_id']);
		$valor_saldo=$valor_total_factura-$valor_abonos;
	?>
    <form name="mos_fac" id="mos_fac" method="post" action="./formularios/modificar_factura.php">
           <center>
            <table>
             <tr>
              <td>Factura No</td>
              <td>Fecha</td>
              <td>Descripcion</td>
              <td>Fecha Radicado</td>
              <td>Valor Unitario</td>
              <td>Valor abonos</td>
              <td>Saldo</td>
              <td>Modificar</td>
              <td>Anular</td>
              <td>Imprimir</td>
              <td>Pagar como adelanto?</td>
             </tr>
             <?php
			  if($row['fac_estado']==5)
			    echo "<script>alert('La factura esta anulada!!');</script>";
			  else
			  { ?>	
             <tr>
              <td><input type='hidden' name='fac_conse' id='fac_conse' value="<?php echo $row['fac_consecutivo']; ?>" ><?php echo $row['fac_consecutivo']; ?><input type="hidden" name="num_fac" id="num_fac" value="<?php echo $row['fac_id']; ?>"  /></td>
              <td><input type='hidden' name='fac_nit' id='fac_nit' value="<?php echo $row['fac_nit']; ?>" ><?php echo $row['fac_fecha']; ?></td>
              <td><input type='hidden' name='fac_centro' id='fac_centro' value="<?php echo $row['fac_cen_cos']; ?>" ><input type="text" height="4" name="desc" id="desc" value="<?php echo $row['fac_descripcion']; ?>" /></td>
              <td><input type="text" name="fec_radicado" id="fec_radicado" value="<?php echo $row['fac_fec_radicado']; ?>" readonly="readonly"/><a href="javascript:NewCal('fec_radicado','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
        </td>
        <td><input type='hidden' name='val_factura' id='val_factura' value='<?php echo $row['fac_val_unitario']?>'/>
       	<?php echo number_format($valor_total_factura); ?></td>
        <td><?php echo number_format($valor_abonos); ?></td>
        <td><?php echo number_format($valor_saldo); ?></td>
        <td><input name="rad_mod" id="rad_mod" type="radio" value="<?php echo $row['fac_id'] ?>" onClick="modificar();"/></td>
        <td><input name="rad_mod" id="rad_mod" type="radio" value="<?php echo $row['fac_id'] ?>" onClick="anular();"/></td>
        <td><input name="rad_mod" id="rad_mod" type="radio" value="<?php echo $row['fac_id'] ?>" onclick="imprimir(<?php echo $row['fac_contrato']; ?>);"/></td>
        <td><input name="rad_mod" id="rad_mod" type="radio" value="<?php echo $row['fac_id'] ?>" onclick="adelantar(this.value);"/></td>
            </tr>
          <?php } ?>
            <tr><td colspan="11"><input type="button" class="art-button" value="Guardar Radicado" onclick="radicado();" /></tr>
           </table>
          </center>
        </form>
       <?php 
		}
		else
			echo "<script>alert('No existe la factura con el consecutivo.');</script>";
}
?>