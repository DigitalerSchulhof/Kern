<?php
$SEITE = new Kern\Seite("Personen", "verwaltung.rechte.rollen.anlegen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Neue Rolle anlegen"));

$formular         = new UI\FormularTabelle();

$artwahl          = new UI\Textfeld("dshNeueRolleBezeichnung");
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Bezeichnung:"),      $artwahl);

include_once("$DSH_MODULE/Kern/klassen/rechtebaum.php");

$rechtebaum = new Kern\Rechtebaum("dshNeueRolleRechtebaum");

$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Rechte:"),      $rechtebaum);

$formular[]       = (new UI\Knopf("Neue Rolle anlegen", "Erfolg"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.verwaltung.rollen.neu()");
$spalte[]         = $formular;

$SEITE[] = new UI\Zeile($spalte);
?>
