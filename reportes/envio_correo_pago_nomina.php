<?php session_start();
/*if(isset($send)) 
{*/
  @include_once("../librerias/PHPMailer-master/class.phpmailer.php");
  @include_once("librerias/PHPMailer-master/class.phpmailer.php");
  @include_once('../clases/nits.class.php');
  @include_once('clases/nits.class.php');
  @include_once('../clases/factura.class.php');
  @include_once('clases/factura.class.php');
  @include_once('../clases/centro_de_costos.class.php');
  @include_once('clases/centro_de_costos.class.php');
  $ins_cen_costo=new centro_de_costos();
  $ins_nits=new nits();
  $ins_factura=new factura();
  $nocontiene="[_]";
  $sigla=$_GET['nomina'];
  $con_correos=$ins_nits->con_cor_nit_por_id($sigla,$nocontiene);
  $con_dat_factura=$ins_factura->ConFacPorSigla($sigla);
  $res_dat_factura=mssql_fetch_array($con_dat_factura);
  
  $con_dat_centro=$ins_cen_costo->con_cen_cos_pabs($res_dat_factura['fac_cen_cos']);
  $res_dat_centro=mssql_fetch_array($con_dat_centro);
  
  $con_correos_auxiliares=$ins_nits->ConDatCorNit(2,$res_dat_centro['ciud_ciu_id']);
  
  $mail = new PHPMailer();
  $mail->IsSMTP();                       // telling the class to use SMTP
  $mail->SMTPDebug = 0;                  
	// 0 = no output, 1 = errors and messages, 2 = messages only.
  $mail->SMTPAuth = true;                // enable SMTP authentication
  $mail->SMTPSecure = "tls";              // sets the prefix to the servier
  $mail->Host = "mail.sedar.com.co";        // sets Gmail as the SMTP server
  $mail->Port = 587;                     // set the SMTP port for the GMAIL
  $mail->Username = "contactenos@sedar.com.co";  // Gmail username
  $mail->Password = "morfeo";      // Gmail password
	
  $mail->CharSet = 'windows-1250';
  
  $mail->SetFrom ("contactenos@sedar.com.co", "Contactenos Sedar");
  
  while($res_correos=mssql_fetch_array($con_correos))
  {
    if($res_correos['nits_cor_electronico']!=""&&$res_correos['nits_cor_electronico']!="NULL"&&$res_correos['nits_cor_electronico']!="NA"&&$res_correos['nits_cor_electronico']!="N/A")
		$mail->AddBCC ($res_correos['nits_cor_electronico'], 'AFILIADO');
  }

  //$mail->AddBCC ('lewisperezc@gmail.com', 'AFILIADO');
  
  $mail->Subject = "Confirmacion registro liquidacion de compensacion - SEDAR";
  $mail->IsHTML(false);
  $mail->Body = "Se ha registrado la liquidacion de una compensacion a su nombre en el sistema que corresponde a la factura ".$res_dat_factura['fac_consecutivo']." de ".$res_dat_centro['cen_cos_nombre'].", pronto estaremos realizando la transaccion bancaria correspondiente. Por favor revise su saldo dentro de los proximos tres(3) dias, si no se hace efectivo por favor comuniquese con nosotros para ayudar a solucionar el inconveniente.";
  //$mail->AltBody = "Confirmacion registro liquidacion de compensacion - SEDAR";
  $exito=$mail->Send();
  
  //ENVIAR CORREO A LA AUXILIAR DE N�CLEO
  
	/*while($res_correos_auxiliares=mssql_fetch_array($con_correos_auxiliares))
  	{
    	if($res_correos_auxiliares['nits_cor_electronico']!=""&&$res_correos['nits_cor_electronico']!="NULL"&&$res_correos['nits_cor_electronico']!="NA"&&$res_correos['nits_cor_electronico']!="N/A")
    	{
        	$mail->AddAddress($res_correos_auxiliares['nits_cor_electronico']);
			$mail->Subject = "Confirmaci�n registro liquidaci�n de compensaci�n - SEDAR";
			$mail->Body = "Se ha registrado la liquidaci�n de una compensaci�n que corresponde a la factura ".$res_dat_factura['fac_consecutivo']."de ".$res_dat_centro['cen_cos_nombre']."
        , pronto estaremos realizando la transaccion bancaria correspondiente.
        <br> Por favor comunicarle a los afiliados que dentro de los proximos tres(3) dias se vera refleado su pago, si no se hace efectivo por favor comuniquese con nosotros para ayudar a
        solucionar el inconveniente.";
        	$mail->AltBody = "Confirmaci�n registro liquidaci�n de compensaci�n - SEDAR";
			$exito2=$mail->Send();
    	}
  	}*/
  
  
  if(!$mail->Send())
  {
  	$error_message = "Mailer Error: " . $mail->ErrorInfo;
  }
  else 
  {
	echo "<script>alert('Correo electronico enviado correctamente.');</script>";
	echo "<script>window.close();</script>";
  }
?>