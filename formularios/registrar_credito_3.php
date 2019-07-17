<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

	include_once('../clases/credito.class.php');
	include_once('../clases/varios.class.php');
	
	$ins_varios=new varios();
	
	/*$nueva_fecha=$ins_varios->sumar_fecha_a_fecha('08/09/2017',24);
	echo $nueva_fecha;*/
	
	//include_once('../clases/cuenta.class.php');
	//$ins_cuenta=new cuenta();
	$instancia_credito = new credito();
	
	//Inicio captura el ID de la persona que seleccionan
	if($_POST['sel_persona'])
	{
		$_SESSION['sel_persona'] = $_POST['sel_persona'];
	}
	$id_asociado = $_SESSION['sel_persona'];
	//Fin captura el ID de la persona que seleccionan
	$con_linea = $instancia_credito->con_linea(119);
	
	//$con_cuenta=$ins_cuenta->con_cue_pabs(132505);
	$con_cen_cos = $instancia_credito->con_cen_cos_credito($id_asociado,$_SESSION['k_nit_id']);
	$con_tip_des_credito = $instancia_credito->con_tip_des_credito();
	$con_for_liq_credito = $instancia_credito->con_for_liq_credito();
	//$con_tip_nit = $instancia_credito->con_nit_por_id_estado($_SESSION['sel_tip_persona'],1);
	$con_tip_nit = $instancia_credito->con_nit_codeudor($_SESSION['sel_tip_persona'],1,$id_asociado);
	$con_tod_ciudades = $instancia_credito->consultar_ciudades();
	$con_tod_tip_garantia = $instancia_credito->con_tod_tip_garantia();
	
?>
<script src="../librerias/js/datetimepicker.js" language="javascript" type="text/javascript"></script>
<script src="../librerias/js/validacion_num_letras.js" language="javascript" type="text/javascript"></script>
<script src="../librerias/js/separador.js"  language="javascript" type="text/javascript"></script>
<script>
function validar_vacios(registrar_credito)
{

	//CADENA PARA MOSTRAR LOS CAMPOS VACIOS EN UN SOLO MENSAJE
	var Mensaje = "Los Siguientes Campos Son Obligatorios: \n\n";
	var CamposVacios = "";
	//VALIDAMOS EL CAMPO NOMBRE
	if (document.registrar_credito.cre_linea.selectedIndex == 0) 
	{ CamposVacios += "* Linea\n"; }
        if (document.registrar_credito.cre_observacion.value == "") 
	{ CamposVacios += "* Observacion\n"; }
	if (document.registrar_credito.cre_cen_cos.selectedIndex == 0)
	{ CamposVacios += "* Centro de Costo\n"; }
	if (document.registrar_credito.cre_valor.value == "")
	{ CamposVacios += "* Valor\n"; }
	if (document.registrar_credito.cre_dtf.value == "")
	{ CamposVacios += "* DTF\n"; }	
	if (document.registrar_credito.cre_num_cuotas.value == "" || document.registrar_credito.cre_num_cuotas.value == 0)
	{ CamposVacios += "* Numero de Cuotas\n"; }
	if (document.registrar_credito.cre_tip_descuento.selectedIndex == "")
	{ CamposVacios += "* Tipo Descuento\n"; }
	if (document.registrar_credito.cre_for_liquidacion.selectedIndex == 0)
	{ CamposVacios += "* Forma Liquidacion\n"; }
        if (document.registrar_credito.cre_nota.value == "")
	{ CamposVacios += "* Nota\n"; }
    //SI EN LA VARIABLE CAMPOSVACIONS TIENE ALGUN DATO... MOSTRAMOS MENSAJE
	if (CamposVacios != "")
	{
		alert(Mensaje + CamposVacios);
		return true;
	}
	document.registrar_credito.submit();
}
</script>
<script language="javascript" src="../librerias/js/jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function(){
   $("#cre_garantia").click(function(evento){
      if ($("#cre_garantia").attr("checked")){
         $("#result").css("display","block");
      }else{
         $("#result").css("display", "none");
		 $("#casa").css("display", "none");
		 $("#carro").css("display","none");
      }
   });
});
</script>
<script language="javascript">
function probando(val)
{
	if(val=='NULL'){
		$("#casa").css("display", "none");
		$("#carro").css("display","none");
	}
	if(val==1){
		$("#casa").css("display", "none");
		$("#carro").css("display","block");
	}
	if(val==2){
	  $("#carro").css("display","none");
	  $("#casa").css("display", "block");
	}
}

