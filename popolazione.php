<?php
require("connessione.php");
$bag = array("mermec","google","termicensor","mcsensor","nicesensor","sensorproducts","sensornet","netlive","prosensor","cheapsensor");

$colonne=mysql_query("SHOW COLUMNS FROM rilevazione WHERE Field NOT LIKE 'id%' ")or die(mysql_error());
$array_campi=array();
$i=0;
$array_desc=array("descrizione1","descrizione2","descrizione3"," ");
while($row=mysql_fetch_assoc($colonne)){
    $array_campi[$i]=$row["Field"];
    $i++;
}

$testUtente= $bag[rand(0,(count($bag)-1))];


$ins_utente =  mysql_query("INSERT INTO utente (username,password,nome,cognome) values('".$testUtente."', '".$testUtente."', '".$testUtente."', '' )") or die(mysql_error());
$utenti=mysql_query("SELECT id_cliente FROM utente ORDER BY id_cliente DESC LIMIT 1")or die(mysql_error());
$row=mysql_fetch_assoc($utenti);
$id_clienteFK=($row["id_cliente"]);
echo "Cliente: <br>";
print($id_clienteFK.' '.$testUtente);echo "<br>";
$tipi_sensore=mysql_query("SELECT marca,tipo FROM tipi_sensore")or die(mysql_error());

for($k=0;$k<=rand(1,5);$k++){
	$rand=rand(1,mysql_num_rows($tipi_sensore));
	for($i=1;$i<=$rand;$i++){
		$row=mysql_fetch_assoc($tipi_sensore);
	    //$salva = $row["tipo"];    
		$marca=$row["marca"];
	    $tipo=$row["tipo"];
	}
	mysql_data_seek($tipi_sensore,0);
	$ins_sensore=mysql_query("INSERT INTO sensore (id_clienteFK,marca,tipo) values('".$id_clienteFK."', '".$marca."', '".$tipo."' )")or die(mysql_error());
	$sensori=mysql_query("SELECT id_sensore FROM sensore ORDER BY id_sensore DESC LIMIT 1")or die(mysql_error());
	$row=mysql_fetch_assoc($sensori);
	$id_sensoreFK=($row["id_sensore"]);*/
	echo "Sensore: <br>";
	print($id_sensoreFK.' '.$id_clienteFK.' '.$marca.' '.$tipo);echo "<br>";
}


?>