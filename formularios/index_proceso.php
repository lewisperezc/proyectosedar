<?php session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
function validaIngreso($parametro)
{
	// Funcion utilizada para validar el dato a ingresar recibido por GET
	$parametro=trim($parametro);
	
	if(eregi("^[a-zA-Z0-9.@ ]{4,40}$", $parametro)) return TRUE;
	else return FALSE;
}

function validaBusqueda($parametro)
{
	// Funcion para validar la cadena de busqueda de la lista desplegable
	if(eregi("^[a-zA-Z0-9.@ ]{2,40}$", $parametro)) return TRUE;
	else return FALSE;
}


if(isset($_POST["busqueda"]))
{
	$valor=$_POST["busqueda"];
	if(validaBusqueda($valor))
	{
		include_once("../conexion/conexion.php");
		$sql = "SELECT nit_id, nits_nombres+' '+nits_apellidos nombre FROM nits WHERE nits_nombres LIKE '".$valor."%'";
		$consulta=mssql_query($sql);
		$cantidad=mssql_num_rows($consulta);
		if($cantidad==0)
		{
			/* 0: no se vuelve por mas resultados
			vacio: cadena a mostrar, en este caso no se muestra nada */
			echo "0&vacio";
		}
		else
		{
			if($cantidad>20) echo "1&"; 
			else echo "0&";
			$cantidad=1;
			while(($registro=mssql_fetch_row($consulta)) && $cantidad<=20)
			{
				echo "<div onClick=\"clickLista(this);\" onMouseOver=\"mouseDentro(this);\">".$registro[1]."</div>";
				$cantidad++;
			}
		}
	}
}
?>