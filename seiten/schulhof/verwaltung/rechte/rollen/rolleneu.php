<?php
$SEITE = new Kern\Seite("Personen", "verwaltung.rechte.rollen.anlegen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Neue Rolle anlegen"));

$formular         = new UI\FormularTabelle();

$artwahl          = new UI\Eingabe("dshNeueRolleBezeichnung");
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Bezeichnung:"),      $artwahl);

$formular[]       = (new UI\Knopf("Neue Rolle anlegen", "Erfolg"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.verwaltung.rollen.neu.person()");
$spalte[]         = $formular;

$SEITE[] = new UI\Zeile($spalte);
?>
