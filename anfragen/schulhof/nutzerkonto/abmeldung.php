<?php

if(!Check::angemeldet()) {
  Anfrage::addFehler(6, true);
}

// Benutzer abmelden
$DSH_BENUTZER->abmelden();

Anfrage::setTyp("Weiterleitung");
Anfrage::setRueck("Ziel", "Schulhof/Anmeldung/Bis_bald!");
?>
