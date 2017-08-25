<!DOCTYPE html>
<html>
<head>
	<link href="stile.css" rel="stylesheet" type="text/css">
	<meta charset="utf-8" />
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>
<h2 align="center">Scegli dati da visualizzare </h2>
<h3 align="center">Menu</h3>
<div align="center" style="border: solid 2px #42a1f4; border-radius:10px;"><br>
<button class="bottone" onclick="window.location.href='gestioneApplicazioneEsterna.php'">Gestione Applicazioni esterne</button>
<button class="bottone" onclick="window.location.href='dashboard.html'">Visualizza dashboard</button>
<button class="bottone" onclick="window.location.href='logout.php'">Logout</button>

<br><br>
</div>


<br><br><br>
<form action="scegliDati.php" method="POST">
<?php
	session_start();
    require("connessione.php");
    $array_campi=array();
    $campi = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('rilevazione', 'sensore') AND COLUMN_NAME NOT LIKE 'id_%'"); 
   $i=0;
   while($righe=mysql_fetch_assoc($campi)){
    	$array_campi[$i]=$righe["COLUMN_NAME"];
        $i++;
    }
    $i=0;
    echo"<div align='center'>";
    for($i=0;$i<count($array_campi);$i++){
    	 echo"<font class='scritta'> $array_campi[$i]</font>"."<input type='checkbox' name='$array_campi[$i]'>";
         
    }
   
    echo "<input type=submit class='bottone' value='Scegli dati'></form><br>";
   
   for($i=0;$i<count($array_campi);$i++){
	if(isset($_POST[$array_campi[$i]])){
    	
 		$campi_query=$campi_query.$array_campi[$i].',';
 	}
}	

 $arrpost = explode(",",$campi_query);
 
echo"<table class='table'>";
echo"<tr>";
	 for($i=0;$i<count($arrpost);$i++){
     echo"<th class='th'>$arrpost[$i]</th>";
     }
     echo"</tr>";
	
	$campi_query=substr($campi_query,0,-1);
    $tabella_da_visualizzare=mysql_query("SELECT ".mysql_real_escape_string($campi_query)." FROM ((rilevazione inner join sensore ON rilevazione.id_sensoreFK=sensore.id_sensore) inner join utente ON sensore.id_clienteFK=utente.id_cliente) WHERE id_cliente=".mysql_real_escape_string($_SESSION["id"])."");
    echo "<br>";
    while($righe=mysql_fetch_assoc($tabella_da_visualizzare))
    {
    echo"<tr>";
       for($i=0;$i<count($righe);$i++){
       echo"<td align='center' class='td'>". $righe[$arrpost[$i]] ."</td>";
       }
       echo"</tr>";
    }

    echo"</div>";
?>

</body>
</html>