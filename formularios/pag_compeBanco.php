<?php session_start();
  if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
  $ano = $_SESSION['elaniocontable'];
  include_once("../clases/compensacion_nomina.class.php");
  include_once("../clases/pabs.class.php");
  include_once("../clases/mes_contable.class.php");
  @include_once("clases/mes_contable.class.php");
  include_once("../clases/cuenta.class.php");
  include_once('../clases/recibo_caja.class.php');
  include_once('../clases/comprobante.class.php');

  $compensacion = new compensacion_nomina();
  $pabs = new pabs();
  $ins_mes = new mes_contable();
  $cuenta = new cuenta();
  $recibo = new rec_caja();
  $comprobante= new comprobante();
  $fecha=date(d-m-Y);
  $compensaciones = $compensacion->pagoCausacion($ano);
  //$desplazamiento = $pabs->pagFabs();
  $mes = date(m);
  //echo "Aqui es: ".substr($mes,0,1);
  if(substr($mes,0,1)==0)
  	$mes = substr($mes,1);
  
  $conce = $comprobante->cons_comprobante($ano,$mes,25);
  $sig = $comprobante->sig_comprobante(25);
  $comprobante->act_comprobante($ano,$mes,25);
  $sigla = $sig.$conce;
  
  
  
  $meses = $ins_mes->DatosMesesAniosContables($ano);
  
?>

<script src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/js/datetimepicker.js"></script>
<script src="../librerias/js/separador.js"></script>
<script>
$(document).ready(function(){
   $("#boton").click(function(evento)
   {
	 quitarComas();
	 
	if($("#mes_sele").val()=='')
	{
		alert('Debe seleccionar el mes contable.');
	}
	else
	{
		var ano = $("#estAno").val();
	    var cadena = document.pag_compensacion.mes_sele.value;
		cadena = cadena.split("-");
	    if(cadena[0]==1)
		{
			$("#pag_compensacion").submit(function(){return false;});
			alert("Mes de solo lectura.");
		}
		else
		{ 
			document.pag_compensacion.submit();
		}
	}
	 
   });
});
</script>
<script type="text/javascript">
function abreFactura(URL)
    {
	 quitarComas();
	 document.pag_compensacion.action = URL;
	 document.pag_compensacion.submit();
    }
