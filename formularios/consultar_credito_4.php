<?php 
    session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

	include_once('../clases/credito.class.php');
	$instancia_credito = new credito();
	//Inicio captura el ID de la persona que seleccionan
	if($_GET['cre_id'])
		$_SESSION['cre_id'] = $_GET['cre_id'];
	$credi_id = $_SESSION['cre_id'];
	
	//Fin captura el ID de la persona que seleccionan
	
	$dat_credito = $instancia_credito->con_dat_credito($credi_id);
	$res_dat_credito = mssql_fetch_array($dat_credito);
	
	$nit = $res_dat_credito['nit_id'];
	$con_concepto = $instancia_credito->con_linea(119);
	$con_cen_cos = $instancia_credito->con_cen_cos_credito($nit,$_SESSION['k_nit_id']);
	
	$con_tip_des_credito = $instancia_credito->con_tip_des_credito();
	$con_for_liq_credito = $instancia_credito->con_for_liq_credito();
	//$con_codeudor = $instancia_credito->con_nit_por_id_estado($_SESSION['sel_tip_persona'],1);
	$con_codeudor = $instancia_credito->con_nit_codeudor($_SESSION['sel_tip_persona'],1,$_SESSION['sel_persona']);
	$con_tod_tip_garantia = $instancia_credito->con_tod_tip_garantia();
	
	$con_tod_ciudades = $instancia_credito->consultar_ciudades();
	
