<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
   $ano = $_SESSION['elaniocontable'];
   include_once('../clases/reporte_jornadas.class.php');
   include_once('../clases/moviminetos_contables.class.php');
   include_once('../clases/factura.class.php');
   include_once('../clases/mes_contable.class.php');
   
   $ins_mes_contable=new mes_contable();
   $ins_Repjornadas = new reporte_jornadas();
   $mov_contable = new movimientos_contables();
   $ins_factura = new factura(); 

   $val_reporte =0;/*$_POST['val_recibo'];*/
   $rep_jor = $_SESSION["rep_modificar"];
   $mes = $_SESSION["fecha"];
   $iden_aso = $_POST['identificacion'];
   $nombres = $_POST['nombre']; 
   $estados = $_POST['estado'];
   $jornadas = $_POST['novedad'];
   $mes_contable = $_POST['mes_sele'];
   $est_ano=$_POST['estAno'];
   $num_fac = $_POST['factura'];
   //echo "el num de la fac es: ".$num_fac."<br>";
   $consecutivo_jornadas=$_POST['consecutivo'];

   $_SESSION['tipo'] = $_POST['tip_rep'];
   $ban = 0;
   $est_mes=explode("-",$mes_contable);
   //echo "Factura ".$num_fac."<br>";
   unset($_SESSION['conse']);
   $_SESSION["conse"][1]=$num_fac;
   //echo "en la sesion hay: ".$_SESSION["conse"][1];
   if($val_reporte>=0)
   {
    for($i=0;$i<sizeof($jornadas);$i++)
    {
       $act = $ins_Repjornadas->modificarReporte($jornadas[$i],$_POST['jor_glo'.$i],$_SESSION['tipo']);
     //$conModRep = "UPDATE reporte_jornadas SET rep_jor_num_jornadas = $jornada,rep_jor_causado = $jornada WHERE rep_jor_id = $nit_cen";
       if(!$act)
        $ban = 1;
    }
   if($ban==0)
   {
      echo "<script type=\"text/javascript\">alert(\"Se actualizo el reporte de jornadas satisfactoriamente.\");</script>";
	  
	  
	  $res_mes_ano_causacion=$mov_contable->ConsultarMesAnoNomCausada('CAU-NOM-'.$num_fac,$num_fac,$num_fac);
	  $res_est_mes_ano=$ins_mes_contable->ConsultarEstadoPorMesAno($res_mes_ano_causacion['mov_mes_contable'],$res_mes_ano_causacion['mov_ano_contable']);
	  
	  //echo "los datos son: ".$res_mes_ano_causacion['mov_mes_contable']."___".$res_mes_ano_causacion['mov_ano_contable']."<br>";
	  
      if($res_est_mes_ano['est_mes_por_ano_con_id']==2)
      {
         //echo "entra a borrar el documento <br>";
         //echo "entra con: ".$est_mes[0]."---".$est_mes[1];
         if(!empty($num_fac))
         {
            //echo "entra por este";
            $borrar = $mov_contable->borrarDocumento('CAU-NOM-'.$num_fac,$res_mes_ano_causacion['mov_ano_contable'],$res_mes_ano_causacion['mov_mes_contable']);
            $datos_fac = $ins_factura->datFactura($num_fac);
            $val_fac = mssql_fetch_array($datos_fac);

            $_SESSION["tip_sele"]=1;
            $_SESSION["centro"] = $val_fac['fac_cen_cos'];
            for($i=0;$i<sizeof($jornadas);$i++)
            {
               $num_aso[$i] = $_POST['nit'.$i];
               $jor_mod[$i] = $_POST['num_jornadas'.$i];
            }   
            $consecutivo_factura= $num_fac;
            $i=0;
            while($i < sizeof($jornadas))
             {
               $aso = $num_aso[$i];
               $jor = $jor_mod[$i];
               $tot_jornadas = $tot_jornadas+$jor;
               $i++;
             }
            $_SESSION['tot_jornadas'] =  $tot_jornadas;      
            $fecha=$val_fac['fac_fecha'];
            $dat_fecha = split("-",$fecha);
            $mes = $dat_fecha[1];
            //echo "el mes contable es: ".$est_mes[1]."<br>";
            $gua_factura = $ins_factura->guardar_factura($_SESSION["centro"],"PRESTACIÓN DE SERVICIOS DE ANESTESIA",$val_fac['fac_val_unitario'],$val_fac['fac_val_unitario'],$consecutivo_factura,$_SESSION["centro"],$est_mes[1],$ano,$val_fac['fac_nit'],$consecutivo_jornadas,$est_mes[1],5,$val_fac['fac_fecha'],$est_mes[1],0,'',$datos_fac['fac_ano_servicio']);

            if($gua_factura)
               echo "<script>alert(\"Reporte de Jornadas registrado con Exito.\");</script>";
         }
      }
   }
   else
    echo "<script type=\"text/javascript\">alert(\"No se pudo actualizar el reporte de jornadas.\");</script>";
    
//LIMPIAR SESSIONES//
//unset($_SESSION['val_recibo']);
//unset($_SESSION["rep_jornadas"]);
//unset($_SESSION["fecha"]);
//unset($_SESSION['tipo']);
/////////////////////
    
   echo "<script>location.href='../index.php?c=34'</script>";
   //echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=34'>";
   }
?>