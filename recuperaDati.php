<?php
require 'connessione.php';
require 'alert.php';

$nome='';
$username='';
$password='';
$id_cliente='';

$marca='';
$tipo='';
$pattern='';
$id_sensore='';

$id_sensoreFK='';
$rilevazione='';
$data='';
$ora='';
$errore='';
$descrizione='';



//PREMUTO IL BOTTONE AGGIUNGI SENSORE
if(isset($_POST['submitAggiungiSensore'])){

if(isset($_POST['id_clienteFK']) && isset($_POST['marca']) && isset($_POST['tipo'])) {

$id_clienteFK = htmlspecialchars( $_POST['id_clienteFK']);
$marca = mysql_real_escape_string( $_POST['marca']);
$tipo = mysql_real_escape_string( $_POST['tipo']);


if(aggiungiSensore($id_clienteFK, $marca, $tipo)){
$mess='Sensore aggiunto';
 chiamaAlert($mess);}
else{
$mess='Proprietario non esistente';
 chiamaAlert($mess);}

}
}

//PREMUTO IL BOTTONE AGGIUNGI RILEVAZIONE

if(isset($_POST['submitAggiungiRilevazione'])){

if(isset($_POST['id_sensoreFK']) && isset($_POST['rilevazione']) && isset($_POST['data'])  && isset($_POST['ora'])  ){
$id_sensoreFK = mysql_real_escape_string($_POST['id_sensoreFK']);
$rilevazione = mysql_real_escape_string( $_POST['rilevazione']);
$data = mysql_real_escape_string($_POST['data']);
$ora = mysql_real_escape_string($_POST['ora']);
if(isset($_POST['errore']))$errore = mysql_real_escape_string($_POST['errore']);
else $errore=0;
if(isset($_POST['descrizione']))$descrizione = mysql_real_escape_string($_POST['descrizione']);
else $descrizione=NULL;


if(aggiungiRilevazione( $id_sensoreFK, $rilevazione, $data, $ora,  $errore, $descrizione)) {
$mess='Rilevazione aggiunta';
Alert($mess);}
else {
$mess='Errore!';
Alert($mess);}
}
}

//PREMUTO IL BOTTONE AGGIUNGI CLIENTE

if(isset($_POST['submitAggiungiCliente'])){
//aggiungere controlli lunghezza
if(isset($_POST['nome']) && isset($_POST['username']) && isset($_POST['password'])){
$username = htmlspecialchars($_POST['username']);
$password = mysql_real_escape_string($_POST['password']);
$nome = mysql_real_escape_string($_POST['nome']);

if(aggiungiCliente( $username, $password, $nome)) {
$mess='Cliente aggiunto';
Alert($mess);}
else{
$mess='Username esistente';
Alert($mess);
}

}
}




function aggiungiSensore($id_clienteFK, $marca, $tipo){
$checkcliente = mysql_query("SELECT * FROM utente WHERE id_cliente = '".$id_clienteFK."' ");
if(mysql_num_rows($checkcliente)==0){ return false;}

//$query = mysql_query("INSERT INTO sensore (id_clienteFK,marca,tipo) values('".$id_clienteFK."', '".$marca."', '".$tipo."' )") ;
		$stmt = $dbh->prepare("INSERT INTO sensore (id_clienteFK,marca,tipo) values(:id, :marca, :tipo )");
		$stmt->bindParam(':id', $id_clienteFK);
		$stmt->bindParam(':marca', $marca);
		$stmt->bindParam(':tipo', $tipo);
		$stmt->execute();
if(isset($stmt)){
return true;
}
}

function aggiungiRilevazione($id_sensoreFK, $rilevazione, $data, $ora, $errore, $descrizione){
//$query = mysql_query("INSERT INTO rilevazione (id_sensoreFK,rilevazione,data,ora,errore,descrizione) values('".$id_sensoreFK."', '".$rilevazione."','".$data."' ,'".$ora."' ,'".$errore."' ,'".$descrizione."')") ;
		$stmt = $dbh->prepare("INSERT INTO rilevazione (id_sensoreFK,rilevazione,data,ora,errore,descrizione) values(:id, :rilevazione, :data, :ora, :errore, :descr)");
		$stmt->bindParam(':data', $data);
		$stmt->bindParam(':ora', $ora);
		$stmt->bindParam(':rilevazione', $rilevazione);
		$stmt->bindParam(':descr', $descrizione);
		$stmt->bindParam(':id', $id_sensoreFK);
		$stmt->bindParam(':errore', $errore);
		$stmt->execute();
if(isset($stmt)){
return true;
}
else {
return false;
}

}





