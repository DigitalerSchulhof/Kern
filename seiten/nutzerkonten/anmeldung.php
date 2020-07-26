<?php
$DSH_TITEL = "Anmeldung";
$CODE .= new Kern\Aktionszeile();

$CODE .= UI\Zeile::standard(new UI\SeitenUeberschrift("Schulhof"));


$spalte1 = new UI\Spalte("A2");
$spalte1->add(new UI\Ueberschrift(2, "Anmeldung"));

$spalte1->add(new UI\Meldung("Kompatibilität prüfen", "Der verwendete Browser unterstützt möglicherweise nicht alle Funktionen des Digitalen Schulhofs! Gegebenenfalls sind die Cookies nicht aktiviert.", "Warnung"));

// @TODO: Platzhalter für den Benutzernamen
$formzeile1 = new UI\FormularFeld(new UI\InhaltElement("Benutzer:"), new UI\Textfeld("dshAnmeldungBenutzer"));
$formzeile2 = new UI\FormularFeld(new UI\InhaltElement("Passwort:"), new UI\Passwortfeld("dshAnmeldungPasswort"));
$anmelden = (new UI\Knopf("Anmelden", "Erfolg"))->addFunktion("onclick", "kern.schulhof.nutzerkonten.anmelden()");
$formular = new UI\FormularTabelle($anmelden, $formzeile1, $formzeile2);
$formular->addKnopf((new UI\Knopf("Passwort vergessen"))->addFunktion("href", "Schulhof/Passwort_vergessen"));
$formular->addKnopf((new UI\Knopf("Registrieren"))->addFunktion("href", "Schulhof/Registrieren"));
$spalte1->add($formular);

$spalte2 = new UI\Spalte("A2");
$spalte2->add(new UI\Ueberschrift(2, "Links"));

$CODE .= new UI\Zeile($spalte1, $spalte2);

?>
