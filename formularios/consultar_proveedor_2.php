<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script>
function verificacion(nit)
{
   $.ajax({
   type: "POST",
   url: "../llamados/calcularVerificacion.php",
   data: "nit="+nit,
   success: function(msg){
     $("#pro_dig_verificacion").val(msg);
   }
 });
 alert("Verifique que el DV corresponda al nit, si no es asi, corriga su entrada");
}

function Atras()
{
	var form=document.datos_proveedor;
	form.action='consultar_proveedor_1.php';
	form.submit();
}

function Habilitar()
{
	 for (i=0;i<document.datos_proveedor.elements.length;i++) 
	  {
        if (document.datos_proveedor.elements[i].disabled) 
		{
          document.datos_proveedor.elements[i].disabled = false;
		  document.datos_proveedor.mod.disabled = true;
		  document.datos_proveedor.gua.disabled = false;
        }
      }
}
</script>
</head>
<body>
<?php
$proveedor_id=$_GET['pro_id'];
include_once('../clases/nits.class.php');
$ins_nits = new nits();
$con_dat_proveedor=$ins_nits->ConDatProPorTipId(3,$proveedor_id);
$res_dat_proveedor=mssql_fetch_array($con_dat_proveedor);
$con_tip_identificacion=$ins_nits->con_tip_identificacion();
$con_regimenes=$ins_nits->cons_regimen();
$con_tip_regimen=$ins_nits->cons_tipo_regimen();
$con_tod_bancos=$ins_nits->cons_bancos();
$con_tod_tip_cuenta=$ins_nits->con_tip_cuenta();

$data_1=$ins_nits->con_ciu_dep_asociado(1,$proveedor_id);
$dat_dep_1=mssql_fetch_array($data_1);
$ciu_1 = $ins_nits->con_ciu_dep_asociado(1,$proveedor_id);
$ciudades=$ins_nits->consultar_ciudades();

