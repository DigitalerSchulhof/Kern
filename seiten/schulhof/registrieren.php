<?php
$DSH_TITEL = "Registrieren";
$CODE .= new Kern\Aktionszeile();

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Registrieren"));

$spalte->add(new UI\Meldung("Mehrfache Registrierungen", "Registrierungen müssen vom Administrator Personen zugeordnet werden. Dies kann einige Zeit in Anspruch nehmen. Mehrfache Registrierungen beschleunigen das Verfahren nicht!", "Warnung"));

$titel = new UI\FormularFeld(new UI\InhaltElement("Titel:"), (new UI\Textfeld("dshRegistrierenTitel")));
$vorname = new UI\FormularFeld(new UI\InhaltElement("Vorname:"), (new UI\Textfeld("dshRegistrierenVorname")));
$nachname = new UI\FormularFeld(new UI\InhaltElement("Nachname:"), (new UI\Textfeld("dshRegistrierenNachname")));
$klasse = new UI\FormularFeld(new UI\InhaltElement("Klasse:"), (new UI\Textfeld("dshRegistrierenKlasse")));
$passwort = new UI\FormularFeld(new UI\InhaltElement("Passwort:"), (new UI\Passwortfeld("dshRegistrierenPasswort")));
$passwort2 = new UI\FormularFeld(new UI\InhaltElement("Passwort bestätigen:"), (new UI\Passwortfeld("dshRegistrierenPasswort", $passwort)));
$email = new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"), (new UI\Mailfeld("dshRegistrierenMail")));
$datenschutz = new UI\FormularFeld(new UI\InhaltElement("Datenschutz:"), (new UI\IconToggle("dshRegistrierenDatenschutz", "Ich bin mit den Datenschutzvorkehrungen des Digitalen Schulhofs einverstanden und erteile meine Erlaubnis zur Datenverarbeitung.", new UI\Icon(UI\Konstanten::HAKEN)))->setAttribut("type", "button"));
$berechtigung = new UI\FormularFeld(new UI\InhaltElement("Entscheidungsberechtigung:"), (new UI\IconToggle("dshRegistrierenEntscheidung", "Ich bin 18 Jahre alt oder älter, oder ein Erziehungsberechtigter hat mir erlaubt, diese Registrierung durchzuführen.", new UI\Icon(UI\Konstanten::HAKEN)))->setAttribut("type", "button"));
$korrektheit = new UI\FormularFeld(new UI\InhaltElement("Korrektheit:"), (new UI\IconToggle("dshRegistrierenKorrektheit", "Meine Angaben sind nach bestem Wissen und Gewissen korrekt.", new UI\Icon(UI\Konstanten::HAKEN)))->setAttribut("type", "button"));
$captcha = new UI\FormularFeld(new UI\InhaltElement("Spamverhinderung:"), (new UI\Textfeld("dshRegistrierenCaptcha")));

$formular = new UI\FormularTabelle($titel, $vorname, $nachname, $klasse, $passwort, $passwort2, $email, $datenschutz, $berechtigung, $korrektheit, $captcha);
$formular->addKnopf((new UI\Knopf("Registrieren", "Erfolg")) ->setSubmit(true));
$formular->addKnopf((new UI\Knopf("Zurück zur Anmeldung")) ->addFunktion("href", "Schulhof/Anmeldung"));
$formular->getAktionen()->addFunktion("onsubmit", "kern.schulhof.nutzerkonto.registrieren()");
$spalte->add($formular);

$CODE .= new UI\Zeile($spalte);
?>
