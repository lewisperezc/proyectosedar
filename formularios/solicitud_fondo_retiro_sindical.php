<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Solicitud fondo de retiro sindical</title>
		<meta name="description" content="">
		<meta name="author" content="SISTEMAS2">

		<meta name="viewport" content="width=device-width; initial-scale=1.0">

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
		<script src="librerias/js/datetimepicker.js"></script>
		<script>
		function popUp(url)
		{
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(url, '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=600,left=300,top=200');");
		}
		</script>
	</head>
	<body>
		<center>
		<form method="post" name="frm_ver_solicitudes" action="./reportes_PDF/solicitud_fondo_retiro_sindical.php">
			<table>
				<tr><th colspan="4">Solicitudes fondo de retiro sindical</th></tr>
				<tr>
					<td>Fecha inicial</td>
					<td><input type="text" name="fec_inicio" id="fec_inicio" required/>
                    <a href="javascript:NewCal('fec_inicio','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date"/></a>
                    <td>Fecha final</td>
					<td><input type="text" name="fec_final" id="fec_final" required/>
                    <a href="javascript:NewCal('fec_final','ddmmyyyy')"><img src="imagenes/cal.gif" width="16" height="16" border="0" alt="Pick a date"/></a>
				</tr>
				<tr>
					<td colspan="4"><input type="submit" name="btn_ver_solicitudes"  value="Ver solicitudes"/></td>
				</tr>
			</table>
		</form>
		</center>
	</body>
</html>
