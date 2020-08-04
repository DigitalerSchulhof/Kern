<?php

if(!Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

// Benutzer abmelden
$DSH_BENUTZER->abmelden();

$website = new UI\Knopf("Zurück zur Website");
$website->addFunktion("href", "Website");
$website->addFunktion("onclick", "ui.laden.aus()");
$schulhof = new UI\Knopf("Zurück zur Anmeldung");
$schulhof->addFunktion("href", "Schulhof/Anmeldung");
$schulhof->addFunktion("onclick", "ui.laden.aus()");
$knoepfe = [$website, $schulhof];
Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Abmeldung erfolgreich!", "Die Abmeldung wurde durchgeführt. Bis bald!", "Information", new UI\Icon(UI\Konstanten::ABMELDEN)));
Anfrage::setRueck("Knöpfe", $knoepfe);
?>
