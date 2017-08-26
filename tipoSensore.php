<?php
require 'connessione.php';
require 'alert.php';

if (isset($_POST['tipo'])){
	$tipo=$_POST['tipo'];
}    
if (isset($_POST['marca'])){
	$marca=$_POST['marca'];
}  
$stringhe_campi='';
if (isset($_POST['array_di_stringhe'])){
	$stringhe_campi=mysql_real_escape_string($_POST['array_di_stringhe']);
}  
$array_campi=explode(',',$stringhe_campi);
if (isset($_POST['pattern'])){
	$pattern=$_POST['pattern'];
}  
if (isset($_POST['fk_sensore'])){
	$fk_sensore=$_POST['fk_sensore'];
}  
if (isset($_POST['stringa_errore'])){
	$errore=mysql_real_escape_string($_POST['stringa_errore']);
} 
$colonne_tab_rilevazione=mysql_query('SHOW COLUMNS FROM rilevazione');
$field=array();
$i=0;
while($righe=mysql_fetch_assoc($colonne_tab_rilevazione)){
	$field[$i]=$righe["Field"];
    $i++;
}

for ($j=0;$j<count($array_campi);$j++){
	$trovato=false;
    for ($k=0;$k<count($field);$k++){
    	if ($array_campi[$j]==$field[$k]){
        	$trovato=true;
        }
	}
    if ($trovato==false){
	$stmt = $dbh->prepare("ALTER TABLE rilevazione ADD :array varchar(30)");
	$stmt->bindParam(':array', $array_campi[$j]);
	$stmt->execute();
   // mysql_query("ALTER TABLE rilevazione ADD $array_campi[$j] varchar(30)");
    }
}
if(isset($_POST['submitAdd'])){
	$stmt = $dbh->prepare("INSERT INTO tipi_sensore (tipo,marca,pattern,array_stringhe,id_sensoreFK,stringa_errore) values ( :tipo, :marca, :pattern, :array, :id, :errore)");
	$stmt->bindParam(':tipo', $tipo);
	$stmt->bindParam(':marca', $marca);
	$stmt->bindParam(':pattern', $pattern);
	$stmt->bindParam(':array', $stringhe_campi);
	$stmt->bindParam(':id', $fk_sensore);
	$stmt->bindParam(':errore', $errore);
	$stmt->execute();
	//$query="INSERT INTO tipi_sensore (tipo,marca,pattern,array_stringhe,id_sensoreFK,stringa_errore) values (".$tipo.','.$marca.','.$pattern.','.$stringhe_campi.','.$fk_sensore.','.$errore.')';
	//$ins_tipo=mysql_query($query); //or die(mysql_error());
	if(isset($stmt)){
		$mex='tipologia sensore aggiunto!';
		Alert($mex);
	}
	else{
		$mex='tipologia sensore non valido!';
		Alert($mex);
	}
}
if(isset($_POST['submitRimuoviTipo'])){

if(isset($_POST['tipo'])&&isset($_POST['marca'])){
$tipo = htmlspecialchars($_POST['tipo']);
$marca = htmlspecialchars($_POST['marca']);

if(!rimuoviTipo($tipo,$marca)){
	$mex='Tipologia di sensore non trovata!';
	Alert($mex);
}
else {
	$mex='Tipologia di sensore rimossa!';
	Alert($mex);

}

}
}
if(isset($_POST['submitVisualizzaTipi'])){
	$query = mysql_query('SELECT * FROM tipi_sensore ');
}

function visualizzaTipi(){
	 mysql_query('SELECT * FROM tipi_sensore ');
}

