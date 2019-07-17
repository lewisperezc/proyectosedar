<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="iso-8859-1">
<title>Untitled Document</title>
<script type="text/javascript" language="javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<script>
function verificacion(nit)
{
   $.ajax({
   type: "POST",
   url: "../llamados/calcularVerificacion.php",
   data: "nit="+nit,
   success: function(msg){
     $("#dig_veri").val(msg);
   }
 });
 alert("Verifique que el DV corresponda al nit, si no asi, corriga su entrada");
}
function Habilitar()
{
	 for (i=0;i<document.datos_hospital.elements.length;i++) 
	  {
        if (document.datos_hospital.elements[i].disabled) 
		{
          document.datos_hospital.elements[i].disabled = false;
		  document.datos_hospital.modifica.disabled = true;
		  document.datos_hospital.gua_mof_hospital.disabled = false;
        }
      }
}

function Atras()
{
	var form=document.datos_hospital;
	form.action='consultar_hospital_1.php';
	form.submit();
}
</script>
</head>
<body>
<?php
$hospital=$_GET['hos_id'];
include_once('../clases/departamento.class.php');
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/regimenes.class.php');
include_once('../clases/tipo_regimen.class.php');
include_once('../clases/cuenta.class.php');
$ins_regimen=new regimenes();
$ins_nits=new nits();
$ins_tip_regimen=new tipo_regimen();

$ins_cuenta = new cuenta();
$cuentas="1305";
$con_cue_uni_fincional=$ins_cuenta->con_cue_menores($cuentas);

$con_tod_tip_regimenes=$ins_tip_regimen->cons_tipo_regimen();
$con_tod_regimentes=$ins_regimen->cons_regimen();
$con_dat_hospital=$ins_nits->consulta_hospital_exacto(8,$hospital);
$res_dat_hospital=mssql_fetch_array($con_dat_hospital);

$data_1=$ins_nits->con_ciu_dep_asociado(1,$hospital);
$dat_dep_1=mssql_fetch_array($data_1);
$ciu_1 = $ins_nits->con_ciu_dep_asociado(1,$hospital);
$ciudades=$ins_nits->consultar_ciudades();


