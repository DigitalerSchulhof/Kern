<?php

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

// Benutzer abmelden
$DSH_BENUTZER->abmelden();
?>
