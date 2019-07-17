<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<link rel="stylesheet" type="text/css" href="estilos/screen.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="../estilos/screen.css" media="screen"/>
<script src="../librerias/ajax/select_deptos_2.js"></script>
<script type="text/javascript" src="../librerias/js/jquery-1.5.0.js"></script>
<script type="text/javascript" src="../librerias/js/separador.js"></script>
<script type="text/javascript" language="javascript">
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
 alert("Verifique que el DV corresponda al nit, si no asi, corriga su entrada");
}

function ValidaDocumento(numero)
 {
   $.ajax({
   type: "GET",
   url: "../llamados/valida_documento.php",
   data: "docum="+numero,
   success: function(msg)
    {
	  var res=0;
	  if(msg>0)
	  {
	  	alert("Ya hay un nit creado con este numero de documento.");
	  	res=1
	  }
	  else
	  {
	  	res=2
	  }
          document.crear_proveedor.retorno.value=res;
    }
   });
 }
/*
$(document).ready(function()
{
	$("#gua").click(function(evento)
	{
		var form=document.crear_proveedor;
		var nit=form.pro_nit.value;
		var ver=form.pro_dig_verificacion.value;
		var num_ducumento=nit+"-"+ver;
		var ret=form.retorno.value;
		ValidaDocumento(num_ducumento);
		if(ret==1)
			return false;
		else
		if(ret==2)
			return true;
			
    });
});
*/
</script>
<?php
//include_once('clases/nits.class.php');
include_once('../clases/nits.class.php');
$tiponit = new nits();
$tipoidentificacion=$tiponit->con_tip_identificacion();
$regimenes =$tiponit->cons_regimen();
$tipo_regimen= $tiponit->cons_tipo_regimen();
$bancos = $tiponit->cons_bancos();
$tipocuenta=$tiponit->con_tip_cuenta();
function genera_departamentos()
{
    include_once('../clases/departamento.class.php');
    $depto = new departamento();
    $list_deptos = $depto->buscar_departamentos();
	echo "<select name='select1' id='select1' onChange='cargaContenido_1(this.id)' required x-moz-errormessage='Seleccione Una Opcion Valida'>";
	echo "<option value=''>--Seleccione--</option>";
	while($row = mssql_fetch_array($list_deptos))
		echo "<option value='".$row['dep_id']."'>".strtoupper($row['dep_nombre'])."</option>";
	echo "</select>";
}
?>
<form name="crear_proveedor" id="crear_proveedor" method="post" action="../control/guardar_proveedor.php">
  <center>
    <table>
      <tr>
            <th colspan="4">CREACI&Oacute;N PROVEEDOR</th>
        </tr>
        <tr>
            <th colspan="4">Datos B&aacute;sicos</th>
        </tr>
        <tr>
            <td colspan="4"><hr /></td>
        </tr>
        <tr>
            <td>Raz&oacute;n Social:</td>
            <td><input name="pro_nombre" id="pro_nombre" type="text" required="required"/></td>
            <td>N&uacute;mero de Identificaci&oacute;n:</td>
            <td><input type="text" name="pro_nit" id="pro_nit" maxlength="20" size="12" onchange="ValidarDocumentoTercero2(this.value,'pro_nit','gua');"></td>
         </tr>  
        <tr>
          <td>Representa Legal:</td>
            <td><input name="pro_representante" id="pro_representante" type="text" required="required"></td>
          <td>Tipo de Identificaci&oacute;n</td>
            <td>
              <select name="pro_tip_documento" id="pro_tip_documento" required x-moz-errormessage="Seleccione Una Opcion Valida">
                <option value="">Seleccione Tipo de Identificaci&oacute;n</option>
                <?php
        while($row= mssql_fetch_array($tipoidentificacion))
        {
        ?>
                <option value="<?php echo $row['tip_ide_id'];?>"><?php echo $row['tip_ide_nombre'] ?></option>
                <?php
        }
        ?>
                </select>
            </td>
          </tr>  
          <tr>
            <td>R&eacute;gimen</td>
            <td><select name="pro_regimen" id="pro_regimen" required x-moz-errormessage="Seleccione Una Opcion Valida">
              <option value="">Seleccione el R&eacute;gimen </option>
                <?php
        while($row=mssql_fetch_array($regimenes)){
        ?>
              <option value="<?php echo $row['reg_id'];?>"><?php echo strtoupper($row['reg_nombre']);?></option>
                <?php
        }
                ?>
                </select>
            </td>
            <td>Tipo R&eacute;gimen</td>
            <td><select name="pro_tip_regimen" id="pro_tip_regimen" required x-moz-errormessage="Seleccione Una Opcion Valida">
              <option value="">Seleccione el Tipo de R&eacute;gimen</option>
            <?php
        while($row=mssql_fetch_array($tipo_regimen))
        {
      ?>
                <option value="<?php echo $row['tip_reg_id'];?>"><?php echo $row['tip_reg_nombre'];?></option>
            <?php
        }
      ?>
            </select>
            </td>
         </tr>
            <tr>
              <td>Departamento</td>
                <td><?php genera_departamentos();?></td>
                <td>Ciudad</td>
                <td><select name="select2" id="select2" disabled="disabled" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option  value="" >--Seleccione--</option>
            </select>
            </td>
           </tr>
           <tr>
              <td>Direcci&oacute;n:</td>
              <td><input type="text" name="pro_direccion" id="pro_direccion" required="required" /></td>
              <td>T&eacute;lefono:</td>
              <td><input name="pro_telefono" id="pro_telefono" type="text" required="required"/>
           </tr>
           <tr>
              <td>Contacto</td>
              <td><input name="pro_contacto" id="pro_contacto" type="text" /></td>
              <td>Correo electr&oacute;nico:</td>
                <td><input type="email" name="pro_correo" id="pro_correo" required="required"/></td>
           </tr>
           <tr>
              <td>Fax</td>
                <td><input type="text" name="pro_fax" id="pro_fax" /></td>
                <td>Banco</td>
                <td>
                  <select name="pro_banco" id="pro_banco">
                    <option value="NULL">Seleccione el Banco</option>
                    <?php
          while($row =mssql_fetch_array($bancos)){
          ?>
                    <option value="<?php echo $row ['cod_banco'];?>"><?php echo substr($row['banco'],0,30);?></option>
                    <?php
          }
          ?> 
                    </select>
                </td>
           </tr>
           <tr>
              <td>Tipo Cuenta</td>
                <td><select name="pro_tip_cuenta" id="pro_tip_cuenta">
                  <option value="NULL">Seleccione el Tipo Cuenta</option>
                    <?php
          while($row=mssql_fetch_array($tipocuenta))
          {
          ?>
                      <option value="<?php echo $row['tip_cue_ban_id'];?>"><?php echo $row['tip_cue_ban_nombre']?></option>
                    <?php
          }
          ?>
                  </select>
                </td>
                <td>N&deg; de Cuenta:</td>
                <td><input name="pro_num_cuenta" id="pro_num_cuenta" type="text" /></td>               
           </tr>
           <tr><td colspan='2'>Dias provis&iacute;on</td><td colspan='2'><input type='text' name='diaPro' id='diaPro' value='0'></td></tr>
           <tr>
                <th colspan="4">
                <input type="hidden" name="retorno" id="retorno"  />
                <input value="Guardar" type="submit" class="art-button" name="gua" id="gua"/></th>
           </tr>
        </table>
      </td>
  </center>	
</form>