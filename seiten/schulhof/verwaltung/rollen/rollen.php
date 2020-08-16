<?php
$SEITE = new Kern\Seite("Rollen", "kern.rechte.rollen.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Rollen"));

$tabelle = new UI\Tabelle("dshVerwaltungRollen", new UI\Icon("fas fa-tag"), "Rolle", "Personen");

$sql = $DBS->anfrage("SELECT id, {bezeichnung} FROM kern_rollen");
while($sql->werte($id, $bezeichung)) {
  $zeile = new UI\Tabelle\Zeile($id);
  $zeile["Rolle"]     = $bezeichung;

  if($DSH_BENUTZER->hatRecht("kern.rechte.rollen.bearbeiten")) {
    $zeile->addAktion(new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::BEARBEITEN), "Rolle bearbeiten"));
  }
  if($DSH_BENUTZER->hatRecht("kern.rechte.rollen.löschen")) {
    $zeile->addAktion(new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::BEARBEITEN), "Rolle bearbeiten"));
  }

  $tabelle[] = $zeile;
}
$spalte[] = $tabelle;
$knoepfe = [];
if ($DSH_BENUTZER->hatRecht("kern.rechte.rollen.anlegen")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Rolle anlegen", "Erfolg");
  $knopf      ->addFunktion("href", "Schulhof/Verwaltung/Rollen/Zuordnen");
  $knoepfe[]   = $knopf;
}

if ($DSH_BENUTZER->hatRecht("kern.rechte.rollen.zuordnen")) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-user-tag"), "Rollen zuordnen");
  $knopf      ->addFunktion("href", "Schulhof/Verwaltung/Rollen/Zuordnen");
  $knoepfe[]   = $knopf;
}

if (count($knoepfe) > 0) {
  $spalte[] = new UI\Absatz(join(" ", $knoepfe));
}

$SEITE[] = new UI\Zeile($spalte);
?>
