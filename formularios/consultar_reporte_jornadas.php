<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
 
 @include_once('clases/reporte_jornadas.class.php');
 @include_once('clases/centro_de_costos.class.php');
 @include_once('clases/mes_contable.class.php');
 @include_once('clases/presupuesto.class.php');
 @include_once('conexion/conexion.php');
 $presupuesto = new presupuesto();
 $cen_cos = new centro_de_costos();
 $cen_sec = $cen_cos->cen_cos_sec();
 $mes = new mes_contable();
 $anos = $presupuesto->obtener_lista_anios();
?>
<script type="text/javascript" src="librerias/js/validacion_num_letras.js" language="javascript"></script>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function ValidaRadio(){
if(!$("input[name=tipo_cliente]:checked").val()) {
	alert('No hay ninguna opcion seleccionada.');
}
}
</script>

<form id="conRep" name="conRep" method="post" action="#">
 <center>
  <table width="402" id="conRepTab">
   <tr>
       <th>Centro de Costo</th>
       <th>A&ntilde;o</th>
       <th>Mes</th>
   </tr>
   <tr>
      <td><select id="com_hospitales" name="com_hospitales" required x-moz-errormessage="Seleccione Una Opcion Valida">
      <option value="">Seleccione...</option>
     <?php 
          while($row = mssql_fetch_array($cen_sec))
	        {
	          echo "<option value='".$row['cen_cos_id']."'>".$row['cen_cos_nombre']."</option>";
	        }
      ?>
      </select>
     </td>
	 <td><select name="ano" id="ano" required x-moz-errormessage="Seleccione Una Opcion Valida">
           <option value="">Seleccione</option>
           <?php
		    for($a=0;$a<sizeof($anos);$a++)
			   echo "<option value='".$anos[$a]."'>".$anos[$a]."</option>";
		   ?>
         </select>
	</td>
	<td>
	  <select id="mes" name="mes" required x-moz-errormessage="Seleccione Una Opcion Valida">
	   <option value="">Seleccione...</option>
	   <option value="01">Enero</option>
       <option value="02">Febrero</option>
       <option value="03">Marzo</option>
       <option value="04">Abril</option>
       <option value="05">Mayo</option>
       <option value="06">Junio</option>
       <option value="07">Julio</option>
       <option value="08">Agosto</option>
       <option value="09">Septiembre</option>
       <option value="10">Octubre</option>
       <option value="11">Noviembre</option>
       <option value="12">Diciembre</option>
	  </select>
	</td>
   </tr>
   <tr>
    <th colspan="3">
     <input type="submit" class="art-button" value="Consultar" id ="bot_con_rep" name="bot_con_rep"/>
    </th>
   </tr>
 </table>
 <br></br>
<!-- <table>
  <tr>
   <td>Numero Contrato</td>
   <td>Numero Factura</td>
  </tr>
  <tr> 
  <td><input type="text" id="tex_contrato" name="tex_contrato" onKeyPress="return permite(event, 'car')"></td>
  <td><input type="text" id="factura" name="factura" onKeyPress="return permite(event, 'num')"/></td>
  </tr>
    <tr>
	  <td colspan="2">
	    <input type="submit" class="art-button" id = "bot_con_rep" name="bot_con_rep"/>
	  </td>
	</tr>
 </table>-->
</center>
</form>

<?php
$mes = $_POST['mes'];
$ano = $_POST['ano'];
$centro = $_POST['com_hospitales'];

 if($mes&&$ano&&$centro)
 {
   //echo "Entra por aca";
   $reporte = new reporte_jornadas();
   $cantidad = $reporte->cant_reportes($centro,$mes,$ano);
   $numero_registros=mssql_num_rows($cantidad);
   ?>
   <form name="rep_jor" id="rep_jor" method="post" action="formularios/mostrar_reporte.php">
   <center>
   <table id="rep">
   <?php
   if($numero_registros>0)
   {
   ?>
      <tr>
	   <th>Factura Asignada</th>
       <th>Reporte de jornadas</th>
       <th>Cantidad jornadas</th>
       <th>Fecha</th>
       <th>Seleccionar</th>
      </tr>
      <?php
	   while($row = mssql_fetch_array($cantidad))
	   {
        echo "<tr><td>".$row['fac_consecutivo']."</td><td>".$row['consecutivo']."</td><td>".$row['suma']."</td><td>".$mes."/".$ano."</td><td>";
		  echo "<input type='radio' name='jor' id='jor' value='".$row['consecutivo']."_".$row['fac_id']."' required />";
		  echo "<input type='hidden' name='factura' id='factura' value='".$row['fac_id']."' />";
		  echo "<input type='hidden' name='tipo_consulta' id='tipo_consulta' value='1' />";
		 echo "</td></tr>";
	   }
	  ?> 
       <tr>
        <th colspan="5"><input type="submit" value="Ver jornadas" class="art-button" id = "bot_env_rep" name="bot_env_rep"/></th>
       </tr>
 <?php
   }
   else
   {
   ?>
   		<tr><th>No se encontraton reportes de jornadas que concuerden con los datos ingresados.</th></tr>
   <?php
   }
   ?>
   </table>
   </center>
   </form>
<?php
 }
?>