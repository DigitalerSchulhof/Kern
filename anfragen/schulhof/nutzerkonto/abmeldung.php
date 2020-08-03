<?php

if(!Check::angemeldet()) {
  Anfrage::addFehler(6, true);
}

// Benutzer abmelden
$sql = "UPDATE kern_nutzersessions SET sessiontimeout = 0 WHERE sessionid = [?] AND nutzer = ?";
$anfrage = $DBS->anfrage($sql, "si", $DSH_BENUTZER->getSessionid(), $DSH_BENUTZER->getId());

// Postfachordner verwalten
$DSH_BENUTZER->postfachOrdnerAufraeumen();

unset($_SESSION);

Anfrage::setTyp("Weiterleitung");
Anfrage::setRueck("Ziel", "Schulhof/Anmeldung/Bis_bald!");
?>
