<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="../estilos/limpiador.css">
        <link rel="stylesheet" type="text/css" href="../estilos/alienar_texto.css">
        <link rel="stylesheet" type="text/css" href="../estilos/screen.css">
        <title></title>

        <script src="../librerias/js/datetimepicker.js"></script>
        <script src="../librerias/js/jquery-1.5.0.js"></script>
        <script src="../librerias/js/contenedor_empleados.js"></script>
        
        <script language="javascript" type="text/javascript">
        function recargar(FrmId)
        {
            var datos=$("#"+FrmId).serialize();
            $.ajax({
            type: "POST",
            url: "../control/actualizar_empleado.php",
            data: datos,
            success: function(msg){
                if(msg!="")
                {
                    alert(msg);
                    window.location.reload();
                }
            }
            });
        }
        
        function EliCenCosto(EmpId)
        {
            $.ajax({
            type: "POST",
            url: "../control/eliminar_centro_costo_empleado.php",
            data: "EmpId="+EmpId,
            success: function(msg){
                if(msg!="")
                {
                    alert(msg);
                    window.location.reload();
                }
            }
            });
        }
        function ObtenerCiudades(dep,opt)
        {
            if(opt==1)
                elcampo="#select2";
            if(opt==2)
                elcampo="#select4";
            $.ajax({
            type: "POST",
            url: "../llamados/trae_ciudades.php",
            data: "dep_id="+dep,
            success: function(msg){
            $(elcampo).html(msg);
            }
            });
        }
        
        function HabilitarCampos(num_frm,id_frm,id_btn)
        {
            for(i=0;i<document.forms[num_frm].elements.length;i++) 
            {
                if (document.forms[num_frm].elements[i].disabled) 
				{
                    document.forms[num_frm].elements[i].disabled=false;
				}
            }
        }
        
        function ocultar(valor)
        {
            if(valor=='NULL' || valor=='')
            {
                    $("#ciudades").css("display", "none");
                    $("#hospitales").css("display","none");
            }
            if(valor==1)
            {
                    $("#ciudades").css("display", "block");
                    $("#hospitales").css("display","none");
            }
            if(valor==2)
            {
              $("#ciudades").css("display","none");
              $("#hospitales").css("display", "block");
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
    $_SESSION['emp_id']=$_GET['emp_id'];
    $ano=$_SESSION['elaniocontable'];
    include_once('../clases/nits.class.php');
    include_once('../clases/departamento.class.php');
    $depto=new departamento();
    $list_deptos_1=$depto->buscar_departamentos();
    $list_deptos_2=$depto->buscar_departamentos();
    $instancia_nits=new nits();
    $con_tip_identificacion=$instancia_nits->con_tip_identificacion();
    $con_est_civil=$instancia_nits->con_est_civil();
    $con_ciudad=$instancia_nits->consultar_ciudades();
    $con_ciudad_2=$instancia_nits->consultar_ciudades();
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
    //TRAE DATOS EMPLEADO//
    $datos=$instancia_nits->con_dat_per_empleado($_SESSION['emp_id']);
    $dat_personales=mssql_fetch_array($datos);
    $apellidos=explode(" ",$dat_personales['nits_apellidos']);
	
	//echo $dat_personales['tip_arl_emp_id'];
    
    //INICIO CIUDAD Y DPTO DE NACIMIENTO
    $data_1=$instancia_nits->con_ciu_dep_empleado(1,$_SESSION['emp_id']);
    $dat_ciu_dep_1=mssql_fetch_array($data_1);
    //FIN CIUDAD Y DPTO DE NACIMIENTO
	
    //INICIO CIUDAD Y DPTO DE RESIDENCIA
    $data_2 = $instancia_nits->con_ciu_dep_empleado(2,$_SESSION['emp_id']);
    $dat_ciu_dep_2=mssql_fetch_array($data_2);
    //FIN CIUDAD Y DPTO DE RESIDENCIA
    //////////////////////
    
    $con_tip_nit_1 = $instancia_nits->con_tip_nit(7);
    $consulta_eps = $instancia_nits->con_eps_empleado($_SESSION['emp_id']);
    $con_eps = mssql_fetch_array($consulta_eps);

    $con_tip_nit_2 = $instancia_nits->con_tip_nit(5);
    $consulta_arp = $instancia_nits->con_arp_empleado($_SESSION['emp_id']);
    $con_arp = mssql_fetch_array($consulta_arp);

    $pensiones = $instancia_nits->con_tip_nit(11);
    $cesantias = $instancia_nits->con_tip_nit(12);
    $caja_compensacion = $instancia_nits->con_tip_nit(14);

    $consulta_pension=$instancia_nits->con_pension_empleado($_SESSION['emp_id']);
    $con_pension=mssql_fetch_array($consulta_pension);

    $consulta_caja_compensacion = $instancia_nits->con_caj_com_empleado($_SESSION['emp_id']);
    $con_caja_compensacion = mssql_fetch_array($consulta_caja_compensacion);
    
    $con_cen_cos_asociado=$instancia_nits->con_cen_cos_asociado($_SESSION['emp_id']);
    $con_cen_cos_ciudad=$instancia_nits->cen_cos_prin();
    $con_cen_cos_hospital=$instancia_nits->cen_cos_sec();
    ?>
        <!--INICIO FORMULARIO 1-->
        <form id="frm_con_emp_1" id="frm_con_emp_1" method="post" onsubmit="recargar(this.id);return false;">
            <div id='cont1'>
               <center>
                 <table class="texto_alineado_izquierda">
                    <tr>
                        <th colspan="4" >CONSULTA EMPLEADO</th>
                    </tr>
                    <tr>
                        <th colspan="4">DATOS PERSONALES</th>
                    </tr>
                    <tr>
                        <td>Primer Apellido</td>
                        <td><input name="emp_pri_apellido" id="emp_pri_apellido" value="<?php echo $apellidos[0]; ?>" type="text" required disabled/></td>
                        <td>Segundo Apellido</td>
                        <td><input name="emp_seg_apellido" id="emp_seg_apellido" value="<?php echo $apellidos[1]; ?>" type="text" required disabled/></td>
                    </tr>
                    <tr>
                        <td>Nombres</td>
                        <td><input  name="emp_nombres" id="emp_nombres" type="text" value="<?php echo $dat_personales['nits_nombres']; ?>" required disabled/></td>
                        <td>Tipo documento</td>
                        <td><select name="emp_tip_documento" id="emp_tip_documento" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled>
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_tip_identificacion))
                        {
                            if($row['tip_ide_id']==$dat_personales['tip_ide_id']){ echo '<option value="'.$row['tip_ide_id'].'" selected>'.$row['tip_ide_nombre'].'</option>'; }
                            else{ echo '<option value="'.$row['tip_ide_id'].'">'.$row['tip_ide_nombre'].'</option>'; }
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Numero Documento</td>
                        <td><input type="text" readonly="readonly" name="emp_num_documento" id="emp_num_documento" required pattern="[0-9]{6,20}" value="<?php echo $dat_personales['nits_num_documento']; ?>" disabled/></td>
                        <td>Fecha De Nacimineto</td>
                        <td><input type="date" name="emp_fec_nacimiento" id="emp_fec_nacimiento" required value="<?php echo $dat_personales['nits_fec_nacimiento']; ?>" disabled/>
                        <a href="javascript:NewCal('emp_fec_nacimiento','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Genero</td>
                        <td>
                        <input  name="emp_genero" id="emp_genero" value="1" type="radio" <?php if($dat_personales['nit_gen_id']==1){ ?>checked="checked" <?php } ?> disabled/>Hombre
                        <input name="emp_genero" id="emp_genero" value="2" type="radio" <?php if($dat_personales['nit_gen_id']==2){ ?>checked="checked" <?php } ?> disabled/>Mujer
                        </td>
                        <td>Estado Civil</td>
                        <td><select name="emp_est_civil" id="emp_est_civil" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled>
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_est_civil))
                        {
                            if($row['est_civ_id']==$dat_personales['est_civ_id'])
                            { echo '<option value="'.$row['est_civ_id'].'" selected>'.$row['est_civ_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['est_civ_id'].'">'.$row['est_civ_nombre'].'</option>'; }
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Departamento Nacimiento</td>
                        <td><select name='select1' id='select1' required x-moz-errormessage="Seleccione Una Opcion Valida" onChange='ObtenerCiudades(this.value,1)' disabled>
                        <option value=''>--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($list_deptos_1))
                        {
                            if($row['dep_id']==$dat_ciu_dep_1['dep_id'])
                            { echo '<option value="'.$row['dep_id'].'" selected>'.strtoupper($row['dep_nombre']).'</option>'; }
                            else
                            { echo '<option value="'.$row['dep_id'].'">'.strtoupper($row['dep_nombre']).'</option>'; }
                        }
                        ?>
                        </select></td>
                        <td>Ciudad Nacimiento</td>
                        <td><select name="select2" id="select2" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled>
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_ciudad))
                        {
                            if($row['ciu_id']==$dat_ciu_dep_1['ciu_id'])
                            { echo '<option value="'.$row['ciu_id'].'" selected>'.strtoupper($row['ciu_nombre']).'</option>'; }
                            else
                            { echo '<option value="'.$row['ciu_id'].'">'.strtoupper($row['ciu_nombre']).'</option>'; }
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Direccion Residencia</td>
                        <td><input type="text"  name="emp_dir_residencia" id="emp_dir_residencia" required value="<?php echo $dat_personales['nits_dir_residencia']; ?>" disabled/></td>
                        <td>Telefono Residencia</td>
                        <td><input type="text" name="emp_tel_residencia" id="emp_tel_residencia" required pattern="[0-9]+" value="<?php echo $dat_personales['nits_tel_residencia']; ?>" disabled/></td>
                    </tr>
                    <tr>
                        <td>Numero Celular</td>
                        <td><input type="text" name="emp_num_celular" id="emp_num_celular" required pattern="[0-9]+" value="<?php echo $dat_personales['nits_num_celular']; ?>" disabled/></td>
                        <td>Correo Electronico</td>
                        <td><input name="emp_cor_electronico" id="emp_cor_electronico" required type="text" value="<?php echo $dat_personales['nits_cor_electronico']; ?>" disabled/></td>
                    </tr>
                    <tr>
                        <td>Correo Electronico adicional</td>
                        <td><input type="text" name="emp_cor_electronico_adicional" id="emp_cor_electronico_adicional" value="<?php echo $dat_personales['nit_cor_electronico_adicional']; ?>" disabled/></td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Departamento Residencia</td>
                        <td><select name="select3" id="select3" required x-moz-errormessage="Seleccione Una Opcion Valida" onChange="ObtenerCiudades(this.value,2)" disabled>
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($list_deptos_2))
                        {
                            if($row['dep_id']==$dat_ciu_dep_2['dep_id'])
                            { echo '<option value="'.$row['dep_id'].'" selected>'.strtoupper($row['dep_nombre']).'</option>'; }
                            else
                            { echo '<option value="'.$row['dep_id'].'">'.strtoupper($row['dep_nombre']).'</option>'; }
                        }
                        ?>
                        </select></td>
                        <td>Ciudad Nacimiento</td>
                        <td><select name="select4" id="select4" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled>
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_ciudad_2))
                        {
                            if($row['ciu_id']==$dat_ciu_dep_2['ciu_id'])
                            { echo '<option value="'.$row['ciu_id'].'" selected>'.strtoupper($row['ciu_nombre']).'</option>'; }
                            else
                            { echo '<option value="'.$row['ciu_id'].'">'.strtoupper($row['ciu_nombre']).'</option>'; }
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <th colspan="4"><input type="button" class="art-button" id="btn1" name="btn1" value="<< Atras" onclick="Javascript:history.back(-1);"/>
                        <input type="button" class="art-button" id="btn2" name="btn2" value="Modificar" onclick="HabilitarCampos(0,'frm_con_emp_1','btn3');"/>
                        <input type="submit" class="art-button" id="btn3" name="btn3" value="Guardar" disabled/>
                        <input type="hidden" name="num_formulario" id="num_formulario" value="1"/>
                        <input type="button" class="art-button" id="btn4" name="btn4" value="Siguiente >>" onclick="ContenedoresEmpleado(1);"/></th>
                    </tr>
                </table>
               </center>
            </div>
        </form>
        <!--FIN FORMULARIO 1-->
        <!--INICIO FORMULARIO 2-->
        <form id="frm_con_emp_2" id="frm_con_emp_2" method="post" onsubmit="Javascript:recargar(this.id);return false;">
            <div id="cont2" style="display:none">
               <center>
                 <table class="texto_alineado_izquierda">
                    <tr>
                        <th colspan="4">CONSULTA EMPLEADO</th>
                    </tr>
                    <tr>
                        <tH colspan="4">DATOS CONTRATO</tH>
                    </tr>
                    <tr>
                        <td>Tipo Contrato</td>
                        <td><select name="emp_tip_contrato" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_contrato))
                        {
                            if($dat_personales['tip_con_nit_id']==$row['tip_con_nit_id'])
                            { echo '<option value="'.$row['tip_con_nit_id'].'" selected>'.$row['tip_con_nit_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['tip_con_nit_id'].'">'.$row['tip_con_nit_nombre'].'</option>'; }
                        }
                        ?>
                        </select></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Periodo de pago</td>
                        <td><select name="emp_per_pag_contrato" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_per_pago))
                        {
                            if($dat_personales['per_pag_nit_id']==$row['per_pag_nit_id'])
                            { echo '<option value="'.$row['per_pag_nit_id'].'" selected>'.$row['per_pag_nit_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['per_pag_nit_id'].'">'.$row['per_pag_nit_nombre'].'</option>'; }
                        }
                        ?>
                        </select></td>
                        <td>Salario</td>
                        <td><input type="text" name="emp_sal_contrato" value="<?php echo $dat_personales['nits_salario']; ?>" disabled required pattern="[0-9.]+" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
                    </tr>
                    <tr>
                        <td>Fecha Inicio Contrato</td>
                        <td><input type="text" name="emp_fec_ini_contrato" id="emp_fec_ini_contrato" value="<?php echo $dat_personales['con_fec_inicio']; ?>" disabled required/>
                        <a href="javascript:NewCal('emp_fec_ini_contrato','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>            
                        <td>Fecha Final Contrato</td>
                        <td><input type="text" name="emp_fec_fin_contrato" id="emp_fec_fin_contrato" value="<?php echo $dat_personales['con_fec_fin']; ?>" disabled required/>
                        <a href="javascript:NewCal('emp_fec_fin_contrato','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Aux Transporte</td>
                        <td><input type="text" name="emp_aux_transporte" id="emp_aux_transporte" required value="<?php echo $dat_personales['nit_aux_transporte']; ?>" pattern="[0-9.]+" onkeypress="mascara(this,cpf);" onpaste="return false" disabled/></td>
                        <td>Prima Extralegal</td>
                        <td><input type="text" disabled name="bonificacion" id="bonificacion" required value="<?php echo $dat_personales['nit_bonificacion']; ?>" onkeypress="mascara(this,cpf);" onpaste="return false"/></td>
                    </tr>
                    <?php //if($dat_personales['nit_bonificacion']>0){ ?>
                    <!--<tr id="elporcentaje">
                        <td>Porcentaje a pagar</td>
                        <td><input type="number" size="3" name="emp_por_bonificacion" id="emp_por_bonificacion" onkeypress="return permite(event,'num')" value="<?php //echo $dat_personales['nits_por_pabs']; ?>" disabled/>%</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>-->
                    <?php //} ?>
                    <tr>
                        <td>Cargo</td>
                        <td><select name="emp_cargo" id="emp_cargo" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_cargos))
                        {
                            if($dat_personales['nit_perfil']==$row['per_id'])
                            { echo '<option value="'.$row['per_id'].'" selected>'.$row['per_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['per_id'].'">'.$row['per_nombre'].'</option>'; }
                        }
                        ?>
                        </select></td>
                        <td>Estado</td>
                        <td><select name="emp_estado" id="emp_estado" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_est_nit))
                        {
                            if($dat_personales['nit_est_id']==$row['nit_est_id'])
                            {
                            ?>
                                <option value="<?php echo $row['nit_est_id']; ?>" selected><?php echo $row['nit_est_nombre']; ?></option>
                            <?php
                            }
                            else
                            {
                            ?>
                                <option value="<?php echo $row['nit_est_id']; ?>"><?php echo $row['nit_est_nombre']; ?></option>
                            <?php
                            }
                        }
                        ?>
                        </select></td>
                    </tr>
                    
                    <tr>
                        <td>Procedimiento</td>
                        <td><select required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida" name="emp_tip_procedimiento" id="emp_tip_procedimiento" disabled>
                        <option value="">Seleccione</option>
                        <?php
                        if($dat_personales['nit_tip_procedimiento']==1||$dat_personales['nit_tip_procedimiento']==''||$dat_personales['nit_tip_procedimiento']=='NULL')
                        {
                        ?>
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                        <?PHP
                        }
                        else
                        {
                        ?>
                            <option value="1">1</option>
                            <option value="2" selected>2</option>
                        <?php
                        }
                        ?>
                        </select></td>
                        <?php
                        if($dat_personales['nit_tip_procedimiento']==2)
                        {
                        ?>
                        <td id="porcentaje_1">Porcentaje</td>
                        <td id="porcentaje_2"><input disabled name="emp_por_ret_fuente" id="emp_por_ret_fuente" value="<?php echo $dat_personales['nit_por_ret_fuente'] ?>" type="text" size="4" maxlength="2" pattern="[0-9]+"/>%</td>
                        <?php
                        }
                        else
                        {
                        ?>
                        <td id="porcentaje_1" style="display:none;">Porcentaje</td>
                        <td id="porcentaje_2" style="display:none;"><input disabled name="emp_por_ret_fuente" id="emp_por_ret_fuente" value="<?php echo $dat_personales['nit_por_ret_fuente'] ?>" type="text" size="4" maxlength="2" pattern="[0-9]+"/>%</td>
                        <?php
                        }
                        ?>
                        
                        <td>Pagar Aux. Transporte(Si aplica)</td>
                        <?php
                        if($dat_personales['nit_pag_aux_transporte']==1)
						{
                        ?>
                        <td><input disabled type="checkbox" name="emp_pag_aux_transporte" id="emp_pag_aux_transporte" checked /></td>
                        <?php
						}
						else
						{
						?>
							<td><input disabled type="checkbox" name="emp_pag_aux_transporte" id="emp_pag_aux_transporte" /></td>
						<?php
						}
                        ?>
                        
                    </tr>
                    
                    <tr>
                        <th colspan="4">
                        <input type="button" class="art-button" name="btn5" id="btn5" onclick="ContenedoresEmpleado(2);" value="<< Atras"/>
                        <input type="button" class="art-button" name="btn6" id="btn6" value="Modificar" onclick="HabilitarCampos(1,'frm_con_emp_2','btn7')"/>
                        <input type="submit" class="art-button" name="btn7" id="btn7" value="Guardar" disabled="disabled"/>
                        <input type="hidden" name="num_formulario" id="num_formulario" value="2"/>
                        <input type="button" class="art-button" name="btn8" id="btn8" onclick="ContenedoresEmpleado(3)" value="Siguiente >>"/>
                        </th>
                    </tr>
                </table>
               </center>
            </div>
        </form>
        <!--FIN FORMULARIO 2-->
        <!--INICIO FORMULARIO 3-->
        <form id="frm_con_emp_3" id="frm_con_emp_3" method="post" onsubmit="Javascript:recargar(this.id);return false;">
            <div id="cont3" style="display:none">
                <center>
                    <table class="texto_alineado_izquierda">
                    <tr>
                        <th colspan="4">CONSULTA EMPLEADO</th>
                    </tr>
                    <tr>
                        <th colspan="4">DATOS COMPLEMENTARIOS</th>
                    </tr>
                    <tr>
                        <td>Banco</td>
                        <td><select name="emp_banco" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_banco))
                        {
                            if($dat_personales['cod_banco'] == $row['cod_banco'])
                            { echo '<option value="'.$row['cod_banco'].'" selected>'.strtoupper(substr($row['banco'],0,50)).'</option>'; }
                            else
                            { echo '<option value="'.$row['cod_banco'].'">'.strtoupper(substr($row['banco'],0,50)).'</option>'; }
                        }
                        ?>
                        </select></td>
                        <td>Tipo De Cuenta</td>
                        <td><select name="emp_tip_cuenta" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccicone--</option>
                        <?php
                        while($row=mssql_fetch_array($con_tip_cuenta))
                        {
                            if($dat_personales['tip_cue_ban_id'] == $row['tip_cue_ban_id'])
                            { echo '<option value="'.$row['tip_cue_ban_id'].'" selected>'.$row['tip_cue_ban_nombre'].'</option>'; }
                            else
                            { echo '<option value="'.$row['tip_cue_ban_id'].'">'.$row['tip_cue_ban_nombre'].'</option>'; }
                        }
            ?>
                        </select></td>
                    </tr>
                    <tr>
                    <td>N&uacute;mero Cuenta</td>
                        <td><input name="emp_num_cuenta" id="emp_num_cuenta" required type="text" value="<?php echo $dat_personales['nits_num_cue_bancaria']; ?>" disabled/></td>
                        
                    <td>Caja Compensacion</td>
                         <td>
                         <select name="caj_compensacion" id="caj_compensacion" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                         <option value="">--Seleccione--</option>
                         <?php
                        while($row=mssql_fetch_array($caja_compensacion))
                        {
                            if($row['nit_id']==$con_caja_compensacion['nit_id'])
                            { echo "<option value='".$row['nit_id']."' selected='selected'>".$row['nits_nombres']."</option>"; }
                            else
                            { echo "<option value='".$row['nit_id']."'>".$row['nits_nombres']."</option>"; }
                        }
                         ?>
                         </select>
                         </td>
                    </tr>
                    <tr>
                        <td>EPS</td>
                        <td><select name="emp_eps" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_nit_1))
                        {
                            if($con_eps['nit_id'] == $row['nit_id'])
                            { echo '<option value="'.$row['nit_id'].'" selected="selected">'.$row['nits_nombres'].'</option>'; }
                            else
                            { echo '<option value="'.$row['nit_id'].'">'.$row['nits_nombres'].'</option>'; }
                        }
                        ?>
                        </select>
                        </td>
                        
                        <td>Fondo de Pensiones</td>
                        <td>
                        <select name="pensiones" id="pensiones" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($pensiones))
                        {
                            if($row['nit_id']==$con_pension['nit_id'])
                                echo "<option value='".$row['nit_id']."' selected='selected'>".$row['nits_nombres']."</option>";
                            else
                                echo "<option value='".$row['nit_id']."'>".$row['nits_nombres']."</option>";      
                        }
                        ?>
                         </select>
                         </td>
                    </tr>
                    <tr>
                        <td>ARL</td>
                        <td><select name="emp_arp" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($rows = mssql_fetch_array($con_tip_nit_2))
                        {
                            if($con_arp['nit_id'] == $rows['nit_id'])
                            { echo '<option value="'.$rows['nit_id'].'" selected="selected">'.$rows['nits_nombres'].'</option>'; }
                            else
                            { echo '<option value="'.$rows['nit_id'].'">'.$rows['nits_nombres'].'</option>'; }
                        }
                        ?>      
                        </select>
                        </td>
                        <td>Tipo ARL</td>
                        <td><select name="emp_tip_arl" id="emp_tip_arl" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccicone--</option>
                        <?php
                        while($res_arl=mssql_fetch_array($arl))
                        {
                            if($dat_personales['tip_arl_emp_id']==$res_arl['tip_arl_emp_id'])
                            { echo '<option value="'.$res_arl['tip_arl_emp_id'].'" selected>'.$res_arl['tip_arl_emp_nombre']." - ".$res_arl['tip_arl_emp_porcentaje']."%".'</option>'; }
                            else
                            { echo '<option value="'.$res_arl['tip_arl_emp_id'].'">'.$res_arl['tip_arl_emp_nombre']." - ".$res_arl['tip_arl_emp_porcentaje']."%".'</option>'; }
                        }
            ?>
                        </select></td>
                    </tr>
                    <tr>
                        <th colspan="4">
                        <input type="button" class="art-button" name="btn9" id="btn9" onClick="ContenedoresEmpleado(4)" value="<< Atras"/>
                        <input type="button" class="art-button" name="btn10" id="btn10" value="Modificar" onclick="HabilitarCampos(2,'frm_con_emp_3','btn11')"/>
                        <input type="submit" class="art-button" name="btn11" id="btn11" value="Guardar" disabled="disabled"/>
                        <input type="hidden" name="num_formulario" id="num_formulario" value="3"/>
                        <input type="button" class="art-button" name="btn12" id="btn12" onClick="ContenedoresEmpleado(5)" value="Siguiente >>" target="frame2"/>
                        </th>
                    </tr>
                </table>
                </center>
            </div>
        </form>
        <!--FIN FORMULARIO 3-->
        <!--INICIO FORMULARIO 4-->
        <form id="frm_con_emp_4" id="frm_con_emp_4" method="post" onsubmit="Javascript:recargar(this.id);return false;">
            <div id="cont4" style="display:none">
                <center>
                    <table>
                    <tr>
                        <td rowspan="20" colspan="20">
                            <table>
                                <tr>
                                <th colspan="2">CONSULTA EMPLEADO</th>
                                </tr>
                                <tr>
                                    <th colspan="2">CENTROS DE COSTOS A LOS QUE PERTENECE:</th>
                                </tr>
                                <?php
                                $h=0;
                                while($row = mssql_fetch_array($con_cen_cos_asociado))
                                {
                                    //INICIO CAPTURO EN UN ARREGLO EL ID DE LA TABLA nits_por_cen_costo
                                    echo "<input type='hidden' name='valor[$h]' id='valor[$h]' value='$row[id_nit_por_cen]'/>";
                                    //FIN CAPTURO EN UN ARREGLO EL ID DE LA TABLA nits_por_cen_costo
                                    $con_cen_costos = $instancia_nits->con_cen_cos_ord_por_hospital();
                                ?>
                                <tr>
                                    <td>
                                        <select name="cen_cos_empleado[<?php echo $h; ?>]" id="cen_cos_empleado[<?php echo $h; ?>]" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
                                        <?php
                                        while($rows = mssql_fetch_array($con_cen_costos))
                                        {
                                            if($row['cen_cos_id'] == $rows['cen_cos_id'])
                                            { echo '<option value="'.$rows['cen_cos_id'].'" selected>'.$rows['cen_cos_nombre'].'</option>'; }
                                            else
                                            { echo '<option value="'.$rows['cen_cos_id'].'">'.$rows['cen_cos_nombre'].'</option>'; }
                                        }
                                        ?>
                                        </select></td>
                                        <!--<td><a href="Javascript:void(0);" onclick="EliCenCosto(<?php echo $row['id_nit_por_cen']; ?>);" title="Eliminar">Eliminar</a></td>-->
                                </tr>
                                <?php
                                $h++;
                                }
                                $_SESSION['nit_por_cen_cos_id'] = $nit_por_cen_cos_id;
                                ?>
                                <tr>
                                    <th colspan="4">
                                    <input type="button" class="art-button" id="btn13" name="btn13" onClick="ContenedoresEmpleado(6)" value="<< Atras"/>
                                    <input type="button" class="art-button" id="btn14" name="btn14" onClick="ContenedoresEmpleado(7)" value="<< Volver al Inicio"/>
                                    <input type="button" class="art-button" id="btn15" name="btn15" onclick="HabilitarCampos(3,'frm_con_emp_4','btn16');" value="Modificar"/>
                                    <input type="hidden" name="num_formulario" id="num_formulario" value="4"/>
                                    <input type="submit" class="art-button" id="btn16" name="btn16" value="Guardar" disabled="disabled"/>
                                    </th>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <th colspan="4">CENTRO DE COSOTOS</th>
                                </tr>
                                <tr>
                                    <td colspan="4"><hr /></td>
                                </tr>
                                <tr>
                                    <th>CIUDAD</th><td><input type="radio" name="opcion" id="opcion" value="1" onclick="ocultar(this.value)" disabled="disabled"/></td>
                                    <th>HOSPITAL</th><td><input type="radio" name="opcion" id="opcion" value="2" onclick="ocultar(this.value)" disabled="disabled"/></td>
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
                                    while($row=mssql_fetch_array($con_cen_cos_ciudad))
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
                        </td>
                    </tr>
                </table>
                </center>
            </div>
        </form>
        <!--INICIO FORMULARIO 4-->
    </body>
</html>