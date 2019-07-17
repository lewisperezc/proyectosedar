<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$id_asociado = $_SESSION['aso_id'];
$nit_por_cen_cos_id = $_POST['valor'];
//INICIO DATOS DEL CENTRO DE COSTOS
$_SESSION['cen_cos_asociado'] = $_POST['cen_cos_asociado'];
$_SESSION['aso_cen_costos'] = $_POST['aso_cen_costos'];
//FIN DATOS DEL CENTRO DE COSTOS
//Centros de Costo Que Tiene
$cen_cos_asociado = $_SESSION['cen_cos_asociado'];
//Centros de Costo Que Agrega
$aso_cen_costos = $_SESSION['aso_cen_costos'];
?>
<?php
$i = 0;
while($i < sizeof($cen_cos_asociado))
{
	$actualizar_centro_costos_asociado = $instancia_nits->act_dat_cen_cos_asociado(strtoupper($cen_cos_asociado[$i]),strtoupper($nit_por_cen_cos_id[$i]));
	$i++;
}
$j = 0;
while($j < sizeof($aso_cen_costos))
{
	$guardar_nits_por_centros = $instancia_nits->agr_cen_cos_asociado(strtoupper($aso_cen_costos[$j]),strtoupper($id_asociado));
	$j++;
}

//LIMPIAR SESSIONES//
unset($_SESSION['cen_cos_asociado']);
unset($_SESSION['aso_cen_costos']);
/////////////////////

if($actualizar_centro_costos_asociado || $guardar_nits_por_centros)
{
	echo "<script>alert('Afiliado actualizado correctamente.');
			location.href = '../formularios/consultar_asociado_7.php';
		 </script>";
}
else
{
	echo "<script>alert('Error al actualizar el afiliado, Intentelo de nuevo.');
			location.href = '../formularios/consultar_asociado_7.php';
		  </script>";
}
?>