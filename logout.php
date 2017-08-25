<?php

require("connessione.php");
session_start();
unset($_SESSION['id']);
unset($_SESSION['nome']);
unset($_SESSION['codice']);

header("location:index.php");
?>