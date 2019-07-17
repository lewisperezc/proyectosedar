<?php session_start();
include_once('../clases/departamento.class.php');
include_once('../conexion/conexion.php');
include_once('../clases/centro_de_costos.class.php');
include_once('../clases/nits.class.php');
include_once('../clases/cuenta.class.php');
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
$ins_cuenta = new cuenta();
$cuentas="1305";
$con_cue_uni_fincional=$ins_cuenta->con_cue_menores($cuentas);

?>
<script type="text/javascript" language="javascript" src="../librerias/js/validacion_num_letras.js"></script>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<script>
function validar_vacios(){
if(document.crear_hospital.costo.value==0)
	alert("* Seleccion si es centro de costo proncipal");
else
     document.crear_hospital.submit();
}
function valida_vacios2(){
var mensaje="* Estos campos son obligatorios:\n";
var campos="";
if(document.crear_hospital2.raz.value=="")
	campos+="* Razon Social no debe ser vacio\n";
if(document.crear_hospital2.num_nit.value=="")
	campos+="* Nit Numero no debe ser vacio\n";
if(document.crear_hospital2.reg.selectedIndex==0)
	campos+="* Seleccione el regimen\n";
if(document.crear_hospital2.tip_reg.selectedIndex==0)
	campos+="* Seleccione el tipo de regimen\n";
if(document.crear_hospital2.select1.value==0)
	campos+="* Seleccione un Departamento\n";
if(document.crear_hospital2.select2.value==0)
	campos+="* Seleccione una ciudad\n";
if(document.crear_hospital2.direccion.value=="")
	campos+="* La direccion no debe ser vacia \n";
if(document.crear_hospital2.telefono.value=="")
	campos+="* El telefono no debe ser vacio\n";
if(document.crear_hospital2.correo.value=="")
	campos+="* El correo no debe ser vacio\n";
if(document.crear_hospital2.clase_hos.value==0)
  campos+="* Debe seleccionar una clase de IPS";
if(campos!="")
	alert(mensaje+campos);
else
	document.crear_hospital2.submit();
}

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

</script>

<?php
function genera_departamentos()
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido_1(this.id)'>";
	echo "<option value='0'onclick='validar_vacios();'>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		echo "<option value='".$row['dep_id']."'>".$row['dep_nombre']."</option>";
	echo "</select>";
}

 $dato= new nits();
 $reg=$dato->cons_regimen();
 $tip_hos = $dato->tip_hospital();
 $tip_reg=$dato->cons_tipo_regimen();
 $ciu=$dato->consultar_ciudades(); ?>    
 <form name="crear_hospital2" method="post" action="../control/guardar_hospital.php">
	<center>
		<table>
        	<tr align="center">
        	<td colspan="6"><h4>Creaci&oacute;n Hospital</h4></td>            
		    </tr>
            <tr align="center">
        	<td colspan="6"><h4>Datos B&aacute;sicos</h4></td>
        </tr>
        <tr>
          <td>Raz&oacute;n Social</td>
          <td><input name="raz" type="text" /></td>
          <td>NIT</td>
          <td>
            <input type="text" name="num_nit" id="num_nit" size="10"/> - 
            <input type="text" name="dig_veri" id="dig_veri" size="1" /></td>
        	<?php
			if($principal==1 || $principal==2)
			{
			?> 
        	<?php
			}	
			?>
       	<tr>
          <td>Clase IPS
          <input name="cod_costo" type="hidden" />
          </td>
          <td>
           <select id='clase_hos' name='clase_hos'>
            <option value='0'>Seleccione...</option>
            <option value='1'>Publica</option>
            <option value='2'>Privada</option>
           </select>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
       	  </tr>    
        	<tr>
        	  <td>Regimen</td>
        	  <td><select name="reg">
        	    <option value="0">--Seleccione una Opcion--</option>
        	    <?php
			while($row=mssql_fetch_array($reg)){
			?>
        	    <option value="<?php echo $row['reg_id'];?>"><?php echo $row['reg_nombre'];?></option>
        	    <?php
			                                   }
			?>
      	    </select></td>
        	  <td>Tipo Regimen</td>
        	  <td><select name="tip_reg">
        	    <option value="0">--Seleccion una Opcion--</option>
        	    <?php
			while($row= mssql_fetch_array($tip_reg)){
			?>
        	    <option value="<?php echo $row['tip_reg_id'];?>"><?php echo $row['tip_reg_nombre'];?></option>
        	    <?php
			                                        }
			?>
      	    </select></td>
           	</tr>
            <tr>
              <td>Departamento</td>
              <td><?php genera_departamentos();?></td>
              <td>Ciudad</td>
              <td><select name="select2" id="select2">
                <option value="0">--Seleccione una Ciudad</option>
              </select></td>
   		  </tr>
       <tr>
         <td>Direcci&oacute;n</td>
         <td><input name="direccion" type="text" /></td>
         <td>Telef&oacute;no</td>
         <td><input type="text" name="telefono" /></td>
       	</tr>
       <tr>
         <td>Fax</td>
         <td><input type="text" name="fax" /></td>
         <td>Representante</td>
         <td><input type="text"  name="representante"/></td>
       	</tr>
       	<tr>
         <td>Correo Electronico</td>
         <td><input  type="text" name="correo" /></td>
         <td>Contacto</td>
         <td><input type="text" name="contacto" /></td>
         </tr>
         
         <td>Unidad funcional</td>
         <td>
         <select name="nit_uni_funcional" id="nit_uni_funcional">
         	<option value="">Seleccione</option>
         <?php
         while($res_cue_uni_fincional=mssql_fetch_array($con_cue_uni_fincional))
		 {
		 ?>
		 	<option value="<?php echo $res_cue_uni_fincional['cue_id']; ?>"><?php echo $res_cue_uni_fincional['cue_id']."-".$res_cue_uni_fincional['cue_nombre']; ?></option>
		 <?php
		 }
         ?>
         </select>
         	
         </td>
         
       <tr align="center">
         <td colspan="4"><input type="button" class="art-button" value="Guardar"   onclick="valida_vacios2();" /></td>
       </tr>
        </table>
	</center>
</form>