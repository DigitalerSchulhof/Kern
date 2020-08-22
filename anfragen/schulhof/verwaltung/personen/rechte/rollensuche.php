<?php
Anfrage::postSort();

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.sehen")) {
  Anfrage::addFehler(-4, true);
}

$spalten = [["id"], ["{bezeichnung} AS bezeichnung"]];

$sql = "SELECT ?? FROM kern_rollen";

$ta = new Kern\Tabellenanfrage($sql, $spalten, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);
$tanfrage = $ta->anfrage($DBS);
$anfrage = $tanfrage["Anfrage"];

$tabelle = new UI\Tabelle("dshVerwaltungRollen", new UI\Icon("fas fa-tag"), "Rolle", "Personen");
$tabelle->setSeiten($tanfrage, "kern.schulhof.verwaltung.rollen.suche");

while($anfrage->werte($id, $bezeichung)) {
  $zeile = new UI\Tabelle\Zeile($id);
  $zeile["Rolle"]     = $bezeichung;

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