function rimuoviTipo($tipo,$marca){
	//se esiste lo elimino
	if(trovaTipo($tipo,$marca)){
		$stmt = $dbh->prepare("DELETE FROM tipi_sensore WHERE tipo = :tipo AND marca = :marca");
		$stmt->bindParam(':tipo', $tipo);
		$stmt->bindParam(':marca', $marca);
		$stmt->execute();
		//$query="DELETE FROM tipi_sensore WHERE tipo = ".$tipo." AND marca = ".$marca;
		// mysql_query($query);
		return true;
	}
	else {
		return false;
	}
}
function trovaTipo($tipo,$marca){
//controllo l'esistenza del tipo e marca
	$query = mysql_query("SELECT * FROM tipi_sensore  WHERE tipo = '".$tipo."' AND marca = '".$marca."' ");
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




<div id="menu" align="center">
<h2 > Aggiungi tipo sensore </h2>
<h3>Menu</h3>

<div align="center" style="border: solid 2px #42a1f4; border-radius:10px;"><br>
<button class="bottone" onclick="window.location.href='gestioneCliente.php'">Gestione clienti</button>
<button class="bottone" onclick="window.location.href='gestioneSensore.php'">Gestione sensori</button>
<button class="bottone" onclick="window.location.href='recuperaDati.php'">Recupera dati</button>
<button class="bottone" onclick="window.location.href='logout.php'">Logout</button>
<br><br>
</div>
<br>



<div id="tipoSensore" align="center">
<form action="tipoSensore.php" method="post">

<input type="text"  name="tipo" class="casella" placeholder="Inserisci tipo">Es. temperatura<br><br>
<input type="text" name="marca" class="casella" placeholder="Inserisci marca">Es. marcaX<br><br>
<input type="text" name="pattern"  class="casella" placeholder="Inserisci pattern sensore">Es. 8,10,4<br><br>
<input type="text" name="array_di_stringhe" class="casella" placeholder="Inserisci campi rilevazione">Es data,ora,rilevazione<br><br>
<input type="text" name="stringa_errore" class="casella" placeholder="Inserisci pattern errore" value="0">Es. 00000000000<br> (Codice di errore utilizzato dal sensore)<br>
<input type="hidden" name="fk_sensore" class="casella" value="-1"><br>
<input type="submit" name="submitAdd" class="bottone"><br>

</form>
</div>
<br>
<form action="tipoSensore.php" method="post">
<input type="submit" class="bottone" value="Visualizza tipologie" name="submitVisualizzaTipi">
</form>
<br>
<div id="visualizza">
<table class="table">

<?php
	$tr='<tr>';
	$_tr='</tr>';
	$td_class="<td class='td'>";
	$_td='</td>';
	
$query='';
if(isset($query)){
echo $tr;
$stampa="<th class='th'>TIPOLOGIA</th>";
echo $stampa;
$stampa="<th class='th'>MARCA</th>";
echo $stampa;
$stampa="<th class='th'>PATTERN</th>";
echo $stampa;
$stampa="<th class='th'>NOME CAMPI</th>";
echo $stampa;
$stampa="<th class='th'>ERRORE</th>";
echo $stampa;
echo $_tr;
}

while ($row = mysql_fetch_assoc($query)) {
	$tipo= htmlspecialchars( mysql_result($row, 0, 'tipo') );
	$marca= htmlspecialchars( mysql_result($row, 0, 'marca') );
	$pattern= htmlspecialchars( mysql_result($row, 0, 'pattern') );
	$array_stringhe= htmlspecialchars( mysql_result($row, 0, 'array_stringhe') );
	$stringa_errore= htmlspecialchars( mysql_result($row, 0, 'stringa_errore') );
	echo $tr;
        $stampa=$td_class.$tipo.$_td;
	echo $stampa;
	 $stampa=$td_class.$marca.$_td;
	echo $stampa;
	 $stampa=$td_class.$pattern.$_td;
	echo $stampa;
        $stampa=$td_class.$array_stringhe.$_td;
	echo $stampa;
        $stampa=$td_class.$stringa_errore.$_td;
	echo $stampa;
        echo $_tr;
        }

?>
</table>
</div>
<br><br>

<form action="tipoSensore.php" method="post">


<input type="text"  name="tipo" class="casella" placeholder="Inserisci tipologia da eliminare"><br><br>
<input type="text"  name="marca" class="casella" placeholder="Inserisci marca da eliminare"><br><br>
<input type="submit" name="submitRimuoviTipo" class="bottone" value="Rimuovi Tipo">

</form><br><br>
</div><br><br><br>
</body>
</html>
