<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
include_once('../clases/contrato.class.php');
include_once('../clases/nits.class.php');
$instancia_contrato = new contrato();
$ins_nits = new nits();

$con_est_contrato=$instancia_contrato->con_est_contrato();
$con_cen_cos=$instancia_contrato->con_cen_cos_es_nit();
$con_est_con_legalizado = $instancia_contrato->con_est_con_legalizado();
$tipos="9,10";
$con_tip_nit=$instancia_contrato->con_tip_nit($tipos);
$con_tip_concepto=$instancia_contrato->con_tip_concepto(122);

$con_tip_nit_2=$instancia_contrato->con_tip_nit($tipos);
$con_tip_concepto_2=$instancia_contrato->con_tip_concepto(122);

$consultar_tipos_contratos=$instancia_contrato->con_tip_con_prestacion();


////////////////////////CONSULTA LOS DATOS DEL CONTRATO////////////////////////
$datos=$_GET['con_id'];
$cortar=explode("-",$datos);
$_SESSION['con_pre_id']=$cortar[0];
$consulta=$instancia_contrato->consultar_un_contrato_externo2($_SESSION['con_pre_id']);
$adi=$instancia_contrato->ConAdiOtrPorContrato($_SESSION['con_pre_id']);
$con_dat_contrato=mssql_fetch_array($consulta);

$con_pol_contrato=$instancia_contrato->consultar_poliza_o_impuesto($_SESSION['con_pre_id']);

$con_tip_nit_2=$instancia_contrato->con_tip_nit($tipos);
$con_tip_concepto_2=$instancia_contrato->con_tip_concepto(122);

$con_pol_contrato_informativo=$instancia_contrato->consultar_poliza_o_impuesto_informativo($_SESSION['con_pre_id']);

