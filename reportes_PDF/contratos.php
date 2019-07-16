<?php session_start();
@require_once("../librerias/dompdf/dompdf_config.inc.php");
$dompdf= new DOMPDF();   
$dompdf->set_paper("A4","portrait");  //tiene que ser horizontal y lo deja en vertical (landscape)
$dompdf->load_html($_SESSION['datos_contratos_vencidos']);  
$dompdf->render(); 
$dompdf->stream("Reporte.pdf", array("Attachment" => 0));//LO ABRE EN EL NAVEGADOR*/
?>