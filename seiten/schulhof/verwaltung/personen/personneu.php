<?php
$SEITE = new Kern\Seite("Personen", "kern.personen.anlegen.person");

$darfnutzerkonto = $DSH_BENUTZER->hatRecht("kern.personen.anlegen.nutzerkonto");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Neue Person anlegen"));

$formular         = new UI\FormularTabelle();

$artwahl          = new UI\Auswahl("dshNeuePersonArt");
$artwahl          ->add("Schüler", "s");
$artwahl          ->add("Erziehungsberechtigte(r)", "e");
$artwahl          ->add("Lehrkraft", "l");
$artwahl          ->add("Verwaltung", "v");
$artwahl          ->add("Externe(r)", "x");
$artwahl          ->addFunktion("oninput", "kern.schulhof.verwaltung.personen.benutzername()");
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Art des Person:"),      $artwahl);

$geschlechtswahl  = new UI\Auswahl("dshNeuePersonGeschlecht");
$geschlechtswahl  ->add("Weiblich", "w");
$geschlechtswahl  ->add("Männlich", "m");
$geschlechtswahl  ->add("Divers", "d");
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Geschlecht:"),      $geschlechtswahl);

$formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Titel:"),                    (new UI\Textfeld("dshNeuePersonTitel"))    ->setAutocomplete("honorific-prefix")))->setOptional(true);
$vorname = new UI\Textfeld("dshNeuePersonVorname");
$vorname->setAutocomplete("given-name");
$vorname->addFunktion("oninput", "kern.schulhof.verwaltung.personen.benutzername()");
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),  $vorname);
$nachname = new UI\Textfeld("dshNeuePersonNachname");
$nachname->setAutocomplete("family-name");
$nachname->addFunktion("oninput", "kern.schulhof.verwaltung.personen.benutzername()");
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachname:"), $nachname);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Kürzel:"),              new UI\Textfeld("dshNeuePersonKuerzel"));

if ($darfnutzerkonto) {
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nutzerkonto hinzufügen:"),  (new UI\IconToggle("dshNeuePersonNutzerkonto", "Gleichzeitig auch ein Nutzerkonto anlegen", (new UI\Icon(UI\Konstanten::HAKEN)))));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Benutzername:"),                  (new UI\Textfeld("dshNeuePersonBenutzername")) ->setAutocomplete("username"));
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"),             (new UI\Mailfeld("dshNeuePersonMail"))     ->setAutocomplete("email"));
}

$formular[]       = (new UI\Knopf("Neue Person anlegen", "Erfolg"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.verwaltung.personen.neu.person()");
$spalte[]         = $formular;

$SEITE[] = new UI\Zeile($spalte);
?>
