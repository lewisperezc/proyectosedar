<?php
    $foto = $_FILES['file']['tmp_name'];
    if(is_uploaded_file($foto))
	{
	  $ruta = "seg_Anestecoop/";
      if(move_uploaded_file($foto,$ruta."anestecoop".'.csv'))
       {
		 $seg_anestecoop = fopen($ruta."anestecoop.csv","r");
		 if($seg_anestecoop == ""){echo "Error abriendo archivo"; }
		 else
		 {
			 $sql = "TRUNCATE TABLE seg_social_anestecoop";
			 $query = mssql_query($sql);
			 if(!$query) 
			 	echo "Error <B>Error";
			 else
			 {
				 while ($data = fgetcsv($seg_anestecoop,10000,";")) 
				 {
    				$num = count($data);
					
					$query = "INSERT INTO seg_social_anestecoop VALUES('$data[0]',$data[2],$data[3])";
				 	$req = mssql_query($query);
			     }
		   	 }
	      }
	   }
	  else
	    echo "no se pudo abrir el archivo"; 
	}?>