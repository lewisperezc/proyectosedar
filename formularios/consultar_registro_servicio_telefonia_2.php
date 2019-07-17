<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<script language="JavaScript" src ="../librerias/js/jquery.js"></script>
<script>
function obtenerP(idt){
   $.ajax({
   type: "POST",
   url: "../llamados/trae_tod_lin_tel_por_aso.php",
   data: "id="+idt,
   success: function(msg){
     $("#select2").html(msg);
   }
 });
}

function val_sel_linea(){
	var form = document.form_sel_persona;
	form.submit();
}

</script>
</head>
<body>
<?php
@include_once('clases/nits.class.php');
@include_once('../clases/nits.class.php');
$ins_nits = new nits();
@include_once('../clases/telefonia.class.php');
@include_once('clases/telefonia.class.php');
$ins_telefonia = new telefonia();

$_SESSION['tipo_nit'] = $_POST['tipo_nit'];
$tipo_nit = $_SESSION['tipo_nit'];
$cons_nits = $ins_telefonia->con_nit_con_reg_telefonia($tipo_nit,1);
?>
<form name="form_sel_persona" method="post" action="consultar_registro_servicio_telefonia_3.php" target="frame3">
<center>
	<table bordercolor="#0099CC" border="1">
    	<tr>
           <td>Persona</td>
           <td>
           <select onchange='obtenerP(this.value)' id='select1' name='select1'>
		   <option value='0'>Seleccione...</option>
           <?php while($nits = mssql_fetch_array($cons_nits)){ ?>
              <option value="<?php echo $nits['nit_id']; ?>"><?php echo $nits['nombres'] ?></option> 
            <?php } ?>
		   </select>
	        </td>
        <td>Linea</td>
        <td><select name="select2" id="select2" required x-moz-errormessage="Seleccione Una Opcion Valida">
		     <option value="" >--Seleccione--</option>
		   </select>
        </td>
        </tr>
    </table>
 </center>
</form>
</center>
</body>
</html>