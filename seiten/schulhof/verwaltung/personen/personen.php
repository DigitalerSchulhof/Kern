<?php
$SEITE = new Kern\Seite("Personen", "kern.personen.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Personen"));

$reiter = new UI\Reiter("dshKonfiguration");

global $EINSTELLUNGEN;


$knoepfe = [];
if ($DSH_BENUTZER->hatRecht("kern.personen.anlegen")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Neue Person anlegen", "Erfolg");
  $knopf      ->addFunktion("href", "Schulhof/Verwaltung/Personen/Neue_Person");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("kern.personen.importieren.(|konten,ids)")) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-file-import"), "Importieren");
  $knopf      ->addFunktion("onclick", "kern.personen.importieren.auswahl()");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("kern.personen.kurszuordnung.(|ausDatei,ausKlasse,zuruecksetzen)")) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-file-import"), "Kurszuordnung");
  $knopf      ->addFunktion("onclick", "kern.personen.kurszuordnung.auswahl()");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("kern.personen.loeschen")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::LOESCHEN), "Nicht zugeordnete Personen löschen", "Warnung");
  $knopf      ->addFunktion("onclick", "kern.personen.loeschen.nichtzugeornet.fragen()");
  $knoepfe[]   = $knopf;
}

if (count($knoepfe) > 0) {
  $spalte[] = new UI\Absatz(join(" ", $knoepfe));
}

$SEITE[] = new UI\Zeile($spalte);
?>
