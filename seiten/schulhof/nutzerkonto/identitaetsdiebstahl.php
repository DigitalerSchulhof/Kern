<?php
$SEITE = new Kern\Seite("Identitätsdiebstahl", null);

$spalte    = new UI\Spalte("A1");
$spalte[]  = new UI\SeitenUeberschrift("Identitätsdiebstahl melden");

$spalte[]  = new UI\Meldung("Identitätsdiebstahl", "<p>Ein Identitätsdiebstahl liegt nur vor, wenn dieses Nutzerkonto benutzt wurde, ohne dass die Benutzung vom Besitzer des Kontos ausging. Diese Funktion ist nicht leichtfertig zu benutzen, denn sie löst eine Reihe an Folgetätigkeiten (Sicherheitsprüfungen, Datenschutzvorkehrungen, Informieren der Schulgemeinschaft, ...) aus.</p><p><b>Diese Funktion darf nicht zum leichtfertigen Ändern des Passworts verwendet werden!!</b> Der Verdacht auf einen Identitätsdiebstahl ist meldepflichtig!</p><p>Rückfragen durch die Administration sind sehr wahrscheinlich.</p>", "Warnung", new UI\Icon("fas fa-user-secret"));

$formular         = new UI\FormularTabelle();
$passwortaltF = (new UI\Passwortfeld("dshIdentitaetPasswortAlt"));
$passwortneuF = (new UI\Passwortfeld("dshIdentitaetPasswortNeu"));
$passwortneu2F = (new UI\Passwortfeld("dshIdentitaetPasswortNeu2", $passwortneuF));
$hinweise = (new UI\Textarea("dshIdentitaetHinweise"));

$passwortaltF->setAutocomplete("password");
$passwortneuF->setAutocomplete("password");
$passwortneu2F->setAutocomplete("password");

$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Altes Passwort:"),                 $passwortaltF);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort:"),                 $passwortneuF);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort bestätigen:"),      $passwortneu2F);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Hinweise / Bemerkungen:"),      $hinweise);

$formular[]       = (new UI\Knopf("Identitätsdiebstahl melden", "Warnung"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.nutzerkonto.identitaetsdiebstahl()");
$spalte[]  = $formular;

$SEITE[]   = new UI\Zeile($spalte);
?>
