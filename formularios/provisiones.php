<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<?php
 include_once('clases/mes_contable.class.php');
 $mes = new mes_contable();
 $meses = $mes->DatosMesesAniosContables($ano);
?>
 <script language="javascript" src="../librerias/js/validacion_num_letras.js" ></script>
 <script type="application/javascript" language="javascript">
   function abreFactura(URL)
   {
      day = new Date();
      id = day.getTime();
      eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');"); 
   }

	function provision(valor)
  	{
  		
  		if($("#provi").val()=='')
  		{
  			$("#eje_prov").submit(function(){return false;});
  		}
  		else
  		{
			mes=$("#mes_sele").val().split('-');
			anio_seleccionado=$("#anio_seleccionado").val();
			
	      	var ano = $("#estAno").val();
	      	if(mes[0]==1)
	      	{
	        	alert("Mes de solo lectura.");
	        	return false;
	      	}
	      	else
	      	{
			    $.ajax({
			    type: "POST",
			    url: "llamados/provisiones.php",
			    data: "tipo="+valor+"&mes="+mes[1],
					success: function(msg)
					{
						alert('Provision realizada correctamente.');
						abreFactura("reportes_PDF/causacion_pago.php?sigla="+msg+"&mes="+mes[1]+"&anio="+anio_seleccionado);
					}
			   	}); 
	   		}
   		}
	}
 </script>

 <form name="eje_prov" id="eje_prov" method="post">
   <center>
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
    <br />
    <table id="chequera" border="1">
      <tr>
      <td>Seleccione el tipo de provision</td>
      <td>
        <select required name="provi" id="provi"'>
         <option value="">Seleccione...</option>
         <option value="1">Creditos</option>
         <option value="2">Cartera</option>
        </select>
        <input type='hidden' name='estAno' id='estAno' value='<?php echo $mes->conAno($ano); ?>'/>
        <input type='hidden' name='anio_seleccionado' id='anio_seleccionado' value='<?php echo $_SESSION['elaniocontable']; ?>'/>
      </td>
     </tr>
     <tr><td><input type="submit" value="Ejecutar Provision" onclick="provision(this.value);"/></td></tr>
  </table>        
  </center>          
 </form>