<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$instancia_nits = new nits();
$id_asociado = $_SESSION['aso_id'];
//INICIO CAPTURA DATOS FAMILIARES ASOCIADO
$_SESSION["aso_per_cargo"] = strtoupper($_POST['aso_per_cargo']);
$_SESSION["aso_num_hijos"] = strtoupper($_POST['aso_num_hijos']);
$_SESSION['aso_estado'] = strtoupper($_POST['aso_estado']);
$_SESSION['aso_afi_scare']=$_POST['aso_afi_scare'];
$_SESSION['aso_sec_scare']=$_POST['aso_sec_scare'];
$_SESSION['aso_fepasde']=$_POST['aso_fepasde'];
$_SESSION['aso_caj_comensacion']=$_POST['aso_caj_comensacion'];
$_SESSION['aso_fon_pen_obli']=$_POST['aso_fon_pen_obli'];//SI o NO
$_SESSION['fon_pen_obligatorio']=$_POST['fon_pen_obligatorio'];//Fondo
$_SESSION['aso_fon_pen_voluntaria']=$_POST['aso_fon_pen_voluntaria'];//SI o NO
$_SESSION['fon_pen_voluntario']=$_POST['fon_pen_voluntario'];//Fondo
//FIN CAPTURA DATOS FAMILIARES ASOCIADO
$aso_per_cargo = $_SESSION['aso_per_cargo'];
$aso_num_hijos = $_SESSION['aso_num_hijos'];
$aso_estado = $_SESSION['aso_estado'];
$aso_afi_scare = $_SESSION['aso_afi_scare'];
$aso_sec_scare = $_SESSION['aso_sec_scare'];
$aso_fepasde = $_SESSION['aso_fepasde'];
$aso_caj_comensacion = $_SESSION['aso_caj_comensacion'];
$aso_fon_pen_obli = $_SESSION['aso_fon_pen_obli'];
if($aso_fon_pen_obli=="NO")
	$fon_pen_obligatorio="NULL";
else
	$fon_pen_obligatorio=$_SESSION['fon_pen_obligatorio'];
	
$aso_fon_pen_voluntaria = $_SESSION['aso_fon_pen_voluntaria'];
if($aso_fon_pen_voluntaria=="NO")
	$fon_pen_voluntario="NULL";
else
	$fon_pen_voluntario=$_SESSION['fon_pen_voluntario'];

$actualizar_datos_familiares_asociado = $instancia_nits->act_dat_fam_asociado($aso_per_cargo,$aso_num_hijos,$aso_estado,$aso_afi_scare,$aso_sec_scare,$aso_fepasde,$aso_caj_comensacion,$aso_fon_pen_obli,$fon_pen_obligatorio,$aso_fon_pen_voluntaria,$fon_pen_voluntario,$id_asociado);
/*$actualizar_seccional_scare_asociado = $instancia_nits->act_ciu_dep_1_asociado($aso_sec_scare,$des_ubi_asociado,$id_asociado);*/

//LIMPIAR SESSIONES//
unset($_SESSION["aso_per_cargo"]);
unset($_SESSION["aso_num_hijos"]);
unset($_SESSION['aso_estado']);
unset($_SESSION['aso_afi_scare']);
unset($_SESSION['aso_sec_scare']);
unset($_SESSION['aso_fepasde']);
unset($_SESSION['aso_caj_comensacion']);
unset($_SESSION['aso_fon_pen_obli']);
unset($_SESSION['fon_pen_obligatorio']);
unset($_SESSION['aso_fon_pen_voluntaria']);
unset($_SESSION['fon_pen_voluntario']);
/////////////////////
if($actualizar_datos_familiares_asociado)
{
	echo "<script>alert('Afiliado actualizado correctamente.');
			location.href = '../formularios/consultar_asociado_4.php';
		 </script>";
}
else
{
	echo "<script>alert('Error al actualizar el afiliado, Intentelo de nuevo.');</script>";
}
?>