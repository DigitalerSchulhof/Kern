<?php

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.anlegen")) {
  Anfrage::addFehler(-4, true);
}

$spalte   = new UI\Spalte("A1", new UI\SeitenUeberschrift("Neue Rolle anlegen"));

include_once __DIR__."/rollendetails.php";

$spalte[] = rollenDetails(null);

$code = new UI\Fenster("dshVerwaltungNeueRolle", "Neue Rolle anlegen", new UI\Zeile($spalte), true, true);
$code->addFensteraktion(UI\Knopf::schliessen("dshVerwaltungNeuePerson"));

Anfrage::setRueck("Code", (string) $code);
?>