function genera_departamentos_1($dep_1)
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido_1(this.id)' disabled='disabled' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
	echo "<option value='0'>--Seleccione--</option>";
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
<form name="datos_hospital" id="datos_hospital" method="post" action="../control/modifcar_hospital.php?hospital_id=<?php echo $hospital; ?>">
	<center>
    <table>
    <tr>
          <td colspan="4"><h4>Datos Hospital</h4></td>
    </tr>
        <tr>
          <td colspan="4"><hr></td>
        </tr>
        <tr>
          <td>Raz&oacute;n Social</td>
          <td><input name="hos_nombre" id="hos_nombre" type="text" value="<?php echo $res_dat_hospital['nits_nombres']; ?>" size="50" disabled required/></td>
          <?php $par_nit=explode("-",$res_dat_hospital['nits_num_documento'],2) ?>
          <td>NIT</td><td><input readonly="readonly" type="text" name="hos_nit" id="hos_nit" onchange="verificacion(this.value)" size="9" value="<?php echo $par_nit[0]; ?>" disabled required/> - <input type="text" name="hos_dig_verificacion" id="hos_dig_verificacion" size="1" value="<?php echo $par_nit[1]; ?>" disabled required readonly="readonly"/></td>
        <tr>
          <input name="hos_codigo" id="chos_codigo" type="hidden" value="<?php echo $res_dat_hospital['cen_cos_codigo']; ?>" disabled/>
          <td>Clase IPS</td>
          <td>
            <select id='clase_hos' name='clase_hos' disabled>
              <?php
                if($res_dat_hospital['tip_con_nit_id']==1)
                    echo "<option value='1' selected>Publica</option><option value='2'>Privada</option>";
                else
                    echo "<option value='1'>Publica</option><option value='2' selected>Privada</option>";
              ?>
           </select></td>
        </tr>    
        <tr>
          <td>Regimen</td>
          <td><select name="hos_regimen" id="hos_regimen" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
          <option value="">--Seleccione una Opcion--</option>
          <?php
      while($res_tod_regimentes=mssql_fetch_array($con_tod_regimentes))
      {
        if($res_tod_regimentes['reg_id']==$res_dat_hospital['reg_id'])
        {
      ?>
              <option value="<?php echo $res_tod_regimentes['reg_id'];?>" selected><?php echo $res_tod_regimentes['reg_nombre'];?></option>
          <?php
        }
        else
        {
      ?>
          <option value="<?php echo $res_tod_regimentes['reg_id'];?>"><?php echo $res_tod_regimentes['reg_nombre'];?></option>
            <?php
        }
      }
      ?>
            </select></th>
            <td>Tipo Regimen</td>
            <td><select name="hos_tip_regimen" id="hos_tip_regimen" disabled required x-moz-errormessage="Seleccione Una Opcion Valida">
              <option value="">--Seleccion una Opcion--</option>
              <?php
      while($res_tod_tip_regimenes=mssql_fetch_array($con_tod_tip_regimenes))
      {
        if($res_tod_tip_regimenes['tip_reg_id']==$res_dat_hospital['tip_reg_id'])
        {
      ?>
              <option value="<?php echo $res_tod_tip_regimenes['tip_reg_id'];?>" selected><?php echo $res_tod_tip_regimenes['tip_reg_nombre'];?></option>
          <?php
        }
        else
        {
        ?>
                <option value="<?php echo $res_tod_tip_regimenes['tip_reg_id'];?>"><?php echo $res_tod_tip_regimenes['tip_reg_nombre'];?></option>
        <?php
        }
      }
      ?>
            </select></td>
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
         <td>Direcci&oacute;n</td>
         <td><input name="hos_direccion" id="hos_direccion" type="text" value="<?php echo $res_dat_hospital['nits_dir_residencia']; ?>" disabled required/></td>
         <td>Telef&oacute;no</td>
         <td><input type="text" name="hos_telefono" id="hos_telefono" value="<?php echo $res_dat_hospital['nits_tel_residencia']; ?>" disabled required/></td>
        </tr>
        <tr>
         <td>Fax</td>
         <td><input type="text" name="hos_fax" id="hos_fax" value="<?php echo $res_dat_hospital['nits_num_celular']; ?>" disabled/></td>
         <td>Representante</td>
         <td><input type="text" name="hos_representante" id="hos_representante" value="<?php echo $res_dat_hospital['nits_representante']; ?>" disabled/></td>
        </tr>
        <tr>
         <td>Correo Electronico</td>
         <td><input  type="email" name="hos_correo" id="hos_correo" value="<?php echo $res_dat_hospital['nits_cor_electronico']; ?>" disabled required/></td>
         <td>Contacto</td>
         <td><input type="text" name="hos_contacto" id="hos_contacto" value="<?php echo $res_dat_hospital['nits_contacto']; ?>" disabled/></td>
        </tr>
        
        <td>Unidad funcional</td>
         <td>
         <select name="nit_uni_funcional" id="nit_uni_funcional" disabled>
         	<option value="">Seleccione</option>
         <?php
         while($res_cue_uni_fincional=mssql_fetch_array($con_cue_uni_fincional))
		 {
		 	if($res_cue_uni_fincional['cue_id']==$res_dat_hospital['nit_uni_funcional'])
			{
		 ?>
		 		<option selected value="<?php echo $res_cue_uni_fincional['cue_id']; ?>"><?php echo $res_cue_uni_fincional['cue_id']."-".$res_cue_uni_fincional['cue_nombre']; ?></option>
		 <?php
		 	}
			else 
			{
			?>
				<option value="<?php echo $res_cue_uni_fincional['cue_id']; ?>"><?php echo $res_cue_uni_fincional['cue_id']."-".$res_cue_uni_fincional['cue_nombre']; ?></option>
			<?php
			}

		 }
         ?>
         </select>
         	
         </td>
        
        
        <tr>
         <td colspan="4">
         <input type="button" class="art-button" value="<< Atras" onclick="Atras()"/>
         <input type="button" class="art-button" id="modifica" name="modifica" value="Modificar" onclick="Habilitar();" />
         <input type="submit" class="art-button" id="gua_mof_hospital" name="gua_mof_hospital" value="Guardar" disabled/>
         </td>
        </tr>
  </table>
  </center>
</form>
</body>
</html>