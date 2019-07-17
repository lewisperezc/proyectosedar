<?php
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/credito.class.php');
$ins_credito = new credito();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Descuento Creditos</title>
</head>
<body>
<form method="post" name="des_nomina" id="des_nomina" action="../control/des_creditos.php">
<center>
 <table border="1" bordercolor="#0099CC" id="mitabla">
  <tr>
   <th>AFILIADO/EMPLEADO</th>
   <th>CREDITO</th>
   <th>INTERES</th>
   <th>CAPITAL</th>
   <th>VALOR CUOTA</th>
   <th>DESCONTAR</th>
   <th>ELIMINAR DEL RECAUDO</th>
  </tr>
 <?php
 	$sum_interes=0;
	$sum_capital=0;
	$sum_couta=0;
    /******************************DESCONTAR CREDITOS*************************************/
	$cred_nit = $ins_credito->buscar_descuento($_GET['desc']);
	$interes=0;$capital=0;$cuota=0;$p=0;
	if($cred_nit)
	{
	  while($dat_credito = mssql_fetch_array($cred_nit))
		{ 
		 echo "<tr>";
		 echo "<td><input type='hidden' name='nit".$p."' id='nit".$p."' value='".$_GET['desc']."' /><input type='text' size='35' name='nits".$p."' id='nits".$p."' value='".$dat_credito['nombre']."' /></td><td><input type='hidden' name='num_cuota".$p."' id='num_cuota".$p."' value='".$dat_credito['des_cre_id']."' /><input type='text' name='credito".$p."' id='credito".$p."' value='".$dat_credito['cre_id']."' /></td><td><input type='text' name='interes".$p."' id='interes".$p."' value='".$dat_credito['des_cre_interes']."' onchange='sumaCuota($p);' /></td>";
		 echo "<td><input type='text' name='capital".$p."' id='capital".$p."' value='".$dat_credito['des_cre_capital']."' onchange='sumaCuota($p);' /></td><td><input type='text' name='total".$p."' id='total".$p."' value='".$dat_credito['des_cre_total']."' /></td>";
		 echo "<td><input type='radio' name='descontar".$p."' id='descontar".$p."' /></td>";
     echo "<td><input type='radio' name='eliminar".$p."' id='eliminar".$p."' /><input type='hidden' name='des_cre_id' id='des_cre_id' value='".$dat_credito['des_cre_id']."'  /></td>";
     echo "<input type='hidden' name='recibo' id='recibo' value='".$_GET['recibo']."' /></td>";
		 echo "</tr>";
		 $sum_interes+=$dat_credito['des_cre_interes'];
		 $sum_capital+=$dat_credito['des_cre_capital'];
		 $sum_couta+=$dat_credito['des_cre_total'];
		 
		 $p++;
		} ?>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><b><?php echo number_format($sum_interes); ?></b></td>
			<td><b><?php echo number_format($sum_capital); ?></b></td>
			<td><b><?php echo number_format($sum_couta); ?></b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
    <tr align='center'>
        <input type='hidden' name='cantidad' id='cantidad' value='<?php echo $p; ?>' />
        <input type="hidden" name="factura" id="factura" value="<?php echo $_GET['fac']; ?>"/>
        <input type='hidden' name='centro' id='centro' value='1169'/>
        <input type="hidden" name="sigla" id="sigla" value="<?php echo $sigla_cue_pagar; ?>"  />
		<td colspan='7'><input type="submit" class="art-button" value="Descontar Creditos" /></td></tr>
		<?php
     } ?>
   </table>
  </center>
 </form>
</body>
</html>