<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
?>
<ul class="art-hmenu">
<?php
	$sqlmod="SELECT * FROM modulos";
	$conmod=mssql_query($sqlmod);
	while($filmod=mssql_fetch_array($conmod))
	{
?>
	<li><a href="index.php?m=<?php echo $filmod['mod_id'];?>"><?php echo $filmod['mod_nombre'];?></a></li>
<?php
	}
?>
	<li><a href="Javascript:void(0)" onclick="window.location='control/cerrar_sesion.php'">CERRAR SESI&Oacute;N</a></li>
    <?php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
	?>
    <!--<li><a href="javascript:void(0);" onclick="window.location='#'">Ayuda</a></li>
    <font style='color:white; float:right;'><?php echo strtoupper(strftime("%A %d de %B del %Y")); ?></font>-->
	<font style='color:white; float:right;'><?php echo "A&Ntilde;O ACTIVO: ".$_SESSION['elaniocontable']; ?></font>
</ul>