<?php
@include_once('../conexion/conexion.php');
@include_once('../inicializar_session.php');
@include_once('conexion/conexion.php');
@include_once('inicializar_session.php');
	class varios
	{
		function diasMes($mes,$ano) 
		{
		  if($mes==13)
		    $mes=12;
		//Si la extensión que mencioné está instalada, usamos esa.
			//$ano=$_SESSION['elaniocontable'];
			if(is_callable("cal_days_in_month"))  
			  return cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
	   		else 
		  //Lo hacemos a mi manera. 
		  	  return date("d",mktime(0,0,0,$mes+1,0,$ano));  
		}
		
		public function con_arc_tip_reporte($tip_reporte)
		{
			$query="SELECT * FROM formularios_reportes WHERE tip_rep_id = $tip_reporte ORDER BY for_rep_nombre ASC";
			$ejecutar = mssql_query($query);
			if($ejecutar)
				return $ejecutar;
			else
				return false;
		}
		
		function suma_fechas_dias($fecha,$ndias)
		{
			  if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
					  list($dia,$mes,$ao)=split("/", $fecha);
			  if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
					  list($dia,$mes,$ao)=split("-",$fecha);
				$nueva = mktime(0,0,0, $mes,$dia,$ao) + $ndias * 24 * 60 * 60;
				$nuevafecha=date("d-m-Y",$nueva);
			  return ($nuevafecha);  
		}
		
		function restaFechas($fec_inicial,$fec_final)
		{	
			//echo "fechas: ".$fec_inicial."___".$fec_final."<br>";
			$inicial=explode("-",$fec_inicial);
			$final=explode("-",$fec_final);
			
			
			if(strlen($inicial[1])==1)
				$dFecIni=$inicial[0]."-0".$inicial[1]."-".$inicial[2];
			else
				$dFecIni=$inicial[0]."-".$inicial[1]."-".$inicial[2];
			
			if(strlen($final[1])==1)
				$dFecFin=$final[0]."-0".$final[1]."-".$final[2];
			else
				$dFecFin=$final[0]."-".$final[1]."-".$final[2];
			
			//echo "fechas: ".$dFecIni."___".$dFecFin."<br>";
		  	$dFecIni = str_replace("-","",$dFecIni);
		  	$dFecFin = str_replace("-","",$dFecFin);
		
		  	ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
		  	ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);
		
			$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
			$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
			
			return round(($date2 - $date1) / (60 * 60 * 24));
		}
                
                public function ConvertirLetrasANumeros($letras)
                {
                    //echo $letras." Antes<br>";
                    $mes="";
                    $let_mayusculas=strtoupper($letras);
                    //echo $let_mayusculas." Despues<br>";
                    if(trim($let_mayusculas)=="ENERO")
                    { $mes=1; }
                    elseif(trim($let_mayusculas)=="FEBRERO")
                    { $mes=2; }
                    elseif(trim($let_mayusculas)=="MARZO")
                    { $mes=3; }
                    elseif(trim($let_mayusculas)=="ABRIL")
                    { $mes=4; }
                    elseif(trim($let_mayusculas)=="MAYO")
                    { $mes=5;}
                    elseif(trim($let_mayusculas)=="JUNIO")
                    { $mes=6; }
                    elseif(trim($let_mayusculas)=="JULIO")
                    { $mes=7; }
                    elseif(trim($let_mayusculas)=="AGOSTO")
                    { $mes=8; }
                    elseif(trim($let_mayusculas)=="SEPTIEMBRE")
                    { $mes=9; }
                    elseif(trim($let_mayusculas)=="OCTUBRE")
                    { $mes=10; }
                    elseif(trim($let_mayusculas)=="NOVIEMBRE")
                    { $mes=11; }
                    elseif(trim($let_mayusculas)=="DICIEMBRE")
                    { $mes=12; }
                    return $mes;
                }
                public function fecha_diff_function($data1,$data2)//Obtener la diferencia en dias entre una fecha y otra
                {
                // 86400 seg = 60 [seg/1_minuto] * 60 [1_minuto / 1_hora]* 24 [1_hora]
                $segundos  = strtotime($data2)-strtotime($data1);
                $dias      = intval($segundos/86400);
                //$segundos -= $dias*86400;
                //$horas     = intval($segundos/3600);
                //$segundos -= $horas*3600;
                //$minutos   = intval($segundos/60);
                //$segundos -= $minutos*60;
                //$sl_retorna = $dias;
                //return $sl_retorna;
                return $dias;
                }
                
                public function sumar_fecha_a_fecha($fecha,$numero)
                {
                    //echo $fecha."___".$numero;
                    /*$fec=date_create($fecha);
                    $nueva_fecha=date_format($fec,'Y-m-d');
                    echo $nueva_fecha;*/
                    $nueva_fecha=date("d-m-Y", strtotime("$fecha +".$numero." month"));
                    return $nueva_fecha;
                }
				
				public function envia_array_url($array)
				{
    				$tmp = serialize($array);
    				$tmp = urlencode($tmp);
    				return $tmp;
				}
				
				function recibe_array_url($url_array)
				{
    				$tmp = stripslashes($url_array);
    				$tmp = urldecode($tmp);
    				$tmp = unserialize($tmp);
   					return $tmp;
				}
				
    public function ConsultarDatosVariablesPorId($id)
	{					
	   $query="SELECT * FROM variables WHERE var_id='$id'";
		$ejecutar=mssql_query($query);
		if($ejecutar)
		{
		  $res_datos=mssql_fetch_array($ejecutar);
		  return $res_datos;
	   }
	   else
	       return false;
    }
    
    public function FondoSolidaridad($base)
    {
        $val_solidaridad=0;
        $porcentaje=0;
        
        $minimo=$this->ConsultarDatosVariablesPorId(1);
        if((int)$base/$minimo['var_valor'] < 4)
        {
            $val_solidaridad=0;
            $porcentaje=0;
        }   
        elseif((int)$base/$minimo['var_valor'] >= 4 && (int)$base/$minimo['var_valor']<16)
        {
            $val_solidaridad=$base*(1/100);
            $porcentaje=1;
        }
        elseif((int)$base/$minimo['var_valor'] >=16 && (int)$base/$minimo['var_valor'] <17)
        {
            $val_solidaridad=$base*(1.2/100);
            $porcentaje=1.2;
        }   
        elseif((int)$base/$minimo['var_valor'] >=17 && (int)$base/$minimo['var_valor'] <18)
        {
            $val_solidaridad=$base*(1.4/100);
            $porcentaje=1.4;
        }   
        elseif((int)$base/$minimo['var_valor'] >=18 && (int)$base/$minimo['var_valor'] <19)
        {
            $val_solidaridad=$base*(1.6/100);
            $porcentaje=1.6;
        }   
        elseif((int)$base/$minimo['var_valor'] >=19 && (int)$base/$minimo['var_valor'] <20)
        {
            $val_solidaridad=$base*(1.8/100);
            $porcentaje=1.8;
        }   
        elseif((int)$base/$minimo['var_valor'] >=20)
        {
            $val_solidaridad=$base*(2/100);
            $porcentaje=2;
        }
        return array($val_solidaridad,$porcentaje);
    }
    public function ConsultarTiposDescuentos()
    {
        $query="select * from tipo_descuentos";
        $ejecutar=mssql_query($query);
        if($ejecutar)
            return $ejecutar;
        else
            return false;
    } 
	
	function DesactivarCache()
	{
		header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	
	function getBrowser($user_agent)//VALIDAR EL NAVEGADOR POR EL QUE INGRESA
	{
		if(strpos($user_agent, 'MSIE') !== FALSE)
   			return 1; //'Internet explorer'
 		elseif(strpos($user_agent, 'Edge') !== FALSE) //Microsoft Edge
   			return 2; //'Microsoft Edge';
 		elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
    		return 3; //'Internet explorer';
 		elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
   			return 4; //"Opera Mini";
 		elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
   			return 5; //"Opera";
 		elseif(strpos($user_agent, 'Firefox') !== FALSE)
   			return 6; //'Mozilla Firefox';
 		elseif(strpos($user_agent, 'Chrome') !== FALSE)
   			return 7; //'Google Chrome';
 		elseif(strpos($user_agent, 'Safari') !== FALSE)
   			return 8; //"Safari";
 		else
   			return 9;//'No hemos podido detectar su navegador';
	}
	
	
}
?>