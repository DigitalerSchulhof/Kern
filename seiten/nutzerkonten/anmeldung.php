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
$spalte2->add(new UI\Ueberschrift(3, "Schüler und Lehrer"));
$linkliste = new UI\Liste();
$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "Dateien im Schulnetzwerk");
$link->addFunktion("href", "https://filr-schulen.schorndorf.de/ssf/a/do?p_name=ss_forum&p_action=1&action=__login&refererUrl=https%3A%2F%2Ffilr-schulen.schorndorf.de%2Fssf%2Fa%2Fc%2Fp_name%2Fss_forum%2Fp_action%2F1%2Faction%2Fview_permalink%2FshowCollection%2F-1%2FentityType%2Fuser%2FentryId%2Fss_user_id_place_holder%2Fnovl_url%2F1%2Fnovl_root%2F1");
$link->setAttribut("target", "_blank");
$linkliste->add($link);
$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "Buchungssystem der Mensa");
$link->addFunktion("href", "http://www.mitte.mensa-pro.de/");
$link->setAttribut("target", "_blank");
$linkliste->add($link);
$spalte2->add($linkliste);


$linkliste = new UI\Liste();
$spalte2->add(new UI\Ueberschrift(3, "Lehrer"));
$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "Webmailportal für Lehrer");
$link->addFunktion("href", "https://webmail.all-inkl.com/index.php");
$link->setAttribut("target", "_blank");
$linkliste->add($link);
$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "NEO");
$link->addFunktion("href", "https://neo.kultus-bw.de/");
$link->setAttribut("target", "_blank");
$linkliste->add($link);
$spalte2->add($linkliste);

$CODE .= new UI\Zeile($spalte1, $spalte2);

?>
