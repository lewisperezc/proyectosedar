<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="../estilos/alienar_texto.css">
        <link rel="stylesheet" type="text/css" href="estilos/alienar_texto.css">
        <link rel="stylesheet" type="text/css" href="../estilos/screen.css">
        <link rel="stylesheet" type="text/css" href="estilos/screen.css">
        <title></title>
        <script src="librerias/js/datetimepicker.js"></script>
        <script src="librerias/js/jquery-1.5.0.js"></script>
        <script src="librerias/js/contenedor_empleados.js"></script>
        
        <script src="librerias/js/jquery.js"></script>
        <script src="librerias/js/jquery.validate.js"></script>
        <script src="librerias/js/separador.js"></script>
        
        <script type="text/javascript">
        function recargar(dato,FrmId)
        {
            //alert('los datos en el frm son: '+dato+'__'+FrmId);
            var datos=$("#"+FrmId).serialize();
            $.ajax({
            type: "POST",
            url: "control/guardar_empleado.php",
            data: datos,
            success: function(msg){
                if(msg!="")
                {
                    alert(msg);
                    window.location.reload();
                }
            }
            });
            if(dato!=8)
            {
            //alert('Entra aca!!!');
                ContenedoresEmpleado(dato);
            }
        }
        
        function ObtenerCiudades(dep,opt)
        {
            if(opt==1)
                elcampo="#select2";
            if(opt==2)
                elcampo="#select4";
            $.ajax({
            type: "POST",
            url: "llamados/trae_ciudades.php",
            data: "dep_id="+dep,
            success: function(msg){
            $(elcampo).html(msg);
            }
            });
        }
        
        function ocultar(valor)
        {
            if(valor=='NULL' || valor==''){
                    $("#ciudades").css("display", "none");
                    $("#hospitales").css("display","none");
                    $("#botones").css("display","none");
            }
            if(valor==1){
                    $("#ciudades").css("display", "block");
                    $("#hospitales").css("display","none");
                    $("#botones").css("display","block");
            }
            if(valor==2){
              $("#ciudades").css("display","none");
              $("#hospitales").css("display", "block");
              $("#botones").css("display","block");
            }
        }
        
        $(document).ready(function()
        {
            $("#emp_tip_procedimiento").click(function(evento)
            {
                if($("#emp_tip_procedimiento").val()==2)
                { $("#porcentaje_1").css("display","table-cell");$("#porcentaje_2").css("display","table-cell"); }
                else
                { $("#porcentaje_1").css("display","none");$("#porcentaje_2").css("display","none"); }
            });
        });

        </script>
    </head>
    <body>
    <?php    
    $ano = $_SESSION['elaniocontable'];
    @include_once('../clases/nits.class.php');
    @include_once('clases/nits.class.php');
    @include_once('../clases/departamento.class.php');
    @include_once('clases/departamento.class.php');
    $depto=new departamento();
    $list_deptos_1=$depto->buscar_departamentos();
    $list_deptos_2=$depto->buscar_departamentos();
    $instancia_nits=new nits();
    $con_tip_identificacion=$instancia_nits->con_tip_identificacion();
    $con_est_civil=$instancia_nits->con_est_civil();
    $con_ciudad=$instancia_nits->consultar_ciudades();
    $con_tip_contrato=$instancia_nits->con_tip_contrato();
    $con_per_pago=$instancia_nits->con_per_pago();
    $con_cargos=$instancia_nits->get_perfiles();
    $con_est_nit=$instancia_nits->con_est_nits();
    $con_banco=$instancia_nits->cons_bancos();
    $con_tip_cuenta=$instancia_nits->con_tip_cuenta();
    $con_tip_nit=$instancia_nits->con_tip_nit(7);
    $con_tip_nit_2=$instancia_nits->con_tip_nit(5);
    $pensiones=$instancia_nits->con_tip_nit(11);
    $cesantias=$instancia_nits->con_tip_nit(12);
    $caj_compensacion=$instancia_nits->con_tip_nit(14);
	
	$arl=$instancia_nits->ConsultarTipoArlEmpleado();
	
    $con_cen_cos_ciudad = $instancia_nits->cen_cos_prin();
    $con_cen_cos_hospital = $instancia_nits->cen_cos_sec();
    ?>
        <!--INICIO FORMULARIO 1-->
        <form id="frm_con_emp_1" id="frm_con_emp_1" method="post" onsubmit="Javascript:recargar(1,this.id);return false;">
        <center>
            <div id='cont1'>
                <table class="texto_alineado_izquierda">
                    <tr>
                        <th colspan="4" >CREACI&Oacute;N EMPLEADO</h4></th>
                    </tr>
                    <tr>
                        <th colspan="4">DATOS PERSONALES</th>
                    </tr>
                    <tr>
                        <td>Primer Apellido</td> 
                        <td><input name="emp_pri_apellido" id="emp_pri_apellido" type="text" required/></td>
                        <td>Segundo Apellido</td>
                        <td><input name="emp_seg_apellido" id="emp_seg_apellido" type="text" required/></td>
                    </tr>
                    <tr>
                        <td>Nombres</td>
                        <td><input  name="emp_nombres" id="emp_nombres" type="text" required/></td>
                        <td>Tipo documento</td>
                        <td><select name="emp_tip_documento" id="emp_tip_documento" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_tip_identificacion))
                        {
                        ?>
                            <option value="<?php echo $row['tip_ide_id']; ?>"><?php echo $row['tip_ide_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Numero Documento</td>
                        <td><input type="text" name="emp_num_documento" id="emp_num_documento" required pattern="[0-9]{6,20}" onchange="ValidarDocumentoTercero(this.value,'emp_num_documento','btn1');"/></td>
                        <td>Fecha De Nacimineto</td>
                        <td><input type="date" name="emp_fec_nacimiento" id="emp_fec_nacimiento" required/>
                        <a href="javascript:NewCal('emp_fec_nacimiento','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Genero</td>
                        <td><input  name="emp_genero" id="emp_genero" value="1" type="radio" checked="checked"/>Hombre<input name="emp_genero" id="emp_genero" value="2" type="radio"/>Mujer</td>
                        <td>Estado Civil</td>
                        <td><select name="emp_est_civil" id="emp_est_civil" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_est_civil))
                        {
                        ?>
                            <option value="<?php echo $row['est_civ_id']; ?>"><?php echo $row['est_civ_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Departamento Nacimiento</td>
                        <td><select name='select1' id='select1' required x-moz-errormessage="Seleccione Una Opcion Valida" onChange='ObtenerCiudades(this.value,1)'>
                        <option value=''>--Seleccione--</option>
                        <?php while($row = mssql_fetch_array($list_deptos_1)){ ?>
                        <option value="<?php echo $row['dep_id'];?>"><?php echo strtoupper($row['dep_nombre']);?></option>
                        <?php } ?>
                        </select></td>
                        <td>Ciudad Nacimiento</td>
                        <td><select name="select2" id="select2" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Direccion Residencia</td>
                        <td><input type="text"  name="emp_dir_residencia" id="emp_dir_residencia" required/></td>
                        <td>Telefono Residencia</td>
                        <td><input type="text" name="emp_tel_residencia" id="emp_tel_residencia" required pattern="[0-9]+"/></td>
                    </tr>
                    <tr>
                        <td>Numero Celular</td>
                        <td><input type="text" name="emp_num_celular" id="emp_num_celular" required pattern="[0-9]+"/></td>
                        <td>Correo Electronico</td>
                        <td><input name="emp_cor_electronico" id="emp_cor_electronico" required type="text"/></td>              
                    </tr>
                    <tr>
                        <td>Correo Electronico adicional</td>
                        <td><input type="text" name="emp_cor_electronico_adicional" id="emp_cor_electronico_adicional"/></td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Departamento Residencia</td>
                        <td><select name='select3' id='select3' required x-moz-errormessage="Seleccione Una Opcion Valida" onChange='ObtenerCiudades(this.value,2)'>
                        <option value=''>--Seleccione--</option>
                        <?php while($row = mssql_fetch_array($list_deptos_2)){ ?>
                        <option value="<?php echo $row['dep_id'];?>"><?php echo strtoupper($row['dep_nombre']);?></option>
                        <?php } ?>
                        </select></td>
                        <td>Ciudad Nacimiento</td>
                        <td><select name="select4" id="select4" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                            </select></td>
                    </tr>
                    <tr>
                        <th colspan="4"><input type="submit" class="art-button" id="btn1" name="btn1" value="Siguiente >>"/>
                        </th>
                    </tr>
                </table>
            </div>
        </center>
        </form>
        <!--FIN FORMULARIO 1-->
        <!--INICIO FORMULARIO 2-->
        <form id="frm_con_emp_2" id="frm_con_emp_2" method="post" onsubmit="Javascript:recargar(3,this.id);return false;">
        <center>
            <div id="cont2" style="display:none">
                <table class="texto_alineado_izquierda">
                    <tr>
                        <th colspan="6" >CREACI&Oacute;N EMPLEADO</h4></th>
                    </tr>
                    <tr>
                        <th colspan="6">DATOS CONTRATO</th>
                    </tr>
                    <tr>
                            <td colspan="4"><hr /></td>
                    </tr>
                    <tr>
                        <td>Tipo Contrato</td>
                        <td><select name="emp_tip_contrato" id='emp_tip_contrato' required x-moz-errormessage="Seleccione Una Opcion Valida">
                         <option value="">--Seleccione--</option>
                            <?php
                            while($row = mssql_fetch_array($con_tip_contrato))
                            {
                            ?>
                                <option value="<?php echo $row['tip_con_nit_id']; ?>"><?php echo $row['tip_con_nit_nombre']; ?></option>
                            <?php
                            }
                            ?>
                         </select></td>
                         <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Periodo de pago</td>
                        <td><select name="emp_per_pag_contrato" id='emp_per_pag_contrato' required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_per_pago))
                        {
                        ?>
                            <option value="<?php echo $row['per_pag_nit_id']; ?>"><?php echo $row['per_pag_nit_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select></td>
                        <td>Salario</td>
                        <td><input type="text" name="emp_sal_contrato" id="emp_sal_contrato" required pattern="[0-9]+"/></td>
                    </tr>
                    <tr>
                        <td>Fecha Inicio Contrato</td>
                        <td><input type="text" name="emp_fec_ini_contrato" id="emp_fec_ini_contrato" required/>
                            <a href="javascript:NewCal('emp_fec_ini_contrato','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                        <td>Fecha Final Contrato</td>
                        <td><input type="text" name="emp_fec_fin_contrato" id="emp_fec_fin_contrato" required/>
                        <a href="javascript:NewCal('emp_fec_fin_contrato','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                    </tr>
                    <tr>
                      <td>Aux Transporte</td>
                      <td><input type="text" name="emp_aux_transporte" id="emp_aux_transporte" required pattern="[0-9]+"/></td>
                      <td>Prima Extralegal</td>
                      <td><input type="text" name="bonificacion" id="bonificacion" required pattern="[0-9]+"/></td>
                    </tr>
                    <tr>
                        <td>Cargo</td>
                        <td><select name="emp_cargo" id="emp_cargo" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">Seleccione</option>
                        <?php while($res_cargos=mssql_fetch_array($con_cargos))
                        {
                        ?>
                            <option value="<?php echo $res_cargos['per_id']; ?>"><?php echo $res_cargos['per_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select></td>
                        <td>Estado</td>
                        <td><select name="emp_estado" id="emp_estado" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">Seleccione</option>
                        <?php
                        while($row = mssql_fetch_array($con_est_nit))
                        {
                        ?>
                        <option value="<?php echo $row['nit_est_id']; ?>"><?php echo $row['nit_est_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select>
                      </td>
                    </tr>
                    
                    <tr>
                        <td>Procedimiento</td>
                        <td><select required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida" name="emp_tip_procedimiento" id="emp_tip_procedimiento">
                        <option value="">Seleccione</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        </select></td>
                        <td id="porcentaje_1" style="display:none;">Porcentaje</td>
                        <td id="porcentaje_2" style="display:none;"><input name="emp_por_ret_fuente" id="emp_por_ret_fuente" type="text" size="4" maxlength="2" pattern="[0-9]+"/>%</td>

                        <td>Pagar Aux. Transporte(Si aplica)</td>
                        <td><input type="checkbox" name="emp_pag_aux_transporte" id="emp_pag_aux_transporte" checked /></td>
                        
                    </tr>
                    
                    <tr>
                        <th colspan="4">
                            <input type="button" class="art-button" onClick="ContenedoresEmpleado(2);" id="btn2" name="btn2" value="<< Atras"/>
                            <input type="submit" class="art-button" value="Siguiente >>" id="btn3" name="btn3"/>
                        </th>
                    </tr>
                </table>
            </div>
        </center>    
        </form>
            <!--FIN FORMULARIO 2-->
            <!--INICIO FORMULARIO 3-->
            <form id="frm_con_emp_3" id="frm_con_emp_3" method="post" onsubmit="Javascript:recargar(5,this.id);return false;">
            <center>
                <div id="cont3" style="display:none">
                    <table class="texto_alineado_izquierda">
                    <tr>
                        <th colspan="4" >CREACI&Oacute;N EMPLEADO</h4></th>
                    </tr>
                    <tr>
                        <th colspan="4">DATOS COMPLEMENTARIOS</th>
                    </tr>
                    <tr>
                        <td>Banco</td>
                        <td><select name="emp_banco" id="emp_banco" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_banco))
                        {
                        ?>
                        <option value="<?php echo $row['cod_banco']; ?>"><?php echo strtoupper(substr($row['banco'],0,40)); ?></option>
                        <?php
                        }
                        ?>
                        </select></td>
                        <td>Tipo De Cuenta</td>
                        <td><select name="emp_tip_cuenta" id="emp_tip_cuenta" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccicone--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_cuenta))
                        {
                        ?>
                            <option value="<?php echo $row['tip_cue_ban_id']; ?>"><?php echo $row['tip_cue_ban_nombre']; ?></option>
                        <?php
                        }
                        ?>
                        </select> </td>
                    </tr>
                    <tr>
                        <td>N&uacute;mero Cuenta</td>
                        <td><input name="emp_num_cuenta" id="emp_num_cuenta" type="text" required pattern="[0-9]+"/></td>
                        <td>Caja Compensacion</td>
                        <td>
                        <select name="caja_compensacion" id="caja_compensacion" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($caj_compensacion)){
                        echo "<option value='".$row['nit_id']."'>".strtoupper(substr($row['nits_nombres'],0,40))."</option>";
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>EPS</td>
                        <td><select name="emp_eps" id="emp_eps" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_nit))
                        {
                        ?>
                            <option value="<?php echo $row['nit_id']; ?>"><?php echo $row['nits_nombres']; ?></option>
                        <?php
                        }
                        ?>
                        </select>
                        </td>
                        <td>Fondo de Pensiones y Cesantias</td>
                        <td>
                        <select name="pensiones" id="pensiones" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($pensiones)){
                            echo "<option value='".$row['nit_id']."'>".$row['nits_nombres']."</option>";
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>ARL</td>
                        <td><select name="emp_arp" id="emp_arp" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_nit_2))
                        {
                        ?>
                            <option value="<?php echo $row['nit_id']; ?>"><?php echo $row['nits_nombres']; ?></option>
                        <?php
                        }
                        ?>
                        </select>
                        </td>  
                        <td>Tipo ARL</td>
                        <td><select name="emp_tip_arl" id="emp_tip_arl" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccicone--</option>
                        <?php
                        while($res_arl=mssql_fetch_array($arl))
                        {
                        ?>
                            <option value="<?php echo $res_arl['tip_arl_emp_id']; ?>"><?php echo $res_arl['tip_arl_emp_nombre']." - ".$res_arl['tip_arl_emp_porcentaje']; ?></option>
                        <?php
                        }
            ?>
                        </select></td>  
                    </tr>
                    <tr>
                        <th colspan="4"><input type="button" class="art-button" onClick="ContenedoresEmpleado(4);" id="btn4" name="btn4" value="<< Atras"/>
                        <input type="submit" class="art-button" value="Siguiente >>" id="btn5" name="btn5"/></th>
                    </tr>
                </table>
            </div>
            </center>
            </form>
            <!--FIN FORMULARIO 3-->
            <!--INICIO FORMULARIO 4-->
            <form id="frm_con_emp_4" id="frm_con_emp_4" method="post" onsubmit="Javascript:recargar(8,this.id);return false;">
            <center>
                <div id="cont4" style="display:none">
                <table class="texto_alineado_izquierda">
                    <tr>
                        <th colspan="4" >CREACI&Oacute;N EMPLEADO</h4></th>
                    </tr>
                    <tr>
                        <th colspan="4">DATOS CENTRO DE COSTOS</th>
                      </tr>
                      <tr>
                      <td colspan="4"><hr /></td>
                      </tr>
                          <tr>
                        <th>CIUDAD</th><td><input type="radio" name="opcion" value="1" onclick="ocultar(this.value)"/></td>
                        <th>HOSPITAL</th><td><input type="radio" name="opcion" value="2" onclick="ocultar(this.value)"/></td>
                      </tr>
                </table>
                <table id="hospitales" style="display:none">
                    <tr>
                        <td>HOSPITALES</td>
                    </tr>
                    <tr>
                        <td>
                        <select name="emp_cen_cos_hospital[]" id="emp_cen_cos_hospital[]" size=5 style='width:450px;height:320px;border:solid' multiple="multiple">
                        <?php
                        $i=0;
                        while($row = mssql_fetch_array($con_cen_cos_hospital))
                        {
                        ?>
                            <option value="<?php echo $row['cen_cos_id']; ?>"><?php echo $row['cen_cos_nombre']; ?></option>
                        <?php
                        $i++;
                        }
                        ?> 
                        </select>
                        </td>
                     </tr>
                </table>
                <table id="ciudades" style="display:none">
                      <tr>
                       <td>CIUDADES</td>
                      </tr>
                      <tr>
                            <td>
                              <select name="emp_cen_cos_ciudad[]" id="emp_cen_cos_ciudad[]" size=5 style='width:450px;height:320px;border:solid' multiple="multiple">
                              <?php
                                   $i=0;
                                   while($row = mssql_fetch_array($con_cen_cos_ciudad))
                                   {
                                   ?>
                                        <option value="<?php echo $row['cen_cos_id']; ?>"><?php echo $row['cen_cos_nombre']; ?></option>
                           <?php
                                         $i++;
                                   }
                                   ?> 
                          </select>
                        </td>
                      </tr>
                </table>
                <table id="botones" style="display:none">      
                      <tr>
                       <th><input type="button" class="art-button" onClick="ContenedoresEmpleado(6);" id="btn6" name="btn6" value="<< Atras"/>
                           <input type="button" class="art-button" value="<< Volver al inicio" onClick="ContenedoresEmpleado(7);" id="btn7" name="btn7"/>
                        <input type="submit" class="art-button" value="Guardar Empleado" id="btn8" name="btn8"/>
                       </th>
                    </tr>
                </table>
            </div>
            </center>
            </form>
            <!--INICIO FORMULARIO 4-->
    </body>
</html>