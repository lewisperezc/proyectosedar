<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
@include_once('../conexion/conexion.php');
@include_once('../clases/reporte_jornadas.class.php');
@include_once('../clases/hospital.class.php');
@include_once('../clases/factura.class.php');
@include_once('../clases/moviminetos_contables.class.php');
@include_once('../clases/transacciones.class.php');  
@include_once('../clases/centro_de_costos.class.php');
@include_once('clases/centro_de_costos.class.php');

@include_once('../clases/nits.class.php');
@include_once('clases/nits.class.php'); 

$ins_cen_costos=new centro_de_costos();
if(trim($_SESSION["hospital"])!="" && trim($_SESSION["hospital"])!=0)
{
	$centro_costo=$_SESSION["hospital"];
	$con_dat_cen_cos_por_id=$ins_cen_costos->ConsultarDatosCentroCostoPorId($centro_costo);
}
	
else
{
	$datos=explode("-",$_POST['fac_sin']);
	$centro_costo=$datos[0];
	$con_dat_cen_cos_por_id=$ins_cen_costos->ConsultarDatosCentroCostoPorId($centro_costo);
}

$res_dat_cen_cos_por_id=mssql_fetch_array($con_dat_cen_cos_por_id);


$ins_nit=new nits();

$con_uni_funcional=$ins_nit->ConsultarUnidadFuncionalPorId($res_dat_cen_cos_por_id['cen_cos_nit']);//CUENTA QUE MuEVE SEGUN LA UNIDAD FUNCIONAL
$tie_uni_funcional=mssql_fetch_array($con_uni_funcional);
//echo "la unidad es: ".$tie_uni_funcional['nit_uni_funcional'];
	
//echo "el dato es: ".$tie_uni_funcional['nit_uni_funcional']."<br>";

