<?php
//PAGINA CLINTE
session_start();
if(!isset($_SESSION['nome'])) header("location:index.php");

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
<h2> BENVENUTO <?=$_SESSION['nome']?> </h2>
<h3> Menu </h3>
<div align="center" style="border: solid 2px #42a1f4; border-radius:10px;"><br>
<button class="bottone" onclick="window.location.href='gestioneApplicazioneEsterna.php'">Gestione Applicazioni esterne</button>
<button class="bottone" onclick="window.location.href='dashboard.html'">Visualizza dashboard</button>
<button class="bottone" onclick="window.location.href='scegliDati.php'">Scegli dati da visualizzare</button>
<button class="bottone" onclick="window.location.href='logout.php'">Logout</button>

<br><br>
</div>
</div>
</body>
</html>
