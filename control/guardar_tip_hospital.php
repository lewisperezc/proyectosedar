<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
include_once('../clases/nits.class.php');
$nits = new nits();
$tip_hos = strtoupper($_POST["tip_hos"]);
$cue_cob = strtoupper($_POST["cue_cob"]);
$guardar = $nits->gua_tip_hos($tip_hos,$cue_cob);
if($guardar)
   echo "<script type=\"text/javascript\">alert(\"Se guardo satisfactoriamente.\");</script>"; 
else
 {
   echo "<script type=\"text/javascript\">alert(\"No se pudo guardar el tipo, intente nuevamente.\");</script>";
   echo "<META HTTP-EQUIV='refresh' CONTENT='1; URL=../index.php?c=69'>";
 }
?>