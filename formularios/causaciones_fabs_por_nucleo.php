<?php 
    session_start();
    if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
    $ano = $_SESSION['elaniocontable'];
?>
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.js"></script> 
<script type="text/javascript" language="javascript" src="../librerias/datatable/jquery.dataTables.js"></script> 
<style type="text/css" title="currentStyle"> 
@import "../librerias/datatable/demo_table.css";
</style> 
<script type="text/javascript" charset="utf-8"> 
		$(document).ready(function() {
			$('#example').dataTable();
		} );
</script>
<script>

//Leer un valor
//document.write('Nombre: '+window.opener.document.dat_pabs.conse.value);
//Cambiar un valor
function AsignaValor(elvalor)
{
	window.opener.document.dat_pabs.conse.value=elvalor;
	window.opener.document.getElementById('conse').onblur();
	window.close();
}
</script>
<?php
include_once('../clases/pabs.class.php');
$ano = $_SESSION['elaniocontable'];
$ins_pabs=new pabs();
$mes=$_GET['mes'];
$con_reg_pab_por_cen_costo=$ins_pabs->ConRegFabPorCenCosto($ano,$mes);
$res_pabs=$ins_pabs->res_pabs($ano,$mes);
?>
<body alink="#000000" link="#000000" vlink="#000000">
<center>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead> 
            <tr> 
                <th>Documento</th>
                <th>Nombres</th>
                <th>Ciudad de Realizacion</th>
                <th>Comprobante</th>
                <th>Valor</th>
                <th>Fec. Elaboraci&oacute;n</th>
                <th>Mes Afectado</th>
                <th>Ver</th>
            </tr> 
        </thead> 
        <tbody> 
             <?php
            while($res_reg_pab_por_cen_costo=mssql_fetch_array($con_reg_pab_por_cen_costo))
            {
                $nom_ciudad=$ins_pabs->ciu_causado($res_reg_pab_por_cen_costo['trans_user']);
            ?>
                <tr class="gradeA"> 
                    <td><?php echo $res_reg_pab_por_cen_costo['nits_num_documento']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['nombres']; ?></td>
                    <td><?php echo $nom_ciudad; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mov_compro']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mov_valor']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mov_fec_elabo']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mes_nombre']; ?></td>
                    <td><input type="radio" name="lasigla" id="lasigla" value="<?php echo $res_reg_pab_por_cen_costo['mov_compro']; ?>" onClick="AsignaValor(this.value);"/></td>
                </tr>
            <?php
            }
            while($res_reg_pab_por_cen_costo=mssql_fetch_array($res_pabs))
             {
             ?>
                <tr class="gradeA"> 
                    <td><?php echo $res_reg_pab_por_cen_costo['nits_num_documento']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['nombres']; ?></td>
                    <td><?php echo $nom_ciudad; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mov_compro']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mov_valor']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mov_fec_elabo']; ?></td>
                    <td><?php echo $res_reg_pab_por_cen_costo['mes_nombre']; ?></td>
                    <td><input type="radio" name="lasigla" id="lasigla" value="<?php echo $res_reg_pab_por_cen_costo['mov_compro']; ?>" onClick="AsignaValor(this.value);"/></td>
                </tr>
            <?php
            }
            ?>
    </tbody>    
</table>
</center>
</body>