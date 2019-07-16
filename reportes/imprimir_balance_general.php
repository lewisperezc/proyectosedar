<?PHP

/* En los encabezados indicamos que se trata de un documento de MS-WORD
  y en el nombre de archivo le ponemos la extencion      */
header('Content-type: application/vnd.ms-word');
header("Content-Disposition: attachment; filename=balance.doc");
header("Pragma: no-cache");
header("Expires: 0");

/*  Comenzamos a armar el documento  */

/* Parrafo */
@include_once('../conexion/conexion.php');
include_once('../clases/saldos.class.php');
$prueba=$_POST['selecuenta'];
	$saldo1=0;						
			   		$totalde1=0;
			 		$totalcre1=0;
					$totalsal1=0;	
				    	///////////////////empieza la siguiente cuenta 1
						$sqllike="select *
					              from cuentas
								  where cue_subdivision ='no' and cue_id like'1%' ";
						$sallike=mssql_query($sqllike);	
												
						echo "<table id='ordCom' bgcolor='#999999' border='1'>";
						echo "<tr>";
					    echo "<th colspan='5'  >BALANCE ALA FECHA ACTUAL  DE LAS CUENTAS QUE HAN TENIDO MOVIMIENTOS </th>";
						echo "</tr>";
						echo "<tr>";
					    echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  		echo "</tr>";
						echo "<tr>";
						////encabezado
						echo "<td colspan='2' rowspan='1'><img src='imagenes/logo_sedar_dentro2.jpg' width='100' height='100'  border='5'alt='Pick a date' /></td>";
						
						echo "<td colspan='3'>
         						 <strong> REPORTE DE BALANCE</strong>
        						</td> 	";
						echo "</tr>";
						////fin rncabezado
						//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						 
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
						echo "<tr>";
						echo "<td rowspan='2'>   1 ACTIVOS     </td>";
						echo "<td rowspan='2'>  NOMBRE     </td>";
					    echo "<td colspan='2'>naturaleza naturaleza</td>";
						echo "<td rowspan='2'>  saldo      </td>";
					    echo "</tr>";
			     		echo "<tr>";
						echo "<td >debito</td>";
						echo "<td >credito</td>";
						echo "</tr>";		          		
	              		echo "<tr>";
						 while($row = mssql_fetch_array($sallike))
								{$row[0];			
					  			 $form =new saldos();
				 	 			 $cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 $deb=$form->con_sal_cuen_debito($row[0]);
				  				 $debito = mssql_fetch_array($deb);
				  				 $credito=mssql_fetch_array($cred);
				  				 $saldo =$debito ['debito']- $credito['credito'];	
				  				 $totalde1=$totalde1+$debito ['debito'];
				  				 $totalcre1=$totalcre1+$credito['credito'];	
				  				 $totalsal1= $totalde1- $totalcre;			
		          				  echo "<td>$row[0] </td>";
				  				  echo "<td>$row[1]</td>";
				 				  echo "<td>".$debito ['debito']." </td>"; 
		         				  echo "<td>".$credito['credito']." </td>"; 
								  echo "<td>$saldo</td>"; 
		          				  echo "</tr>";		     
			  					  
								}//fin while 1
								   echo "<tr>";
              					   echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               					   echo "<td ><input name='' id='' type='text' value='$totalde1' /></td>";
               					   echo "<td ><input name='' id='' type='text' value='$totalcre1' /></td>";
               					   echo "<td ><input name='' id='' type='text' value=' $totalsal1' /></td>";
               					   echo "</tr>";  
							 /////////////////////////////////////////empieza la siguiente cuenta 2
					             	$saldo2=0;						
			   						$totalde2=0;
			 						$totalcre2=0;
									$totalsal2=0;
								  	$sqllike="select *
					              			  from cuentas
								  			  where cue_subdivision ='no' and cue_id like'2%' ";
								 	$sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>    2     </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									   $form =new saldos();
				 	 			 	   $cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 	   $deb=$form->con_sal_cuen_debito($row[0]);
				  					   $debito = mssql_fetch_array($deb);
				  					   $credito=mssql_fetch_array($cred);
				  					   $saldo =$debito ['debito']- $credito['credito'];	
				  					   $totalde2=$totalde2+$debito ['debito'];
				  					   $totalcre2=$totalcre2+$credito['credito'];	
				  					   $totalsal2= $totalde2- $totalcre2;			
		          				 		  echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 		  echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[1]' /></td>";
				 				 		  echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  	      echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 		  echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  		  echo "</tr>";		     
			  					      }//fin while 2
									  echo "<tr>";
              						 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde2' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre2' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal2' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
							/////////////////////////////////////////empieza la siguiente cuenta 3
							$saldo3=0;						
			   				$totalde3=0;
			 				$totalcre3=0;
							$totalsal3=0;
					              $sqllike="select *
					                        from cuentas
								            where cue_subdivision ='no' and cue_id like'3%' ";
								  $sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>    3   </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									   $form =new saldos();
				 	 			 	   $cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 	   $deb=$form->con_sal_cuen_debito($row[0]);
				  					   $debito = mssql_fetch_array($deb);
				  					   $credito=mssql_fetch_array($cred);
				  					   $saldo =$debito ['debito']- $credito['credito'];	
				  					   $totalde3=$totalde3+$debito ['debito'];
				  					   $totalcre3=$totalcre3+$credito['credito'];	
				  					   $totalsal3= $totalde3- $totalcre3;			
		          				 		echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 		echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[1]' /></td>";
				 				 		echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  		echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 		echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  		echo "</tr>";		     
			  					     }//fin while 3
									    echo "<tr>";
              						 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde3' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre3' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal3' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
							/////////////////////////////////////////empieza la siguiente cuenta 4
							$saldo4=0;						
			   		        $totalde4=0;
			 		        $totalcre4=0;
					        $totalsal4=0;
					               $sqllike="select *
					                         from cuentas
								             where cue_subdivision ='no' and cue_id like'4%' ";
								    $sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>    4     </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									    $form =new saldos();
				 	 			 		$cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 		$deb=$form->con_sal_cuen_debito($row[0]);
				  						$debito = mssql_fetch_array($deb);
				  						$credito=mssql_fetch_array($cred);
				  						$saldo =$debito ['debito']- $credito['credito'];	
				  						$totalde4=$totalde4+$debito ['debito'];
				  						$totalcre4=$totalcre4+$credito['credito'];	
				  						$totalsal4= $totalde4- $totalcre4;			
		          				 		 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 		 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[1]' /></td>";
				 				 		 echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  		 echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 		 echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  		 echo "</tr>";		     
			  					 	  }//fin while 4
									   echo "<tr>";
              						 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde4' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre4' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal4' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
							/////////////////////////////////////////empieza la siguiente cuenta 5
							$saldo5=0;						
			   		$totalde5=0;
			 		$totalcre5=0;
					$totalsal5=0;
					              $sqllike="select *
					              from cuentas
								  where cue_subdivision ='no' and cue_id like'5%' ";
								 $sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>    5    </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									   $form =new saldos();
				 	 			 		$cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 		$deb=$form->con_sal_cuen_debito($row[0]);
				  						 $debito = mssql_fetch_array($deb);
				  						 $credito=mssql_fetch_array($cred);
				  						 $saldo =$debito ['debito']- $credito['credito'];	
				  						 $totalde5=$totalde5+$debito ['debito'];
				  						 $totalcre5=$totalcre5+$credito['credito'];	
				  						 $totalsal5= $totalde5- $totalcre5;			
		          				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[1]' /></td>";
				 				 			 echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  			echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 			 echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  			echo "</tr>";		     
			  					 
									}//fin while5
									echo "<tr>";
              						 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde5' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre5' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal5' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
							/////////////////////////////////////////empieza la siguiente cuenta 6
							$saldo6=0;						
			   		$totalde6=0;
			 		$totalcre6=0;
					$totalsal6=0;
					              $sqllike="select *
					              from cuentas
								  where cue_subdivision ='no' and cue_id like'6%' ";
								 $sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>    6    </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									   $form =new saldos();
				 	 			 		$cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 		$deb=$form->con_sal_cuen_debito($row[0]);
				  						 $debito = mssql_fetch_array($deb);
				  						 $credito=mssql_fetch_array($cred);
				  						 $saldo =$debito ['debito']- $credito['credito'];	
				  						 $totalde6=$totalde6+$debito ['debito'];
				  						 $totalcre6=$totalcre6+$credito['credito'];	
				  						 $totalsal6= $totalde6- $totalcre6;			
		          				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[1]' /></td>";
				 				 			 echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  			echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 			 echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  			echo "</tr>";		     
			  					 
									}//fin while 6
									echo "<tr>";
              						 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde6' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre6' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal6' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
							/////////////////////////////////////////empieza la siguiente cuenta7
							$saldo7=0;						
			   		$totalde7=0;
			 		$totalcre7=0;
					$totalsal7=0;
					              $sqllike="select *
					              from cuentas
								  where cue_subdivision ='no' and cue_id like'7%' ";
								 $sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>    7     </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									   $form =new saldos();
				 	 			 		$cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 		$deb=$form->con_sal_cuen_debito($row[0]);
				  						 $debito = mssql_fetch_array($deb);
				  						 $credito=mssql_fetch_array($cred);
				  						 $saldo =$debito ['debito']- $credito['credito'];	
				  						 $totalde7=$totalde7+$debito ['debito'];
				  						 $totalcre7=$totalcre7+$credito['credito'];	
				  						 $totalsal7= $totalde7- $totalcre7;			
		          				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[1]' /></td>";
				 				 			 echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  			echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 			 echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  			echo "</tr>";		     
			  					 
									}//fin while 7
									echo "<tr>";
									 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde7' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre7' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal7' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
							/////////////////////////////////////////empieza la siguiente cuenta 8
							$saldo8=0;						
			   		$totalde8=0;
			 		$totalcre8=0;
					$totalsal8=0;
					              $sqllike="select *
					              from cuentas
								  where cue_subdivision ='no' and cue_id like'8%' ";
								 $sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
						
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>    8   </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									   $form =new saldos();
				 	 			 		$cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 		$deb=$form->con_sal_cuen_debito($row[0]);
				  						 $debito = mssql_fetch_array($deb);
				  						 $credito=mssql_fetch_array($cred);
				  						 $saldo =$debito ['debito']- $credito['credito'];	
				  						 $totalde8=$totalde8+$debito ['debito'];
				  						 $totalcre8=$totalcre8+$credito['credito'];	
				  						 $totalsal8= $totalde8- $totalcre8;			
		          				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[1]' /></td>";
				 				 			 echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  			echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 			 echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  			echo "</tr>";		     
			  					 
									}//fin while 8
									echo "<tr>";
              						 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde8' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre8' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal8' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
							/////////////////////////////////////////empieza la siguiente cuenta 9
							$saldo9=0;						
			   		$totalde9=0;
			 		$totalcre9=0;
					$totalsal9=0;
					              $sqllike="select *
					              from cuentas
								  where cue_subdivision ='no' and cue_id like'9%' ";
								 $sallike=mssql_query($sqllike);	
									
									echo "<tr>";
					    			echo "<th colspan='5'>las   cuentas  tiene el  siguiente balance </th>";				
				  					echo "</tr>";
									//trae los saldos del mes anterior de la cuenta principal
						echo "<tr>";
					    echo "<td colspan='2' rowspan='2'>saldo que viene</td>";	
						echo "<td colspan='1'>naturaleza debito</td>";	
						echo "<td colspan='1'>naturaleza credito</td>";	
						echo "<td colspan='1'> saldo</td>";			
				  		echo "</tr>";
						echo "<tr>";					   
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";	
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";
						echo "<td><input name='cuenta'  id='cuenta' type='text' value='$' /></td>";		
				  		echo "</tr>";
						/////hasta aaca
									echo "<tr>";
									echo "<td rowspan='2'>   9    </td>";
									echo "<td rowspan='2'>  NOMBRE     </td>";
					   			    echo "<td colspan='2'>naturaleza naturaleza</td>";
									echo "<td rowspan='2'>  saldo      </td>";
					   			    echo "</tr>";
			     		            echo "<tr>";
						            echo "<td >debito</td>";
						            echo "<td >credito</td>";
						            echo "</tr>";		          		
	              		            echo "<tr>";
						             while($row = mssql_fetch_array($sallike))
								      {      
									   $form =new saldos();
				 	 			 		$cred=$form->con_sal_cuen_credito($row[0]);
				 	 			 		$deb=$form->con_sal_cuen_debito($row[0]);
				  						 $debito = mssql_fetch_array($deb);
				  						 $credito=mssql_fetch_array($cred);
				  						 $saldo =$debito ['debito']- $credito['credito'];	
				  						 $totalde9=$totalde9+$debito ['debito'];
				  						 $totalcre9=$totalcre9+$credito['credito'];	
				  						 $totalsal9= $totalde9- $totalcre9;			
		          				 			 echo "<td><input name='cuenta'  id='cuenta' type='text' value='$row[0]' /></td>";
				  				 			 echo "<td>$row[1]</td>";
				 				 			 echo "<td><input name='debito'  id='' type='text' value='".$debito ['debito']."' /></td>"; 
		         				  			echo "<td><input name='credito'  id='' type='text' value='".$credito['credito']."' /></td>"; 
								 			 echo "<td><input name='saldo'  id='' type='text' value='$saldo' /></td>"; 
		          				  			echo "</tr>";		     
			  					 
									}//fin while 9
									echo "<tr>";
              						 echo "<td colspan='2'><input name='totales' id='' type='text' value='totales' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalde9' /></td>";
               						 echo "<td ><input name='' id='' type='text' value='$totalcre9' /></td>";
               						 echo "<td ><input name='' id='' type='text' value=' $totalsal9' /></td>";
               						 echo "</tr>";  
							///////////////////////////////////////////////////////////
                                 
								 echo " <tr>";
                                echo "<th colspan='5'>TOTAL  GENERAL </th>";
                                 echo "  </tr>";
                                 echo "  <tr>";
                                 echo "<td rowspan='2' colspan='2' ><center><input name='cuenta'  id='cuenta' type='text' value='TOTALES GENERALES' /></center></td>";
				  				 echo "<td><input name='cuenta'  id='cuenta' type='text' value='DEBITO' /></td>";
				 				 echo "<td><input name='debito'  id='' type='text' value='CREDITO' /></td>"; 
		         				 echo "<td><input name='credito'  id='' type='text' value='SALDO' /></td>"; 
								 echo "</tr>";
              						
									$totalde= $totalde1+$totalde2+$totalde3+$totalde4+$totalde5+$totalde6+$totalde7+$totalde8+$totalde9;
									$totalcre=$totalcre1+$totalcre2+$totalcre3+$totalcre4+$totalcre5+$totalcre6+$totalcre7+$totalcre8+$totalcre9;
									$totalsal=$totalsal1+$totalsal2+$totalsal3+$totalsal4+$totalsal5+$totalsal6+$totalsal7+$totalsal8+$totalsal9;
								echo "<tr>";	 
								 echo " <td ><input name='' id='' type='text' value='$totalde' /></td> ";
								 echo " <td ><input name='' id='' type='text' value='$totalcre' /></td> ";
								 echo " <td ><input name='' id='' type='text' value='$totalsal' /></td> ";
								
				   			 		
							  echo "</table >";
											
							 
			
	       
			     
					
$output.= "\\par ";  //<-- ENTER      
$output.= "\\par ";  //<-- ENTER      
$output.= "\\par ";  //<-- ENTER      




/*  Enviamos el documento completo a la salida  */

