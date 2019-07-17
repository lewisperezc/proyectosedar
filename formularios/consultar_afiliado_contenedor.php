<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
@include_once('../clases/departamento.class.php');
@include_once('../clases/nits.class.php');
@include_once('../clases/pais.class.php');

$ins_nits=new nits();
$ins_depto=new departamento();
$ins_pais=new pais();

$con_tip_identificacion=$ins_nits->con_tip_identificacion();
$con_est_civil=$ins_nits->con_est_civil();
$con_ciudad=$ins_nits->consultar_ciudades();

$list_deptos_1=$ins_depto->buscar_departamentos();
$list_deptos_2=$ins_depto->buscar_departamentos();

////////////////////////////////////////////////////

$con_tip_cuenta=$ins_nits->con_tip_cuenta();
$con_tip_nit=$ins_nits->con_tip_nit_eps(7,1315);
$con_tip_nit_2=$ins_nits->con_tip_nit(5);
$con_banco=$ins_nits->cons_bancos();
$con_tip_seg_social=$ins_nits->con_tip_seg_social();

/////////////////////////////////////////////////////////////////

$con_est_nits=$ins_nits->con_est_nits();
$con_tod_departamentos=$ins_nits->buscar_departamentos();
$con_caj_compensacion=$ins_nits->con_tip_nit(14);
$con_fon_pen_obligatoria=$ins_nits->con_tip_nit(11);
$con_fon_pen_voluntaria = $ins_nits->con_tip_nit(11);

//////////////////////////////////////////////////////////////////

$con_tip_documento=$ins_nits->con_tip_identificacion();
$con_tod_parentescos=$ins_nits->con_tod_parentescos();

//////////////////////////////////////////////////////////////////

$con_ciudades_1=$ins_pais->paises();
$con_ciudades_2=$ins_pais->paises();
$con_ciudades_3=$ins_pais->paises();

//////////////////////////////////////////////////////////////////

$con_cen_costos=$ins_nits->cen_cos_contrato();

