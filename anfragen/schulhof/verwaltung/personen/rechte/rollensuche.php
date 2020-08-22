<?php
Anfrage::postSort();

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.sehen")) {
  Anfrage::addFehler(-4, true);
}

$spalten = [["kr.id as id"], ["{bezeichnung} AS bezeichnung"], ["(SELECT GROUP_CONCAT(CONCAT({kp.titel}, ' ', {kp.vorname}, ' ', {kp.nachname}) SEPARATOR ', ') FROM kern_personen as kp JOIN kern_rollenzuordnung as krz ON krz.person = kp.id WHERE krz.rolle = kr.id) as personen"], ["(SELECT GROUP_CONCAT({krr.recht} SEPARATOR ', ') FROM kern_rollenrechte as krr WHERE krr.rolle = kr.id) as rechte"]];

$sql = "SELECT ?? FROM kern_rollen as kr";

$ta = new Kern\Tabellenanfrage($sql, $spalten, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);
$tanfrage = $ta->anfrage($DBS);
$anfrage = $tanfrage["Anfrage"];

$tabelle = new UI\Tabelle("dshVerwaltungRollen", new UI\Icon("fas fa-tag"), "Rolle", "Personen", "Rechte");
$tabelle->setSeiten($tanfrage, "kern.schulhof.verwaltung.rollen.suche");

while($anfrage->werte($id, $bezeichung, $personen, $rechte)) {
  $zeile = new UI\Tabelle\Zeile($id);
  $zeile["Rolle"]     = $bezeichung;
  $zeile["Personen"]  = $personen;
  $zeile["Rechte"]    = $rechte;
  if($id === 0) {
    $zeile->setIcon(new UI\Icon("fas fa-star"));
  } else {
    if($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.bearbeiten")) {
      $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::BEARBEITEN), "Rolle bearbeiten");
      $knopf ->addFunktion("href", "Schulhof/Verwaltung/Rollen/".str_replace(" ", "_", $bezeichung));
      $zeile ->addAktion($knopf);
    }
    if($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.löschen")) {
      $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::LOESCHEN), "Rolle löschen", "Warnung");
      $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.rollen.loeschen.fragen($id)");
      $zeile ->addAktion($knopf);
    }
  }

  $tabelle[] = $zeile;
}

Anfrage::setRueck("Code", (string) $tabelle);
?>