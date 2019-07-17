<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('clases/cuenta.class.php');
  include_once('clases/credito.class.php');
  include_once('clases/moviminetos_contables.class.php');
  include_once('clases/mes_contable.class.php');
  include_once('clases/nits.class.php');
  
  $opcion = $_GET['opc'];
  $cuenta = new cuenta();
  $nit = new credito();
  $inst_mov_contable = new movimientos_contables();
  $tercero = new nits();
  $cue_pagar = $cuenta->cuentas_pagar();
  $nits = $nit->nits_credito();
  $tipos="1,2,3,4,5,6,7,8,9,10,11,12,13,14";
  $mes = new mes_contable();
  $meses = $mes->DatosMesesAniosContables($ano);
  $con_cue_credito=$cuenta->ConsultarCuentasCredito(1);
  
?>
<script language="javascript" src="librerias/js/datetimepicker.js"></script>
<script language="javascript" src="librerias/js/jquery.js"></script>

<script>
function enviar()
{
  if(document.sal_credito.opcion.selectedIndex == 0)
    alert('Seleccione Una Opcion Valida');
  else
     document.sal_credito.submit(); 
}

function obtenerP(si,idt,id){
   $.ajax({
   type: "POST",
   url: "../llamados/chequera.php",
   data: "si="+si+"&id="+idt+"&id2="+id,
   success: function(msg){
     $("#hidden"+id+"").html(msg);}
 });
}
</script>
<script language="javascript">
function validarMes(can)
{
	
	var can_registros=can;
	
	/*var mensaje=confirm("Recuerde que solo quedaran contabilizados los creditos que selecciono en la opcion 'Contabilizar'");
    if(mensaje)
	{*/
    	var cadena=document.sal_credito.mes_sele.value;
      	if(cadena!="")
      	{
      		cadena = cadena.split("-");
      		if(cadena[0]==1)
        		alert("No se puede ingresar mas datos en este mes.");
     		else
     		{
     			///////DESDE AQUI
     			var i=0;
     			var bandera=1;
     			var can_seleccionados=0;
    			while(i<can_registros)
    			{
    				if($("#credito_id"+i).is(':checked'))
    				{
    					if($("#asoc"+i).val()==''||$("#cue_bancaria"+i).val()==''||$("#fec_des_credito"+i).val()==''||$("#fec_con_credito"+i).val()=='')
    					{
    						$("#sal_credito").submit(function(){return false;});
    						bandera++;
    						
    					}
    				can_seleccionados++;
    				}
    				
    				
    			i++;
    			}
    			
    			if(bandera==1 && can_seleccionados==0)
    			{
    				alert("Debe seleccionar por lo menos un registro.");	
    			}
    			else
    			{	
    				if(bandera>1)//NO DEBE HACER NADA PORQUE HAY CAMPOS VACIOS
     				{
     					alert('Todos los campos de los registros seleccionados son obligatorios.');
    					$("#sal_credito").submit(function(){return false;});
      				}
      				else
      				{
      					document.sal_credito.action = 'control/guardar_salida_credito.php'
      					document.sal_credito.submit();
      				}
      			}
     		}
      	}
      	else
      	{
        	alert('Seleccione el mes contable.');
          	document.sal_credito.mes_sele.focus();
      	}
  	/*}
  	else
    	$("#sal_credito").submit(function(){return false;});*/
}

 function unificar(URL)
 {
    day=new Date();
    id=day.getTime();
    eval("page"+id+"=window.open(URL,'"+id+"','toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=900,height=600,left=300,top=112');");
 }
