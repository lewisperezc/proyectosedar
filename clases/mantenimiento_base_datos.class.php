<?php
@include_once('../conexion/conexion.php');
@include_once('../inicializar_session.php');
@include_once('conexion/conexion.php');
@include_once('inicializar_session.php');
class mantenimiento_base_datos
{
	public function VaciarLog($nombre_base_datos,$nombre_log)
    {
    	$query="BACKUP LOG [sedasoftrediseno] WITH TRUNCATE_ONLY
		DBCC SHRINKFILE(bd_sedasoft_puch_log,1)";
		//echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
            return $ejecutar;
        else
            return false;
    }
	
	public function BackupBaseDatos($base_datos)
    {
    	$fecha=date('dmY');
    	$query="DECLARE @fecha_backup VARCHAR(50)
    	SET @fecha_backup=$fecha
    	DECLARE @archivo VARCHAR(255)
		SET @archivo = 'D:\BACKUPSBDSEDASOFT\'+@fecha_backup+'_bd_sedasoft_puch.bak';
		ALTER DATABASE $base_datos SET SINGLE_USER WITH ROLLBACK IMMEDIATE BACKUP DATABASE $base_datos TO DISK = @archivo
		ALTER DATABASE $base_datos SET MULTI_USER";
		//echo $query;
        $ejecutar=mssql_query($query);
        if($ejecutar)
            return $ejecutar;
        else
            return false;
    }
}
?>