?>
<script src="../librerias/js/datetimepicker.js" language="javascript" type="text/javascript"></script>
<script>
function habilitar()
{
    document.consultar_credito.cre_codeudor.disabled=false;
	document.consultar_credito.gua.disabled=false;
}
function Atras()
{
	var form=document.consultar_credito;
	form.action='consultar_credito_3.php';
	form.submit();
}
function Siguiente()
{
	var form=document.consultar_credito;
	form.action='consultar_credito_6.php';
	form.submit();
}
</script>
<form name="consultar_credito" id="consultar_credito" method="post" action="../control/modificar_codeudor_credito.php">
	<center>
        <table>
        <tr>
            <td colspan="4"><b>Datos Del Cr&eacute;dito</b></td>
        </tr>
        <tr>
             <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td>Consecutivo</td><td><input type="text" name="cre_consecutivo" value="<?php echo $res_dat_credito['cre_id'] ?>" disabled/></td>
        </tr>
        <tr>
            <td>Linea</td>
            <td><select name="cre_linea" disabled>
                <option value="0">--Seleccione--</option>
        <?php
                while($row = mssql_fetch_array($con_concepto))
                {
                    if($res_dat_credito['con_id'] == $row['con_id'])
                    {
        ?>
                    <option value="<?php echo $row['con_id']; ?>" selected><?php echo $row['con_nombre']; ?></option>
        <?php
                    }
                    else
                    {
        ?>
                        <option value="<?php echo $row['con_id']; ?>"><?php echo $row['con_nombre']; ?></option>
        <?php
                    }
                }
        ?>
            </select></td>
            <td>Centro de Costo</td>
            <td>
             <select name="cre_cen_cos" disabled="disabled">
              <option value="0">--Seleccione--</option>
               <?php
                    while($row = mssql_fetch_array($con_cen_cos))
                    {
                        if($res_dat_credito['cen_cos_id'] == $row['cen_cos_id'])
                        {
                ?>
                <option value="<?php echo $row['cen_cos_id']; ?>" selected="selected"><?php echo $row['cen_cos_nombre'];?></option>";
               <?php
                        }
                        else
                        {
                ?>
                    <option value="<?php echo $row['cen_cos_id']; ?>"><?php echo $row['cen_cos_nombre'];?></option>";
                <?php
                        }
                 }
               ?>    
             </select> 
            </td>
       </tr>
        <tr>
            <td>Observaci&oacute;n</td>
            <td><input type="text" name="cre_observacion" value="<?php echo $res_dat_credito['cre_observacion']; ?>" disabled/></td>
            <td>Valor</td>        
            <td><input type="text" name="cre_valor" value="<?php echo number_format($res_dat_credito['cre_valor']); ?>" disabled/></td>
         </tr>
         <tr>
         	<td>DTF(Tasa nominal)</td>
            <td><input type="text" name="cre_dtf" value="<?php echo $res_dat_credito['cre_dtf']; ?>" disabled/></td>
            <td>Tasa mensual</td>
            <td><input type="text" name="cre_interes" value="<?php echo $res_dat_credito['cre_interes']; ?>" disabled/></td>

         </tr>
        <tr>
            <td>Numero de Cuotas</td>
            <td><input type="text" name="cre_num_cuotas" value="<?php echo $res_dat_credito['cre_num_cuotas']; ?>" disabled/></td>
        </tr>
        <tr>
            <td>Tipo Descuento</td>
            <td><select name="cre_tip_descuento" disabled>
                <option value="0">--Seleccione--</option>
        <?php
            while($row = mssql_fetch_array($con_tip_des_credito))
            {
                if($res_dat_credito['tip_des_cre_id'] == $row['tip_des_cre_id'])
                {
        ?>
                <option value="<?php echo $row['tip_des_cre_id']; ?>" selected><?php echo $row['tip_des_cre_nombre']; ?></option>
        <?php
                }
                else
                {
        ?>
                <option value="<?php echo $row['tip_des_cre_id']; ?>"><?php echo $row['tip_des_cre_nombre']; ?></option>
        <?php
                }
            }
        ?>
            </select></td>
            <td>Codeudor</td>
            <td><select name="cre_codeudor" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
                <option value="">Seleccione</option>
            <?php
            while($row = mssql_fetch_array($con_codeudor))
            {
                if($res_dat_credito['cre_codeudor'] == $row['nit_id'])
                {
            ?>
                <option value="<?php echo $row['nit_id']; ?>" selected><?php echo $row['nits_nombres']." ".$row['nits_apellidos']; ?></option>
            <?php
                }
                else
                {
        ?>
                <option value="<?php echo $row['nit_id']; ?>"><?php echo $row['nits_nombres']." ".$row['nits_apellidos']; ?></option>
        <?php
                }
            }
        ?>
            </select></td>
        </tr>
        <tr>
            <td>Fecha Solicitud</td>
            <td><input type="text" name="cre_fec_solicitud" id="cre_fec_solicitud" value="<?php echo $res_dat_credito['cre_fec_solicitud']; ?>" disabled/>
            <a href="javascript:NewCal('cre_fec_solicitud','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
        </tr>
        <tr>
            <td>Fecha Primer Pago</td>
            <td><input type="text" name="cre_fec_pri_pago" id="cre_fec_pri_pago" value="<?php echo $res_dat_credito['cre_fec_pri_pago']; ?>" disabled/>
            <a href="javascript:NewCal('cre_fec_pri_pago','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
            <td>Fecha Vencimiento</td>
            <td><input type="text" name="cre_fec_vencimiento" id="cre_fec_vencimiento" value="<?php echo $res_dat_credito['cre_fec_vencimiento']; ?>" disabled/>
            <a href="javascript:NewCal('cre_fec_vencimiento','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
            </td>
        </tr>
        <tr>
            <td>Forma Liquidaci&oacute;n</td>
            <td><select name="cre_for_liquidacion" disabled="disabled">
            <option value="0">--Seleccione--</option>
        <?php
            while($row = mssql_fetch_array($con_for_liq_credito))
            {
                if($res_dat_credito['for_liq_cre_id'] == $row['for_liq_cre_id'])
                {
        ?>
                <option value="<?php echo $row['for_liq_cre_id']; ?>" selected="selected"><?php echo $row['for_liq_cre_nombre']; ?></option>
        <?php
                }
                else
                {
        ?>
                <option value="<?php echo $row['for_liq_cre_id']; ?>"><?php echo $row['for_liq_cre_nombre']; ?></option>
        <?php
                }
            }
        ?>
            </select></td>
        </tr>
        <?php if($res_dat_credito['cre_garantia']=='SI'){ ?>
        <tr>
            <td>Garantia</td>
            <td>Si<input type="checkbox" name="cre_garantia" id="cre_garantia" checked="checked" disabled="disabled"/></td>
            <td colspan="4">
            <div id="result" style="display:block;">
            Tipo Garantia
                <select name="cre_tip_garantia" disabled="disabled">
                    <option value="NULL" onClick="probando(this.value);">--Seleccione--</option>
                <?php
                while($res_tod_tip_garantia = mssql_fetch_array($con_tod_tip_garantia)){
                if($res_dat_credito['tip_gar_id'] == $res_tod_tip_garantia['tip_gar_id']){
                ?>
                <option value="<?php echo $res_tod_tip_garantia['tip_gar_id']; ?>" onClick="probando(this.value);" selected="selected"><?php echo $res_tod_tip_garantia['tip_gar_nombres']; ?></option>
                <?php
                }
                else{
                ?>
                <option value="<?php echo $res_tod_tip_garantia['tip_gar_id']; ?>" onClick="probando(this.value);"><?php echo $res_tod_tip_garantia['tip_gar_nombres']; ?></option>
                <?php
                }
                $_SESSION['tipo'] = $res_dat_credito['tip_gar_id'];
                }    
                ?>
                </select>
                </div>
            </td>
         </tr>
         <tr>
         <td colspan="4">
            <?php
                if($_SESSION['tipo'] == 1){
                ?>
                <div id="carro" style="display:block;">
                    Ciudad Secional Transito
                    <select name="cre_sec_tra_carro" disabled="disabled"><option value="NULL">--Seleccione--</option>
                    <?php while($res_tod_ciudades = mssql_fetch_array($con_tod_ciudades)){
                    if($res_dat_credito['cre_ciu_sec_transito'] == $res_tod_ciudades['ciu_id']){
                    ?>
                    <option value="<?php echo $res_tod_ciudades['ciu_id']; ?>" selected="selected">
                    <?php echo $res_tod_ciudades['ciu_nombre']; ?>
                    </option>
                    <?php
                    }
                    else{
                    ?>
                    <option value="<?php echo $res_tod_ciudades['ciu_id']; ?>">
                    <?php echo $res_tod_ciudades['ciu_nombre']; ?>
                    </option>
                    <?php
                    }
                    }
                    ?>
                    </select>
                    N° De Placa <input type="text" name="cre_num_pla_carro" value="<?php echo $res_dat_credito['cre_num_pla_carro']; ?>" disabled="disabled"/>
                   </div>
                <?php
                }
                elseif($_SESSION['tipo'] == 2){
                ?>
                <div id="casa" style="display: block;">
                N° Escritura <input type="text" name="cre_num_esc_casa" disabled="disabled" value="<?php echo $res_dat_credito['cre_num_esc_casa']; ?>"/>
                N° Notaria <input type="text" name="cre_num_not_casa" disabled="disabled" value="<?php echo $res_dat_credito['cre_num_not_casa']; ?>"/>
                Fecha Constitución <input type="text" name="cre_fec_con_casa" id="cre_fec_con_casa" disabled="disabled" value="<?php echo $res_dat_credito['cre_fec_con_casa']; ?>"/>
                <a href="javascript:NewCal('cre_fec_con_casa','ddmmyyyy')"><img src="../imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date" /></a>
                </div>
                <?php
                }
                ?>
            </td>
         </tr>
        <?php } ///CIERRA EL IF QUE PREGUNTA SI TIENE GARANTIA ?>
        <tr>
            <td><b>Nota</b></td>
        </tr>
        <tr>
            <td colspan="4"><textarea name="cre_nota" cols="100" rows="2" disabled><?php echo $res_dat_credito['cre_nota']; ?>
            </textarea></td>
        </tr>
        <tr>
            <td colspan="4"><input type="button" class="art-button" name="atras" onclick="Atras();" value="<< Atras"/>
            <input type="button" class="art-button" name="siguiente" onclick="Siguiente();" value="Siguiente >>"/>
            <input type="button" class="art-button" name="mod" value="Modificar Codeudor" onclick="habilitar();"/>
            <input type="submit" class="art-button" name="gua" value="Guardar" disabled="disabled"/>
            </td>
        </tr>
    </table>
    </center>
</form>