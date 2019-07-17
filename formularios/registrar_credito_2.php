<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

	include_once('../clases/credito.class.php');
	$instancia_credito = new credito();
	$_SESSION['sel_tip_persona'] = $_POST['sel_tip_persona'];
	$sel_tip_persona = $_SESSION['sel_tip_persona'];
	$estados="1,2,3,4,5";
	$con_tip_nit = $instancia_credito->con_nit_por_id_estado($sel_tip_persona,$estados);
?>
<script>
	function validar()
	{
		if(document.selecciona_persona.sel_persona.selectedIndex == 0)
		{
			alert('Seleccione una Persona.');
		}
		else
		{
			document.selecciona_persona.submit();
		}
	}
</script>
<form name="selecciona_persona" method="post" target="frame3" action="registrar_credito_3.php">
	<center>
		<table>
    	<tr>
        	<td><b>Seleccione Persona</b></td>
        </tr>
        <tr>
        	<td><select name="sel_persona">
            	<option value="0" onClick="validar();">--Seleccione--</option>
        <?php
			while($row = mssql_fetch_array($con_tip_nit))
			{
		?>
                <option value="<?php echo $row['nit_id']; ?>" onClick="validar();"><?php echo $row['nits_num_documento']."--".$row['nits_apellidos']." ".$row['nits_nombres']; ?></option>
        <?php
				}
		?>
            </select></td>
        </tr>
    </table>
	</center>
</form>