function calcular_nominal(tasa)
{
  var mensual=0;
  mensual=tasa/12
  mensual=Math.round(mensual*100)/100;
  //anual =Math.round((((Math.pow((1+(tasa/100)),(1/12))-1)*12)*100)*100)/100;
  $("#cre_dtf").val(mensual);
}


</script>

<body>

<form name="registrar_credito" id="registrar_credito" method="post" action="registrar_credito_tabla_amortizacion.php" target="frame3">
	<center>
    <table>
      <tr>
          <td colspan="4"><b>Registro de cr&eacute;dito</b></td>
        </tr>
        <tr>
           <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td>Linea</td>
            <td><select name="cre_linea">
              <option value="0">--Seleccione--</option>
            <?php
              while($row = mssql_fetch_array($con_linea))
              echo "<option value='".$row['con_id']."'>".$row['con_nombre']."</option>";
        ?>
            </select></td>
          <td>Centro de Costo</td>
            <td>
             <select name="cre_cen_cos">
              <option value="0">--Seleccione--</option>
               <?php
               while($res = mssql_fetch_array($con_cen_cos))
               {
                $cen = $res['cen_cos_id'];
                $consulta = $instancia_credito->con_cen_cos_credito2($cen);
                  while($resul = mssql_fetch_array($consulta))
                   {
                ?>
                        <option value="<?php echo $resul['cen_cos_id']; ?>"><?php echo $resul['cen_cos_nombre'];?></option>";
                       <?php
                   }
                 }
                 ?>  
             </select> 
            </td>
       </tr>
        <tr>
          <td>Observaci&oacute;n</td>
                <td><input type="text" name="cre_observacion" id="cre_observacion" value="<?php echo $_SESSION['cre_observacion']; ?>"/></td>
    <td>Valor</td>        
            <td><input type="text" name="cre_valor" onKeyPress="permite(event,'num');mascara(this,cpf);" value="<?php echo $_SESSION['cre_valor']; ?>"/></td>
         </tr>
         <tr>
          
            <td>DTF (Tasa Nominal)</td>
            <td><input type="text" name="cre_men" id="cre_men" onKeyPress="return permite(event,'num')" value="<?php echo $_SESSION['cre_men']; ?>" onchange="calcular_nominal(this.value);"/></td>
            <td>Tasa Mensual</td>
            <td><input type="text" name="cre_dtf" id="cre_dtf" readonly="readonly" value="<?php echo $_SESSION['cre_dtf']; ?>"/></td>
          </tr>
          <tr>
            <td>Numero de Cuotas</td>
            <td><input type="text" id="cre_num_cuotas" name="cre_num_cuotas" onKeyPress="return permite(event,'num')" value="<?php echo $_SESSION['cre_num_cuotas']; ?>"/></td>
        </tr>
        <tr>
          <td>Tipo Descuento</td>
            <td><select name="cre_tip_descuento">
              <option value="">--Seleccione--</option>
        <?php
          while($row = mssql_fetch_array($con_tip_des_credito))
      {
    ?>
            <option value="<?php echo $row['tip_des_cre_id']; ?>"><?php echo $row['tip_des_cre_nombre']; ?></option>
        <?php
      }
    ?>
            </select></td>
            <td>Codeudor</td>
            <td><select name="cre_codeudor">
              <option value="NULL">Seleccione</option>
            <?php
            while($row = mssql_fetch_array($con_tip_nit))
      {
      ?>
            <option value="<?php echo $row['nit_id']; ?>" onClick="validar();"><?php echo $row['nits_nombres']." ".$row['nits_apellidos']; ?></option>
        <?php
      }
    ?>
            </select></td>
        </tr>
        <tr>
          <td>Fecha Solicitud</td>
            <td><input type="text" name="cre_fec_solicitud" id="cre_fec_solicitud" value="<?php echo $_SESSION['cre_fec_solicitud']; ?>" readonly />
            <a href="javascript:NewCal('cre_fec_solicitud','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
          	<td>Fecha Primer Pago</td>
            <td><input type="text" name="cre_fec_pri_pago" id="cre_fec_pri_pago" value="<?php echo $_SESSION['cre_fec_pri_pago']; ?>" readonly />
            <a href="javascript:NewCal('cre_fec_pri_pago','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
        </tr>
        <tr>
          <td>Forma Liquidaci&oacute;n</td>
            <td><select name="cre_for_liquidacion" readonly="readonly">
            <option value="0">--Seleccione--</option>
        <?php
          while($row = mssql_fetch_array($con_for_liq_credito))
      		{
      		if($row['for_liq_cre_id']==1)
			{
    ?>
            <option value="<?php echo $row['for_liq_cre_id']; ?>" selected="selected"><?php echo $row['for_liq_cre_nombre']; ?></option>
        <?php
			}
      	}
    ?>
            </select></td>
        </tr>
        <tr>
          <td>Garantia</td>
            <td>Si<input type="checkbox" name="cre_garantia" id="cre_garantia"/></td>
            <td colspan="4">
          <div id="result" style="display: none;">
            Tipo Garantia
              <select name="cre_tip_garantia">
                <option value="NULL" selected="selected" onClick="probando(this.value);">--Seleccione--</option>
                <?php
                while($res_tod_tip_garantia = mssql_fetch_array($con_tod_tip_garantia)){
        ?>
                <option value="<?php echo $res_tod_tip_garantia['tip_gar_id']; ?>" onClick="probando(this.value);"><?php echo $res_tod_tip_garantia['tip_gar_nombres']; ?></option>
                <?php
        }
        ?>
              </select>
          </div>
            </td>
         </tr>
         <tr>
         <td colspan="4">
            <div id="carro" style="display: none;">
            Ciudad Secional Transito
              <select name="cre_sec_tra_carro"><option value="NULL">--Seleccione--</option>
                <?php while($res_tod_ciudades = mssql_fetch_array($con_tod_ciudades)){ ?>
                <option value="<?php echo $res_tod_ciudades['ciu_id']; ?>">
        <?php echo $res_tod_ciudades['ciu_nombre']; ?>
                </option>
                <?php } ?>
                </select>
             N° De Placa <input type="text" name="cre_num_pla_carro"/>
          </div>
          <div id="casa" style="display: none;">
            N° Escritura <input type="text" name="cre_num_esc_casa"/>
            N° Notaria <input type="text" name="cre_num_not_casa"/>
            Fecha Constitución <input type="text" name="cre_fec_con_casa" id="cre_fec_con_casa"/>
            <a href="javascript:NewCal('cre_fec_con_casa','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
          </div>
          </td>
        </tr>
        <tr>
          <td><b>Nota</b></td>
        </tr>
        <tr>
          <td colspan="4"><textarea name="cre_nota" id="cre_nota" cols="100" rows="2"><?php echo $_SESSION['cre_nota']; ?></textarea></td>
        </tr>
        <tr>
          <td colspan="4"><input type="button" class="art-button" name="siguiente" onClick="validar_vacios();" value="Siguiente >>"/></td>
        </tr>
    </table>
  </center>
</form>
</body>