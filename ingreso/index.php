<?php session_start();
/*include_once('../clases/usuario.class.php');
$ins_usuario = new usuario();
$cerrar_sesion = $ins_usuario->cerrar_sesion();*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link rel="stylesheet" type="text/css" href="../estilos/limpiador.css" media="screen"/>
  <link rel="stylesheet" type="text/css" href="../estilos/estilos_ingreso.css" media="screen"/>
  <link type="image/x-icon" href="../imagenes/Rollover.png" rel="shortcut icon"/>
  <title>Sistema Sedar</title>
  
  	<script src="../librerias/js/jquery-1.5.0.js"></script>
  	
	<script>
	function deshabilitar()
	{
		$("#form_ingreso").submit(function(){return false;});
		alert('Verifique su navegador, recuerde que para que el sistema funciona de manera correcta debe ingresar por MOZILLA FIREFOX.');
	}
  	</script>
</head>
<body>
<?php
@include_once('clases/mes_contable.class.php');
@include_once('../clases/mes_contable.class.php');
@include_once('clases/varios.class.php');
@include_once('../clases/varios.class.php');

$ins_varios=new varios();

$user_agent=$_SERVER['HTTP_USER_AGENT'];
$con_navegador=$ins_varios->getBrowser($user_agent);

$ins_mesContable=new mes_contable();
$estados=2;
$lis_anos = $ins_mesContable->ObtenerAniosPorEstado($estados);
?>
<div class="contorno">
	<div class="cabecera"></div>
  		<div class="formulario">
			<div class="formu">
    		<form name="form_ingreso" id="form_ingreso" method="post" action="../control/ingreso/ingreso.php">
           	<fieldset>
           	<legend>Iniciar Sesi&oacute;n</legend>
           	<table>
           		<tr><td align="right"><label>Usuario: </label><input name="usu" type="text" id="usu" class="campo" required="required" autofocus="autofocus" /></td></tr>
           <tr>
           	<td><label>Contrase&ntilde;a:</label><input name="pass" type="password" id="pass" class="campo" required="required"/></td>
           </tr>
           <tr>
           	<td align="right"><label>A&ntilde;o Contable</label>
            <select name="anio_contable" id="anio_contable" required x-moz-errormessage="Seleccione Una Opcion Valida">
            <option value="">--</option>
        	<?php
			       while($row=mssql_fetch_array($lis_anos))
              {
                echo "<option value='".$row['ano_con_id']."'>".$row['ano_con_id']."</option>";
              }
			?>
    		</select>
            </td>
           </tr>    
           <tr><td align="center"><input name="boton" id="boton" type="submit" value="Entrar" class="boton" /></td></tr>
           <tr><td align="center"><a href="javascript:void()" onclick="modificar_usuario.php" class="a">Olvid&eacute; Mi Usuario &oacute; Contrase&ntilde;a</a></td></tr>
           </table>
           </fieldset>
           <?php
			if($con_navegador!=6)//VALIDAR SI ESTÁ POR MOZILLA FIREFOX
           	{
           		echo "<script>deshabilitar();</script>";
           	?>
           		<table>
           			<tr>
           				<th style="color: red;">USTED EST&Aacute; TRATANTO DE INGRESAR POR UN NAVEGADOR DIFERENTE A <b>MOZILLA FIREFOX</b>, POR FAVOR VERIFIQUE PARA CONTINUAR CON EL INGRESO.</th>
           			</tr>
           		</table>
           	<?php	
           	}
		   	?>
       </form>
    </div>
  </div>
   <div class="piepagina">Dise&ntilde;o Y Desarrollo - Tecnologia e Informatica SEDAR</div>
</div>
</body>