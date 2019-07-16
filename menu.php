<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
?>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=iso-8859-1" />
<ul class="art-vmenu">
<?php
if($otra[1])
{
	$sqlmen="SELECT * FROM menus where mod_id='$otra[1]'";
	$conmen=mssql_query($sqlmen);
	
	if($_SESSION['k_perfil']!=17)
	{
		while($filmen=mssql_fetch_array($conmen))
		{
?>
		<li>
			<a href="index.php?e=<?php echo $filmen['men_id'];?>" class="active"><?php echo $filmen['men_nombre'];?></a>
		</li>
<?php
			if($otra[2]==$filmen['men_id'])
			{	
				$sqlcas="SELECT cas_uso_id,cas_uso_nombre 
	            FROM casos_uso cu
	            INNER JOIN perfiles_por_casosUso ppcu
	            ON cu.cas_uso_id = ppcu.per_por_cas_cas_id
	            INNER JOIN nits nit
	            ON ppcu.per_por_cas_per_id = nit.nit_perfil
	            WHERE cu.men_id = $otra[2] AND nit_id =".$_SESSION["k_nit_id"];
				$concas=mssql_query($sqlcas);
				
				while($filcas=mssql_fetch_array($concas))
				{
?>
					<li>
						<a href="index.php?c=<?php echo $filcas['cas_uso_id'];?>"><?php echo $filcas['cas_uso_nombre'];?></a>
					</li>
<?php
				}
			}
		}
	}
	elseif($otra[1]!=5)
	{
		while($filmen=mssql_fetch_array($conmen))
		{
?>
		<li>
			<a href="index.php?e=<?php echo $filmen['men_id'];?>" class="active"><?php echo $filmen['men_nombre'];?></a>
		</li>
<?php
			if($otra[2]==$filmen['men_id'])
			{	
				$sqlcas="SELECT cas_uso_id,cas_uso_nombre 
	            FROM casos_uso cu
	            INNER JOIN perfiles_por_casosUso ppcu
	            ON cu.cas_uso_id = ppcu.per_por_cas_cas_id
	            INNER JOIN nits nit
	            ON ppcu.per_por_cas_per_id = nit.nit_perfil
	            WHERE cu.men_id = $otra[2] AND nit_id =".$_SESSION["k_nit_id"];
				$concas=mssql_query($sqlcas);
				
				while($filcas=mssql_fetch_array($concas))
				{
?>
					<li>
						<a href="index.php?c=<?php echo $filcas['cas_uso_id'];?>"><?php echo $filcas['cas_uso_nombre'];?></a>
					</li>
<?php
				}
			}
		}
	}
}
?>
</ul>