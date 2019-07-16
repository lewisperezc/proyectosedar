<?php
  session_start(); 
  include_once('../clases/nits.class.php');
  include_once('../clases/factura.class.php');
  include_once('../clases/contrato.class.php');
  $nit = new nits();
  $factura = new factura();
  $cen_cos = new centro_de_costos();
  $ins_contrato=new contrato();
  $dat_fac = $factura->bus_factura($_SESSION["consecu"]);
  $dat_factura = mssql_fetch_array($dat_fac);
  $_SESSION["consecutivo"] = $_SESSION["consecu"];
  $dat_contrato=$ins_contrato->DiaVenFactura($dat_factura['fac_contrato']);
?>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script language="javascript" type="text/javascript">
function abreFactura(URL,num)
    {
     day = new Date();
	 id = day.getTime();
	 eval("page" + (id+num) + " = window.open(URL, '" + (id+num) + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");	
    }
</script>
<form name="fta" id="fta" action="#" method="post">
      <table id="encabezado" border="1">
       <tr>
        <td><img src="../imagenes/logo_sedar_dentro.png" height="100" width="100" /></td>
        <td>
          <center>
            NIT. 900460519-8<br>
            RESOLUCION DIAN: 320001209289<br>
            FECHA: 2014/11/21<br>
            HABILITA DEL 102 AL 1000<br>
          </center>
        </td>
        <td>
          <strong>FACTURA DE VENTA No. <?php echo $dat_factura['fac_consecutivo']; ?></strong>
        </td>
       </tr>
      </table>
      <?php 
      /********************CIERRE ENCABEZADO*******************/

	  $cen_costo = $dat_factura['fac_cen_cos'];
	  $ano = $_SESSION['elaniocontable'];
	  $mes= date('m');
	  $mes_contable = "0".date('m');
	  
	  if($mes==1)
	     $nombre_mes = "Enero";
	  elseif($mes==2)
	     $nombre_mes = "Febrero";
	  elseif($mes==3)
	     $nombre_mes = "Marzo";
	  elseif($mes==4)
	     $nombre_mes = "Abril";
	  elseif($mes==5)
	     $nombre_mes = "Mayo";
	  elseif($mes==6)
	     $nombre_mes = "Junio";
	  elseif($mes==7)
	     $nombre_mes = "Julio";	 
	  elseif($mes==8)
	     $nombre_mes = "Agosto";
	  elseif($mes==9)
	     $nombre_mes = "Septiembre";
	  elseif($mes==10)
	     $nombre_mes = "Octubre";
	  elseif($mes==11)
	     $nombre_mes = "Noviembre";
	  elseif($mes==12)
	     $nombre_mes = "Diciembre";
	  
	  $fecha = date('d-m-Y');
	  $anio=$_SESSION['elaniocontable'];
	  $_SESSION['fecha']=$dat_factura['fac_fecha'];
	  //tipo de contrato y sus datos
	  $centro_cos = $cen_cos->buscar_centros($cen_costo);
	  $centro = mssql_fetch_array($centro_cos);
	  $_SESSION['ciudad'] = $centro['ciu_nombre'];
	  $val_unitario = $dat_factura['fac_val_unitario'];
	  $val_total = $dat_factura['fac_val_total'];
	  $_SESSION['val_unitario']=$val_unitario;
	  $_SESSION['val_total']=$val_total;
	  
	  $_SESSION['descripcion'] = $dat_factura['fac_descripcion'];
	  $_SESSION['dias'] = $dat_contrato;
	  $dat_hospital = $nit->consul_nits($centro['cen_cos_nit']);
	  $datos_hos = mssql_fetch_array($dat_hospital);
	  $_SESSION['nits_nombres']=$datos_hos['nits_nombres'];
	  $_SESSION['nits_num_documento']= $datos_hos['nits_num_documento'];
	  $_SESSION['nits_dir_residencia']=$datos_hos['nits_dir_residencia'];
	  $_SESSION['nits_tel_residencia']=$datos_hos['nits_tel_residencia'];
	  ?>
      
        <table id="dat_encabe" border="1">
         <tr>
           <td>Fecha: </td>
           <td><?php echo  $_SESSION['fecha']; ?></td>
           <td>Ciudad: </td>
           <td><?php echo $_SESSION['ciudad']; ?></td>
         </tr>
         <tr>
           <td>Cliente: </td>
           <td><?php echo $_SESSION['nits_nombres']; ?></td>
           <td>NIT: </td>
           <td><?php echo  $_SESSION['nits_num_documento']; ?></td>
         </tr>
         <tr>
           <td>Direccion: </td>
           <td><?php echo $_SESSION['nits_dir_residencia']; ?></td>
           <td>Tels</td>
           <td><?php echo $_SESSION['nits_tel_residencia']; ?></td>
         </tr>
         <tr>
           <td colspan="4"><?php echo "LA FACTURA VENCE PASADOS ".$_SESSION['dias']." DIAS CALENDARIO"; ?></td>
         </tr>
        </table>
        <table id="dat_factura" border="1">
         <tr>
          <td>DESCRIPCION</td>
          <td>VALOR UNITARIO</td>
          <td>VALOR TOTAL</td>
         </tr>
         <tr>
		  <td><?php echo $_SESSION['descripcion']; ?></td>
          <td><?php echo round($_SESSION['val_unitario']); ?></td>
          <td><?php echo round($_SESSION['val_total']); ?></td>
         </tr>
        </table>
        <input type="button" name="regresar" id="regresar" value="&larr; Regresar" onclick="Javascript:location.href='../index.php?c=52'" />
  		<input type="button" name="factura" id="factura" value="Imprimir Factura" onclick="abreFactura('../reportes_PDF/factura_febrero.php?contrato=<?php echo $_GET['con_id']; ?>',3)" />
  </form>