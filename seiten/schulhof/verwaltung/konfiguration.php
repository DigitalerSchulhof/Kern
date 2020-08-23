<?php
$SEITE = new Kern\Seite("Konfiguration", "technik.konfiguration");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Konfiguration"));

$reiter = new UI\Reiter("dshKonfiguration");

$meldung  = new UI\Meldung("Ende der Spielewiese", "<p>Falsche Daten in dieser Tabelle können die Funktionsfähigkeit des Digitalen Schulhofs nachhaltig beschädigen!</p>", "Warnung", new UI\Icon("fas fa-exclamation-triangle"));
$formular = new UI\FormularTabelle();
$basisver = (new UI\Textfeld("dshKonfigBasis"))                                 ->setWert($EINSTELLUNGEN["Base"]);
$formular[]  = new UI\FormularFeld(new UI\InhaltElement("Basisverzeichnis:"),   $basisver);
$formular[]  = (new UI\Knopf("Änderungen speichern", "Erfolg"))                 ->setSubmit(true);
$formular    ->addSubmit("kern.konfiguration.verzeichnisse()");
$reiterkopf = new UI\Reiterkopf("Verzeichnisse");
$reiterspalte = new UI\Spalte("A1", $meldung, $formular);
$reiterkoerper = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
$reiter->addReitersegment(new UI\Reitersegment($reiterkopf, $reiterkoerper));

$meldung  = new UI\Meldung("Ende der Spielewiese", "<p>Falsche Daten in dieser Tabelle können die Funktionsfähigkeit des Digitalen Schulhofs nachhaltig beschädigen!</p>", "Warnung", new UI\Icon("fas fa-exclamation-triangle"));
$ueberschriftsh = new UI\Ueberschrift(3, "Schulhof");
$formularsh = new UI\FormularTabelle();
$dbshhost = (new UI\Textfeld("dshKonfigDatenbankShHost"))               ->setWert($EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Host"]);
$dbshport = (new UI\Zahlenfeld("dshKonfigDatenbankShPort",0,65535))     ->setWert($EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Port"]);
$dbshdate = (new UI\Textfeld("dshKonfigDatenbankShDatenbank"))          ->setWert($EINSTELLUNGEN["Datenbanken"]["Schulhof"]["DB"]);
$dbshuser = (new UI\Textfeld("dshKonfigDatenbankShBenutzer"))           ->setWert($EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Benutzer"]);
$dbshpass = (new UI\Passwortfeld("dshKonfigDatenbankShPasswort"))       ->setWert($EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Passwort"]);
$dbshschl = (new UI\Passwortfeld("dshKonfigDatenbankShSchluessel"))     ->setWert($EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Schluessel"]);
$formularsh[]  = new UI\FormularFeld(new UI\InhaltElement("Host:"),      $dbshhost);
$formularsh[]  = new UI\FormularFeld(new UI\InhaltElement("Port:"),      $dbshport);
$formularsh[]  = new UI\FormularFeld(new UI\InhaltElement("Datenbank:"), $dbshdate);
$formularsh[]  = new UI\FormularFeld(new UI\InhaltElement("Benuzter:"),  $dbshuser);
$formularsh[]  = (new UI\FormularFeld(new UI\InhaltElement("Passwort:"), $dbshpass))->setOptional(true);
$formularsh[]  = new UI\FormularFeld(new UI\InhaltElement("Schlüssel:"), $dbshschl);
$formularsh[]  = (new UI\Knopf("Änderungen speichern", "Erfolg"))           ->setSubmit(true);
$formularsh    ->addSubmit("kern.konfiguration.datenbanken.schulhof()");

$reiterkopf     = new UI\Reiterkopf("Datenbanken");
$reiterspalte   = new UI\Spalte("A1", $meldung, $ueberschriftsh, $formularsh);
$reiterkoerper  = new UI\Reiterkoerper($reiterspalte->addKlasse("dshUiOhnePadding"));
$reiter[]       = new UI\Reitersegment($reiterkopf, $reiterkoerper);

$spalte[] = $reiter;

$SEITE[] = new UI\Zeile($spalte);
?>