function trovaSensore($id_sensore){
//controllo l'esistenza del cliente
$query = mysql_query("SELECT * FROM sensore WHERE id_sensore = '".$id_sensore."' ");
if(mysql_num_rows($query)>0){
return true;
}
else {
return false;
}
}


function aggiungiCliente($username, $password, $nome){
//controllo l'esistenza del cliente
$query = mysql_query("SELECT * FROM cliente WHERE username = '".$username."' ");

//se non esiste lo aggiungo
if(mysql_num_rows($query)==0){
//mysql_query("INSERT INTO cliente (username,password,nome) values('".$username."', '".$password."', '".$nome."' )") ;
		$stmt = $dbh->prepare("INSERT INTO cliente (username,password,nome) values(:user, :pass, :nome)");
		$stmt->bindParam(':user', $username);
		$stmt->bindParam(':pass', $password);
		$stmt->bindParam(':nome', $nome);
		$stmt->execute();
return true;
}

else {
return false;
}

}




function trovaCliente($id_cliente){
//controllo l'esistenza del cliente
$query = mysql_query("SELECT * FROM cliente WHERE id_cliente = '".$id_cliente."' ");
if(mysql_num_rows($query)>0){
return true;
}
else {
return false;
}
}
mysql_close();
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

<div id="recuperadati">
<h2> Recupera dati </h2>


<div align="center" style="border: solid 2px #42a1f4; border-radius:10px;"><br>
<button class="bottone" onclick="window.location.href='gestioneCliente.php'">Gestione clienti</button>
<button class="bottone" onclick="window.location.href='gestioneSensore.php'">Gestione sensori</button>
<button class="bottone" onclick="window.location.href='tipoSensore.php'">Inserisci tipo di sensore</button>
<button class="bottone" onclick="window.location.href='logout.php'">Logout</button>
<br><br>
</div>
<br><br><br>

<div id="recuperadaticliente" style="display:inline-block">
<form action="recuperaDati.php" method="post">

<input type="text" class="casella" name="nome" class="casella" placeholder="Inserisci nome azienda"><br><br>
<input type="text" class="casella" name="username" class="casella" placeholder="Inserisci username"><br><br>
<input type="password" class="casella" name="password" class="casella" placeholder="Inserisci password"><br><br>
<input type="submit" class="bottone" name="submitAggiungiCliente" value="Aggiungi cliente">

</form>
</div>

<div id="recuperadatisensore" style="display:inline-block">
<form action="recuperaDati.php" method="post">

<input type="text" name="id_clienteFK" class="casella" placeholder="Inserisci il proprietario del sensore"><br><br>
<input type="text" name="marca" class="casella" placeholder="Inserisci marca"><br><br>
<input type="text" name="tipo" class="casella" placeholder="Inserisci tipo"><br><br>
<input type="submit" class="bottone" name="submitAggiungiSensore" value="Aggiungi sensore">

</form>
</div>

<div id="recuperadatirilevazione" style="display:inline-block">
<form action="recuperaDati.php" method="post">

<input type="number" name="id_sensoreFK" class="casella" placeholder="Inserisci id sensore"><br><br>
<input type="text" name="rilevazione" class="casella" placeholder="Inserisci rilevazione"><br><br>
<input type="date" name="data">
<input type="time" name="ora"><br><br>
<input type="number" name="errore" class="casella" placeholder="Inserisci errore" value="0"><br><br>
<input type="text" name="descrizione" class="casella" placeholder="Inserisci descrizione"><br><br>
<input type="submit" class="bottone" name="submitAggiungiRilevazione" value="Aggiungi rilevazione">

</form>
</div>

</div>
</body>
</html>