</script>
<form name="pag_compensacion" id="pag_compensacion" method="post" action="../control/gua_pag_compensacion.php">
 <center>
  <table border="1">
	<tr align="center">
		<td colspan="10"><strong>Mes contable</strong></td>
        <td><select required="required" name="mes_sele" id="mes_sele">
        <option value=''>Seleccione</option>
        <?php
        while($dat_meses = mssql_fetch_array($meses))
        	echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
        ?>  
        </select><input type='hidden' name='estAno' id='estAno' value='<?php echo $ins_mes->conAno($ano); ?>'/>  
        </td>
		
	</tr>
  	
  	
  	
  <tr align="center"><td colspan="11"><strong>Compensaciones</strong></td></tr>
   <tr><td><strong>Fichero</strong></td><td><strong>Cedula</strong></td><td><strong>Nombre Afiliado</strong></td></td><td><strong>Fecha de pago</strong></td><td><strong>Banco</strong></td><td><strong>Cuenta Bancaria</strong></td><td><strong>Valor a pagar</strong></td></tr></table>
   <?php
   $i=0;$html="";$p=0;$pa_nit=0;
   while($row=mssql_fetch_array($compensaciones))
   {	   
	$cons_cue_bancarias = $cuenta->cuentas_bancarias();
	if($p==0)
	 {
	  $temp = $row['uno'];
	  echo "<br><table border='1'><tr><td><input name='fichero' id='fichero' type='text' value ='".$sigla."' readonly='readonly' size='6' /></td><td><input type='hidden' name='nit".$i."' id='nit".$i."' value='$row[uno]' />$row[cuatro]</td><td>".$row['dos']." ".$row['tres']."<input type='hidden' name='centro".$i."' id='centro".$i."' value='1169' /></td><td>".date('d-m-Y')."</td><td>$row[seis]</td><td>$row[cinco]</td><td><input type='text' name='valor".$i."' id='valor".$i."' value='".number_format($row['siete'])."' size='10' />";
	  $pa_nit+=$row['siete'];
	  if(empty($row['ocho']))
		echo "<input type='hidden' name='cue".$i."' id='cue".$i."' value='23803001'/>";
	  else
		echo "<input type='hidden' name='cue".$i."' id='cue".$i."' value='$row[ocho]'/>";
	  echo "<input type='hidden' name='tip_cue".$i."' id='tip_cue".$i."' value='1' /></td></tr>";
	  $p++;$i++;
	 }
	 else
	 {
	  if($temp==$row['uno'])
		{
		 $i++;
		 $valor = $row['siete']-$row['des_ane_dinero'];
		 echo "<br><table border='1'><tr><td><input name='fichero' id='fichero' type='text' value ='".$sigla."' readonly='readonly' size='6' /></td><td><input type='hidden' name='nit".$i."' id='nit".$i."' value='$row[uno]' />$row[cuatro]</td><td>".$row['dos']." ".$row['tres']."<input type='hidden' name='centro".$i."' id='centro".$i."' value='1169' /></td><td>".date('d-m-Y')."</td><td>$row[seis]</td><td>$row[cinco]</td><td><input type='text' name='valor".$i."' id='valor".$i."' value='".number_format($row['siete'])."' size='10' />";
		 $pa_nit+=$row['siete'];
		 if(empty($row['ocho']))
		   echo "<input type='hidden' name='cue".$i."' id='cue".$i."' value='23803001'/>";
	     else
			echo "<input type='hidden' name='cue".$i."' id='cue".$i."' value='$row[ocho]'/>";
	  	 echo "<input type='hidden' name='tip_cue".$i."' id='tip_cue".$i."' value='1' /></td></tr>";
		}
	   else
	   {
		  $i++;
		  echo "<tr><td colspan='4'><strong>Total a Pagar</strong></td><td><input type='text' name='valor".$i."' id='valor".$i."' value='".number_format($pa_nit)."'/><input type='hidden' name='fichero".$i."' id='fichero".$i."' value='".$sigla."'/><input type='hidden' name='nit".$i."' id='nit".$i."' value='$temp'/><input type='hidden' name='tip_cue".$i."' id='tip_cue".$i."' value='2' /></td><td><select name='cue".$i."' id='cue".$i."'>";
		  while($cue_banco = mssql_fetch_array($cons_cue_bancarias))
		   {
			if($cue_banco['cue_id']==11100524)
			  echo "<option value='$cue_banco[cue_id]' selected>".substr($cue_banco[cue_nombre],0,15)."</option>";
		    else
		      echo "<option value='$cue_banco[cue_id]'>".substr($cue_banco[cue_nombre],0,15)."</option>";
		   }
		  
		  echo "</select></td></tr><br><hr><br>";
		  $pa_nit=0;$i++;
		  $temp = $row['uno'];
		  $valor = $row['siete']-$row['des_ane_dinero'];
		  
		  echo "<br><table border='1'><tr><td><input name='fichero' id='fichero' type='text' value ='".$sigla."' readonly='readonly' size='6' /></td><td><input type='hidden' name='nit".$i."' id='nit".$i."' value='$row[uno]' />$row[cuatro]</td><td>".$row['dos']." ".$row['tres']."<input type='hidden' name='centro".$i."' id='centro".$i."' value='1169' /></td><td>".date('d-m-Y')."</td><td>$row[seis]</td><td>$row[cinco]</td><td><input type='text' name='valor".$i."' id='valor".$i."' value='".number_format($row['siete'])."' size='10' />";
		  $pa_nit+=$valor;
	  	  
		  if(empty($row['ocho']))
			echo "<input type='hidden' name='cue".$i."' id='cue".$i."' value='23803001'/>";
	  	  else
			echo "<input type='hidden' name='cue".$i."' id='cue".$i."' value='$row[ocho]'/>";
	      echo "<input type='hidden' name='tip_cue".$i."' id='tip_cue".$i."' value='1' /></td></tr>";
	   }
	 }
	 $ultimo_nit=$row[uno];
   }
   $i++;
    $cons_cue_bancarias = $cuenta->cuentas_bancarias();
   echo "<tr><td colspan='3'><strong>Total a Pagar</strong></td>
		  <td><input type='text' name='valor".$i."' id='valor".$i."' value='".number_format($pa_nit)."'/>
		  <input type='hidden' name='fichero".$i."' id='fichero".$i."' value='".$sigla."'/>
		  <input type='hidden' name='nit".$i."' id='nit".$i."' value='$ultimo_nit'/><input type='hidden' name='tip_cue".$i."' id='tip_cue".$i."' value='2' /></td>
		  <td><select name='cue".$i."' id='cue".$i."'>";
		  while($cue_banco = mssql_fetch_array($cons_cue_bancarias))
			{echo "<option value='$cue_banco[cue_id]'>".substr($cue_banco[cue_nombre],0,15)."</option>";}
		  echo "</select></td></tr></table><br><hr><br>";
		  $pa_nit=0;
   		  echo "<input type='hidden' name='cant' id='cant' value='".($i)."' />";
   ?>
   <tr><td colspan="11" align="center"><input type="button" class="art-button" name="boton" id="boton" value="Pagar" /></td>
   	<td><input type="button" class="art-button" name="imp" id="imp" value="Imprimir PDF" onclick="abreFactura('../reportes_PDF/compensacion.php');" /></td></tr>
   	<td><input type="button" class="art-button" name="imp" id="imp" value="Imprimir EXCEL" onclick="abreFactura('../reportes_EXCEL/compensacion.php');" /></td></tr>
  </table>
 </center>
</form>