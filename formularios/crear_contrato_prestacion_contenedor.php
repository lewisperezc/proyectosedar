<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title></title>
        <script src="librerias/js/datetimepicker.js"></script>
        <script src="librerias/js/jquery-1.5.0.js"></script>
        <script src="../librerias/js/jquery-1.5.0.js"></script>
        <script src="librerias/js/contenedor_contratos.js"></script>
        <script src="../librerias/js/contenedor_contratos.js"></script>
        
        <script src="librerias/js/jquery.js"></script>
        <script src="librerias/js/jquery.validate.js"></script>

        <script src="librerias/js/separador.js"></script>
        <script src="../librerias/js/separador.js"></script>
        <script type="text/javascript">
        function recargar2(dato,FrmId)
        {
            //alert('los datos en el frm son: '+dato+'__'+FrmId);
            var datos=$("#"+FrmId).serialize();
            $.ajax({
            type: "POST",
            url: "control/guardar_contrato_prestacion.php",
            data: datos,
            success: function(msg){
                if(msg!="")
                {
					//alert(msg);
                    /*URL='reportes_PDF/sinopsis.php';
                    day = new Date();
                    id = day.getTime();
                    eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");*/
                    window.location.reload();
                }
            }
            });
            if(dato!=6)
            {
                Contenedores(dato);
            }
         }
        
       function calcular_total()
       {
        val_contrato=$("#con_valor").val();val_contrato=val_contrato.split('.').join('');
        meses=$("#con_vigencia").val();meses=meses.split('.').join('');
        total_mensual=val_contrato/meses
        $("#con_cuo_mensual").val(Math.round(total_mensual));
        }
        //DESDE AK

        function VerCenCosDisponible(valor){
            $.ajax({
                type: "POST",
                url: "llamados/verifica.php",
                data: "centro="+valor,
                success: function(msg){
                $("#resp").val(msg);
                genNits(valor);
                }
            });
        }
        
		function CalFecFinal()
       	{		
			var d=parseInt($("#con_vigencia").val())*30;
			var fecha = new Date($("#con_fec_inicial").val());
			
			if(d!=""&&fecha!="")
			{
                var myDate = new Date(fecha + (7 * 24 * 3600 * 1000));
                alert(myDate);
				/*var Fecha = new Date();
				var sFecha = fecha || (Fecha.getDate() + "-" + (Fecha.getMonth() +1) + "-" + Fecha.getFullYear());
				var sep = sFecha.indexOf('-') != -1 ? '-' : '-'; 
				var aFecha = sFecha.split(sep);
				var fecha = aFecha[2]+'-'+aFecha[1]+'-'+aFecha[0];
				fecha= new Date(fecha);
				fecha.setDate(fecha.getDate()+parseInt(d));
				var anno=fecha.getFullYear();
				var mes= fecha.getMonth()+1;
				var dia= fecha.getDate();
				mes = (mes < 10) ? ("0" + mes) : mes;
				dia = (dia < 10) ? ("0" + dia) : dia;
				var fechaFinal = dia+sep+mes+sep+anno;
				$("#con_fec_fin").val(fechaFinal);*/
			}
        }
        
        function genNits(centro)
        {
            $.ajax({
                type: "POST",
                url: "llamados/traeNitsContrato.php",
                data: "centro="+centro,
                success: function(msg){
                $("#aso_cen_costos").html(msg);
                }
            });
        }

        function Agr_Fila()
        {
            <?php
            $tipos="9,10";
            $con_tip_nit_1=$instancia_contrato->con_tip_nit($tipos);
            $con_tip_concepto_1=$instancia_contrato->con_tip_concepto(122);
            ?>
            var cant=$("#lasfilas > tbody > tr").length-1;
            var filas='<tr><td>Aseguradora</td><td><select name="con_nom_pol_aseguradora'+cant+'" id="con_nom_pol_aseguradora'+cant+'"><option value="">--Seleccione--</option>';
            <?php
            while($row=mssql_fetch_array($con_tip_nit_1)) { ?>
            filas+= '<option value="<?php echo $row['nit_id']; ?>"><?php echo substr($row['nits_nombres'],0,30); ?></option>';
            <?php } ?>
            filas+='</select></td>';
            filas+='<td>Poliza o Impuesto</td><td><select name="con_pol_nombre'+cant+'" id="con_pol_nombre'+cant+'"><option value="">--Seleccione--</option>';
            <?php
            while($row=mssql_fetch_array($con_tip_concepto_1)) { ?>
            filas+= '<option value="<?php echo $row['con_id']; ?>"><?php echo substr($row['con_nombre'],0,30); ?></option>';
            <?php } ?>
            filas+='</select></td>';
            filas+='<td>Valor</td><td><input type="text" name="con_pol_porcentaje'+cant+'" id="con_pol_porcentaje'+cant+'" onChange="puntitos(this,this.value.charAt(this.value.length-1),\'llamados/retMoneda.php");\'/></td>';
            filas+='<td>Tipo</td><td><select name="tip_pol_impuesto'+cant+'" id="tip_pol_impuesto'+cant+'"><option value="">--</option>';
            filas+='<option value="1">DESCONTABLE</option><option value="2">INFORMATIVO</option></select></td>';
            filas+='<td>Observaci&oacute;n</td><td><input type="text" name="obs_pol_impuesto'+cant+'" id="obs_pol_impuesto'+cant+'"/></td></tr>';
            $("#lasfilas").append(filas);
            $("#cant_campos").val(cant);
        }
        </script>
    </head>
    <body>
    <?php
    include_once('clases/contrato.class.php');
    include_once('clases/nits.class.php');
    $instancia_contrato = new contrato();
    $ins_nits = new nits();
    $con_tod_afiliados=$ins_nits->con_nit_contrato(1,1,3);
    
    $con_est_contrato = $instancia_contrato->con_est_contrato();
    $con_cen_cos = $instancia_contrato->con_cen_cos_es_nit();
    $con_est_con_legalizado = $instancia_contrato->con_est_con_legalizado();
    $tipos="9,10";
    $con_tip_nit=$instancia_contrato->con_tip_nit($tipos);
    $con_tip_concepto=$instancia_contrato->con_tip_concepto(122);
    $consultar_tipos_contratos=$instancia_contrato->con_tip_con_prestacion();	
    ?>
        <!--INICIO FORMULARIO 1-->
        <form id="frm_con_pre_1" name="frm_con_pre_1" method="post" onsubmit="Javascript:recargar2(1,this.id);return false;">
            <center>
                <div id='frm1'>
                <table>
                    <tr>
                        <th colspan="6" >CREACI&Oacute;N DE CONTRATO POR PRESTACI&Oacute;N DE SERVICIOS</h4></th>
                    </tr>
                    <tr>
                        <th colspan="6">Datos b&aacute;sicos</th>
                    </tr>
                    <tr>
                        <td height="25" colspan="1">Consecutivo</td>
                        <td><input type="text" name="con_num_consecutivo" id="con_num_consecutivo" required/></td>
                        <td >Hospital</td>
                        <td><select name="con_hospital_seleccionado" id="con_hospital_seleccionado" onchange="VerCenCosDisponible(this.value);" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_cen_cos))
                        {
                        ?>
                        <option value="<?php echo $row['cen_cos_id']; ?>"><?php echo $row['cen_cos_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select><input type='hidden' name='hosp' id='hosp' value='' onChange='genNits();'></td>
                    </tr>    
                    <tr>
                        <td>Vigencia Contrato</td>   
                        <td><input name="con_vigencia" id="con_vigencia" type="text"size="3" onChange="calcular_total();" required pattern="[0-9.]+"/> MESES</td>
                        <td>Valor Contrato</td>
                        <td>$<input name="con_valor" id="con_valor" type="text" onChange="calcular_total();" required/></td>
                    </tr>
                    <tr>
                        <td>Valor Factura Mensual</td>
                        <td><input type="text" name="con_cuo_mensual" id="con_cuo_mensual"required/></td>
                        <td>Dias habiles factura</td>
                        <td><input type="text" name="ven_fac" id="ven_fac" required pattern="[0-9]+"/></td>
                    </tr>
                    <tr>
                        <td>Valor Hora Diurna</td><td><input type="text" name="con_mon_fij_val_hor_diurna" id="con_mon_fij_val_hor_diurna" value="0" required/></td>
                        <td>Valor Hora Nocturna</td><td><input type="text" name="con_mon_fij_val_hor_nocturna" id="con_mon_fij_val_hor_nocturna" required value="0"/></td>
                    </tr>                
                    <tr>
                        <td>Fecha Inicial</td>
                        <td><input type="text" name="con_fec_inicial" id="con_fec_inicial" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" required/>
                        <a href="javascript:NewCal('con_fec_inicial','DDMMYYYY')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                        <td>Fecha Final</td>
                        <td><input type="text" name="con_fec_fin" id="con_fec_fin" required onSelect="CalFecFinal();"/>
                        <a href="javascript:NewCal('con_fec_fin','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
                    </tr>
                    <tr>
                        <td>Estado Contrato</td>
                        <td><select name="con_estado" id="con_estado" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="" >--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_est_contrato))
                        {
                        ?>
                            <option value="<?php echo $row['est_con_id']; ?>"><?php echo $row['est_con_nombre']; ?></option>
                        <?php
                        } 
                        ?>                    
                        </select>
                        </td>
                        <td>Fecha Legalizacion</td>
                        <td><input type="text" name="fec_legalizado" id="fec_legalizado" required/>
                        <a href="javascript:NewCal('fec_legalizado','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a></td>
                    </tr>
                    <tr>
                        <td>Tipo contrato</td>
                        <td><select name="sel_tip_con_pre_servicios" id="sel_tip_con_pre_servicios" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($consultar_tipos_contratos))
                        {
                        ?>
                            <option value="<?php echo $row['tip_con_pre_id']; ?>"><?php echo $row['tip_con_pre_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select></td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">Observaciones
                        <input type="hidden" name="resp" id="resp"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><textarea name="con_observaciones" id="con_observaciones" rows="2" cols="100" maxlength="590" required placeholder="Escriba aqui..."></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4"><input name="btn1" id="btn1" type="submit" class="art-button" value="Siguiente >>"/></th>
                    </tr>
                </table>
            </div>
            </center>
        </form>
        <!--FIN FORMULARIO 1-->
        <!--INICIO FORMULARIO 2-->
        <form name="frm_con_pre_2" id="frm_con_pre_2" method="post" onsubmit="Javascript:recargar2(3,this.id);return false;">
            <center>
                <div id="frm2" style="display:none">
            <table id="lasfilas">
                <tr>
                    <td colspan="10"><b>Polizas o Impuestos</b></td>
                </tr>
                <tr>
                    <td colspan="10"><hr /></td>
                </tr>
                <tr>
                    <td>Aseguradora</td>
                    <td><select name="con_nom_pol_aseguradora1" id="con_nom_pol_aseguradora1">
                       <option value="">--Seleccione--</option>
                       <?php
                       while($row = mssql_fetch_array($con_tip_nit))
                       {
                       ?>
                        <option value="<?php echo $row['nit_id']; ?>"><?php echo substr($row['nits_nombres'],0,30); ?></option>
                       <?php
                       }
                       ?>                    
                    </select></td>
                    <td>Poliza o Impuesto</td>
                    <td><select name="con_pol_nombre1" id="con_pol_nombre1">
                    <option value="">--Seleccione--</option>
                    <?php
                    while($row = mssql_fetch_array($con_tip_concepto))
                    {
                    ?>
                        <option value="<?php echo $row['con_id']; ?>"><?php echo substr($row['con_nombre'],0,30); ?></option>
                    <?php
                    }
                    ?>
                    </select></td>
                    <td>Valor</td>
                    <td><input type="text" name="con_pol_porcentaje1" id="con_pol_porcentaje1"/></td>
                    <td>Tipo</td>
                    <td><select name="tip_pol_impuesto1" id="tip_pol_impuesto1">
                    <option value="">--</option>
                    <option value="1">DESCONTABLE</option>
                    <option value="2">INFORMATIVO</option>
                    </select>
                    </td>
                    <td>Observaci&oacute;n</td><td><input type="text" name="obs_pol_impuesto1" id="obs_pol_impuesto1"/></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="4"><hr /></td>
                </tr>
                <tr>
                    <td><input type="button" class="art-button" name="agregar" value="Agregar" onclick="Agr_Fila();"/>
                        <input type="hidden" name="cant_campos" id="cant_campos" value="1">
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><hr /></td>
                </tr>
                <tr>
                    <th><input type="button" class="art-button" name="btn2" id="btn2" value="<< Atras" onclick="Contenedores(2);"/>
                        <input type="submit" class="art-button" name="btn3" id="btn3" value="Siguiente >>"/>
                    </th>
                </tr>
            </table>
            </div>
            </center>
        </form>
        <!--FIN FORMULARIO 2-->
        <!--INICIO FORMULARIO 3-->
        <form name="frm_con_pre_3" id="frm_con_pre_3" method="post" onsubmit="Javascript:recargar2(6,this.id);return false;">
        <center>
            <div id="frm3" style="display:none">
            <table> 
                <tr>
                    <th>Seleccione los afiliado que van a pertenecer al contrato</th>
                </tr>       
                <tr>
                    <td>
                    <select name="aso_cen_costos" id="aso_cen_costos" style='width:500px;height:400px;border:solid' multiple="multiple">
                    <?php
                    while($row = mssql_fetch_array($con_tod_afiliados))
                    {
                        if($_SESSION['aso_cen_costos'] == $row['nit_id'])
                        {
                    ?>
                            <option value="<?php echo $row['nit_id']; ?>" selected><?php echo $row['nits_apellidos']." ".$row['nits_nombres']; ?></option>
                   <?php
                        }
                        else
                        {
                        ?>
                            <option value="<?php echo $row['nit_id']; ?>"><?php echo $row['nits_apellidos']." ".$row['nits_nombres']; ?></option>   
                        <?php
                        }
                    }
                    ?> 
                  </select>
                  </td>
                </tr>
                <tr>
                    <th>
                        <input type="hidden" name="ult_frm" id="ult_frm" value="1"/>
                        <input name="btn4" id="btn4" type="button" class="art-button" onclick="Contenedores(4);" value="<< Atras"/>
                        <input name="btn5" id="btn5" type="button" class="art-button" onclick="Contenedores(5);" value="<< Regresar al inicio"/>
                        <input type="submit" class="art-button" id="btn6" name="btn6" value="Crear Contrato">
                    </th>
                </tr>
            </table>
        </div>
        </center>
        </form>
        <!--FIN FORMULARIO 3-->
    </body>
</html>