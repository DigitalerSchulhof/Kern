<?php
$DSH_TITEL = "Anmeldung";
$CODE .= new Kern\Aktionszeile();

$CODE .= UI\Zeile::standard(new UI\SeitenUeberschrift("Schulhof"));

$meldungBrowserLaden    = new UI\Meldung("Kompatibilität prüfen",       "Es wird geprüft, ob Ihr Browser unterstützt wird...", "Arbeit");
$meldungBrowserErfolg   = new UI\Meldung("Kompatibilität prüfen",       "Ihr Browser unterstützt alle Funktionen des Digitalen Schulhofs!", "Erfolg", new UI\Icon(""));
$meldungBrowserFehler   = new UI\Meldung("Kompatibilität prüfen",       "<b>Ihr Browser unterstützt nicht alle Funktionen des Digitalen Schulhofs!</b>", "Fehler", new UI\Icon(""));
$meldungBrowserUnsicher = new UI\Meldung("Kompatibilität prüfen",       "Ihr Browser konnte nicht erkannt werden! Um sicherzustellen, dass alle Funktionen des Digitalen Schulhofs verwendet werden können, muss ein aktueller Browser verwendet werden. <a href=\"https://digitaler-schulhof.de/Wiki/Browser\" class=\"dshExtern\">Hier</a> finden Sie eine Liste an Browsern, die offiziell unterstützt werden.", "Warnung"); // @TODO: Browserliste
$meldungBrowserInternet = new UI\Meldung("Langsame Internetverbindung", "Es wurde eine langsame Internetverbindung festgestellt. Für ein bestmögliches Erlebnis ist eine schnelle Internetverbindung notwendig.", "Warnung");
$meldungBrowserLaden    ->setID("dshBrowsercheckLaden");
$meldungBrowserErfolg   ->setID("dshBrowsercheckErfolg")  ->setStyle("display", "none");
$meldungBrowserFehler   ->setID("dshBrowsercheckFehler")  ->setStyle("display", "none");
$meldungBrowserUnsicher ->setID("dshBrowsercheckUnsicher")->setStyle("display", "none")->setAttribut("title", "Fehlt Ihr Browser? Lassen Sie es uns über GitHub wissen! :)");
$meldungBrowserInternet ->setID("dshBrowsercheckInternet")->setStyle("display", "none");
$browserMeldungen       = [$meldungBrowserLaden, $meldungBrowserErfolg, $meldungBrowserFehler, $meldungBrowserUnsicher, $meldungBrowserInternet];

$spalteAnmeldung = new UI\Spalte("A2");
$spalteAnmeldung->add(new UI\Ueberschrift(2, "Anmeldung"));
$spalteAnmeldung->add(...$browserMeldungen);


// @TODO: Platzhalter für den Benutzernamen
$anmeldungFeldBenutzer = new UI\FormularFeld(new UI\InhaltElement("Benutzer:"), (new UI\Textfeld("dshAnmeldungBenutzer"))     ->setAutocomplete("username"));
$anmeldungFeldPasswort = new UI\FormularFeld(new UI\InhaltElement("Passwort:"), (new UI\Passwortfeld("dshAnmeldungPasswort")) ->setAutocomplete("current-password"));

$anmeldungFormular = new UI\FormularTabelle($anmeldungFeldBenutzer, $anmeldungFeldPasswort);
$anmeldungFormular->addKnopf((new UI\Knopf("Anmelden", "Erfolg")) ->setSubmit(true));
$anmeldungFormular->addKnopf((new UI\Knopf("Passwort vergessen")) ->addFunktion("href", "Schulhof/Passwort_vergessen"));
$anmeldungFormular->addKnopf((new UI\Knopf("Registrieren"))       ->addFunktion("href", "Schulhof/Registrieren"));
$anmeldungFormular->getAktionen()->addFunktion("onclick",  "kern.schulhof.anmeldung.brclick(event)");
$anmeldungFormular->getAktionen()->addFunktion("onsubmit", "kern.schulhof.anmeldung.anmelden()");
$spalteAnmeldung->add($anmeldungFormular);

$spalteLinks = new UI\Spalte("A2");
$spalteLinks->add(new UI\Ueberschrift(2, "Links"));
$spalteLinks->add(new UI\Ueberschrift(3, "Schüler und Lehrer"));
$linksListe = new UI\Liste();

$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "Dateien im Schulnetzwerk");
$link->addKlasse("dshExtern");
$link->addFunktion("href", "https://filr-schulen.schorndorf.de/");
$link->setAttribut("target", "_blank");
$linksListe->add($link);

$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "Buchungssystem der Mensa");
$link->addKlasse("dshExtern");
$link->addFunktion("href", "http://www.mitte.mensa-pro.de/");
$link->setAttribut("target", "_blank");
$linksListe->add($link);

$spalteLinks->add($linksListe);

$linksListe = new UI\Liste();

$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "Webmailportal für Lehrer");
$link->addKlasse("dshExtern");
$link->addFunktion("href", "https://webmail.all-inkl.com/index.php");
$link->setAttribut("target", "_blank");
$linksListe->add($link);

$link = new UI\IconKnopf(new UI\Icon(UI\Konstanten::LINKEXT), "NEO");
$link->addKlasse("dshExtern");
$link->addFunktion("href", "https://neo.kultus-bw.de/");
$link->setAttribut("target", "_blank");
$linksListe->add($link);

$spalteLinks->add(new UI\Ueberschrift(3, "Lehrer"));
$spalteLinks->add($linksListe);

$CODE .= new UI\Zeile($spalteAnmeldung, $spalteLinks);

$CODE .= "<script>kern.schulhof.anmeldung.browsercheck()</script>";
?>