if(trim($tie_uni_funcional['nit_uni_funcional'])!='' && trim($tie_uni_funcional['nit_uni_funcional'])!=0 && trim($tie_uni_funcional['nit_uni_funcional']!=NULL))
{



$movimiento = new movimientos_contables();
$tran = new transacciones();
$fac = new factura();
$rep_jornadas = new reporte_jornadas();
$exi_factura = $_GET['factura'];

$tot_jornadas=0;
$temp = 0;
$tama = $_POST['cantidad'];

if($tama == "")
   $tama = $_SESSION['i'];

$nota = strtoupper($_SESSION["nota"]);
$fecha = date('d-m-Y');

$_SESSION["centro"] = $centro_costo;

for($i=0;$i<$tama;$i++)
{
	$jornadas[$i] = $_POST['num_jornadas'.$i];
    $num_jor_individuales[$i]=$_POST['jor_por_afiliado'.$i];
}

$_SESSION['jornadas'] = $jornadas;
$_SESSION['jor_ind_por_afiliado'] = $num_jor_individuales;
$tip_reporte = $_POST['tip_rep'];
$cons_fac = $fac->obt_consecutivo(7);
$consecutivo =  $cons_fac+1;
$_SESSION["conse"] = $consecutivo;
$sum_tot_jornadas=$_POST['sum_jorn'];
$sum_jor_por_afiliado=$_POST['sum_jor_por_afiliado'];
//echo "el consecutivo es: ".$_SESSION["conse"]."<br>";
$mes=$_POST['mes_sele'];
$dat_mes=explode("-",$mes);

if($dat_mes[1]<=9)
{ $mes_jornada = (string)"0".$dat_mes[1]; }
else
{ $mes_jornada = (string)$dat_mes[1]; }

if(!$exi_factura)
{
    //echo "Entra por el if";
	$tipo = $_SESSION["tipo"];
	$tama = $_SESSION['i'];
	$num_aso = $_SESSION["num_aso"];
	$i = 0;
	while($i < sizeof($jornadas))
	 {
	   $aso = $num_aso[$i];
	   $jor = $jornadas[$i];
       $jor_ind=$num_jor_individuales[$i];
	   $tot_jornadas = $tot_jornadas+$jor;
	   $rep_jornadas->registrarReporte_jornadas($jor,$aso,$tipo,$consecutivo,$tip_reporte,$ano,$mes_jornada,$jor_ind);
	   $i++;
	}
	$_SESSION['tot_jornadas'] =  $tot_jornadas;
	$_SESSION["tip_sele"]=$tipo;
	if($i==sizeof($jornadas))
	{
	   echo "<script type=\"text/javascript\">alert(\"Reporte de Jornadas registrado con Exito.\");  
	   		var a = confirm('Desea Imprimir la factura para el reporte de jornadas?');
		   	if(a)
            {
				var descr=prompt('Descripcion','PRESTACION DE SERVICIOS DE ANESTESIA MES DE... DE...');
							
				var temp1=0;
                var mes_ser=prompt('Mes de servicio','Mes en numeros Eje: 1 Si es Enero, 2 si es Febrero...');
                do{
                	if((isNaN(mes_ser))||(mes_ser==''))
					{
	                	if(mes_ser=='' || mes_ser=='Mes de servicio','Mes en numeros Eje: 1 Si es Enero, 2 si es Febrero...')
		                {
		                	alert('Debe ingresar el numero del mes de prestacion.');
		                    mes_ser=prompt('Mes de servicio','Mes en numeros Eje: 1 Si es Enero, 2 si es Febrero...');
		                }
						else
							temp1=1;
					}
					else
						temp1=1;
                }while(temp1==0)
							
				var temp10=0;
				var anio_servicio=prompt('A\u00f1o de servicio','Ingrese el a\u00f1o de servicio');
				do{
	                if((isNaN(anio_servicio))||(anio_servicio==''))
					{
		            	if(anio_servicio=='' || anio_servicio=='A\u00f1o de servicio','Ingrese el a\u00f1o de servicio')
			            {
			            	alert('Debe ingresar el numero del a\u00f1o de prestacion.');
			                anio_servicio=prompt('A\u00f1o de servicio','Ingrese el a\u00f1o de servicio');
			            }
						else
							temp10=1;
					}
					else
						temp10=1;
                }while(temp10==0)
							
                var per_facturacion=prompt('Periodo de facturacion','Ingrese el periodo de facturacion');
                            
				location.href = '../reportes/factura.php?sum_tot_jornadas='+".$sum_tot_jornadas."+'&jor='+".$sum_jor_por_afiliado."+'&descr='+descr+'&per_fac='+per_facturacion+'&ano_serv='+anio_servicio+'&mes_ser='+mes_ser+'&mes_con='+".$dat_mes[1].";					
							
		   	}
		   	</script>";
    	}
    	
}
	//location.href = '../reportes/factura.php?sum_tot_jornadas='+".$sum_tot_jornadas."+'&jor='+num_jor+'&fec='+fecha+'&descr='+descr+'&mes_ser='+mes_ser+'&mes_con='+".$dat_mes[1].";
	
else//YA EXISTE UNA FACTURA PARA LAS JORNADAS
{
    //echo "Entra por el else";
	$cen_fac = split("-",$_POST['fac_sin']);
	$_SESSION["conse"] = $cen_fac;
	$datos_fac = $fac->datFactura($_SESSION["conse"][1]);
	$val_fac = mssql_fetch_array($datos_fac);
	$tipo = $_POST['tip_rep1'];
	$_SESSION["tip_sele"]=$tipo;
	$_SESSION["centro"] = $_POST['centro'];
	for($i=0;$i<$tama;$i++)
	   $num_aso[$i] = $_POST['num_aso'.$i];
	$consecutivo = $val_fac['fac_id'];
	$i=0;
	while($i < sizeof($jornadas))
	 {
	   $aso = $num_aso[$i];
	   $jor = $jornadas[$i];
	   $jor_ind=$num_jor_individuales[$i];
	   $tot_jornadas = $tot_jornadas+$jor;
	   $ins_reporte = $rep_jornadas->registrarReporte_factura($jor,$aso,$tipo,$nota,$_SESSION["conse"][1],$tip_reporte,$_SESSION["conse"][1],$jor_ind);
	   if(!$ins_reporte)
	      $temp = 1;
	   $i++;
	 }
	$_SESSION['tot_jornadas'] =  $tot_jornadas;
    
    $sql="UPDATE factura SET fac_jornadas='$sum_jor_por_afiliado' WHERE fac_id='$val_fac[fac_id]'";
    $query=mssql_query($sql);
    
	if($temp == 0)
	   echo "<script>alert('Reporte de jornadas ingresado con exito.');history.back(-1);</script>";
	
	$fecha=$val_fac['fac_fecha'];
	$dat_fecha = split("-",$fecha);
	$mes = $dat_fecha[1];
	$ano = $_SESSION['elaniocontable'];
	
	$gua_factura=$fac->guardar_factura($_SESSION["centro"],"PRESTACION DE SERVICIOS DE ANESTESIA",$val_fac['fac_val_unitario'],$val_fac['fac_val_unitario'],$consecutivo,$_SESSION["centro"],$mes,$ano,$val_fac['fac_nit'],$consecutivo,$mes,5,0,0,$sum_jor_por_afiliado,0,$ano);
	if($gua_factura)
		echo "<script>alert(\"Reporte de Jornadas registrado con exito.\");</script>";
		
	//echo "<script>history.back(1);</script>";
}
//Ciclo para guardar los reportes de jornadas
}
else
{
	echo "<script>alert('NO se pudo registar la factura, Debe asignar una Unidad funcional al nit seleccionado, intentelo de nuevo.');</script>";
	echo "<script>location.href='../index.php?c=32';</script>";
}
?>