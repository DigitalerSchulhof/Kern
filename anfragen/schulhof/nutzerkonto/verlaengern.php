<?php
if(!Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

// Timeout verlaengern
$verlaengern = $DSH_BENUTZER->sessiontimeoutVerlaengern();

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Erfolg!", "Die Session wurde verlängert!", "Erfolg"));
Anfrage::setRueck("Limit", $verlaengern["Limit"]);
Anfrage::setRueck("Ende",  $verlaengern["Ende"]);
?>
