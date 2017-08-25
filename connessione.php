<?php

$connessione = mysql_connect("127.0.0.1","root","");
if($connessione){

if(mysql_select_db("my_easysensor",$connessione)){

}
else{
echo "Errore durante la connessione!";
}
}

?>