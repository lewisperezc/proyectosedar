<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];


?>
<script src="librerias/ajax/select_productos.js"></script>
<script src="librerias/js/datetimepicker.js"></script>
<script src="librerias/js/jquery-1.5.0.js"></script>
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
    <form name="mos_fac" id="mos_fac" method="post" action="control/guardar_modificacion_factura.php">
           <center>
            <table>
             <tr>
              <td>Factura No</td>
              <td>Fecha</td>
              <td>Descripcion</td>
              <td>Valor Unitario</td>
             </tr>
             <?php
			  if($row['fac_estado']==5)
			    echo "<script>alert('La factura esta anulada.');</script>";
			  else
			  { ?>	
             <tr>
              <td><input type='hidden' name='fac_conse' id='fac_conse' value="<?php echo $row['fac_consecutivo']; ?>" ><?php echo $row['fac_consecutivo']; ?><input type="hidden" name="num_fac" id="num_fac" value="<?php echo $row['fac_id']; ?>"  /></td>
              <td><input type='hidden' name='fac_nit' id='fac_nit' value="<?php echo $row['fac_nit']; ?>" >
              <input type="text" name="fec_factura" id="fec_factura" value="<?php echo $row['fac_fecha']; ?>" readonly="readonly" required="required"/>
              <a href="javascript:NewCal('fec_factura','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
              <td><input type='hidden' name='fac_centro' id='fac_centro' value="<?php echo $row['fac_cen_cos']; ?>" ><input type="text" size="70" name="desc" id="desc" value="<?php echo $row['fac_descripcion']; ?>" /></td>
        <td><input type='hidden' name='val_factura' id='val_factura' value='<?php echo $row['fac_val_unitario']?>'/>
       	<?php echo number_format($valor_total_factura); ?></td>
            </tr>
          <?php } ?>
            <tr><td colspan="11"><input type="submit" class="art-button" value="Guardar modificacion" /></tr>
           </table>
          </center>
        </form>
       <?php 
		}
		else
			echo "<script>alert('No existe la factura con el consecutivo.');</script>";
}
?>