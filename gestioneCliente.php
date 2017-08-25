<?php
require "connessione.php";
require "alert.php";
session_start();
$nome ="";
$username="";
$password="";
$id_cliente="";



//PREMUTO IL BOTTONE AGGIUNGI CLIENTE

if(isset($_POST["submitAggiungiCliente"])){

if(isset($_POST["nome"]) && isset($_POST["username"]) && isset($_POST["password"])){
$username = mysql_real_escape_string($_POST["username"]);
$password =  mysql_real_escape_string($_POST["password"]);
$nome =  mysql_real_escape_string($_POST["nome"]);

if(aggiungiCliente( $username, $password, $nome)) {
$mess='Cliente aggiunto';
Alert($mess);
}
else{
$mess='Username esistente';
Alert($mess);}

}
}


//PREMUTO IL BOTTONE ELIMINA CLIENTE
if(isset($_POST["submitRimuoviCliente"])){

if(isset($_POST["id_cliente"])){
$id_cliente = $_POST["id_cliente"];

if(!rimuoviCliente($id_cliente)){
$mess='Cliente non trovato';
Alert($mess);}
else {
$mess='Cliente rimosso';
Alert($mess);}

}
}
if(isset($_POST["submitVisualizzaClienti"])){
$query = mysql_query("SELECT id_cliente, username, password,nome FROM utente WHERE cognome = '' ");

}

function visualizzaClienti(){
 mysql_query("SELECT id_cliente, username, password,nome FROM utente WHERE cognome = '' ");
}


function aggiungiCliente($username, $password, $nome){
//controllo l'esistenza del cliente
$query = mysql_query("SELECT * FROM utente WHERE username = '".$username."' ");


//se non esiste lo aggiungo
if(mysql_num_rows($query)==0){
$ins =  mysql_query("INSERT INTO utente (username,password,nome) values('".$username."', '".$password."', '".$nome."' )") ;

if($ins){
return true;
}

else {
return false;//username non esiste ma l'inserimento non è andato a buon fine
}
}
else{
return false; //username esiste
}


}



function rimuoviCliente($id_cliente){

//se esiste lo elimino
if(trovaCliente($id_cliente)){
 mysql_query("DELETE utente,sensore,rilevazione FROM utente
INNER JOIN sensore ON `utente`.`id_cliente` = `sensore`.`id_clienteFK`
INNER JOIN rilevazione ON `sensore`.`id_sensore` = `rilevazione`.`id_sensoreFK`
WHERE id_cliente = '".$id_cliente."'   ");

return true;
}

else {
return false;
}
}



function trovaCliente($id_cliente){
//controllo l'esistenza del cliente
$query = mysql_query("SELECT * FROM utente WHERE id_cliente = '".$id_cliente."' ");
if(mysql_num_rows($query)>0){
return true;
}
else {
return false;
}
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
<div id="gestioneclienti" align="center">



<h2> Gestione clienti </h2>

<h3>Menu</h3>
<div align="center" style="border: solid 2px #42a1f4; border-radius:10px;"><br>
<button class="bottone" onclick="window.location.href='gestioneSensore.php'">Gestione sensori</button>
<button class="bottone" onclick="window.location.href='recuperaDati.php'">Recupera dati</button>
<button class="bottone" onclick="window.location.href='tipoSensore.php'">Inserisci tipo di sensore</button>
<button class="bottone" onclick="window.location.href='logout.php'">Logout</button>
<br><br>
</div>
<br><br><br>

<form action="gestioneCliente.php" method="post">

<input type="text" class="casella" name="nome" class="AGGIUNGERE STILE CSS" placeholder="Inserisci nome azienda"><br><br>	
<input type="text" class="casella" name="username" class="AGGIUNGERE STILE CSS" placeholder="Inserisci username"><br><br>
<input type="password" class="casella" name="password" class="AGGIUNGERE STILE CSS" placeholder="Inserisci password"><br><br>
<input type="submit" class="bottone" name="submitAggiungiCliente" value="Aggiungi cliente">

</form><br><br>

<form action="gestioneCliente.php" method="post">
<input type="submit" class="bottone" value="Visualizza clienti" name="submitVisualizzaClienti">
</form>

<div id="visualizza">
<table class="table">

<?php
$query="";
if($query){
echo"<tr>";
echo "<th class='th'>ID CLIENTE</th>";
echo "<th class='th'>NOME AZIENDA</th>";
echo "<th class='th'>USERNAME</th>";
echo "<th class='th'>PASSWORD</th>";
echo"</tr>";
}

while ($row = mysql_fetch_assoc($query)) {
		echo"<tr>";
        echo "<td class ='td'> ". $row['id_cliente']."</td> ";
        echo "<td class ='td'> ". $row['nome']." </td>";
        echo "<td class ='td'> ". $row['username']." </td> ";
        echo "<td class ='td'> ". $row['password']." </td> ";
        echo "</tr>";
        }

?>
</table>
</div>
<br><br>

<form action="gestioneCliente.php" method="post">


<input type="text"  name="id_cliente" class="casella" placeholder="Inserisci id cliente da eliminare"><br><br>
<input type="submit" name="submitRimuoviCliente" class="bottone" value="Rimuovi cliente">

</form><br><br>
</div>
</body>
</html>
