<?php
$SEITE = new Kern\Seite("Zugangsdaten vergessen");

$spalte             = new UI\Spalte("A1");
$spalte[]           = new UI\SeitenUeberschrift("Zugangsdaten vergessen");

$ldap               = Kern\Einstellungen::laden("Kern", "LDAP");
if ($ldap == "1") {
  $spalte[]         = new UI\Meldung("Zugang für mehrere Konten", "Dieser Zugang wird für mehrere Dienste verwendet. Wenn er hier geändert wird, hat dies Auswirkungen auf all Ihre Zugänge, die diese Anmeldedaten verwenden!", "Warnung");
}

$SEITE[] = new UI\Zeile($spalte);

$passwortFormular   = new UI\FormularTabelle();
$passwortFormular[] = new UI\FormularFeld(new UI\InhaltElement("Benutzer:"),  new UI\Textfeld("dshZugangsdatenPasswortBenutzer"));
$passwortFormular[] = new UI\FormularFeld(new UI\InhaltElement("eMail:"),     new UI\Mailfeld("dshZugangsdatenPasswortMail"));
$passwortFormular[] = (new UI\Knopf("Zuschicken", "Erfolg"))                  ->setSubmit(true);
$passwortFormular   ->addSubmit("kern.schulhof.nutzerkonto.vergessen.passwort()");

$spaltePasswort     = new UI\Spalte("A2");
$spaltePasswort[]   = new UI\Ueberschrift(2, "Passwort");
$spaltePasswort[]   = $passwortFormular;

$benutzerFormular   = new UI\FormularTabelle();
$benutzerFormular[] = new UI\FormularFeld(new UI\InhaltElement("eMail:"),     new UI\Mailfeld("dshZugangsdatenBenutzerMail"));
$benutzerFormular[] = (new UI\Knopf("Zuschicken", "Erfolg"))                  ->setSubmit(true);
$benutzerFormular   ->addSubmit("kern.schulhof.nutzerkonto.vergessen.benutzername()");

$spalteBenutzer     = new UI\Spalte("A2");
$spalteBenutzer[]   = new UI\Ueberschrift(2, "Benutzername");
$spalteBenutzer[]   = $benutzerFormular;

$SEITE[] = new UI\Zeile($spaltePasswort, $spalteBenutzer);
$SEITE[] = UI\Zeile::standard((new UI\Knopf("Zurück zur Anmeldung"))->addFunktion("href", "Schulhof/Anmeldung"));
?>
