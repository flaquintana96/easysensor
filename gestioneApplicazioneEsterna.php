<?php
require 'connessione.php';
require 'alert.php';
session_start();

$idutente = $_SESSION['id'];


$codice=0;
$radio='';
$vis='';

function visualizzaDashboard(){

}

function scegliDatiTrasferimento(){
$stringa ='0000000000000000';


$checkbox = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('rilevazione', 'sensore') AND COLUMN_NAME NOT LIKE 'id_%'"); 

$i=0;

if(isset($_POST['nome'])){
$nome = $_POST['nome'];
}
while($row = mysql_fetch_assoc($checkbox)){
$temp = $row['COLUMN_NAME'];

 if(isset($_POST['test'.$temp])){
 $stringa[$i]='1';
 } else{
 $stringa[$i]='0';
 }
 
$i++;
}
	$id_sicuro =  mysql_real_escape_string($_SESSION['id']);
	$codice_sicuro = mysql_real_escape_string($codice);
	$nome_sicuro =  mysql_real_escape_string($nome);
	$stringa_sicura=  mysql_real_escape_string($stringa);
	$mex="CODICE APPLICAZIONE ESTERNA: $codice";
	Alert($mex);
	
	
	
	$stmt = $dbh->prepare('INSERT INTO (id_clienteFK,codice,nome,preferenze) 
VALUES (:id, :cod, :nome, :preferenze)');
$stmt->bindParam('id', $id_sicuro);
$stmt->bindParam('cod', $codice_sicuro);
$stmt->bindParam(':nome', $nome_sicuro);
	$stmt->bindParam( ':preferenze', $stringa_sicura);
$stmt->execute();
	
	
	
 //mysql_query("INSERT INTO applicazione_esterna (id_clienteFK,codice,nome,preferenze) values ('".$id_sicuro."','".$codice_sicuro."','".$nome_sicuro."','".$stringa_sicura."' )");               



}



//PREMUTO IL BOTTONE ELIMINA AUTORIZZAZIONE
if(isset($_POST['submitEliminaAutorizzazione'])){

if(isset($_POST["codice"])){
$codiceElimina = mysql_real_escape_string($_POST['codice']);

if( !eliminaAutorizzazione($codiceElimina)){
	$mex='Codice non trovato!';
	Alert($mex);
}
else {
$mex='Applicazione rimossa!';
	Alert($mex);
}

}
}




function autorizzaApplicazioneEsterna(){
$codice = rand(1,1000000000);

}





function eliminaAutorizzazione($codiceElimina){
$n;
$n2;
$select = mysql_query('SELECT * FROM applicazione_esterna');
$n = mysql_num_rows($select);
/*
if(trovaApplicazioneEsterna($codicElimina)){
	$codiceElimina_sicuro = mysql_real_escape_string($codiceElimina);
	$query="DELETE FROM applicazione_esterna WHERE codice =".$codiceElimina_sicuro;
	$query=mysql_query($query);
}*/
$select = mysql_query('SELECT * FROM applicazione_esterna');
$n2 = mysql_num_rows($select);

if($n > $n2){
return true;
}
else{
return false;
}

}

function trovaApplicazioneEsterna($codiceElimina){
$select = mysql_query("SELECT * FROM applicazione_esterna WHERE codice = '".$codiceElimina."' ");
if(mysql_num_rows($select)>0){
return true;
}
else{
return false;
}
}



//PREMUTO IL BOTTONE AUTORIZZA
if(isset($_POST['submitAutorizzazione'])){
autorizzaApplicazioneEsterna();
scegliDatiTrasferimento();
}

//PREMUTO IL BOTTONE VISUALIZZA APPLICAZIONI ESTERNE
if(isset($_POST['submitVisualizzaApplicazioniEsterne'])){
$vis = visualizzaApplicazioniEsterne();
}

function visualizzaApplicazioniEsterne(){

$query = mysql_query("SELECT * FROM applicazione_esterna WHERE id_clienteFK = '".mysql_real_escape_string($_SESSION['id'])."' ");
return $query;
}


?>



<!DOCTYPE html>
<html>
<head>
	<link href="stile.css" rel="stylesheet" type="text/css">
	<meta charset="utf-8" />
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>

<div align="center">
<h2>Benvenuto <?=$_SESSION['nome'] ?> </h2> 
<h3> Menu </h3>
<div align="center" style="border: solid 2px #42a1f4; border-radius:10px;"><br>
<button class="bottone" onclick="window.location.href='dashboard.html'">Visualizza dashboard</button>
<button class="bottone" onclick="window.location.href='scegliDati.php'">Scegli dati da visualizzare</button>
<button class="bottone" onclick="window.location.href='logout.php'">Logout</button>

<br><br>
</div>

<br><br><br><br>
<form action="gestioneApplicazioneEsterna.php" method="post">

<div style="border: solid 2px #42a1f4; border-radius:10px;">
CODICE : <?=$codice; ?><br><br>
<input class="casella" type="text" placeholder="Inserisci nome applicazione esterna" name="nome"><br>
<br><br>
<?


$checkbox = mysql_query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME` IN ('rilevazione', 'sensore') AND COLUMN_NAME NOT LIKE 'id_%'"); 

while($row = mysql_fetch_assoc($checkbox)){

$temp = htmlspecialchars( mysql_result($row, 0, 'COLUMN_NAME') );
	$pre_input_checkbox="<input type='checkbox'  name='test";
	$post_input_checkbox=" value = '$temp'>";
	 $stampa=$temp.$pre_input_checkbox.$temp.$post_input_checkbox;
	echo $stampa;
	

}

?>

<br><br>
<input class="bottone" type="submit" value="Autorizza applicazione esterna" name="submitAutorizzazione"><br>
<br>
</div>
<br><br>
<form action="gestioneApplicazioneEsterna.php" method="post">
<input class="bottone" type="submit" value="Visualizza applicazioni esterne" name="submitVisualizzaApplicazioniEsterne">
</form>
<br>
<div id="visualizza">
<table class="table">

<?php
	$tr='<tr>';
	$_tr='</tr>';
	$td_class="<td class='td'>";
	$_td='</td>';
	
if(isset($vis)){

echo $tr;
$stampa="<th class='th'>CODICE</th>";
echo $stampa;
$stampa="<th class='th'>NOME APPLICAZIONE</th>";
echo $stampa;
$stampa="<th class='th'>FORMATO</th>";
echo $stampa;
echo $_tr;

}
	
while ($row = mysql_fetch_assoc($vis)) {
	$codice= htmlspecialchars( mysql_result($row, 0, 'codice') );
	$nome= htmlspecialchars( mysql_result($row, 0, 'nome') );
	$formato= htmlspecialchars( mysql_result($row, 0, 'formato') );
	echo $tr;
        $stampa=$td_class.$codice.$_td;
	echo $stampa;
        $stampa=$td_class.$nome.$_td;
	echo $stampa;
        $stampa=$td_class.$formato.$_td;
	echo $stampa;
        echo $_tr;
        
}

?>
</table>
</div>
<br><br>

<div style="border: solid 2px #42a1f4; border-radius:10px;">
<form action="gestioneApplicazioneEsterna.php" method="post">
<br>
<input class="casella" type="number" placeholder="Inserisci id applicazione esterna da eliminare" name="codice"><br><br>
<input class="bottone" type="submit" value="Elimina autorizzazione" name="submitEliminaAutorizzazione">
</form>
<br>
</div>

</body>
</html>
