<?php
$DSH_TITEL = "Registrieren";
$CODE .= new Kern\Aktionszeile();

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Registrieren"));

if (!Check::Einwilligung("DSH")) {
  $spalte->add(new UI\Meldung("Datenschutzhinweis", "Um eine Registrierung für den Digitalen Schulhof vorzunehmen, müssen die Cookies des Digitalen Schulhofs akzeptiert werden.", "Information"));

  $akzeptieren = new UI\IconKnopf("Cookies des Digitalen Schulhofs akzeptieren", new UI\Icon(UI\Konstanten::COOKIE));
  $akzeptieren->addFunktion("onclick", "kern.cookies.setzen('1', 'DSH')");

  $spalte->add(new UI\Absatz($akzeptieren));
} else {
  $spalte->add(new UI\Meldung("Mehrfache Registrierungen", "Registrierungen müssen vom Administrator Personen zugeordnet werden. Dies kann einige Zeit in Anspruch nehmen. Mehrfache Registrierungen beschleunigen das Verfahren nicht!", "Warnung"));

  $spalte->add(new UI\Meldung("Datenschutzhinweis", "Mit der Registrierung geht die Einverständnis in die Datenschutzvereinbarung des Digitalen Schulhofs einher. Diese kann hier eingesehen werden: ".(new UI\Link("Datenschutzvereinbarungen lesen", "Website/Datenschutz")), "Information"));

  $titel        = new UI\FormularFeld(new UI\InhaltElement("Titel:"),                     (new UI\Textfeld("dshRegistrierenTitel"))->setAutocomplete("honorific-prefix"));
  $vorname      = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),                   (new UI\Textfeld("dshRegistrierenVorname"))->setAutocomplete("given-name"));
  $nachname     = new UI\FormularFeld(new UI\InhaltElement("Nachname:"),                  (new UI\Textfeld("dshRegistrierenNachname"))->setAutocomplete("family-name"));
  $klasse       = new UI\FormularFeld(new UI\InhaltElement("Klasse:"),                    (new UI\Textfeld("dshRegistrierenKlasse")));

  $passwort     = (new UI\Passwortfeld("dshRegistrierenPasswort"))->setAutocomplete("new-password");
  $passwort2    = (new UI\Passwortfeld("dshRegistrierenPasswort2", $passwort))->setAutocomplete("new-password");
  $passwort     = new UI\FormularFeld(new UI\InhaltElement("Passwort:"),                  $passwort);
  $passwort2    = new UI\FormularFeld(new UI\InhaltElement("Passwort bestätigen:"),       $passwort2);

  $email        = new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"),             (new UI\Mailfeld("dshRegistrierenMail"))->setAutocomplete("email"));
  $datenschutz  = new UI\FormularFeld(new UI\InhaltElement("Datenschutz:"),               new UI\IconToggle("dshRegistrierenDatenschutz", "Ich bin mit den Datenschutzvorkehrungen des Digitalen Schulhofs einverstanden und erteile meine Erlaubnis zur Datenverarbeitung.", (new UI\Icon(UI\Konstanten::HAKEN))));
  $berechtigung = new UI\FormularFeld(new UI\InhaltElement("Entscheidungsberechtigung:"), new UI\IconToggle("dshRegistrierenEntscheidung", "Ich bin 18 Jahre alt oder älter, oder ein Erziehungsberechtigter hat mir erlaubt, diese Registrierung durchzuführen.", (new UI\Icon(UI\Konstanten::HAKEN))));
  $korrektheit  = new UI\FormularFeld(new UI\InhaltElement("Korrektheit:"),              new UI\IconToggle("dshRegistrierenKorrektheit", "Meine Angaben sind nach bestem Wissen und Gewissen korrekt.", (new UI\Icon(UI\Konstanten::HAKEN))));
  // $captcha      = new UI\FormularFeld(new UI\InhaltElement("Spamverhinderung:"),          (new UI\Spamschutz("dshRegistrierenCaptcha", 7)));

  $felder       = [$titel, $vorname, $nachname, $klasse, $passwort, $passwort2, $email, $datenschutz, $berechtigung, $korrektheit, $captcha];

  $formular = new UI\FormularTabelle(...$felder);

  $formular->addKnopf((new UI\Knopf("Registrieren", "Erfolg"))  ->setSubmit(true));
  $formular->addKnopf((new UI\Knopf("Zurück zur Anmeldung"))    ->addFunktion("href", "Schulhof/Anmeldung"));

  $formular->getAktionen()->addFunktion("onsubmit", "kern.schulhof.nutzerkonto.registrieren()");

  $spalte->add($formular);
}

$CODE .= new UI\Zeile($spalte);
?>