$con_tod_afiliados=$instancia_contrato->ConAfiConPrestacion($_SESSION['con_pre_id'],1);
///////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
    <script src="librerias/js/datetimepicker.js"></script>
    <script src="librerias/js/jquery-1.5.0.js"></script>
    <script src="librerias/js/contenedor_contratos.js"></script>
    <script src="librerias/js/jquery.js"></script>
    <script src="librerias/js/separador.js"></script>
        
    <script type="text/javascript" language="javascript">
    function recargar2(FrmId)
    {
    	alert($("#cuantos"));
        //alert('los datos en el frm son: '+FrmId);
        var datos=$("#"+FrmId).serialize();
        $.ajax({
        type: "POST",
        url: "../control/actualizar_contrato_prestacion.php",
        data: datos,
        success: function(msg)
        {
            if(msg!="")
            {
                alert(msg);
                window.location.reload();
             }
         }
         });
    }
    
    function HabilitarCampos(num_frm,id_frm,id_btn)
    {
        if(num_frm==0)
        {
            //$("#con_num_consecutivo").removeAttr("disabled");
            //$("#ven_fac").removeAttr("disabled");
            //$("#con_mon_fij_val_hor_diurna").removeAttr("disabled");
            //$("#con_mon_fij_val_hor_nocturna").removeAttr("disabled");
            //$("#fec_legalizado").removeAttr("disabled");
            $("#con_estado").removeAttr("disabled");
            //$("#sel_tip_con_pre_servicios").removeAttr("disabled");
            //$("#observa").removeAttr("disabled");
        }
        $("#"+id_btn).removeAttr("disabled");
    }
    
    function Agr_Fila()
    {
        <?php
        $con_tip_nit_1=$instancia_contrato->con_tip_nit($tipos);
        $con_tip_concepto_1=$instancia_contrato->con_tip_concepto(122);
        ?>
        $("#btn6").removeAttr("disabled");
        var cant=$("#lasfilas > tbody > tr").length-2;
        var filas='<tr><td>Aseguradora</td><td><select name="con_nom_pol_aseguradora'+cant+'" id="con_nom_pol_aseguradora'+cant+'" required><option value="">--Seleccione--</option>';
        <?php
        while($row=mssql_fetch_array($con_tip_nit_1)) { ?>
        filas+= '<option value="<?php echo $row['nit_id']; ?>"><?php echo strtoupper(substr($row['nits_nombres'],0,30)); ?></option>';
        <?php } ?>
        filas+='</select></td>';
        filas+='<td>Poliza o Impuesto</td><td><select name="con_pol_nombre'+cant+'" id="con_pol_nombre'+cant+'" required><option value="">--Seleccione--</option>';
        <?php
        while($row=mssql_fetch_array($con_tip_concepto_1)) { ?>
        filas+= '<option value="<?php echo $row['con_id']; ?>"><?php echo strtoupper(substr($row['con_nombre'],0,30)); ?></option>';
        <?php } ?>
        filas+='</select></td>';
        filas+='<td>Valor</td><td><input type="text" name="con_pol_porcentaje'+cant+'" id="con_pol_porcentaje'+cant+'" required/></td>';
        filas+='<td>Tipo</td><td><select name="tip_pol_impuesto'+cant+'" id="tip_pol_impuesto'+cant+'" required><option value="">--</option>';
        filas+='<option value="1">DESCONTABLE</option><option value="2">INFORMATIVO</option></select></td>';
        filas+='<td>Observaci&oacute;n</td><td><input type="text" name="obs_pol_impuesto'+cant+'" id="obs_pol_impuesto'+cant+'" required/></td></tr>';
        $("#lasfilas").append(filas);
        $("#cant_campos").val(cant);
    }
    
    function EnviarGuardar()
	{
		document.frm_nue_legalizacion.submit();
	}
     </script>
    </head>
    <body>
        <!--INICIO FORMULARIO 1-->
        <form id="con_frm_con_pre_1" name="con_frm_con_pre_1" method="post" onsubmit="Javascript:recargar2(this.id);return false;">
         <center>
             <div id='frm1'>
                <table>
                    <tr>
                        <th colspan="6" >CONSULTA DE CONTRATO POR PRESTACI&Oacute;N DE SERVICIOS</h4></th>
                    </tr>
                    <tr>
                        <th colspan="6">Datos b&aacute;sicos</th>
                    </tr>
                    <tr>
                        <td height="25" colspan="1">Consecutivo</td>
                        <td><input type="text" name="con_num_consecutivo" id="con_num_consecutivo" disabled required value="<?php echo $con_dat_contrato['con_hos_consecutivo']; ?>"/></td>
                        <td >Hospital</td>
                        <td><select name="con_hospital" id="con_hospital" disabled onchange="VerCenCosDisponible(this.value);" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_cen_cos))
                        {
                            if($row['cen_cos_id']==$con_dat_contrato['cen_cos_id'])
                            { echo '<option value="'.$row['cen_cos_id'].'" selected>'.$row['cen_cos_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['cen_cos_id'].'">'.$row['cen_cos_nombre'].'</option>'; }
                        }
                        ?>
                        </select></td>
                    </tr>    
                    <tr>
                        <td>Vigencia Contrato</td>   
                        <td><input name="con_vigencia" id="con_vigencia" disabled type="text"size="3" onChange="calcular_total();CalFecFinal();" required pattern="[0-9.]+" value="<?php echo number_format($con_dat_contrato['con_vigencia']); ?>"/> MESES</td>
                        <td>Valor Contrato</td>
                        <td>$<input name="con_valor" id="con_valor" disabled type="text" onChange="calcular_total();"  onkeypress="mascara(this,cpf);" onpaste="return false" required pattern="[0-9.]+" value="<?php echo number_format($con_dat_contrato['con_valor']); ?>"/></td>
                    </tr>
                    <tr>
                        <td>Valor Factura Mensual</td>
                        <td><input type="text" name="con_cuo_mensual" disabled id="con_cuo_mensual" onkeypress="mascara(this,cpf);" onpaste="return false" required pattern="[0-9.]+" value="<?php echo number_format($con_dat_contrato['con_val_fac_mensual']); ?>"/></td>
                        <td>Dias habiles facutra</td>
                        <td><input type="text" name="ven_fac" disabled id="ven_fac" required pattern="[0-9]+" value="<?php echo $con_dat_contrato['con_fac_vencimiento']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Valor Hora Diurna</td><td><input type="text" disabled name="con_mon_fij_val_hor_diurna" id="con_mon_fij_val_hor_diurna" value="<?php echo $con_dat_contrato['con_val_hor_trabajada']; ?>" required pattern="[0-9.]+" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
                        <td>Valor Hora Nocturna</td><td><input type="text" disabled name="con_mon_fij_val_hor_nocturna" id="con_mon_fij_val_hor_nocturna" required value="<?php echo $con_dat_contrato['con_val_hor_nocturna']; ?>" pattern="[0-9.]+" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
                    </tr>                
                    <tr>
                        <td>Fecha Inicial</td>
                        <td><input type="text" name="con_fec_inicial" value="<?php echo $con_dat_contrato['con_fec_inicio']; ?>" disabled id="con_fec_inicial" onchange="CalFecFinal();"/>
                        <a href="javascript:NewCal('con_fec_inicial','DDMMYYYY')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                        <td>Fecha Final</td>
                        <td><input type="text" name="con_fec_fin" disabled id="con_fec_fin" value="<?php echo $con_dat_contrato['con_fec_fin']; ?>" required onchange="CalFecFinal();"/>
                        <a href="javascript:NewCal('con_fec_fin','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
                    </tr>
                    <tr>
                        <td>Estado Contrato</td>
                        <td><select name="con_estado" id="con_estado" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="" >--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_est_contrato))
                        {
                            if($row['est_con_id']==$con_dat_contrato['est_con_id'])
                            { echo '<option value="'.$row['est_con_id'].'" selected>'.$row['est_con_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['est_con_id'].'">'.$row['est_con_nombre'].'</option>'; }
                        } 
                        ?>                    
                        </select>
                        </td>
                        <td>Fecha Legalizacion</td>
                        <td><input type="text" name="fec_legalizado" disabled id="fec_legalizado" value="<?php echo $con_dat_contrato['con_fec_leg']; ?>" required/>
                        <a href="javascript:NewCal('fec_legalizado','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
                    </tr>
                    <tr>
                        <td>Tipo contrato</td>
                        <td><select name="sel_tip_con_pre_servicios" disabled="disabled" id="sel_tip_con_pre_servicios" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($consultar_tipos_contratos))
                        {
                            if($row['tip_con_pre_id']==$con_dat_contrato['tip_con_pre_id'])
                            { echo '<option value="'.$row['tip_con_pre_id'].'" selected>'.$row['tip_con_pre_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['tip_con_pre_id'].'">'.$row['tip_con_pre_nombre'].'</option>'; }
                        }
                        ?>
                        </select></td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">Observaciones
                        <input type="hidden" name="resp" disabled id="resp"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><textarea name="observa" disabled id="observa" rows="2" cols="100" maxlength="590" required placeholder="Escriba aqui..."><?php echo utf8_decode($con_dat_contrato['con_observacion']); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4"><input name="btn1" id="btn1" type="button" class="art-button" value="<< Atras" onclick="Javascript:history.back(-1);"/>
                        <input name="btn2" id="btn2" onclick="HabilitarCampos(0,'con_frm_con_pre_3','btn3');" type="button" class="art-button" value="Modificar"/>
                        <input name="btn3" id="btn3" type="submit" class="art-button" value="Guardar" disabled/>
                        <input type="hidden" name="num_formulario" id="num_formulario" value="1"/>
                        <input name="btn4" id="btn4" type="button" class="art-button" value="Siguiente >>" onclick="Contenedores(1)"/></th>
                    </tr>
                </table><br><br>
            </div>
            </form>
            <form id="frm_nue_legalizacion" name="frm_nue_legalizacion" method="post" action="../control/guardar_legalizacion_contrato.php">
            <input type="hidden" name="contrato_seleccionado" id="contrato_seleccionado" value="<?php echo $con_dat_contrato['con_id']; ?>"/>
            <input type="hidden" name="otrosi_adicion_seleccionado" id="otrosi_adicion_seleccionado" value="<?php echo $_POST['elvalor']; ?>"/>
        	<center>
            	<table id="todoslosdatos" style="margin:0 auto;"></table>
                <?php 
                $con_sele=$_SESSION['con_pre_id'];
                include_once("consultar_otrosi_adicion.php");
                ?>
			</center>
        	</form>
        <!--FIN FORMULARIO 1-->
        <!--INICIO FORMULARIO 2-->
        <form name="con_frm_con_pre_2" id="con_frm_con_pre_2" method="post" onsubmit="Javascript:recargar2(this.id);return false;">
            <div id="frm2" style="display:none">
            <table id="lasfilas">
                <tr>
                    <th colspan="10">Polizas o Impuestos</th>
                </tr>
                <tr>
                    <th colspan="10"><hr /></th>
                </tr>
                <?php
                while($res_pol_contrato=mssql_fetch_array($con_pol_contrato))
                {
                    $con_tip_nit=$instancia_contrato->con_tip_nit($tipos);
                    $con_tip_concepto=$instancia_contrato->con_tip_concepto(122);
                ?>
                <tr>
                    <td>Aseguradora</td>
                    <td><select name="con_nom_pol_aseguradora0" id="con_nom_pol_aseguradora0" disabled>
                       <option value="">--Seleccione--</option>
                       <?php
                       while($row=mssql_fetch_array($con_tip_nit))
                       {
                           if($row['nit_id']==$res_pol_contrato['nit_id'])
                           { echo '<option value="'.$row['nit_id'].'" selected>'.strtoupper(substr($row['nits_nombres'],0,30)).'</option>'; }
                           else
                           { echo '<option value="'.$row['nit_id'].'">'.strtoupper(substr($row['nits_nombres'],0,30)).'</option>'; }
                       }
                       ?>                    
                    </select></td>
                    <td>Poliza o Impuesto</td>
                    <td><select name="con_pol_nombre0" id="con_pol_nombre0" disabled>
                    <option value="">--Seleccione--</option>
                    <?php
                    while($row=mssql_fetch_array($con_tip_concepto))
                    {
                        if($row['con_id']==$res_pol_contrato['con_id'])
                        { echo '<option value="'.$row['con_id'].'" selected>'.strtoupper(substr($row['con_nombre'],0,30)).'</option>'; }
                        else
                        { echo '<option value="'.$row['con_id'].'">'.strtoupper(substr($row['con_nombre'],0,30)).'</option>'; }
                    }
                    ?>
                    </select></td>
                    <td>Valor</td>
                    <td><input type="text" name="con_pol_porcentaje0" id="con_pol_porcentaje0" disabled onkeypress="mascara(this,cpf);" onpaste="return false" value="<?php echo number_format($res_pol_contrato['con_por_con_porcentaje']); ?>"/></td>
                    <td>Tipo</td>
                    <td>
                    <select name="tip_pol_impuesto0" id="tip_pol_impuesto0" disabled>
                    <option value="">--</option>
                    <option value="1" selected>DESCONTABLE</option>
                    <option value="2">INFORMATIVO</option>
                    </select>
                    </td>
                    <td>Observaci&oacute;n</td><td><input type="text" name="obs_pol_impuesto0" disabled id="obs_pol_impuesto0" value="<?php echo $res_pol_contrato['con_por_con_observacion']; ?>"/></td>
                </tr>
                <?php
                }
                //POLIZAS O IMPUESTOS INFORMATIVOS
                while($res_pol_con_informativo=mssql_fetch_array($con_pol_contrato_informativo))
                {
                    $con_tip_nit_2=$instancia_contrato->con_tip_nit($tipos);
                    $con_tip_concepto_2=$instancia_contrato->con_tip_concepto(122);
                ?>
                <tr>
                    <td>Aseguradora</td>
                    <td><select name="con_nom_pol_aseguradora0" id="con_nom_pol_aseguradora0" disabled>
                       <option value="">--Seleccione--</option>
                       <?php
                       while($row=mssql_fetch_array($con_tip_nit_2))
                       {
                           if($row['nit_id']==$res_pol_con_informativo['nit_id'])
                           { echo '<option value="'.$row['nit_id'].'" selected>'.strtoupper(substr($row['nits_nombres'],0,30)).'</option>'; }
                           else
                           { echo '<option value="'.$row['nit_id'].'">'.strtoupper(substr($row['nits_nombres'],0,30)).'</option>'; }
                       }
                       ?>                    
                    </select></td>
                    <td>Poliza o Impuesto</td>
                    <td><select name="con_pol_nombre0" id="con_pol_nombre0" disabled>
                    <option value="">--Seleccione--</option>
                    <?php
                    while($row=mssql_fetch_array($con_tip_concepto_2))
                    {
                        if($row['con_id']==$res_pol_con_informativo['con_id'])
                        { echo '<option value="'.$row['con_id'].'" selected>'.strtoupper(substr($row['con_nombre'],0,30)).'</option>'; }
                        else
                        { echo '<option value="'.$row['con_id'].'">'.strtoupper(substr($row['con_nombre'],0,30)).'</option>'; }
                    }
                    ?>
                    </select></td>
                    <td>Valor</td>
                    <td><input type="text" name="con_pol_porcentaje0" id="con_pol_porcentaje0" disabled value="<?php echo number_format($res_pol_con_informativo['con_por_con_porcentaje']); ?>"/></td>
                    <td>Tipo</td>
                    <td>
                    <select name="tip_pol_impuesto0" id="tip_pol_impuesto0" disabled>
                    <option value="">--</option>
                    <option value="1">DESCONTABLE</option>
                    <option value="2" selected>INFORMATIVO</option>
                    </select>
                    </td>
                    <td>Observaci&oacute;n</td><td><input type="text" name="obs_pol_impuesto0" disabled id="obs_pol_impuesto0" value="<?php echo $res_pol_con_informativo['con_por_con_observacion']; ?>"/></td>
                </tr>
                <?php
                }
                ?>
            </table>
            <table>
                <tr>
                    <td colspan="4"><hr /></td>
                </tr>
                <tr>
                    <th><input type="button" class="art-button" name="btn5" id="btn5" value="Agregar" onclick="Agr_Fila();"/>
                        <input type="submit" class="art-button" name="btn6" id="btn6" disabled value="Guardar"/>
                        <input type="hidden" name="cant_campos" id="cant_campos" value="0"></th>
                </tr>
                <tr>
                    <td colspan="4"><hr /></td>
                </tr>
                <tr>
                    <th><input type="button" class="art-button" name="btn7" id="btn7" value="<< Atras" onclick="Contenedores(2);"/>
                        <input type="button" class="art-button" name="btn8" id="btn8" value="Siguiente >>" onclick="Contenedores(3);"/>
                        <input type="hidden" name="num_formulario" id="num_formulario" value="2"/>
                    </th>
                </tr>
            </table>
            </div>
        </form>
        <!--FIN FORMULARIO 2-->
        <!--INICIO FORMULARIO 3-->
        <form name="con_frm_con_pre_3" id="con_frm_con_pre_3" method="post" onsubmit="Javascript:recargar2(this.id);return false;">
        <div id="frm3" style="display:none">
            <table> 
                <tr>
                    <th>Afiliados que pertenecen al centro de costos</th>
                </tr>       
                <tr>
                    <td>
                        <select name="aso_cen_costos[]" id="aso_cen_costos[]" style='width:500px;height:400px;border:solid' multiple="multiple" disabled>
                    <?php
                    while($row=mssql_fetch_array($con_tod_afiliados))
                    {
                        echo '<option value="'.$row['nit_id'].'">'.$row['nits_num_documento']." - ".$row['nits_apellidos']." ".$row['nits_nombres'].'</option>';
                    }
                    ?> 
                  </select>
                  </td>
                </tr>
                <tr>
                    <th>
                        <input name="btn4" id="btn4" type="button" class="art-button" onclick="Contenedores(4);" value="<< Atras"/>
                        <input name="btn5" id="btn5" type="button" class="art-button" onclick="Contenedores(5);" value="<< Regresar al inicio"/>
                        <input type="hidden" name="num_formulario" id="num_formulario" value="3"/>
                    </th>
                </tr>
            </table>
        </div>
         </center>  
        </form>
        <!--FIN FORMULARIO 3-->
    </body>
</html>