<?php
//PAGINA DI LOGIN
require 'connessione.php';
require 'alert.php';
session_start();

global $username;
global $password;

//PREMUTO BOTTONE LOGIN
if(isset($_POST['login'])=== true){
if( isset($_POST['username']) && isset($_POST['username'])){
$username = $_POST['username'];
$password = $_POST['password'];

if(richiediAutenticazione()=== true){
// admin loggato;
header('location:admin.php');
}
else{
//cliente loggato
header('location:cliente.php');
}
}
}

//PREMUTO BOTTONE LOGIN APPLICAZIONE ESTERNA
if(isset($_POST['loginapp'])=== true){

if( isset($_POST['codice'])=== true){
$codice = mysql_real_escape_string($_POST['codice']);

//$sql = mysql_query("SELECT * FROM applicazione_esterna WHERE codice ='".$codice."' ");
	$stmt = $dbh->prepare("SELECT * FROM applicazione_esterna WHERE codice = :cod");
	$stmt->bindParam(':cod', $codice);
	$stmt->execute();

if(mysql_num_rows($stmt)>0){
$row = mysql_fetch_assoc($sql);
$_SESSION['codiceapp'] = $row['codice'];
$_SESSION['nomeapp'] = $row['nome'];
$_SESSION['formatoapp'] = $row['formato'];
$_SESSION['preferenzeapp'] = $row['preferenze'];


header('location: app.php');

}
}
}

function richiediAutenticazione(){


unset($_SESSION['id']);
unset($_SESSION['nome']);

$sql = mysql_query("SELECT * FROM utente WHERE username ='".$username."' AND password = '".$password."' ");
if(mysql_num_rows($sql)>0){
$row = mysql_fetch_assoc($sql);

$_SESSION['id'] = $row['id_cliente'];
$_SESSION['nome'] = $row['nome'];

if($row['cognome'] ==='' || $row['cognome'] ==='' ){ //SE NON C'È IL COGNOME VAI IN CLIENTE.PHP
return false;
} 

else
{
return true; //SE C'È IL COGNOME VAI IN ADMIN.PHP
}



}

else{
$mess='Username o password errati!';
Alert($mess);
unset($_SESSION['id']);
unset($_SESSION['nome']);
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
<div id="menu" align="center">
<h2 > EASYSENSOR  </h2>
<h3> Login </h3>

<form action="index.php" method="post">
<input type="text" name="username" placeholder="Inserisci username" class="casella"><br><br>
<input type="password" name="password" placeholder="Inserisci password" class="casella"><br><br>
<input type="submit" value="Login" name="login" class="bottone">
</form>
<br><br><br>
<h3> Login applicazioni esterne </h3>

<form action="index.php" method="post">
<input type="number" name="codice" placeholder="Inserisci codice" class="casella" ><br><br>
<input type="submit" value="Login" name="loginapp" class="bottone">
</form>




</div>
</body>
</html>