</script>
 <form name="sal_credito" id="sal_credito" method="post" action="#">
   <center>
    <table id="busqueda" border="1">
        <tr><th colspan="12" style="text-align:center">CONTABILIZACI&Oacute;N DE CR&Eacute;DITOS</th></tr>
    <tr>
          <th colspan="12">MES CONTABLE: 
                    <select name="mes_sele" id="mes_sele" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">--Seleccione--</option>
          <?php
        while($dat_meses = mssql_fetch_array($meses))
          echo "<option value='".$dat_meses['mes_estado']."-".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
      ?>  
      </select>    </th>
        </tr>
        <tr>
          <td><b>Nombre</b></td>
          <td width="69"><b>Pagare Num.</b></td>
          <td width="97"><b>Fecha solicitud</b></td>
          <td width="89"><b>Valor a pagar</b></td>
          <td width="42"><b>Nota</b></td>
          <td width="171"><b>Linea credito</b></td>
          <td width="133"><b>Tercero</b></td>
          <!--<td width="133"><b>Cuenta</b></td>-->
          <td width="97"><b>Fecha desmbolso</b></td>
          <td width="97"><b>Fecha contabilizaci&oacute;n</b></td>
          <?php
          if($valor == 1)
             echo "<td><b>Cheque</b></td>"; ?>
          <td width="51"><b>Contabilizar</b></td>
          <!--<td width="51"><b>Unificar creditos</b></td>-->
        </tr>
        <?php
    $i=0;
    $p=0;
    while($result=mssql_fetch_array($nits))
    {
        $cue_ban = $cuenta->cuentas_bancarias();
        $asociados = $tercero->ConProFondo($tipos);
        $con_cue_credito=$cuenta->ConsultarCuentasCredito(1);
      $cre = $result['cre_id'];
      ?>
         <input type="hidden" name="valor<?php echo $result['cre_id']; ?>" id="valor<?php echo $result['cre_id']; ?>"
         value="<?php echo $result['valor']; ?>"/>
      
         <input type="hidden" name="nit<?php echo $result['cre_id']; ?>" id="nit<?php echo $result['cre_id']; ?>"
         value="<?php echo $result['nit']; ?>"/>
             
        <input type="hidden" name="centro<?php echo $result['cre_id']; ?>" id="centro<?php echo $result['cre_id']; ?>"
         value="<?php echo $result['centro']; ?>"/>   
        <tr>
          <td><?php echo $result['nombres']." ".$result['apellidos']; ?></td>
            <td><?php echo $result['cre_id']; ?></td>
            <td><?php echo $result['cre_fec_solicitud']; ?></td>
            <td><?php echo number_format($result['valor']); ?></td>
            <td><?php echo $result['nota']; ?></td>
            <td>
               <input type="text" name="cuenta" id="cuenta" value="<?php echo $result['cue_nombre']; ?>" readonly />
               <input type="hidden" name="cue_cre<?php echo $result['cre_id']; ?>" 
                id="cue_cre<?php echo $result['cre_id']; ?>" value="<?php echo $result['cue_id']; ?>" />
            </td>
            <td>
              <!--<input type="hidden" name="cue_bancaria<?php //echo $result['cre_id']; ?>" id="cue_bancaria<?php //echo $result['cre_id']; ?>" value='23359501')/> -->
              <input type="text" size=25 name="asoc<?php echo $p; ?>" id="asoc<?php echo $p; ?>" list="asoasoc<?php echo $p; ?>" size="13" required="required"/>
              <datalist id="asoasoc<?php echo $p; ?>">
               <?php
                while($dat_aso = mssql_fetch_array($asociados))
                  echo "<option value='".$dat_aso['nit_id']."'' label='".$dat_aso['nits_num_documento']." ".$dat_aso['nits_nombres']." ".$dat_aso['nits_apellidos']."' >";
               ?>
              </datalist>
            </td>
            <!--
            <td>
              <input type="text" size=25 name="cue_bancaria<?php echo $p; ?>" id="cue_bancaria<?php echo $p; ?>" list="cue_ban_lista<?php echo $p; ?>" required="required"/>
              <datalist id="cue_ban_lista<?php echo $p; ?>">
               <?php
                while($res_cue_credito=mssql_fetch_array($con_cue_credito))
                  echo "<option value='".$res_cue_credito['cue_id']."'' label='".$res_cue_credito['cue_nombre']."'/>";
               ?>
              </datalist>
            </td>
            -->
            <td><input type="text" name="fec_des_credito<?php echo $p; ?>" id="fec_des_credito<?php echo $p; ?>" readonly="readonly" required="required"/>
      		<a href="javascript:NewCal('fec_des_credito<?php echo $p; ?>','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
      		</td>
      		
      		<td><input type="text" name="fec_con_credito<?php echo $p; ?>" id="fec_con_credito<?php echo $p; ?>" readonly="readonly" required="required"/>
      		<a href="javascript:NewCal('fec_con_credito<?php echo $p; ?>','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
      		</td>
            
            <?php
       if($valor == 1)
       { ?>
               <td>
              <select name="hidden<?php echo $result['cre_id']?>" id="hidden<?php echo $result['cre_id']?>"></select>
          </td>
             <?php
       } ?>
        <td width="26"><input type="radio" name="credito_id<?php echo $p; ?>" id="credito_id<?php echo $p; ?>" value="<?php echo $result['cre_id']; ?>" required='required'/></td>
        <!--<td width="26"><input type="radio" name="cre_uni<?php echo $p; ?>" id="cre_uni<?php echo $p; ?>" value="<?php echo $result['cre_id']; ?>" onClick='unificar("./llamados/credito_unificar.php?cre_id=<?php echo $result['cre_id']; ?>");'/></td>-->
        </tr>
        <?php
        $p++;
    }
    if($valor==1)
    {
    ?>
      <tr>
            <td colspan="9"><textarea cols="118" rows="2" name="obs_cheque" id="obs_cheque"></textarea></td>
          </tr>
      <?php
    }
    ?>
        <tr>
          <input type='hidden' name='cant' id='cant' value='<?php echo $p; ?>' /> 
          <td colspan="12"><input type="button" class="art-button" name="boton" value="Contabilizar" onclick="validarMes(<?php echo $p; ?>);"/></td>
        </tr>
    </table>        
  </center>          
 </form>
