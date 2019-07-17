<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />
<script language="javascript" src="../librerias/js/datetimepicker.js"></script>


<script>
function enviar()
{
	if(document.consul_saldo.selecdoc.selectedIndex == 0)
	{
		alert("Seleccione ......");
	}
	else
	{
		document.consul_saldo.submit();
	}
}
function enviar1()
{
	if((document.consul_saldo.selectip.selectedIndex == 0) || (document.consul_saldo.selecdocu.selectedIndex == 0 ))
	{
		alert("Seleccione un documento");
		document.consul_saldo.selecdocu.focus();
	}
	else
	{
		document.consul_saldo.submit();
	}
}
</script>
<form name="consul_saldo"  id="consul_saldo" method="post" action="consultar_moviminetos2.php" target="frame3">
<center>
	<?php
@include_once('../conexion/conexion.php');
include_once('../clases/saldos.class.php');
$saldos =new saldos();


$_SESSION['consaldos'] = $_POST['consaldos'];

if ($_SESSION['consaldos'])
{
	if ($_SESSION['consaldos']==1)
	{
?>      
  <table  border="" align="center">
  <td>seleccione el documento a editar
    <select name="selecdoc">
      <option value="0">--Seleccione......--</option>
      <?php
			  $sql= "select DISTINCT  mov_compro
			from dbo.movimientos_contables ";
	$ejecutar = mssql_query($sql);	
	///////////////////////////////////////////////////////////// 		 	 
	          while($eje= mssql_fetch_array($ejecuta))
	          {
	          ?>
      <option  onclick="enviar()"; value="<?php echo $eje[0];?>"> <?php echo $eje[0]; ?></option>
      <?php
	           }
       ?> <option onclick="enviar();" value="999">todos movimientos contables</option> 
    </select></td>
  </table>
	 
	<?php
	 }
	  else if($_SESSION['consaldos']==2){
		  ?>
             <table border="" align="center">
  <td>SELECCIONE  EL DOCUMENTO A CONSULTAR ESTADO
    <select name="selecdocu">
      <option value="0">--Seleccione......--</option>
      <?php
			  	$sql= "select DISTINCT  sal_doc_id
			from dbo.saldos_documentos";
			$ejecutar = mssql_query($sql);
			
    $sqlt= "SELECT tip_cue_id,tip_cue_nombre
             FROM  dbo.tipo_cuenta";
		$ejetip =mssql_query($sqlt);
			 	 
	          while($eje= mssql_fetch_array($ejecutar))
	          {
	          ?>
      <option   value="<?php echo $eje[0];?>"><?php echo $eje[0]; ?></option>
      <?php
	           }
			    ?>
                <option  value="9999">todos los saldos de documentos</option>
    </select></td>
     <td>SELECCIONE TIPO
    <select name="selectip">
      <option value="0">--Seleccione......--</option>
      <?php
			  ////////////////////////////////////////////////////////////////// 		 		 	 
	          while($tip= mssql_fetch_array($ejeti))
	          {
	          ?>
      <option  onclick="enviar1();"value="<?php echo $tip[0];?>"><?php echo  $tip[1]; ?></option>
      <?php
	           }
	           ?>
        <option onclick="enviar1();" value="9999">todos los saldos de documentos</option> 
    </select></td>
  </table>
           <?php
             }
} 
	 else{
		 echo "<script type=\"text/javascript\">alert(\"No se pudo intentelo de nuevo!!\");</script>";
		 }           

?>
</center>
</form>