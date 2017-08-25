<?php




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
<h2> PAGINA DI TEST </h2>
<h3> Menu </h3>
<div align="center" style="border: solid 2px #42a1f4; border-radius:10px;"><br>
<button class="bottone" onclick="window.location.href='index.php'">Login</button>
<br><br>
</div>

<br><br><br>

<form action="rilevazione.php" method="post">
<input type="text" class="casella" name="stringa" placeholder="inserisci stringa di test"><br><br>
<input type="submit" class="bottone" value="Invia stringa">
</form><br><br>
<script>
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}
</script>

Stringhe di test<br><br>
<input type="text" id="id1"  value="0000000115umiditaInterSensorImpianto1023:30:302017-08-2100003198374657" class="casella" readonly> <input type="button" class="bottone" value="Copia" data-clipboard-action="copy" data-clipboard-target="#id1"><br><br>
<input id="id2" type="text" value="0000000021luminosoInterSensor23:01:542017-08-171579descrizione di test" class="casella" readonly> <input type="button" class="bottone" value="Copia" data-clipboard-action="copy" data-clipboard-target="#id2"><br><br>
<input id="id3" type="text" value="0000000043temperaturaInterSensorImpianto1      2017-08-172222descrizioneTest" class="casella" readonly><input type="button" class="bottone" value="Copia" data-clipboard-action="copy" data-clipboard-target="#id3"><br><br>
<input id="id4" type="text" value="0000000043temperaturaInterSensor00000000000000000000000000000descrizioneTest"class="casella" readonly><input type="button" class="bottone" value="Copia" data-clipboard-action="copy" data-clipboard-target="#id4"><br><br>
<input id="id5" type="text" value="0000000042umiditaInterSensor##########################################descrizione di test" class="casella" readonly><input type="button" class="bottone" value="Copia" data-clipboard-action="copy" data-clipboard-target="#id5"><br><br>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.3/clipboard.min.js"></script>
<script>
    
    var clipboard = new Clipboard('.bottone');
  </script>

</div>
</body>
</html>