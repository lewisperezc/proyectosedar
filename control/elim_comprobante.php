<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/moviminetos_contables.class.php');
$comprobante = $_POST['doc_des'];
$mes = $_POST['mes_sele'];
$dat_mes = split('-',$mes,2);
$mov_contable = new movimientos_contables();

$borrar=$mov_contable->borrarDocumento($comprobante,$dat_mes[0],$dat_mes[1]);
if($borrar)
{
  echo "<script>
  			alert('Se elimino el movimiento satisfactoriamente.');
  			
  		</script>";
}
else
{
	echo "<script>
			alert('Error al eliminar el movimiento, Intentelo de nuevo.');
		  </script>";
}
echo "<script>history.back(-1);</script>";
?>