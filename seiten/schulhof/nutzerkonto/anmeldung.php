<?php
if(Kern\Check::angemeldet()) {
  Anfrage::setRueck("Weiterleitung", true);
  Anfrage::setRueck("Ziel", "Schulhof/Nutzerkonto");
  Anfrage::ausgeben();
  die();
}

$SEITE = new Kern\Seite("Anmeldung");
$SEITE[]                = UI\Zeile::standard(new UI\SeitenUeberschrift("Schulhof"));

$spalteAnmeldung        = new UI\Spalte("A2");
$spalteAnmeldung[]      = new UI\Ueberschrift(2, "Anmeldung");

$meldungBrowserLaden      = new UI\Meldung("Kompatibilität prüfen",       "Es wird geprüft, ob Ihr Browser unterstützt wird...", "Arbeit");
$meldungBrowserErfolg     = new UI\Meldung("Kompatibilität prüfen",       "Dieser Browser unterstützt alle Funktionen des Digitalen Schulhofs.", "Erfolg", new UI\Icon(""));
$meldungBrowserFehler     = new UI\Meldung("Kompatibilität prüfen",       "<b>Dieser Browser unterstützt möglicherweise nicht alle Funktionen des Digitalen Schulhofs!</b>", "Fehler", new UI\Icon(""));
$meldungBrowserUnsicher   = new UI\Meldung("Kompatibilität prüfen",       "Dieser Browser konnte nicht erkannt werden! Um sicherzustellen, dass alle Funktionen des Digitalen Schulhofs verwendet werden können, muss ein aktueller Browser verwendet werden. <a href=\"https://digitaler-schulhof.de/Wiki/Browser\" class=\"dshExtern\">Hier</a> finden Sie eine Liste an Browsern, die offiziell unterstützt werden.", "Warnung"); // @TODO: Browserliste
$meldungBrowserInternetM  = new UI\Meldung("Langsame Internetverbindung",       "Es wurde eine langsame Internetverbindung festgestellt. Für ein bestmögliches Erlebnis ist eine schnelle Internetverbindung notwendig.", "Warnung", new UI\Icon("fas fa-wifi"));
$meldungBrowserInternetL  = new UI\Meldung("Sehr langsame Internetverbindung",  "Es wurde eine sehr langsame Internetverbindung festgestellt. Gewisse Bereiche des Digitalen Schulhofs sind nur eingeschränkt nutzbar!", "Fehler", new UI\Icon("fas fa-wifi"));
$meldungBrowserLaden      ->setID("dshBrowsercheckLaden");
$meldungBrowserErfolg     ->setID("dshBrowsercheckErfolg")    ->setStyle("display", "none");
$meldungBrowserFehler     ->setID("dshBrowsercheckFehler")    ->setStyle("display", "none");
$meldungBrowserUnsicher   ->setID("dshBrowsercheckUnsicher")  ->setStyle("display", "none")->setAttribut("title", "Fehlt Ihr Browser? Lassen Sie es uns über GitHub wissen! :)");
$meldungBrowserInternetM  ->setID("dshBrowsercheckInternetM") ->setStyle("display", "none");
$meldungBrowserInternetL  ->setID("dshBrowsercheckInternetL") ->setStyle("display", "none");
$spalteAnmeldung[]        = $meldungBrowserLaden;
$spalteAnmeldung[]        = $meldungBrowserErfolg;
$spalteAnmeldung[]        = $meldungBrowserFehler;
$spalteAnmeldung[]        = $meldungBrowserUnsicher;
$spalteAnmeldung[]        = $meldungBrowserInternetM;
$spalteAnmeldung[]        = $meldungBrowserInternetL;

$anmeldungFormular      = new UI\FormularTabelle();

// @TODO: Platzhalter für den Benutzernamen
$anmeldungFormular[]    = new UI\FormularFeld(new UI\InhaltElement("Benutzer:"),  (new UI\Textfeld("dshAnmeldungBenutzer"))     ->setAutocomplete("username"));
$anmeldungFormular[]    = new UI\FormularFeld(new UI\InhaltElement("Passwort:"),  (new UI\Passwortfeld("dshAnmeldungPasswort")) ->setAutocomplete("current-password"));

$anmeldungFormular[]    = (new UI\Knopf("Anmelden", "Erfolg"))     ->setSubmit(true)->addKlasse("autofocus");
$anmeldungFormular[]    = (new UI\Knopf("Zugangsdaten vergessen")) ->addFunktion("href", "Schulhof/Zugangsdaten_vergessen");
$anmeldungFormular[]    = (new UI\Knopf("Registrieren"))           ->addFunktion("href", "Schulhof/Registrierung");

$anmeldungFormular->addSubmit("kern.schulhof.nutzerkonto.anmelden()");
$spalteAnmeldung[]      = $anmeldungFormular;



$spalteLinks            = new UI\Spalte("A2");
$spalteLinks[]          = new UI\Ueberschrift(2, "Digitaler Schulhof");

$knopfAndroid = new UI\IconKnopf(new UI\Icon(UI\Konstanten::ANDROID), "Andorid");
$knopfAndroid->addFunktion("href", "https://play.google.com/store/apps/details?id=com.dsh.digitalerschulhof");
$knopfAndroid->addKlasse("dshExtern");

$knopfApple = new UI\IconKnopf(new UI\Icon(UI\Konstanten::APPLE), "iOS");
$knopfApple->addFunktion("href", "https://apps.apple.com/de/app/digitaler-schulhof/id1500912100");
$knopfApple->addKlasse("dshExtern");

$knopfGitHub = new UI\IconKnopf(new UI\Icon("fab fa-github"), "GitHub");
$knopfGitHub->addFunktion("href", "https://github.com/DigitalerSchulhof");
$knopfGitHub->addKlasse("dshExtern");

$spalteLinks[]          = new UI\Absatz("$knopfApple $knopfAndroid $knopfGitHub");

$spalteLinks[]          = new UI\Ueberschrift(2, "Links");
$spalteLinks[]          = new UI\Absatz("Links folgen");

$SEITE[]                = new UI\Zeile($spalteAnmeldung, $spalteLinks);

$SEITE->setCodedanach("<script>kern.schulhof.oeffentlich.browsercheck()</script>");
?>
