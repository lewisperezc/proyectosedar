<?php
include_once('../conexion/conexion.php');
include_once('../clases/nits.class.php');
include_once('../clases/varios.class.php');
include_once('../clases/mes_contable.class.php');
$nit = new nits();
$varios=new varios();
$mes_contable = new mes_contable();
$mes_ini=$_POST["mes_ini"];
$ano_ini=$_POST["ano_ini"];
$mes_fin=$_POST["mes_fin"];
$ano_fin=$_POST["ano_fin"];
$doc_ini = $_POST["doc_ini"];
$doc_fin = $_POST["doc_fin"];
$ult_dia = date("d",(mktime(0,0,0,$mes+1,1,$ano)-1));
$doc_ini = $_POST["doc_ini"];
$doc_fin = $_POST["doc_fin"];
$fec_ini = "01-".$mes."-".$ano;
$fec_fin = $ult_dia."-".$mes."-".$ano;
$fec_dia = date('d-m-Y');
$totcargeneral=0;
$sql="EXECUTE carteraHospitales '$doc_ini','$doc_fin','$fec_ini','$fec_fin'";
//echo $sql."<br>";
$query=mssql_query($sql);
if($query)
{
	if($mes_ini<=9)
		$mes_ini_con="0".$mes_ini;
	else
		$mes_ini_con=$mes_ini;
	if($mes_fin<=9)
		$mes_fin_con="0".$mes_fin;
	else
		$mes_fin_con=$mes_fin;
	$sql_rep = "SELECT uno,dos,tres,cinco,seis,siete,ocho,nueve,once,doce,trece,catorce,quince,diesiseis,diesisiete,diesiocho,
	diesinueve,veinte,vientiuno,veintidos,
	SUM(ISNULL(CAST(cuatro AS FLOAT),0))+SUM(ISNULL(CAST(diesisiete AS FLOAT),0)) cuatro,
	SUM(ISNULL(CAST(diez AS FLOAT),0))+SUM(ISNULL(CAST(diesiocho AS FLOAT),0)) diez,
	cuatro-SUM(ISNULL(CAST(diez AS FLOAT),0))+SUM(ISNULL(CAST(diesiocho AS FLOAT),0)) saldo
	FROM reportes
	WHERE cuatro>0 AND CAST(dos AS DATETIME) BETWEEN '01-".$mes_ini_con."-".$ano_ini."' AND '".$ult_dia."-".$mes_fin_con."-".$ano_fin."'
	GROUP BY uno,dos,tres,cinco,seis,siete,ocho,nueve,once,doce,trece,catorce,quince,diesiseis,diesisiete,diesiocho,
	diesinueve,veinte,vientiuno,veintidos,cuatro
	ORDER BY siete,CAST(dos AS DATETIME),cinco";
	//echo $sql_rep."<br>";
	$query_rep=mssql_query($sql_rep);
	$filas=mssql_num_rows($query_rep);
	if($filas>0)
	{
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=CarteraHospital");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<table border="1">
			<tr>
				<th colspan="10">&nbsp;</th>
			</tr>
			<tr>
            	<th>Documento</th>
                <th>Tercero</th>
				<th>Factura</th>
				<th>Periodo</th>
				<th>a&ntilde;o</th>
				<th>Fecha</th>
                <th>Vence</th>
                <th>Valor a pagar</th>
                <th>Abonos</th>
                <th>Saldo</th>
                <th>Dias</th>
			</tr>
			<?php
			while($res_tod_datos=mssql_fetch_array($query_rep))
			{
				$vencimiento=$varios->suma_fechas_dias($res_tod_datos['dos'],$res_tod_datos['seis']);
				if($res_tod_datos['diez']>0)
					$elabono=$res_tod_datos['diez'];
				else
					$elabono=0;
				$elsaldo=$res_tod_datos['cuatro']-$elabono;
				
				if($elsaldo>0)
				{
			?>
			<tr>
            	<td><?php echo $res_tod_datos['ocho']; ?></td>
                <td><?php echo $res_tod_datos['siete']; ?></td>
				<td><?php echo $res_tod_datos['cinco']; ?></td>
				<td><?php echo $res_tod_datos['doce']; ?></td>
				<td><?php echo $res_tod_datos['catorce']; ?></td>
				<td><?php echo $res_tod_datos['dos']; ?></td>
                <td><?php echo $vencimiento; ?></td>
                <td align="right"><?php echo /*number_format(*/$res_tod_datos['cuatro']/*)*/; ?></td>
                <td align="right"><?php echo /*number_format(*/$elabono/*)*/; ?></td>
                <td align="right"><?php echo /*number_format(*/$elsaldo/*)*/; ?></td>
                <td><?php echo $res_tod_datos['seis']; ?></td>
			</tr>
			<?php
				$totcargeneral=$totcargeneral+$elsaldo;
				}
			}
			?>
           <tr>
            	<td align="right" colspan="8"><b>Total cartera general:</b></td>
                <td><b><?php echo $totcargeneral; ?></b></td>
                <td>&nbsp;</td>
            </tr>
		</table>
<?php
	}
	else
	{
		echo "<script>
				alert('No se encontro informacion relacionada con los datos ingresados.');
				window.history.back(1);
		  	</script>";
	}
}
?>