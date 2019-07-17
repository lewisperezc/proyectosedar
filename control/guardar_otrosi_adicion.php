<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];

include_once('../clases/contrato.class.php');
include_once('../clases/moviminetos_contables.class.php');
$ins_contrato = new contrato();
$ins_mov_contable=new movimientos_contables();

$con_id=$_POST['con_sele'];
$tip_otr_adi = $_SESSION['otr_adi'];
$tipo=$_POST['agr'];

$dinero=ereg_replace("[.]","",$_POST['dinero']);
$dinero=ereg_replace("[$]","",$_POST['dinero']);
$dinero=ereg_replace("[,]","",$_POST['dinero']);
$meses=trim($_POST['meses']);
$fec_inicio=trim($_POST['fec_inicio']);
$fec_fin=trim($_POST['fec_fin']);
$nota=trim(strtoupper($_POST['nota']));
$ano=$_SESSION['elaniocontable'];
?>
<script>
function AbreSinopsis(URL)
 {
     day = new Date();
	 id = day.getTime();
	 eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=300,left = 340,top = 362');");
 }
</script>
<?php
if($dinero==""&&$meses==""&&$nota=="")
{
	echo "<script>
		  	alert('Debe ingresar informacion en todos los campos del formulario, No se guardaron datos.');
			history.bak(-1);
	  	  </script>";
}
else{
	if($meses==""&&$fec_inicio==""&&$fec_fin=="")
	{   $meses=0;$fec_inicio="N/A";$fec_fin="N/A";  }
	if($dinero=="")
		$dinero=0;

	$guardar_otrosi_adicion=$ins_contrato->ins_otr_adi_contrato($tipo,$nota,$meses,$dinero,$con_id,$fec_inicio,$fec_fin);
	if($meses>0)
		$ActDurContrato=$ins_contrato->ActDurContrato($meses,$con_id);
	if($guardar_otrosi_adicion)
	{//ADICION EN DINERO
		$fecha=date("d-m-Y");
		$mes=date("m");
		$cuantos=$_POST['cuantos'];
		$j=0;
		while($j<=$cuantos)
		{
                    if(trim($_POST['aseg'.$j])!=""&&trim($_POST['polimp'.$j])!=""&&trim($_POST['valpolimp'.$j])!=""&&trim($_POST['tip_pol_impuesto'.$j])!=""&&trim($_POST['obs_pol_impuesto'.$j])!="")
                    {
                        $aseguradora[$j]=$_POST['aseg'.$j];
						$polizaimpuesto[$j]=$_POST['polimp'.$j];
						$valpolimp[$j]=ereg_replace("[.]","",$_POST['valpolimp'.$j]);
						$valpolimp[$j]=ereg_replace("[$]","",$_POST['valpolimp'.$j]);
						$valpolimp[$j]=ereg_replace("[,]","",$_POST['valpolimp'.$j]);
                        $tip_pol_impuesto[$j]=$_POST['tip_pol_impuesto'.$j];
                        $obs_pol_impuesto[$j]=$_POST['obs_pol_impuesto'.$j];
                    }
		$j++;
		}
		$i=0;
		while($i<sizeof($aseguradora))
		{
                    //echo "Entra por el while <br>";
                    //echo "los datos: ".$aseguradora[$i]."_".$polizaimpuesto[$i]."_".$valpolimp[$i]."_".$tip_pol_impuesto[$i]."_".$obs_pol_impuesto[$i]."<br>";
                    if($aseguradora[$i]!=""&&$polizaimpuesto[$i]!=""&&$valpolimp[$i]!=""&&$tip_pol_impuesto[$i]!=""&&$obs_pol_impuesto[$i]!="")
                    {
                        $con_ult_adi_otrosi=$ins_contrato->con_ult_adi_otrosi($tipo);//CONSULTO LA ULTIMA ADICION U OTROSI QUE SE ACABA DE GUARDAR
                        if($tip_pol_impuesto[$i]==1)
                            $guardar_poliza=$ins_contrato->ins_pol_por_adicion($con_id,$polizaimpuesto[$i],$aseguradora[$i],$valpolimp[$i],$con_ult_adi_otrosi,$obs_pol_impuesto[$i]);
                        elseif($tip_pol_impuesto[$i]==2)
                            $guardar_poliza=$ins_contrato->ins_pol_por_adicion_informativo($con_id,$polizaimpuesto[$i],$aseguradora[$i],$valpolimp[$i],$con_ult_adi_otrosi,$obs_pol_impuesto[$i]);
				/*$con_cen_costo=$ins_contrato->con_cen_cos_id_por_contrato($con_id);
				$mov_contable = $ins_mov_contable->guarCam_movimiento($con_cen_costo,$con_id,"IMP_".$con_id,$aseguradora[$i],$fecha,$con_id,$fecha,1,$valpolimp[$i],$polizaimpuesto[$i],0,0,$mes,$ano);*/
                    }
		$i++;
		}
	}
	if($guardar_otrosi_adicion)
	{
		unset($_SESSION['otr_adi']);
		echo "<script>
				alert('Registro guardado correctamente.');
				AbreSinopsis('../reportes_PDF/sinopsis_adicion_otrosi.php?contrato_id=$con_id');
                                
	  		  </script>";
			  //history.back(-1);
	}
	else
	{
		echo "<script>
				alert('Error al guardar el registro, Intentelo de nuevo.');
                                
				//location.href = '../formularios/agregar_otrosi_adicion_2.php';
	  		</script>";
	}
}
?>