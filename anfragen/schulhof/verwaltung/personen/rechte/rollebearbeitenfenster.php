<?php

Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

if(!$DBS->anfrage("SELECT id FROM kern_rollen WHERE id = ?", "i", $id)->werte($NULL)) {
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.bearbeiten")) {
  Anfrage::addFehler(-4, true);
}

$spalte   = new UI\Spalte("A1", new UI\SeitenUeberschrift("Rolle bearbeiten"));

include_once __DIR__."/rollendetails.php";

$spalte[] = rollenDetails($id);

$code = new UI\Fenster("dshVerwaltungBearbeitenRolle$id", "Rolle bearbeiten", new UI\Zeile($spalte), true, true);

Anfrage::setRueck("Code", (string) $code);
?>