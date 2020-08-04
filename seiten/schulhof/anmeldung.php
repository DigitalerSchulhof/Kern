<?php
if(Check::angemeldet()) {
  Anfrage::setTyp("Weiterleitung");
  Anfrage::setRueck("Ziel", "Schulhof/Nutzerkonto");
  return;
}

$DSH_TITEL = "Anmeldung";
$CODE .= new Kern\Aktionszeile();

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Schulhof"));
$CODE .= new UI\Zeile($spalte);

$meldungBrowserLaden    = new UI\Meldung("Kompatibilität prüfen",       "Es wird geprüft, ob Ihr Browser unterstützt wird...", "Arbeit");
$meldungBrowserErfolg   = new UI\Meldung("Kompatibilität prüfen",       "Dieser Browser unterstützt alle Funktionen des Digitalen Schulhofs.", "Erfolg", new UI\Icon(""));
$meldungBrowserFehler   = new UI\Meldung("Kompatibilität prüfen",       "<b>Dieser Browser unterstützt möglicherweise nicht alle Funktionen des Digitalen Schulhofs!</b>", "Fehler", new UI\Icon(""));
$meldungBrowserUnsicher = new UI\Meldung("Kompatibilität prüfen",       "Dieser Browser konnte nicht erkannt werden! Um sicherzustellen, dass alle Funktionen des Digitalen Schulhofs verwendet werden können, muss ein aktueller Browser verwendet werden. <a href=\"https://digitaler-schulhof.de/Wiki/Browser\" class=\"dshExtern\">Hier</a> finden Sie eine Liste an Browsern, die offiziell unterstützt werden.", "Warnung"); // @TODO: Browserliste
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
$anmeldungFormular->addKnopf((new UI\Knopf("Anmelden", "Erfolg"))     ->setSubmit(true) ->addKlasse("autofocus"));
$anmeldungFormular->addKnopf((new UI\Knopf("Zugangsdaten vergessen")) ->addFunktion("href", "Schulhof/Zugangsdaten_vergessen"));
$anmeldungFormular->addKnopf((new UI\Knopf("Registrieren"))           ->addFunktion("href", "Schulhof/Registrieren"));
$anmeldungFormular->getAktionen()->addFunktion("onsubmit", "kern.schulhof.nutzerkonto.anmelden()");
$spalteAnmeldung->add($anmeldungFormular);



$spalteLinks = new UI\Spalte("A2");

$spalteLinks->add(new UI\Ueberschrift(2, "Apps"));
$android = new UI\IconKnopf(new UI\Icon(UI\Konstanten::ANDROID), "Andorid");
$android->addFunktion("href", "https://play.google.com/store/apps/details?id=com.dsh.digitalerschulhof");
$android->addKlasse("dshExtern");
$apple = new UI\IconKnopf(new UI\Icon(UI\Konstanten::APPLE), "iOS");
$apple->addFunktion("href", "https://apps.apple.com/de/app/digitaler-schulhof/id1500912100");
$apple->addKlasse("dshExtern");
$spalteLinks->add(new UI\Absatz("$apple $android"));


$spalteLinks->add(new UI\Ueberschrift(2, "Links"));

$CODE .= new UI\Zeile($spalteAnmeldung, $spalteLinks);

$CODE .= "<script>kern.schulhof.oeffentlich.browsercheck()</script>";
?>
