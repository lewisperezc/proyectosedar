<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 include_once("../conexion/conexion.php");
 include_once("../clases/centro_de_costos.class.php");
 $nit = new centro_de_costos();
 $cen_cos = $nit->cen_cos_sec();
?>
<script language="javascript" src="../librerias/js/datetimepicker.js"></script>
<script language="javascript" type="text/javascript">  
 function enviar()
	{
		alert("entra a enviar");
        if(document.rep_even.nit.selectedIndex==0)
            alert("Debe seleccionar un hospital");
        else
            document.rep_even.submit();  
    }
</script>
<script type="text/javascript" language="javascript">
  function validar_campos(campos)
	{
	  var suma_jornadas = 0;
	  var bandera = 0;
	  var opcion = document.rep_even.tip_reporte.value;
	  var maxi = 0;
	  <?php 
	    $sql = "select * from centros_costo INNER JOIN contrato on nit_id=cen_cos_nit WHERE cen_cos_id = 67";
		$query = mssql_query($sql);
		$datContrato = mssql_fetch_array($query);
		if($datContrato['tip_con_pre_id'] == 1)
		{ ?>
		  maxi = <?php echo $datContrato['con_val_fac_mensual']; ?>
  <?php }
       else 
	    {?>
	     maxi = <?php echo $datContrato['con_valor']; ?>
  <?php } ?>   
	  if(opcion == 0)
	   {
		   alert("Debe seleccionar el tipo de reporte");
		   return false;
	   }
	  else
	  {
		  if(opcion==1)
		  {
			for(i=0;i<campos;i++)
		      suma_jornadas += parseInt(document.getElementById("num_jornadas"+i).value);
		    document.rep_even.action = '../control/guardar_reporte_jornadas.php';
			document.rep_even.submit(); 
		  }
		  else
		  {
			 for(i=0;i<campos;i++)
	         {
	         if(document.getElementById("num_jornadas"+i).value == '' || document.getElementById("num_jornadas"+i).value >120)
	         {
	          alert("Debe escribir un numero >= 0 y < que 120 en el numero de jornadas "+ (i+1));
			  bandera = 1;
		      f1.num_jornadas+i.focus();
			  return false;
	         }
		     suma_jornadas += parseInt(document.getElementById("num_jornadas"+i).value);
		    }
		    if(bandera==0)
		    {		  
		     if(suma_jornadas > 120)
		      {
				 alert("la suma de los reportes de jornadas no puede ser mayor a 120");
				 return false;
			  }
		     else  
			 { 
			   document.rep_even.action = '../control/guardar_reporte_jornadas.php';
			   document.rep_even.submit();
			 } 
		   }
	   }
     }
  }	
</script>	
<form id="rep_even" name="rep_even" action="crear_reporte_jornadas_por_evento.php" method="post">
 <center>
  <table id="fac_even">
   <tr>
    <td>Concepto: </td> <td colspan="2">FACTURACION POR EVENTO</td>
   </tr>
   <tr> 
    <td>Fecha: </td>
    <td colspan="2"><input name="fecha_fact" id="fecha_fact" type="text" readonly="readonly" />
     <a href="javascript:NewCal('fecha_fact','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
   </tr>
   <tr>
   <td>Nota: </td>
    <td colspan="2">
      <input name="nota" id="nota" type="text" width="250" />
    </td>
   </tr>
   <tr>
    <td>Centro de costo: </td>
    <td>
     <select name="nit" id="nit" required x-moz-errormessage="Seleccione Una Opcion Valida">
      <option value="">Seleccione...</option>
      <?php
	   while($centros = mssql_fetch_array($cen_cos))
		   echo "<option value='".$centros['cen_cos_id']."' onclick='enviar();'>".$centros['cen_cos_nombre']."</option>";
	  ?>
     </select>
    </td>
   </tr>
  </table> 
 </center>
<?php
  	$sel_hosp = $_POST['nit'];
	$fecha = $_POST['fecha_fact'];
	$nota = $_POST['nota'];
	$mes = date('m');
	$ano = $_SESSION['elaniocontable'];
	$_SESSION["hospital"] = $sel_hosp;
	$_SESSION["fecha"] = $fecha;
	$_SESSION["nota"] = $nota;
	$_SESSION["tipo"] = 0;
	if($sel_hosp)
	{  
		   $asociados = $nit->buscar_asociados($sel_hosp);
	       ?>
           <center>
	        <table id="reporte" border="1">
	         <tr>
              <td colspan="2">Tipo reporte</td>
              <td colspan="2">
               <select name="tip_reporte" id="tip_reporte">
                <option value="0">Seleccione...</option>
                <option value="1">Reporte en dinero</option>
                <option value="2">Reporte en jornadas</option>
               </select>
              </td>
             </tr>
             <tr>
		      <td>Identificacion</td>
		      <td>Nombre</td>
		      <td>Estado</td>
		      <td>Novedad</td>
		     </tr>
	       <?php
	         $i=0;
	         while($row = mssql_fetch_array($asociados))
		      {
		          echo "<tr>";
			       echo "<td>".$row['nit_id']."</td>";
			       echo "<td>".$row['nombres']." ".$row['apellidos']."</td>";
			       echo "<td>".$row['estado']."</td>"; 
			       $num_aso[$i] = $row['cen_nit_id'];
				   ?>
			    <td>
<input type="text" name="num_jornadas<?php echo $i; ?>" id="num_jornadas<?php echo $i; ?>" onkeypress="return permite(event, 'num')" size="10" onchange="suma_jornadas(<?php echo $i; ?>);"/></td> 
                <?php
			     echo "</tr>";
			     $i++;
		      } 
		     $_SESSION["num_aso"] = $num_aso;
			 $_SESSION['i'] = $i;
	        ?>
           
           </tr>
	       <tr>
	        <td colspan="4" align="center">
	          <input type="button" class="art-button" value="Guardar" onclick="validar_campos(<?php echo $i; ?>);" />
	        </td>
	       </tr>
	       </table>
           </center>
	     <?php
	    }
  ?>
</form> 