session_start();
require "connessione.php";
include 'g/phpgraphlib.php';
include 'g/phpgraphlib_pie.php';
$sql = "SELECT COUNT(errore) AS frequenza_errori FROM ((rilevazione inner join sensore ON rilevazione.id_sensoreFK=sensore.id_sensore) 
inner join utente ON sensore.id_clienteFK=utente.id_cliente)
WHERE id_cliente=".mysql_real_escape_string($_SESSION["id"])." AND errore=1 GROUP BY errore";
$errore1=mysql_query($sql);
$sql = "SELECT COUNT(errore) AS frequenza_errori FROM ((rilevazione inner join sensore ON rilevazione.id_sensoreFK=sensore.id_sensore) 
inner join utente ON sensore.id_clienteFK=utente.id_cliente)
WHERE id_cliente=".mysql_real_escape_string($_SESSION["id"])." AND errore=0 GROUP BY errore";
$errore0=mysql_query($sql);
$row=mysql_fetch_assoc($errore0);
$errore0=$row['frequenza_errori'];
$row=mysql_fetch_assoc($errore1);
$errore1=$row['frequenza_errori'];
//print($errore0);echo "<br>";
//print($errore1);echo "<br>";
$data = array("presente" =>$errore1,"assente" =>$errore0);
//print_r($data);

$graph = new PHPGraphLibPie(600, 400);
$graph->addData($data);
$graph->setTitle('Frequenza errori');
$graph->setLabelTextColor('50,50,50');
$graph->setLegendTextColor('50,50,50');
$graph->createGraph();
