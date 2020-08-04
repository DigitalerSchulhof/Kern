<?php
if(!Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

// Timeout verlaengern
$param = $DSH_BENUTZER->sessiontimeoutVerlaengern();

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Erfolg!", "Die Session wurde verlängert!", "Erfolg"));
Anfrage::setRueck("Parameter", $param);
?>
