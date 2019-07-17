<?php 
session_start();
if(!$_SESSION["k_username"] || !$_SESSION['k_password']){ header("Location:../ingreso/index.php"); }
$ano = $_SESSION['elaniocontable'];
?>
<link rel="stylesheet" href="estilos/limpiador.css" media="screen" type="text/css" />
<link rel="stylesheet"  type="text/css" href="../estilos/screen.css"  media="screen" />

<form name="resul_saldo"  id="resul_saldo" method="post" action="consultar_saldos3.php" target="frame4">
<center>
  <?php

@include_once('../conexion/conexion.php');

$_SESSION['consaldos'];
 $_SESSION['selecdoc']=$_POST['selecdoc'];
 
 $_SESSION['selecdocu']=$_POST['selecdocu'];
 $_SESSION['selectip']=$_POST['selectip'];
 


if ($_SESSION['consaldos'])
{
 if ($_SESSION['consaldos']==1)
   {
     
     
        if ( $_SESSION['selecdoc']==999)
        {
  
      echo "otra opcion del 1";
          
         
   
         }
         else  {
          ?>  
          <form  name="" >
   
    
           <?php
$_SESSION['result']=$result;
$sqls="select  mov_compro,mov_nume,mov_fec_elabo,mov_cuent,mov_concepto,nits_nombres,mov_nit_tercero,
cen_cos_nombre,mov_cent_costo,mov_valor,mov_tipo,tip_cue_nombre,mov_documento,mov_doc_numer
from dbo.movimientos_contables mc inner join dbo.tipo_cuenta tc
on mc.mov_tipo = tc.tip_cue_id  inner join dbo.nits nt
on mc.mov_nit_tercero = nt.nit_id inner join dbo.centros_costo cc
on mc.mov_cent_costo = cc.cen_cos_id
where mov_compro= '$_SESSION[selecdoc]'";
$ejecu = mssql_query($sqls);
$j=0;
   while($sal= mssql_fetch_array($ejecu))
            {        
        ?>
              <table>
  <tr>
    <td>mov copmprobante</td><td>numero comprobante</td><td>fecha de elaboracion comprobante</td>
    <td>cuenta</td><td>concepto</td><td>identificacion  del tercero</td>
     </tr>
       <tr> <td><input type="text"  disabled="disabled" name="comp[<?php echo $j ;?>]" value="<?php echo $sal[0]; ?>"/></td>
        <td><input type="text" disabled="disabled" name="num[<?php echo $j ;?>]" value="<?php echo $sal[1]?>"/></td>
        <td><input type="text" disabled="disabled"  name="cuen[<?php echo $j ;?>]" value="<?php echo $sal[2] ?>"/></td>
        <td><input type="text" disabled="disabled" name="nit[<?php echo $j ;?>]" value="<?php echo $sal[3] ?>"/></td>
        <td><input type="text" disabled="disabled"name="cent[<?php echo $j ;?>]" value="<?php echo $sal[4] ?>"/></td>
        <td><input type="text" disabled="disabled"name="val[<?php echo $j ;?>]" value="<?php echo $sal[6]."-".$sal[5]?>"/></td>
       </tr>
       <tr>
        <td>centro de costos</td><td>valor</td><td>tipo de movimineto</td><td>numero documento</td> <td>documento</td>
       </tr>
       <tr>     
            <td><input type="text" disabled="disabled" name="tip[<?php echo $j ;?>]" value="<?php echo $sal[8]."-".$sal[7] ?>"/></td>
          <td><input type="text" disabled="disabled" name="tip[<?php echo $j ;?>]" value="<?php echo $sal[9] ?>"/></td>
          <td><input type="text" disabled="disabled" name="tip[<?php echo $j ;?>]" value="<?php echo $sal[10]." - ".$sal[11] ?>"/></td>
          <td><input type="text" disabled="disabled" name="tip[<?php echo $j ;?>]" value="<?php echo $sal[12] ?>"/></td>
          <td><input type="text" disabled="disabled" name="tip[<?php echo $j ;?>]" value="<?php echo $sal[13] ?>"/></td>
       </tr>
      <?php 
    $j++;
        }//fin while
    ?>
 </tr>
  <tr>
        <td align="center" colspan="7"><input type="submit" class="art-button" name="editar" id="editar"    value="EDITAR" /></td>
    </tr>
    </table>
 </form>  
        <?php }
   }//fin if opcion 1
else if($_SESSION['consaldos']==2)
        {
        if ($_SESSION['selecdocu']==9999)
        {
  
        echo "otra opcion del 2";
    
    
        }
        else  {
          ?>
                     <form >
   
    <table>
    <?php
$_SESSION['result']=$result;

$sqls="select sal_doc_valor,sal_doc_tipo,tip_cue_nombre,sal_doc_id,sal_doc_fecha,sal_doc_nit,nits_nombres,sal_doc_cen_costo,cen_cos_nombre,sal_doc_estado
from dbo.saldos_documentos sd inner join dbo.tipo_cuenta tc
on sd.sal_doc_tipo =tc.tip_cue_id inner join dbo.nits nt
on sd.sal_doc_nit = nt.nit_id inner join dbo.centros_costo cc
on sd.sal_doc_cen_costo = cc.cen_cos_id
where sal_doc_id= '$_SESSION[selecdocu]' and sal_doc_tipo= $_SESSION[selectip]";
$ejecu = mssql_query($sqls);
$j=0;
   while($sal= mssql_fetch_array($ejecu))
            {        
        ?>

              
  <tr>
    <td>comprobante</td><td>saldo</td><td>fecha de elaboracion comprobante</td> <td>nit </td>
        <tr>  <td><input type="text"  disabled="disabled" name="comp[<?php echo $j ;?>]" value="<?php echo $sal[3]; ?>"/></td>
        <td><input type="text" disabled="disabled" name="num[<?php echo $j ;?>]" value="<?php echo $sal[0]?>"/></td>
        <td><input type="text" disabled="disabled"  name="cuen[<?php echo $j ;?>]" value="<?php echo $sal[4] ?>"/></td>   
    <td><input type="text" disabled="disabled" name="nit[<?php echo $j ;?>]" value="<?php echo $sal[5]."-".$sal[6]?>"/></td>
    </tr>
    <tr>
   <td>contro de costos</td><td>estado</td><td>tipo</td>
  
   </tr>
   <tr>
        
        <td><input type="text" disabled="disabled"name="cent[<?php echo $j ;?>]" value="<?php echo $sal[7]."-".$sal[8]?>"/></td>
          
        <td><input type="text" disabled="disabled"name="val[<?php echo $j ;?>]" value="<?php echo $sal[9]?>"/>
            </td>
            <td><input type="text" disabled="disabled"name="val[<?php echo $j ;?>]" value="<?php echo $sal[1]?>"/>
            </td>
           
       </tr>
       
      <?php 
    $j++;
        }//fin while
    ?>
 </tr>
  <tr>
        <td align="center" colspan="7"><input type="submit" class="art-button" name="generar" id="generar"    value="EDITAR" /></td>
    </tr>
    
  </table>
    
</center>
</form>	   
					
				<?php	}
 }//fin if opcion 2

				   
             	
	 else{
		 echo "<script type=\"text/javascript\">alert(\"No se pudo intentelo de nuevo.\");</script>";    
	 	 }           
}//fin de existeeee
unset($_SESSION['consaldos']);
unset($_SESSION['selecdoc']);
unset($_SESSION['selecdocu']);
unset($_SESSION['selectip']);
?>
</form>