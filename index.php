<?php
include_once('clases/varios.class.php'); 
$ins_varios=new varios();
$desactivar_cache=$ins_varios->DesactivarCache();

session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }

	include_once('conexion/conexion.php');
	include_once('clases/nits.class.php');
	$nit = new nits();
	session_register('otra');	
	$ar="main.php";
	$otra[99]=$ar;
	if($m)
	{
		$otra[1]=$m;
		$otra[2]=0;
	}
	if($e)
		$otra[2]=$e;
	if($c)
	{
		$otra[3]=$c;
		$sqlcas="SELECT * FROM casos_uso where cas_uso_id='$otra[3]'";
		$concas=mssql_query($sqlcas);
		$filcas=mssql_fetch_array($concas);
		if($filcas['cas_uso_id']==107)
		  {
			  if($_SESSION['k_perfil']!=6 || $_SESSION['k_perfil']!=7)
			      $otra[99] = "ingreso/modificar_usuario.php"; 
			  else
			    $otra[99]=$filcas['cas_uso_archivo'];    	  
		  }
		else
		 $otra[99]=$filcas['cas_uso_archivo'];    	     
	}
    $dat_empresa = $nit->consultar(380);
	$datos_empresa = mssql_fetch_array($dat_empresa);
	$_SESSION['regimen_empresa']=$datos_empresa['reg_id'];
?>

<script src="javascript/desactivar_enter.js"></script>
<script src="../javascript/desactivar_enter.js"></script>


<!DOCTYPE html>
<html dir="ltr" lang="es"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Sedasoft</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
    <link rel="stylesheet" href="style.css" media="screen">
    <link rel="stylesheet" href="style.responsive.css" media="all">
    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
    <script src="javascript/desactivar_enter.js"></script>
</style></head>
<body onload="DesactivarBotonAtras();">
<div id="art-main">
    <div class="art-sheet clearfix">
<header class="art-header clearfix">
    <div class="art-shapes"><div class="art-object64128686" data-left="48.27%"></div></div>  
</header>
<nav class="art-nav clearfix"><?php include_once("navegacion.php");?></nav>
<div class="art-layout-wrapper clearfix">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-sidebar1 clearfix"><div class="art-vmenublock clearfix">
        <div class="art-vmenublockcontent"><?php include_once("menu.php");?></div>
</div></div>
                        <div class="art-layout-cell art-content clearfix"><article class="art-post art-article">
                                
                                                
                <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout">
    <div class="art-content-layout-row">
    <div class="art-layout-cell layout-item-0" style="width: 100%" >
        <?php
        if($e==29 || $e==30 || $e==31 || $e==32 || $e==34 || $e==35 || $e==37 || $e==38 || $e==40 || $e==43 || $e==49)
        {
        ?>
            <div id="contenido"> <?php include_once('formularios/generar_reporte.php');?> </div>
        <?php
        }
        else
        {
        ?>
                <div id="contenido"><?php include_once($otra[99]);?></div>
        <?php
        }
        ?>
    </div>
    </div>
</div>
</div>
</article></div>
                    </div>
                </div>
            </div>
    </div>
<footer class="art-footer clearfix">
  <div class="art-footer-inner">
<p>SEDAR</p>
<p>Copyright &copy; <?php echo date('Y') ?>. Todos los derechos reservados.</p>
  </div>
</footer>
</div>
</body></html>