//////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <script src="librerias/js/datetimepicker.js"></script>
        <script src="librerias/js/contenedor_afiliados.js"></script>
        <script src="../librerias/js/datetimepicker.js"></script>
        <script src="../librerias/js/contenedor_afiliados.js"></script>
        <link rel="stylesheet" type="text/css" href="estilos/screen.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
        <script src="librerias/js/jquery-1.5.0.js"></script>
        <script src="../librerias/js/jquery-1.5.0.js"></script>
        <title></title>
        <script>
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
        
        function recargarAso(dato,FrmId)
        {
            //alert('los datos en el frm son: '+dato+'__'+FrmId);
            var datos=$("#"+FrmId).serialize();
            $.ajax({
            type: "POST",
            url: "control/guardar_afiliado.php",
            data: datos,
            success: function(msg){
                if(msg!="")
                {
                    alert(msg);
                    window.location.reload();
                }
            }
            });
            if(dato!=12)
            {
                ContenedoresAfiliado(dato);
            }
        }
        
        $(document).ready(function()
        {
            $("#aso_tip_procedimiento").click(function(evento)
            {
                if($("#aso_tip_procedimiento").val()==2)
                { $("#porcentaje_1").css("display","table-cell");$("#porcentaje_2").css("display","table-cell"); }
                else
                { $("#porcentaje_1").css("display","none");$("#porcentaje_2").css("display","none"); }
            });
        });
        
        function obli(val)
        {
            if(val=="SI")
            { $("#fondo_obli_1").css("display","table-cell"); $("#fondo_obli_2").css("display","table-cell");}
            else
	    { $("#fondo_obli_1").css("display","none");$("#fondo_obli_2").css("display","none"); }
        }
        
        function volun(val)
        {
            if(val=="SI")
            { $("#fondo_volun_1").css("display","table-cell");$("#fondo_volun_2").css("display","table-cell"); }
            else
            { $("#fondo_volun_1").css("display", "none");$("#fondo_volun_2").css("display", "none"); }
        }
        
        function Agregar()
        {
            var pos=$("#tabla>tbody>tr").length-2;
            <?php
            $con_tod_parentescos_2=$ins_nits->con_tod_parentescos();
            $con_tip_documento_2=$ins_nits->con_tip_identificacion();
            ?>
            campo='<tr><td><input type="text" name="aso_ape_beneficiario'+pos+'" id="aso_ape_beneficiario'+pos+'" required/></td>';
            campo+='<td><input type="text" name="aso_nom_beneficiario'+pos+'" id="aso_nom_beneficiario'+pos+'" required/></td>';
            campo+='<td><select name="aso_parentesco'+pos+'" id="aso_parentesco'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione</option>';
            <?php
            while($res_tod_parentescos_2=mssql_fetch_array($con_tod_parentescos_2))
            {
            ?>
                campo+='<option value="<?php echo $res_tod_parentescos_2['par_id']; ?>"><?php echo $res_tod_parentescos_2['par_nombres']; ?></option>"';
            <?php 
            }
            ?>
            campo+='</select></td>';
            campo+='<td><select name="aso_tip_doc_beneficiario'+pos+'" id="aso_tip_doc_beneficiario'+pos+'" required x-moz-errormessage="Seleccione Una Opcion Valida"><option value="">Seleccione</option>';
            <?php
            while($res_tip_documento_2=mssql_fetch_array($con_tip_documento_2))
            { ?>
            campo+='<option value="<?php echo $res_tip_documento_2['tip_ide_id']; ?>"><?php echo $res_tip_documento_2['tip_ide_nombre']; ?></option>"';
            <?php 
            }
            ?>
            campo+='</select></td>';
            campo+='<td><input type="text" name="aso_num_doc_beneficiario'+pos+'" id="aso_num_doc_beneficiario'+pos+'" required/></td>';
            campo+='<td><input type="text" name="aso_por_ben_beneficiario'+pos+'" id="aso_por_ben_beneficiario'+pos+'" size="3" maxlength="3" required/><b>%</b></td></tr>';
            $("#tabla").append(campo);
            $("#cuantos_beneficiarios").val(pos);
        }
        </script>
    </head>
    <body>
        <!--INICIO FORMULARIO 1-->
        <form id="frm_cre_afi_1" id="frm_cre_afi_1" method="post" onsubmit="recargarAso(1,this.id);return false;">
         <center>
             <div id='cont1'>
                <table>
                    <tr>
                        <th colspan="4" ><h4>CREACI&Oacute;N AFILIADO</h4></th>
                    </tr>
                    <tr>
                        <th colspan="4"><h4>Datos Personales</h4></th>
                    </tr>
                    <tr>
                        <td>Primer Apellido</td> 
                        <td><input name="aso_pri_apellido" id="aso_pri_apellido" type="text" pattern="[A-ZñÑa-z ]+" required></td>
                        <td>Segundo Apellido</td>
                        <td><input name="aso_seg_apellido" id="aso_seg_apellido" type="text" pattern="[A-ZñÑa-z ]+" required/></td>
                    </tr>
                    <tr>
                        <td>Nombres</td>
                        <td><input name="aso_nombres" id="aso_nombres" type="text" pattern="[A-ZñÑa-z ]+" required/></td>
                        <td>Tipo documento</td>
                        <td><select name="aso_tip_documento" id="aso_tip_documento" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_tip_identificacion))
                        {
                            echo '<option value="'.$row['tip_ide_id'].'">'.$row['tip_ide_nombre'].'</option>';
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Numero Documento</td>
                        <td><input type="text" name="aso_num_documento" id="aso_num_documento" pattern="[0-9]+" required/></td>
                        <td>Fecha De Nacimineto</td>
                        <td><input type="text" name="aso_nac_fecha" id="aso_nac_fecha" required/>
                        <a href="javascript:NewCal('aso_nac_fecha','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Genero</td>
                        <td><input  name="aso_genero" id="aso_genero" value="1" type="radio" checked="checked"/>Hombre<input name="aso_genero" id="aso_genero" value="2" type="radio"/>Mujer</td>
                        <td>Estado Civil</td>
                        <td><select name="aso_est_civil" id="aso_est_civil" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_est_civil))
                        {
                            echo '<option value="'.$row['est_civ_id'].'">'.$row['est_civ_nombre'].'</option>';
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Departamento Nacimiento</td>
                        <td><select name='select1' id='select1' required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida" onChange='ObtenerCiudades(this.value,1)'>
                        <option value=''>--Seleccione--</option>
                        <?php while($row=mssql_fetch_array($list_deptos_1)){ ?>
                        <option value="<?php echo $row['dep_id'];?>"><?php echo strtoupper($row['dep_nombre']);?></option>
                        <?php } ?>
                        </select></td>
                        <td>Ciudad Nacimiento</td>
                        <td><select name="select2" id="select2" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        </select>
                        </td>                        
                    </tr>
                    <tr>
                        <td>Direccion Residencia</td>
                        <td><input type="text" name="aso_dir_residencia" id="aso_dir_residencia" required/></td>
                        <td>Telefono Residencia</td>
                        <td><input name="aso_tel_residencia" id="aso_tel_residencia" type="text" pattern="[0-9]+" required/></td>
                    </tr>
                    <tr>
                        <td>Numero Celular</td>
                        <td><input name="aso_num_celular" id="aso_num_celular" type="text" pattern="[0-9]+" required/></td>
                        <td>Correo Electronico</td>
                        <td><input name="aso_cor_electronico" id="aso_cor_electronico" type="text" required/></td>              
                    </tr>
                    <tr>
                        <td>Correo Electronico Adicional</td>
                        <td><input name="aso_cor_electronico_adicional" id="aso_cor_electronico_adicional" type="text"/></td>
                        <td>Porcentaje PABS </td>
                        <td><input name="aso_por_pabs" id="aso_por_pabs" type="text" size="4" value="10" maxlength="2" required pattern="[0-9]+"/>%</td>
                    </tr>
                    <tr>
                        <td>Departamento Residencia</td>
                        <td><select name='select3' id='select3' required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida" onChange='ObtenerCiudades(this.value,2)'>
                        <option value=''>--Seleccione--</option>
                        <?php while($row=mssql_fetch_array($list_deptos_2)){ ?>
                        <option value="<?php echo $row['dep_id'];?>"><?php echo strtoupper($row['dep_nombre']);?></option>
                        <?php } ?>
                        </select></td>
                        <td>Ciudad Residencia</td>
                        <td><select name="select4" id="select4" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        </select>
                        </td>                        
                    </tr>
                    <tr>
                        <td>Fondo De Vacaciones</td><td>Si<input type="checkbox" checked name="aso_fon_vacaciones" id="aso_fon_vacaciones" required disabled/></td>
                        <td>Porcentaje</td><td><input name="aso_por_fon_vacaciones" id="aso_por_fon_vacaciones" size="4" maxlength="2" type="text" value="4" required pattern="[0-9]+"/>%</td>
                    </tr>
                    <tr>
                        <td>Procedimiento</td>
                        <td><select required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida" name="aso_tip_procedimiento" id="aso_tip_procedimiento">
                        <option value="">Seleccione</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        </select></td>
                        <td id="porcentaje_1" style="display:none;">Rete Fuente</td>
                        <td id="porcentaje_2" style="display:none;"><input name="aso_por_ret_fuente" id="aso_por_ret_fuente" type="text" size="4" maxlength="2" pattern="[0-9]+"/>%</td>
                    </tr>
                    <tr>
                        <th colspan="4">
                        <input type="hidden" name="pri_formulario" id="pri_formulario" value="1"/>
                        <input type="submit" class="art-button" name="btn1" id="btn1" value="Siguiente >>"/></th>
                    </tr>
                </table>
            </div>
        </form>
        <!--FIN FORMULARIO 1-->
        <!--INICIO FORMULARIO 2-->
        <form id="frm_cre_afi_2" id="frm_cre_afi_2" method="post" onsubmit="recargarAso(3,this.id);return false;">
            <div id="cont2" style="display:none">
                <table>
                    <tr>
                        <th colspan="4">Datos Afiliaci&oacute;n</th>
                    </tr>
                    <tr>
                        <td>Banco</td>
                        <td><select name="aso_banco" id="aso_banco" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_banco))
                        {
                            echo '<option value="'.$row['cod_banco'].'">'.strtoupper(substr($row['banco'],0,25)).'</option>';
                        }
                        ?>
                        </select></td>
                        <td>Tipo De Cuenta</td>
                        <td><select name="aso_tip_cuenta" id="aso_tip_cuenta" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccicone--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_cuenta))
                        {
                            echo '<option value="'.$row['tip_cue_ban_id'].'">'.$row['tip_cue_ban_nombre'].'</option>';
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>N&uacute;mero Cuenta</td>
                        <td><input type="text" name="aso_num_cuenta" id="aso_num_cuenta" pattern="[0-9-]+" required/></td>
                        <td>EPS</td>
                        <td><select name="aso_eps" id="aso_eps" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_nit))
                        {
                            echo '<option value="'.$row['nit_id'].'">'.substr($row['nits_nombres'],0,30).'</option>';
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>ARL</td>
                        <td><select name="aso_arp" id="aso_arp" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($rows = mssql_fetch_array($con_tip_nit_2))
                        {
                            echo '<option value="'.$rows['nit_id'].'">'.$rows['nits_nombres'].'</option>';
                        }
                        ?>
                        </select>
                        </td>
                        <td>Tipo Seguridad Social</td>
                        <td><select name="aso_tip_seg_social" id="aso_tip_seg_social" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($res_tip_seg_soc = mssql_fetch_array($con_tip_seg_social))
                        {
                            echo '<option value="'.$res_tip_seg_soc['tip_segSoc_id'].'">'.$res_tip_seg_soc['tip_segSoc_nombre'].'</option>';
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <th colspan="4">
                            <input type="hidden" name="seg_formulario" id="seg_formulario" value="2"/>
                            <input type="button" class="art-button" name="btn2" id="btn2" onClick="ContenedoresAfiliado(2);" value="<< Atras"/>
                            <input type="submit" class="art-button" name="btn3" id="btn3" value="Siguiente >>"/>
                        </th>
                    </tr>
                </table>
            </div>
        </form>
        <!--FIN FORMULARIO 2-->
        <!--INICIO FORMULARIO 3-->
        <form id="frm_cre_afi_3" id="frm_cre_afi_3" method="post" onsubmit="recargarAso(5,this.id);return false;">
            <div id="cont3" style="display:none">
                <table>
                    <tr>
                        <th colspan="4">Datos Familiares</th>
                    </tr>
                    <tr>
                        <td>N&uacute;mero Personas a Cargo</td>
                        <td><input type="text" name="aso_per_cargo" id="aso_per_cargo" pattern="[0-9]+" required/></td>
                        <td>N&uacute;mero De Hijos</td>
                        <td><input type="text" name="aso_num_hijos" id="aso_num_hijos" pattern="[0-9]+" required/></td>
                    </tr>
                    <tr>
                        <td>Estado Afiliado</td>
                        <td><select name="aso_estado" id="aso_estado" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_est_nits))
            {
                            echo '<option value="'.$row['nit_est_id'].'">'.$row['nit_est_nombre'].'</option>';
            }
                        ?>
                        </select>
                        </td>
                        <td>&nbsp;</td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Scare</td>
                        <td><input name="aso_afi_scare" id="aso_afi_scare" value="SI" type="radio" required/>Si<input name="aso_afi_scare" id="aso_afi_scare" value="NO" type="radio" required/>No</td>
                        <td>Seccional Scare</td>
                        <td><select name="aso_sec_scare" id="aso_sec_scare" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_tod_departamentos))
                        {
            ?>
                        <option value="<?php echo $row['dep_id']; ?>"><?php echo strtoupper($row['dep_nombre']); ?></option>
                        <?php
            }
            ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Fepasde</td>
                        <td><input name="aso_fepasde" id="aso_fepasde" value="SI" type="radio" required/>Si<input name="aso_fepasde" id="aso_fepasde" value="NO" type="radio" required/>No</td>
                        <td>Caja Compensación</td>
                        <td><select name="aso_caj_compensacion" id="aso_caj_compensacion" required x-moz-errormessage="Seleccione Una Opci&oacute;n Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($res_caj_compensacion=mssql_fetch_array($con_caj_compensacion))
                        {
                            echo '<option value="'.$res_caj_compensacion['nit_id'].'">'.substr($res_caj_compensacion['nits_nombres'],0,150).'</option>';
                        }
                        ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>Cotizaci&oacute;n Pensi&oacute;n Obligatoria</td>
                        <td><input name="aso_fon_pen_obli" id="aso_fon_pen_obli" value="SI" type="radio" onclick="obli(this.value);" required/>Si
                            <input name="aso_fon_pen_obli" id="aso_fon_pen_obli" value="NO"type="radio" onclick="obli(this.value);" required/>No</td>
                        <td id="fondo_obli_1" style="display:none;">Fondo de pensi&oacute;n obligatoria</td>
                        <td id="fondo_obli_2" style="display:none;">
                        <select name="fon_pen_obligatorio" id="fon_pen_obligatorio">
                        <option value="NULL">--Seleccione--</option>
                        <?php
                        while($res_fon_pen_obligatoria=mssql_fetch_array($con_fon_pen_obligatoria))
                        {
                            echo '<option value="'.$res_fon_pen_obligatoria['nit_id'].'">'.substr($res_fon_pen_obligatoria['nits_nombres'],0,30).'</option>';
            }
            ?>
                </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Cotizaci&oacute;n pensi&oacute;n voluntaria</td>
                        <td><input name="aso_fon_pen_voluntaria" id="aso_fon_pen_voluntaria" value="SI" type="radio" onclick="volun(this.value);" required/>Si<input name="aso_fon_pen_voluntaria" id="aso_fon_pen_voluntaria" value="NO"type="radio" onclick="volun(this.value);" required/>No</td>
                        <td id="fondo_volun_1" style="display: none;">Fondo de pensi&oacute;n voluntaria</td>
                        <td id="fondo_volun_2" style="display: none;">
                        <select name="fon_pen_vol" id="fon_pen_vol">
                        <option value="NULL">--Seleccione--</option>
                        <?php
                        while($res_fon_pen_voluntaria=mssql_fetch_array($con_fon_pen_voluntaria))
                        {
                            echo '<option value="'.$res_fon_pen_voluntaria['nit_id'].'">'.substr($res_fon_pen_voluntaria['nits_nombres'],0,30).'</option>';
            }
            ?>
                </select>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">
                            <input type="hidden" name="ter_formulario" id="ter_formulario" value="3"/>
                            <input type="button" class="art-button" name="btn4" id="btn4" onClick="ContenedoresAfiliado(4)" value="<< Atras"/>
                            <input type="submit" class="art-button" name="btn5" id="btn5" value="Siguiente >>"/>
                        </th>
                    </tr>
                </table>
            </div>
        </form>
        <!--FIN FORMULARIO 3-->
        <!--INICIO FORMULARIO 4-->
        <form id="frm_cre_afi_4" id="frm_cre_afi_4" method="post" onsubmit="recargarAso(7,this.id);return false;">
            <div id="cont4" style="display:none">
                <table id="tabla">
                    <tr>
                        <th colspan="6"><h4>Datos Beneficiarios</h4></th>
                    </tr>
                    <tr>
                        <td>Apellidos Beneficiario</td>
                        <td>Nombres Beneficiario</td>
                        <td>Parentesco</td>
                        <td>Tipo Documento</td>
                        <td>N&uacute;mero Documento</td>
                        <td>Porcentaje Beneficios </td>
                    </tr>
                    <tr id="beneficiarios">
                        <td><input type="text" name="aso_ape_beneficiario0" id="aso_ape_beneficiario0" pattern="[A-ZñÑa-z ]+" required/></td> 
                        <td><input type="text" name="aso_nom_beneficiario0" id="aso_nom_beneficiario0" pattern="[A-ZñÑa-z ]+" required/></td>
                        <td><select name="aso_parentesco0" id="aso_parentesco0" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($res_tod_parentescos=mssql_fetch_array($con_tod_parentescos))
                        {
                        ?>
                            <option value="<?php echo $res_tod_parentescos['par_id']; ?>"><?php echo $res_tod_parentescos['par_nombres']; ?></option>
                        <?php
                        }
                        ?>
                        </select></td>
                        <td><select name="aso_tip_doc_beneficiario0" id="aso_tip_doc_beneficiario0" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option value="">--Seleccione--</option>
                        <?php
                        while($row = mssql_fetch_array($con_tip_documento))
            {
            ?>
                            <option value="<?php echo $row['tip_ide_id']; ?>"><?php echo $row['tip_ide_nombre']; ?></option>    
                        <?php
            }
            ?>
                        </select>
                        </td>
                        <td><input type="text" name="aso_num_doc_beneficiario0" id="aso_num_doc_beneficiario0" pattern="[0-9]+" required/></td>
                        <td><input type="text" name="aso_por_ben_beneficiario0" id="aso_por_ben_beneficiario0" size="3" maxlength="3"  pattern="[0-9]+" required/>%</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th colspan="6">
                            <input type="button" class="art-button" name="agregar" value="Agregar" onclick="Agregar();"/>
                            <input type="hidden" name="cuantos_beneficiarios" id="cuantos_beneficiarios" />
                        </th>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <th colspan="6">
                            <input type="hidden" name="cua_formulario" id="cua_formulario" value="4"/>
                            <input type="button" class="art-button" name="btn6" id="btn6" onClick="ContenedoresAfiliado(6);" value="<< Atras"/>
                            <input type="submit" class="art-button" name="btn7" id="btn7" value="Siguiente >>"/>
                        </th>
                    </tr>
                </table>
            </div>
        </form>
        <!--FIN FORMULARIO 4-->
        <!--INICIO FORMULARIO 5-->
        <form id="frm_cre_afi_5" id="frm_cre_afi_5" method="post" onsubmit="recargarAso(9,this.id);return false;">
            <div id="cont5" style="display:none"> 
                <table>
                    <tr>
                        <th colspan="6">Datos Educaci&oacute;n Superior</th>
                    </tr>       
                    <tr>
                        <td>Universidad Pregrado Medicina</td>
                        <td><input type="text" id="aso_uni_pregrado" name="aso_uni_pregrado" required/></td>
                        <td>Fecha Grado</td>
                        <td><input type="text" name="aso_fec_pregrado" id="aso_fec_pregrado" required/>
                        <a href="javascript:NewCal('aso_fec_pregrado','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date"/></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Titulo Obtenido</td>
                        <td><input type="text" id="aso_tit_gra_obtenido" name="aso_tit_gra_obtenido" value="MEDICO GENERAL" required/></td>
                        <td>Pais Pregrado</td>
                        <td><select id="aso_ciu_pregrado" name="aso_ciu_pregrado" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">--Seleccione--</option>
                        <?php
            while($row=mssql_fetch_array($con_ciudades_1))
            {
                            echo '<option value="'.$row['pai_id'].'">'.$row['pai_nombre'].'</option>';
            }
            ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Universidad Posgrado Anestesiologia</td>
                        <td><input type="text" id="aso_uni_posgrado" name="aso_uni_posgrado" required/></td>
                        <td>Fecha Posgrado</td>
                        <td><input type="text" name="aso_fec_posgrado" id="aso_fec_posgrado" required/>
                        <a href="javascript:NewCal('aso_fec_posgrado','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Titulo Obtenido</td>
                        <td><input type="text" id="aso_tit_pos_obtenido" name="aso_tit_pos_obtenido" value="MEDICO ANESTESIOLOGO" required/></td>
                        <td>Pais Posgrado</td>
                        <td><select id="ciu_posgrado" name="ciu_posgrado" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option  value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_ciudades_2))
                        {
                            echo '<option value="'.$row['pai_id'].'">'.$row['pai_nombre'].'</option>';
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>                
                        <td>Universidad otros</td>
                        <td><input type="text" id="aso_uni_otros" name="aso_uni_otros" required/></td>
                        <td>Fecha otros</td>
                        <td>
                        <input type="text" id="aso_fec_otros" name="aso_fec_otros" required/>
                        <a href="javascript:NewCal('aso_fec_otros','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Titulo Obtenido</td>
                        <td><input type="text" id="aso_tit_otr_obtenido" name="aso_tit_otr_obtenido" required/></td>
                        <td>Pais Otros Estudios</td>
                        <td><select id="aso_ciu_otr_obtenido" name="aso_ciu_otr_obtenido" required x-moz-errormessage="Seleccione Una Opcion Valida">
                        <option  value="">--Seleccione--</option>
                        <?php
                        while($row=mssql_fetch_array($con_ciudades_3))
                        {
                            echo '<option value="'.$row['pai_id'].'">'.$row['pai_nombre'].'</option>';
                        }
                        ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">
                            <input type="hidden" name="qui_formulario" id="qui_formulario" value="5"/>
                            <input type="button" class="art-button" name="btn8" id="btn8" onClick="ContenedoresAfiliado(8);" value="<< Atras"/>
                            <input type="submit" class="art-button" name="btn9" id="btn9" value="Siguiente >>"/>
                        </th>
                    </tr>
                </table>
            </div>
        </form>
        <!--FIN FORMULARIO 5-->
        <!--INICIO FORMULARIO 6-->
        <form id="frm_cre_afi_6" id="frm_cre_afi_6" method="post" onsubmit="recargarAso(12,this.id);return false;">
            <div id="cont6" style="display:none">
                <table>
                    <tr>
                        <th colspan="">Centros de costo a asignar</th>
                    </tr>
                    <tr>
                        <td>
                        <select name="aso_cen_costos[]" id="aso_cen_costos[]" size=5 style='width:600px;height:400px;border:solid' multiple="multiple">
                        <?php
                        $i=0;
                        while($row = mssql_fetch_array($con_cen_costos))
                        {
                        ?>
                            <option value="<?php echo $row['cc_id']; ?>"><?php echo $row['cc_nombre']; ?></option>
                        <?php
            $i++;
            }
            ?> 
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="6">
                            <input type="hidden" name="sex_formulario" id="sex_formulario" value="6"/>
                            <input type="button" class="art-button" name="btn10" id="btn10" onClick="ContenedoresAfiliado(10);" value="<< Atras"/>
                            <input type="button" class="art-button" name="btn11" id="btn11" onClick="ContenedoresAfiliado(11);" value="<< Volver al inicio"/>
                            <input type="submit" class="art-button" name="btn12" id="btn12" value="Guardar"/>
                        </th>
                    </tr>
                </table>
            </div>
         </center>  
        </form>
        <!--FIN FORMULARIO 6-->
    </body>
</html>