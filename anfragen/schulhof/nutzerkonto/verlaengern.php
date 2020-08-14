<?php
if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

// Timeout verlaengern
$verlaengern = $DSH_BENUTZER->sessiontimeoutVerlaengern();

Anfrage::setRueck("Limit", $verlaengern["Limit"]);
Anfrage::setRueck("Ende",  $verlaengern["Ende"]);
?>
