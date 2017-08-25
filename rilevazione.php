<?php
require ("connessione.php");
function decodificaStringa ($stringa){
	$id=0;
    $lunghezza=0;
    $idStringa="";
	for ($i=0;$i<10;$i++){
    	$idStringa=$idStringa.$stringa[$i];
    }   
	
	$querymarca = "SELECT marca FROM sensore WHERE id_sensore=$idStringa";
    $marca=mysql_query (addslashes($querymarca));
	
	$querytipo = "SELECT tipo FROM sensore WHERE id_sensore=$idStringa";
    $tipo=mysql_query (addslashes($querytipo));
    
      
   	$marca_stringa=mysql_fetch_assoc($marca);
	$marca_estratta=$marca_stringa["marca"];

    $tipo_stringa=mysql_fetch_assoc($tipo);
	$tipo_estratto=$tipo_stringa["tipo"];
   
	$querypattern = "SELECT pattern FROM tipi_sensore WHERE tipo= $tipo_estratto AND marca= $marca_estratta";
  	$pattern=mysql_query (addslashes($querypattern));
	
	$queryarraystringhe = "SELECT array_stringhe FROM tipi_sensore WHERE tipo=$tipo_estratto AND marca=$marca_estratta";
    $array_stringhe=mysql_query (addslashes($queryarraystringhe));
   	
    $pattern_stringa=mysql_fetch_assoc($pattern);
	$pattern_estratto=$pattern_stringa["pattern"];
    
    $array_stringhe_stringa=mysql_fetch_assoc($array_stringhe);
	$array_stringhe_estratto=$array_stringhe_stringa["array_stringhe"];

    //estrapolo i valori dalla stringa di cifre decimali
    $numero=explode(',',$pattern_estratto);
    $lunghezza=0;
    $stringa_da_inserire="";
	$j=10+ strlen($tipo_estratto) +strlen($marca_estratta)+$lunghezza;
    $k=0;
    for ($i=0;$i<count($numero);$i++){
      	$j=$j+$lunghezza;
        $lunghezza=$numero[$i];
        $valore="";
   		for ($k=$j;$k<$j+$lunghezza;$k++){
			$valore=$valore.$stringa[$k];
   		}
   		$stringa_da_inserire=$stringa_da_inserire.$valore.',';
	}
    
    $array_da_controllare=explode(',',$stringa_da_inserire);
    $stringa_da_controllare=implode($array_da_controllare);;
    $stringa_errore=mysql_query ("SELECT stringa_errore FROM tipi_sensore WHERE tipo=$tipo_estratto AND marca=$marca_estratta");
    $stringa_errore=mysql_fetch_assoc($stringa_errore);
	$stringa_errore=$stringa_errore["stringa_errore"];
    $erroreTrovato=false;
    if ($stringa_da_controllare==$stringa_errore){
    	$erroreTrovato=true;
   	}
		
    //estrapolo la descrizione 
    $descrizione="";
    $descrizione=substr($stringa,$k,strlen($stringa));
    $stringa_da_inserire=$stringa_da_inserire.$descrizione;
    


    
    //prende i nomi delle colonne per la query
    $colonne=mysql_query("SHOW COLUMNS FROM rilevazione WHERE Field NOT LIKE 'id_r%'");
    $stringa_query="";
    while ($righe=mysql_fetch_assoc($colonne)){
    	$stringa_query=$stringa_query.$righe["Field"].',';
    }
   	$stringa_query=substr($stringa_query,0,-1);


    
    //crea la stringa values per la query
    $stringa_query_senza_virgole=explode(',',$stringa_query);
    $array_stringhe_estratto=$array_stringhe_estratto.','."descrizione";
    $array_stringhe_estratto_senza_virgole=explode(',',$array_stringhe_estratto);
    $array_stringhe_da_inserire_senza_virgole=explode(',',$stringa_da_inserire);
    $indice_array_stringhe=0;
    $values_con_virgola="";
    
    
    $array_values_senza_virgola=array();
    
	for ($i=0;$i<count($stringa_query_senza_virgole) && $indice_array_stringhe<count($array_stringhe_da_inserire_senza_virgole);$i++){
    	if ($stringa_query_senza_virgole[$i]==$array_stringhe_estratto_senza_virgole[$indice_array_stringhe]){
        	$array_values_senza_virgola[$i]=$array_stringhe_da_inserire_senza_virgole[$indice_array_stringhe];
    		$indice_array_stringhe++;
            $i=-1;
        }
	}
    //elimino l'ultima virgola
    if($erroreTrovato)$array_values_senza_virgola[4]=1;else $array_values_senza_virgola[4]='0';
    for($i=0;$i<count($stringa_query_senza_virgole);$i++){
    	$array_values_senza_virgola[0]=$idStringa;//avvaloro FK  
       	if($array_values_senza_virgola[$i]!=NULL){
 		   	$values_con_virgola=$values_con_virgola.'"'.$array_values_senza_virgola[$i].'"'.',';
        }else{            
        	$values_con_virgola=$values_con_virgola."NULL".',';//ATTENZIONE AL NULL
        }
    }
    $values_con_virgola=substr($values_con_virgola,0,-1);
   mysql_query ("INSERT INTO rilevazione ($stringa_query) values($values_con_virgola)");
}

function memorizzaDati($stringa){
	decodificaStringa($stringa);
}

$stringa=$_POST["stringa"];
memorizzaDati($stringa);
header("location:paginaTest.php");
?>
