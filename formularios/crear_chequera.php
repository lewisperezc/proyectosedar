<script language="javascript" src="../librerias/js/validacion_num_letras.js" ></script>
<?php
 session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
  include_once('clases/cuenta.class.php');
  $cuenta = new cuenta();
  $cuen_bancarias = $cuenta->cuentas_chequera();
?>
 <form name="cre_chequera" id="cre_chequera" method="post" action="./control/guardar_chequera.php">
   <center>
    <table id="chequera" border="1">
    	<tr>
      	 <td><b>Cuenta</b></td>
         <td>
           <select name="cuenta" id="cuenta" >
            <option value="0">--Seleccione--</option>
            <?php
			 while($row = mssql_fetch_array($cuen_bancarias))
			   echo "<option value='".$row['cue_id']."'>".$row['cue_nombre']."</option>";
			?>
           </select>
         </td>
		</tr>
        <tr>
         <td>Cheque inicial</td>
         <td>Cheque final</td>
        </tr>
        <tr>
         <td><input type="text" name="ini" id="ini" onkeypress="return permite(event,'num')"/></td>
         <td><input type="text" name="fin" id="fin" onkeypress="return permite(event,'num')" /></td>
        </tr>
        <tr>
         <td colspan="2"><input type="submit" class="art-button" name="boton" value="Crear chequera"  /></td>
        </tr>
    </table>        
  </center>          
 </form>