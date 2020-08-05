<?php
$DSH_TITEL          = "Registrierung";
$CODE[]             = new Kern\Aktionszeile();

$spalte             = new UI\Spalte("A1");
$spalte[]           = new UI\SeitenUeberschrift("Registrierung");

if (!Check::Einwilligung("DSH")) {
  $spalte[]         = new UI\Meldung("Datenschutzhinweis", "Um eine Registrierung für den Digitalen Schulhof vorzunehmen, müssen die Cookies des Digitalen Schulhofs akzeptiert werden.", "Information");

  $akzeptieren      = new UI\IconKnopf("Cookies des Digitalen Schulhofs akzeptieren", new UI\Icon(UI\Konstanten::COOKIE));
  $akzeptieren->addFunktion("onclick", "kern.cookies.setzen('1', 'DSH')");

  $spalte[]         = new UI\Absatz($akzeptieren);
} else {
  $spalte[]         = new UI\Meldung("Mehrfache Registrierungen", "Registrierungen müssen vom Administrator Personen zugeordnet werden. Dies kann einige Zeit in Anspruch nehmen. Mehrfache Registrierungen beschleunigen das Verfahren nicht!", "Warnung");

  $spalte[]         = new UI\Meldung("Datenschutzhinweis", "Mit der Registrierung geht die Einverständnis in die Datenschutzvereinbarung des Digitalen Schulhofs einher. Diese kann hier eingesehen werden: ".(new UI\Link("Datenschutzvereinbarungen lesen", "Website/Datenschutz")), "Information");

  $formular         = new UI\FormularTabelle();

  $artwahl          = new UI\Auswahl("dshRegistrierungArt");
  $artwahl          ->add("Schüler", "s");
  $artwahl          ->add("Erziehungsberechtigte(r)", "e");
  $artwahl          ->add("Lehrkraft", "l");
  $artwahl          ->add("Verwaltung", "v");
  $artwahl          ->add("Externe(r)", "x");
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Art des Nutzerkontos:"),      $artwahl);

  $geschlechtswahl  = new UI\Auswahl("dshRegistrierungGeschlecht");
  $geschlechtswahl  ->add("Weiblich", "w");
  $geschlechtswahl  ->add("Männlich", "m");
  $geschlechtswahl  ->add("Divers", "d");
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Geschelcht:"),      $geschlechtswahl);

  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Titel:"),                     (new UI\Textfeld("dshRegistrierungTitel"))    ->setAutocomplete("honorific-prefix"));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),                   (new UI\Textfeld("dshRegistrierungVorname"))  ->setAutocomplete("given-name"));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachname:"),                  (new UI\Textfeld("dshRegistrierungNachname")) ->setAutocomplete("family-name"));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Klasse:"),                    (new UI\Textfeld("dshRegistrierungKlasse")));

  $passwort         = (new UI\Passwortfeld("dshRegistrierungPasswort"))                       ->setAutocomplete("new-password");
  $passwort2        = (new UI\Passwortfeld("dshRegistrierungPasswort2", $passwort))           ->setAutocomplete("new-password");
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Passwort:"),                  $passwort);
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Passwort bestätigen:"),       $passwort2);

  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"),             (new UI\Mailfeld("dshRegistrierungMail"))     ->setAutocomplete("email"));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Datenschutz:"),               (new UI\IconToggle("dshRegistrierungDatenschutz", "Ich bin mit den Datenschutzvorkehrungen des Digitalen Schulhofs einverstanden und erteile meine Erlaubnis zur Datenverarbeitung.", (new UI\Icon(UI\Konstanten::HAKEN)))));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Entscheidungsberechtigung:"), (new UI\IconToggle("dshRegistrierungEntscheidung", "Ich bin 18 Jahre alt oder älter, oder ein Erziehungsberechtigter hat mir erlaubt, diese Registrierung durchzuführen.", (new UI\Icon(UI\Konstanten::HAKEN)))));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Korrektheit:"),               (new UI\IconToggle("dshRegistrierungKorrektheit", "Meine Angaben sind nach bestem Wissen und Gewissen korrekt.", (new UI\Icon(UI\Konstanten::HAKEN)))));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Spamverhinderung:"),          (new UI\Spamschutz("dshRegistrierungSpamschutz", 7)));

  $formular[]       = (new UI\Knopf("Registrieren", "Erfolg"))  ->setSubmit(true);
  $formular[]       = (new UI\Knopf("Zurück zur Anmeldung"))    ->addFunktion("href", "Schulhof/Anmeldung");

  $formular         ->addSubmit("kern.schulhof.nutzerkonto.registrieren()");
  $spalte[]         = $formular;
}

$CODE[]             = new UI\Zeile($spalte);
?>
