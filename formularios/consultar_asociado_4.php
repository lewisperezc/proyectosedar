<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>

<?php
//INICIO CAPTURO TODO LO QUE HAY EN LOS CAMPOS PARA MANTENERLO EN LA SESSION AL MOMENTO DE DAR ATRAS
$_SESSION['aso_banco'] = $_POST['aso_banco'];
$_SESSION['aso_tip_cuenta'] = $_POST['aso_tip_cuenta'];
$_SESSION['aso_num_cuenta'] = $_POST['aso_num_cuenta'];
$_SESSION['aso_eps'] = $_POST['aso_eps'];
$_SESSION['aso_cot_pos'] = $_POST['aso_cot_pos'];
$_SESSION['aso_cot_men_eps'] = $_POST['aso_cot_men_eps'];
$_SESSION['aso_arp'] = $_POST['aso_arp'];
$_SESSION['aso_cot_men_arp'] = $_POST['aso_cot_men_arp'];
//FIN CAPTURO TODO LO QUE HAY EN LOS CAMPOS PARA MANTENERLO EN LA SESSION AL MOMENTO DE DAR ATRAS
?>
<?php
$id_asociado = $_SESSION['aso_id'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();

$datos = $instancia_nits->con_dat_per_asociado($id_asociado);
$dat_familiares = mssql_fetch_array($datos);

$con_est_nits = $instancia_nits->con_est_nits();

$ciu_sec = $instancia_nits->con_ciu_dep_asociado(3,$id_asociado);
$ciu_seccional = mssql_fetch_array($ciu_sec);
$con_tod_departamentos = $instancia_nits->buscar_departamentos();

$con_caj_compensacion = $instancia_nits->con_tip_nit(14);
$consulta_caja_compensacion = $instancia_nits->con_caj_com_asociado($id_asociado);
$con_caj_com = mssql_fetch_array($consulta_caja_compensacion);

$con_tip_nit_1 = $instancia_nits->con_tip_nit(11);
$consulta_fondo_pensiones = $instancia_nits->con_fon_pen_asociado($id_asociado);
$con_fon_pensiones = mssql_fetch_array($consulta_fondo_pensiones);
$con_tip_nit_2 = $instancia_nits->con_tip_nit(11);
$consulta_fondo_pensiones_2 = $instancia_nits->con_fon_pen_vol_asociado($id_asociado);
$con_fon_pensiones_voluntarias = mssql_fetch_array($consulta_fondo_pensiones_2);

?>
<script src="../librerias/js/validacion_num_letras.js"></script>
<script language="javascript" src="../librerias/js/jquery-1.3.2.min.js"></script>
<script>
function habilitar()
  {
    for (i=0;i<document.forms[0].elements.length;i++) 
	  {
        if (document.forms[0].elements[i].disabled) 
		{
          document.forms[0].elements[i].disabled = false;
		}
      }
	  	  document.aso_datos_familiares.guardar.disabled=false;
  }
function obli(val)
{
	if(val=="SI")
		$("#fondo_obli").css("display", "block");
	else
	   	$("#fondo_obli").css("display", "none");	
}

function volu(val)
{
	if(val=="SI")
		$("#fondo_volun").css("display", "block");
	else
	   	$("#fondo_volun").css("display", "none");	
}

function Atras()
{
	var form=document.aso_datos_familiares;
	form.action='consultar_asociado_3.php';
	form.submit();
}

function Siguiente()
{
	var form=document.aso_datos_familiares;
	form.action='consultar_asociado_5.php';
	form.submit();
}
</script>
<form name="aso_datos_familiares" id="aso_datos_familiares" action="../control/actualizar_asociado_3.php" method="post"   >
<center>
  <table>
  <tr>
      <td colspan="6" ><h4>Datos Familiares</h4></th>
  </tr>
  <tr>
    <td colspan="6" ><hr /></td>
  </tr>
  <tr>
      <td>N&uacute;mero Personas a Cargo</td>
    <td><input  name="aso_per_cargo" type="text" onKeyPress="return permite(event,'num')" value="<?php echo $dat_familiares['nits_num_per_cargo']; ?>" disabled="disabled" required="required"/></td>
    <td>N&uacute;mero De Hijos</td>
    <td><input  name="aso_num_hijos" type="text" onKeyPress="return permite(event,'num')" value="<?php echo $dat_familiares['nits_num_hijos']; ?>" disabled="disabled" required="required"/></td>
  </tr>
  <tr>
    <td>Estado Afiliado</td>
      <td><select name="aso_estado" disabled="disabled">
         <option value="0">--Seleccione--</option>
             <?php
             while($row = mssql_fetch_array($con_est_nits))
       {
         if($dat_familiares['nit_est_id'] == $row['nit_est_id'])
         {
       ?>
                  <option value="<?php echo $row['nit_est_id']; ?>" selected="selected">
            <?php echo $row['nit_est_nombre']; ?>
                    </option>
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
        </select>
       </td>
  </tr>
  <tr>
     <td>Scare</td>
     <td><input  name="aso_afi_scare" id="aso_afi_scare" value="SI" type="radio" <?php if($dat_familiares['nits_scare'] == 'SI'){ ?> checked="checked" <?php } ?> disabled="disabled"/>Si<input name="aso_afi_scare" id="aso_afi_scare" value="NO" type="radio" <?php if($dat_familiares['nits_scare'] == 'NO'){ ?> checked="checked" <?php } ?> disabled="disabled"/>No</td>
     <td>Seccional Scare</td>
     <td><select name="aso_sec_scare" disabled="disabled">
             <option value="NULL">--Seleccione--</option>
               <?php
               while($row = mssql_fetch_array($con_tod_departamentos))
         {
            if($dat_familiares['nit_sec_scare'] == $row['dep_id'])
          {
         ?>
                  <option value="<?php echo $row['dep_id']; ?>" selected="selected">
          <?php echo $row['dep_nombre']; ?>
                    </option>
               <?php
          }
          else
          {
        ?>
                    <option value="<?php echo $row['dep_id']; ?>"><?php echo $row['dep_nombre']; ?></option>
                <?php
          }
         }
         ?>
            </select>
    </td>
  </tr>
  <tr>
    <td>Fepasde</td>
    <td>
          <input  name="aso_fepasde" id="aso_fepasde" value="SI" type="radio" <?php if($dat_familiares['nist_fepasde'] == 'SI'){ ?>checked="checked" <?php } ?> disabled="disabled"/>Si
            <input name="aso_fepasde" id="aso_fepasde" value="NO" type="radio" <?php if($dat_familiares['nist_fepasde'] == 'NO'){ ?>checked="checked" <?php } ?> disabled="disabled"/>No
        </td>
        <td>Caja Compensaci&oacute;n</td><td><select disabled="disabled" name="aso_caj_comensacion" id="aso_caj_comensacion"><option value="NULL">--Seleccione--</option>
        <?php while($res_caj_compensacion=mssql_fetch_array($con_caj_compensacion)){ 
    if($con_caj_com['nit_id']==$res_caj_compensacion['nit_id']){
    ?>
        <option value="<?php echo $res_caj_compensacion['nit_id']; ?>" selected="selected"><?php echo $res_caj_compensacion['nits_nombres']; ?></option>
        <?php
        }
    else{
    ?>
      <option value="<?php echo $res_caj_compensacion['nit_id']; ?>"><?php echo $res_caj_compensacion['nits_nombres']; ?></option>
        <?php
    }
    }?>
        </select></td>
  </tr>
  <tr>
    <td>Cotizaci&oacute;n Pensi&oacute;n Obligatoria </td>
    <td>
        <input  name="aso_fon_pen_obli" id="aso_fon_pen_obli" value="SI"type="radio" <?php if($dat_familiares['nist_fon_pen_obligatoria'] == 'SI'){ ?> checked="checked" <?php } ?> disabled="disabled" onclick="obli(this.value)"/>Si
        <input name="aso_fon_pen_obli" id="aso_fon_pen_obli" value="NO"type="radio" <?php if($dat_familiares['nist_fon_pen_obligatoria'] == 'NO'){ ?> checked="checked" <?php } ?> disabled="disabled" onclick="obli(this.value)"/>No
        </td>
        <td>
        <?php if($dat_familiares['nist_fon_pen_obligatoria']=='SI'){ ?>
        <div id="fondo_obli" style="display: block;">
        Fondo de pensi&oacute;n obligatoria
        <select name="fon_pen_obligatorio" id="fon_pen_obligatorio" disabled="disabled">
          <option value="NULL" selected="selected">--Seleccione--</option>
            <?php
              while($res_tip_nit=mssql_fetch_array($con_tip_nit_1))
        {
        if($con_fon_pensiones['nit_id'] == $res_tip_nit['nit_id'])
        {
      ?>
                <option value="<?php echo $res_tip_nit['nit_id']; ?>" selected="selected"><?php echo substr($res_tip_nit['nits_nombres'],0,30); ?></option>
                <?php
        }
        else
        {
        ?>
                  <option value="<?php echo $res_tip_nit['nit_id']; ?>"><?php echo substr($res_tip_nit['nits_nombres'],0,30); ?></option>
                <?php
        }
        }
        ?>
              </select>
        </div>
        <?php
        }
    else{
    ?>
        <div id="fondo_obli" style="display: none;">
        Fondo de pension obligatoria
        <select name="fon_pen_obligatorio" id="fon_pen_obligatorio">
          <option value="NULL" selected="selected">--Seleccione--</option>
            <?php
              while($res_tip_nit = mssql_fetch_array($con_tip_nit_1)){
        if($con_fon_pensiones['nit_id'] == $res_tip_nit['nit_id']){
      ?>
            <option value="<?php echo $res_tip_nit['nit_id']; ?>" selected="selected"><?php echo substr($res_tip_nit['nits_nombres'],0,30); ?></option>
                <?php
        }
        else{
        ?>
                <option value="<?php echo $res_tip_nit['nit_id']; ?>"><?php echo substr($res_tip_nit['nits_nombres'],0,30); ?></option>
                <?php
        }
        }
        ?>
              </select>
        </div>
        <?php
    }
    ?>
        </td>
  </tr>
  <tr>
    <td>Cotizaci&oacute;n pensi&oacute;n voluntaria</td>
    <td>
        <input name="aso_fon_pen_voluntaria" id="aso_fon_pen_voluntaria" value="SI" type="radio" <?php if($dat_familiares['nits_fon_pen_voluntaria'] == 'SI'){ ?> checked="checked" <?php } ?> disabled="disabled" onclick="volu(this.value)"/>Si
        <input name="aso_fon_pen_voluntaria" id="aso_fon_pen_voluntaria" value="NO"type="radio" <?php if($dat_familiares['nits_fon_pen_voluntaria'] == 'NO'){ ?> checked="checked" <?php } ?> disabled="disabled" onclick="volu(this.value)"/>No
        </td>
    <td>
        <?php if($dat_familiares['nits_fon_pen_voluntaria'] == 'SI'){ ?>
        <div id="fondo_volun" style="display: block;">
        Fondo de pension voluntaria
        <select name="fon_pen_voluntario" id="fon_pen_voluntario" disabled="disabled">
          <option value="NULL" selected="selected">--Seleccione--</option>
            <?php
              while($res_tip_nit_2 = mssql_fetch_array($con_tip_nit_2)){
        if($con_fon_pensiones_voluntarias['nit_id'] == $res_tip_nit_2['nit_id']){
      ?>
            <option value="<?php echo $res_tip_nit_2['nit_id']; ?>" selected="selected"><?php echo substr($res_tip_nit_2['nits_nombres'],0,30); ?></option>
                <?php
        }
        else{
        ?>
                <option value="<?php echo $res_tip_nit_2['nit_id']; ?>"><?php echo substr($res_tip_nit_2['nits_nombres'],0,30); ?></option>
                <?php
        }
        }
        ?>
              </select>
        </div>
        <?php
        }
    else{
    ?>
        <div id="fondo_volun" style="display: none;">
        Fondo de pension voluntaria
        <select name="fon_pen_voluntario" id="fon_pen_voluntario">
          <option value="NULL" selected="selected">--Seleccione--</option>
            <?php
              while($res_tip_nit_2 = mssql_fetch_array($con_tip_nit_2)){
        if($con_fon_pensiones_voluntarias['nit_id'] == $res_tip_nit_2['nit_id']){
      ?>
            <option value="<?php echo $res_tip_nit_2['nit_id']; ?>" selected="selected"><?php echo substr($res_tip_nit_2['nits_nombres'],0,30); ?></option>
                <?php
        }
        else{
        ?>
                <option value="<?php echo $res_tip_nit_2['nit_id']; ?>"><?php echo substr($res_tip_nit_2['nits_nombres'],0,30); ?></option>
                <?php
        }
        }
        ?>
              </select>
        </div>
        <?php
    }
    ?>
        </td>
  </tr>
  <tr>
      <td colspan="4">
		<input type="button" class="art-button" onclick="Atras();" value="<< Atras" target"frame2"/>
        <input type="button" class="art-button" value="Siguiente >>" target="frame2" onclick="Siguiente();"/>
        <?PHP
       if($_SESSION['k_perfil']==13 || $_SESSION['k_perfil']==16)//JEFE DE CONTRATACIÓN Y DIRECTOR EJECUTIVO
       {
       ?>
       <input type="button" class="art-button" value="Modificar" name="modificar" onclick="habilitar();"/>
       <input type="submit" class="art-button" value="Guardar" name="guardar" id="guardar" disabled="disabled"/>
       <?PHP
       }
       ?>
      </td>
  </tr>
  </table>
</center>
</form>
