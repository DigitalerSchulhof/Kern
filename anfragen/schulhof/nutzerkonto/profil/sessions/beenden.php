<?php
if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

$sql = "UPDATE kern_nutzersessions SET sessiontimeout = 0 WHERE person = ?";
$DBS->anfrage($sql, "i", $DSH_BENUTZER->getId());
?>
