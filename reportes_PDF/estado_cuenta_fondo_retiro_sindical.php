<?php session_start();
require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf= new DOMPDF();   
$dompdf->set_paper("A4","portrait");  //tiene que ser horizontal y lo deja en vertical (landscape)
$dompdf->load_html($_SESSION['informacion_retiro']);  
$dompdf->render(); 
$dompdf->stream("Reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR*/

/*
$dompdf = new DOMPDF();
$dompdf->load_html($_SESSION['informacion']);
$dompdf->render();
$dompdf->set_paper("legal","landscape");  //tiene que ser horizontal y lo deja en vertical 
$dompdf->stream("Reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR*/
?>