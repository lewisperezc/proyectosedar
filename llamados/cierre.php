<?php session_start();
include_once('../conexion/conexion.php');
include_once('../clases/mes_contable.class.php');
include_once('../clases/moviminetos_contables.class.php');
include_once('../clases/saldos.class.php');
include_once('../clases/cuenta.class.php');
$movimiento = new movimientos_contables();
$saldos = new saldos();
$cuenta = new cuenta();
$mes = $_POST['mes']+1;
$ano = $_SESSION['elaniocontable'];
$columna = "a".$ano."a".$mes;
$cuen_terceros=$saldos->sal_cue_tercero($mes,$ano);
$cuentas = $cuenta->cue_mes($mes,$ano);
$existe = $cuenta->exis_columna($columna,'cuentas');
while($row = mssql_fetch_array($cuentas))
{
	if(substr($row['cuenta'],0,1)==1||substr($row['cuenta'],0,1)==5||substr($row['cuenta'],0,1)==6)
	  $val_cuent = $saldos->saldo_cuenta($row['cuenta'],1,$mes,$ano);
	elseif(substr($row['cuenta'],0,1)==2||substr($row['cuenta'],0,1)==3||substr($row['cuenta'],0,1)==4)
	  $val_cuent = $saldos->saldo_cuenta($row['cuenta'],2,$mes,$ano);
	
	$cuenta->val_cierre($columna,$row['cuenta'],$val_cuent);
}

if($mes==1)
	$col_anterior = "a".($ano-1)."a13";
else
	$col_anterior = "a".$ano."a".($mes-1);

$sql = "UPDATE CUENTAS SET $columna=$col_anterior WHERE ($columna IS NULL OR $columna=0) AND $col_anterior IS NOT NULL AND cue_esmayor='no'";
echo $sql;
$query = mssql_query($sql);
echo 1;
?>