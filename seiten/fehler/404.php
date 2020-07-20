<?php
$DSH_TITEL = "Fehler 404";
$CODE .= new Kern\Aktionszeile(true, false);


$spalte = new UI\Spalte(new UI\Meldung("Fehler 404", "Die Seite konnte nicht gefunden werden!", "Fehler"));
$zeile = new UI\Zeile($spalte);

$CODE .= $zeile;
?>