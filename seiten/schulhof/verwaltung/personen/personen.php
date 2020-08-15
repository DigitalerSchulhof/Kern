<?php
$SEITE = new Kern\Seite("Personen", "kern.personen.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Personen"));

$filter = new Kern\Personenfilter("dshPersonenFilter", "kern.schulhof.verwaltung.personen.suche()");
$spalte[] = $filter->setAnzeigen(true);

$tabelle = new UI\Tabelle("dshVerwaltungModule", new UI\Icon(UI\Konstanten::SCHUELER), "Titel", "Vorname", "Nachname", "Status");

$spalte[] = "<div id=\"dshPersonenLadebereich\">".$tabelle."</div>";

$knoepfe = [];
if ($DSH_BENUTZER->hatRecht("kern.personen.anlegen.person")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Neue Person anlegen", "Erfolg");
  $knopf      ->addFunktion("href", "Schulhof/Verwaltung/Personen/Neue_Person");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("kern.personen.importieren.[|konten,ids]")) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-file-import"), "Importieren");
  $knopf      ->addFunktion("onclick", "kern.personen.importieren.auswahl()");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("kern.personen.kurszuordnung.[|ausDatei,ausKlasse,zuruecksetzen]")) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-chalkboard"), "Kurszuordnung");
  $knopf      ->addFunktion("onclick", "kern.personen.kurszuordnung.auswahl()");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("kern.personen.loeschen.person")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::LOESCHEN), "Nicht zugeordnete Personen löschen", "Warnung");
  $knopf      ->addFunktion("onclick", "kern.personen.loeschen.nichtzugeornet.fragen()");
  $knoepfe[]   = $knopf;
}

if (count($knoepfe) > 0) {
  $spalte[] = new UI\Absatz(join(" ", $knoepfe));
}

$SEITE[] = new UI\Zeile($spalte);
?>
