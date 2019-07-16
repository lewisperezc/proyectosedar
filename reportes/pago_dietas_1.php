<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once('../clases/mes_contable.class.php');
include_once('../clases/nits.class.php');
$ins_mes = new mes_contable();
$tod_mes = $ins_mes->mes();
$ins_nits=new nits();
$con_tod_nit_por_tipo=$ins_nits->con_dat_nit(1);
?>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title></title>
    </head>
    <body>
    	<form method="post" name="frm_pag_dieta" id="frm_pag_dieta" action="pago_dietas_2.php">
    	<center>
        <table border="1" width="50%">
        	<tr>
            	<th colspan="2">REPORTE PAGO DE DIETAS</th>
            </tr>
            <tr>
                <td>Documento:
                <input type="text" name="pag_die_cedula" id="pag_die_cedula" list="afiliado" pattern="[0-9]+" required size="80"/>
          		<datalist id="afiliado">
          		<?php
            	while($res_tod_nit_por_tipo=mssql_fetch_array($con_tod_nit_por_tipo))
				{
				?>
				<option value="<?php echo $res_tod_nit_por_tipo['nits_num_documento']; ?>" label="<?php echo $res_tod_nit_por_tipo['nits_num_documento']." ".$res_tod_nit_por_tipo['nombres']; ?>">
            	<?php
				}
				?>
          		</datalist>
                </td>
                <td>Por mes de pago
                <select name="pag_die_mes_pago" id="pag_die_mes_pago">
                <option value="">--</option>
                <?php
				while($dat_meses=mssql_fetch_array($tod_mes))
				{
		 			echo "<option value='".$dat_meses['mes_id']."'>".$dat_meses['mes_nombre']."</option>";
				}
		  		?>  
                </select>
                </td>
            </tr>
            <tr>
            	<th colspan="2">
                <input type="submit" name="btn_ver_todos" id="btn_ver_todos" value="Ver Todos"/>
                <input type="submit" name="bnt_ver" id="bnt_ver" value="Ver"/>
                </th>
            </tr>
        </table>
        </center>
        </form>
        <?php
        // put your code here
        ?>
    </body>
</html>
