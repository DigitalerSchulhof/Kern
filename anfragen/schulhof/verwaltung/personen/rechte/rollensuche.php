<?php
Anfrage::postSort();

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.sehen")) {
  Anfrage::addFehler(-4, true);
}

$spalten = [["kr.id as id"], ["{bezeichnung} AS bezeichnung"], ["GROUP_CONCAT(CONCAT({kp.titel}, ' ', {kp.vorname}, ' ', {kp.nachname}) SEPARATOR ', ') as personen"]];

$sql = "SELECT ?? FROM kern_rollen as kr JOIN kern_rollenzuordnung as krz ON krz.rolle = kr.id JOIN kern_personen as kp ON krz.person = kp.id";

$ta = new Kern\Tabellenanfrage($sql, $spalten, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);
$tanfrage = $ta->anfrage($DBS);
$anfrage = $tanfrage["Anfrage"];

$tabelle = new UI\Tabelle("dshVerwaltungRollen", new UI\Icon("fas fa-tag"), "Rolle", "Personen");
$tabelle->setSeiten($tanfrage, "kern.schulhof.verwaltung.rollen.suche");

while($anfrage->werte($id, $bezeichung, $personen)) {
  $zeile = new UI\Tabelle\Zeile($id);
  $zeile["Rolle"]     = $bezeichung;
  $zeile["Personen"]  = $personen;
  if($id === 0) {
    $zeile->setIcon(new UI\Icon("fas fa-star"));
  } else {
    if($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.bearbeiten")) {
      $zeile->addAktion(new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::BEARBEITEN), "Rolle bearbeiten"));
    }
    if($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.löschen")) {
      $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::LOESCHEN), "Rolle löschen", "Warnung");
      $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.rollen.loeschen.fragen($id)");
      $zeile->addAktion($knopf);
    }
  }

  $tabelle[] = $zeile;
}

Anfrage::setRueck("Code", (string) $tabelle);
?>