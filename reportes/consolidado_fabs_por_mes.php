<?php
@include_once('../clases/mes_contable.class.php');
@include_once('clases/mes_contable.class.php');

@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php');

$ins_nits=new nits();
$afiliados_1=$ins_nits->con_tip_nit(1);
$afiliados_2=$ins_nits->con_tip_nit(1);

$ins_mes=new mes_contable();
$tod_mes=$ins_mes->mes();

?>
<form name="cartera" id="cartera" action="../reportes_EXCEL/consolidado_fabs_por_mes.php" method="post">
<center>
	<table border="1" bordercolor="#0099CC">
            <tr>
		<th>DOCUMENTO INICIAL</th>
                <th>DOCUMENTO FINAL</th>
                <th>MES</th>
            </tr>
            <tr>
                <td><input type="text" name="doc_inicial" id="doc_inicial" list="doc_1" size="50" required>
                <datalist id="doc_1">
                <?php
                while($res_afiliados_1=mssql_fetch_array($afiliados_1))
                { echo "<option value='".$res_afiliados_1['nits_num_documento']."' label='".$res_afiliados_1['nits_num_documento']." ".$res_afiliados_1['nits_nombres']." ".$res_afiliados_1['nits_apellidos']."'>"; }
                ?> 
                </datalist>
                </td>
                <td><input type="text" name="doc_final" id="doc_final" list="doc_2" size="50" required>
                <datalist id="doc_2">
                <?php
                while($res_afiliados_2=mssql_fetch_array($afiliados_2))
                { echo "<option value='".$res_afiliados_2['nits_num_documento']."' label='".$res_afiliados_2['nits_num_documento']." ".$res_afiliados_2['nits_nombres']." ".$res_afiliados_2['nits_apellidos']."'>"; }
                ?> 
                </datalist>
                </td>
                <td>
     		<select name="mes_contable" id="mes_contable" required x-moz-errormessage="Seleccione Una Opcion Valida">
                    <option value="">Seleccione...</option>
                    <?php
                    while($row=mssql_fetch_array($tod_mes))
                    { echo "<option value='".$row['mes_id']."'>".$row['mes_nombre']."</option>"; }
                    ?>
    		</select>
    		</td>
            </tr>
            <tr>
                <td colspan="3"><input type="submit" value="EXCEL"/></td>
            </tr>
	</table>
</center>
</form>