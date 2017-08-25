session_start();
require "connessione.php";
include 'g/phpgraphlib.php';
include 'g/phpgraphlib_pie.php';

$graph = new PHPGraphLib(550,630);
$dataArray=array();

$sql = "SELECT tipo,COUNT(rilevazione) AS frequenza FROM ((rilevazione inner join sensore ON rilevazione.id_sensoreFK=sensore.id_sensore) 
inner join utente ON sensore.id_clienteFK=utente.id_cliente)
WHERE id_cliente=".mysql_real_escape_string($_SESSION["id"])." GROUP BY tipo";

$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)){

$tipo = $row["tipo"];
$frequenza = $row["frequenza"];
$dataArray[$tipo] = $frequenza;
}

//configure graph
$graph->addData($dataArray);
$graph->setTitle("Istogramma della frequenza delle rilevazioni organizzate per tipologia del sensore");
$graph->setGradient("lime", "green");
$graph->setBarOutlineColor("black");
$graph->createGraph();