function genera_departamentos_1($dep_1)
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido_1(this.id)' disabled='disabled' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
	echo "<option value=''>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		if($row['dep_id'] == $dep_1)
		{
			echo "<option value='".$row['dep_id']."' selected='selected'>".$row['dep_nombre']."</option>";
		}
		else
		{
			echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
		}
	echo "</select>";
}
?>
<form name="datos_proveedor" id="datos_proveedor" method="post" action="../control/modifcar_proveedor.php?pro_id=<?php echo $proveedor_id; ?>">
<center>
  <table>
        <tr>
          <td colspan="4"><h4>Datos Proveedor</h4></td>
        </tr>
        <tr>
          <td colspan="4"><hr></td>
        </tr>
        <tr>
          <td>Raz&oacute;n Social:</td>
            <td><input name="pro_raz_social" id="pro_raz_social" type="text" value="<?php echo $res_dat_proveedor['nits_nombres']; ?>" disabled required/></td>
             <?php $par_nit=explode("-",$res_dat_proveedor['nits_num_documento'],2) ?>
          <td>NIT</td><td><input type="text" readonly="readonly" name="pro_nit" id="pro_nit" onchange="verificacion(this.value)" size="9" value="<?php echo $par_nit[0]; ?>" disabled required/> - <input type="text" name="pro_dig_verificacion" id="pro_dig_verificacion" size="1" value="<?php echo $par_nit[1]; ?>" disabled required readonly="readonly"/></td>
         </tr>  
        <tr>
          <td>Representante Legal:</td>
            <td><input name="pro_representante" id="pro_representante" type="text" value="<?php echo $res_dat_proveedor['nits_representante']; ?>" disabled/></td>
          <td>Tipo de Identificaci&oacute;n</td>
            <td>
              <select name="pro_tip_documento" id="pro_tip_documento" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled>
                <option value="">--Seleccione--</option>
                <?php
        while($res_tip_identificacion=mssql_fetch_array($con_tip_identificacion))
        {
          if($res_tip_identificacion['tip_ide_id']==$res_dat_proveedor['tip_ide_id'])
          {
        ?>
                <option value="<?php echo $res_tip_identificacion['tip_ide_id'];?>" selected><?php echo $res_tip_identificacion['tip_ide_nombre'] ?></option>
                <?php
          }
          else
          {
          ?>
                    <option value="<?php echo $res_tip_identificacion['tip_ide_id'];?>"><?php echo $res_tip_identificacion['tip_ide_nombre'] ?></option>
                    <?php
          }
        }
        ?>
                </select>
            </td>
          </tr>  
          <tr>
            <td>R&eacute;gimen</td>
            <td><select name="pro_regimen" id="pro_regimen" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled>
              <option value="">--Seleccione--</option>
                <?php
        while($res_regimenes=mssql_fetch_array($con_regimenes))
        {
          if($res_regimenes['reg_id']==$res_dat_proveedor['reg_id'])
          {
        ?>
                <option value="<?php echo $res_regimenes['reg_id'];?>" selected><?php echo $res_regimenes['reg_nombre'];?></option>
                <?php
                  }
          else
          {
        ?>
                  <option value="<?php echo $res_regimenes['reg_id'];?>"><?php echo $res_regimenes['reg_nombre'];?></option>
                <?php
          }
        }
                ?>
                </select>
                </td>
                <td>Tipo R&eacute;gimen</td>
                <td><select name="pro_tip_regimen" id="pro_tip_regimen" required x-moz-errormessage="Seleccione Una Opcion Valida" disabled>
                  <option value="">Seleccione el Tipo de R&eacute;gimen</option>
                    <?php
          while($res_tip_regimen=mssql_fetch_array($con_tip_regimen))
          {
            if($res_tip_regimen['tip_reg_id']==$res_dat_proveedor['tip_reg_id'])
            {
          ?>
                    <option value="<?php echo $res_tip_regimen['tip_reg_id'];?>" selected><?php echo $res_tip_regimen['tip_reg_nombre'];?></option>
                    <?php
            }
            else
            {
          ?>
                    <option value="<?php echo $res_tip_regimen['tip_reg_id'];?>"><?php echo $res_tip_regimen['tip_reg_nombre'];?></option>  
                    <?php
            }
          }
          ?>
                </select>
                </td>
            </tr>
            <tr>
              <td>Departamento Nacimiento</td>
       <td><?php genera_departamentos_1($dat_dep_1['dep_id']); ?></td>
     <td>Ciudad Nacimiento</td>
     <td><select name="select2" id="select2" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
           <?php
          while($row = mssql_fetch_array($ciu_1))
        {
          if($ciudades['ciu_id'] == $row['ciu_id'])
          {
      ?>
                  <option value="<?php echo $row['ciu_id']; ?>" selected="selected"/><?php echo $row['ciu_nombre']; ?></option>
            <?php
          }
          else
          {
      ?>
                <option value="<?php echo $row['ciu_id']; ?>"/><?php echo $row['ciu_nombre']; ?></option>         
            <?php
          }
        }
        ?>
       </select>
       </td>
           </tr>
           <tr>
              <td>Direcci&oacute;n:</td>
              <td><input type="text" name="pro_direccion" id="pro_direccion" value="<?php echo $res_dat_proveedor['nits_dir_residencia']; ?>" disabled required/></td>
              <td>T&eacute;lefono:</td>
              <td><input name="pro_telefono" id="pro_telefono" type="number" value="<?php echo $res_dat_proveedor['nits_tel_residencia']; ?>" disabled required/>
           </tr>
           <tr>
              <td>Contacto</td>
              <td><input name="pro_contacto" id="pro_contacto" type="text" value="<?php echo $res_dat_proveedor['nits_contacto']; ?>" disabled/></td>
              <td>Correo electr&oacute;nico:</td>
                <td><input type="email" name="pro_correo" id="pro_correo" value="<?php echo $res_dat_proveedor['nits_cor_electronico']; ?>" disabled required/></td>
           </tr>
           <tr>
              <td>Fax</td>
                <td><input type="text" name="pro_fax" id="pro_fax" value="<?php echo $res_dat_proveedor['nits_num_celular']; ?>" disabled/></td>
                <td>Banco</td>
                <td>
                  <select name="pro_banco" id="pro_banco" disabled>
                    <option value="NULL">--Seleccione--</option>
                    <?php
          echo "el codigo del banco es: ".$res_dat_proveedor['nits_ban_id']."<br>";
          while($res_tod_bancos=mssql_fetch_array($con_tod_bancos))
          {
            if($res_tod_bancos['cod_banco']==$res_dat_proveedor['cod_banco'])
            {
          ?>
                    <option value="<?php echo $res_tod_bancos['cod_banco'];?>" selected><?php echo substr($res_tod_bancos['banco'],0,30);?></option>
                    <?php
            }
            else
            {
          ?>
                    <option value="<?php echo $res_tod_bancos['cod_banco'];?>"><?php echo substr($res_tod_bancos['banco'],0,30);?></option>
                    <?php
            }
          }
          ?> 
                    </select>
                </td>
           </tr>
           <tr>
              <td>Tipo Cuenta</td>
                <td><select name="pro_tip_cuenta" id="pro_tip_cuenta" disabled>
                  <option value="NULL">--Seleccione--</option>
                    <?php
          while($res_tod_tip_cuenta=mssql_fetch_array($con_tod_tip_cuenta))
          {
            if($res_tod_tip_cuenta['tip_cue_ban_id']==$res_dat_proveedor['tip_cue_ban_id'])
            {
          ?>
                    <option value="<?php echo $res_tod_tip_cuenta['tip_cue_ban_id']; ?>" selected><?php echo $res_tod_tip_cuenta['tip_cue_ban_nombre']?></option>
                    <?php
            }
            else
            {
          ?>
                    <option value="<?php echo $res_tod_tip_cuenta['tip_cue_ban_id'];?>"><?php echo $res_tod_tip_cuenta['tip_cue_ban_nombre']?></option>
                    <?php
            }
          }
          ?>
                  </select>
                </td>
                <td>N&deg; de Cuenta:</td>
                <td><input name="pro_num_cuenta" id="pro_num_cuenta" type="text" value="<?php echo $res_dat_proveedor['nits_num_cue_bancaria']; ?>" disabled/></td>               
                <tr><td colspan='2'>Dias provis&iacute;on</td><td colspan='2'><input type='text' name='diaPro' id='diaPro' value='<?php echo $res_dat_proveedor['nits_diaProvision']; ?>' disabled /></td></tr>
           </tr>
           <tr>
            <td colspan="4">
            <input type="button" class="art-button" name="atr" id="atr" onClick="Atras();" value="<< Atras"/>
            <input type="button" class="art-button" name="mod" id="mod" onClick="Habilitar();" value="Modificar"/>
            <input name="gua" id="gua" value="Guardar" type="submit" class="art-button" disabled/>
            </td>
           </tr>              
        </table>
</center>	
</form>
</body>
</html>