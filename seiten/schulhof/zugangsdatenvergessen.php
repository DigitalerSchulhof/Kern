<?php
$DSH_TITEL = "Zugangsdaten vergessen";
$CODE .= new Kern\Aktionszeile();

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Zugangsdaten vergessen"));
$ldap = Kern\Einstellungen::laden("Kern", "LDAP");
if ($ldap == "1") {
  $passwortMeldung = new UI\Meldung("Zugang für mehrere Konten", "Dieser Zugang wird für mehrere Dienste verwendet. Wenn es hier geändert wird, hat das Auswirkungen auf all ihre Zugänge, die diese Anmeldedaten verwenden!", "Warnung");
  $spalte->add($passwortMeldung);
}

$CODE .= new UI\Zeile($spalte);

$spaltePasswort = new UI\Spalte("A2");
$spaltePasswort->add(new UI\Ueberschrift(2, "Passwort"));

$passwortVerBenutzer = new UI\FormularFeld(new UI\InhaltElement("Benutzer:"), (new UI\Textfeld("dshZugangsdatenPasswortBenutzer")));
$passwortVerMail = new UI\FormularFeld(new UI\InhaltElement("eMail:"), (new UI\Mailfeld("dshZugangsdatenPasswortMail")));
$passwortFormular = new UI\FormularTabelle($passwortVerBenutzer, $passwortVerMail);
$passwortFormular->addKnopf((new UI\Knopf("Zuschicken", "Erfolg")) ->setSubmit(true));
$passwortFormular->getAktionen()->addFunktion("onsubmit", "kern.schulhof.nutzerkonto.vergessen.passwort()");
$spaltePasswort->add($passwortFormular);


$spalteBenutzer = new UI\Spalte("A2");
$spalteBenutzer->add(new UI\Ueberschrift(2, "Benutzername"));
$benutzerVerMail = new UI\FormularFeld(new UI\InhaltElement("eMail:"), (new UI\Mailfeld("dshZugangsdatenBenutzerMail")));
$benutzerFormular = new UI\FormularTabelle($benutzerVerMail);
$benutzerFormular->addKnopf((new UI\Knopf("Zuschicken", "Erfolg")) ->setSubmit(true));
$benutzerFormular->getAktionen()->addFunktion("onsubmit", "kern.schulhof.nutzerkonto.vergessen.benutzername()");
$spalteBenutzer->add($benutzerFormular);

$CODE .= new UI\Zeile($spaltePasswort, $spalteBenutzer);

$CODE .= UI\Zeile::standard((new UI\Knopf("Zurück zur Anmeldung"))->addFunktion("href", "Schulhof/Anmeldung"));
?>
