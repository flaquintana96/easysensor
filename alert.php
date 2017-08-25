 <?php
 
function Alert($mex){
  $pre="<script> alert(";
 	$post="); </script>";
  $stampa=$pre.$mex.$post;
 	echo $stampa;
  }
  ?>
