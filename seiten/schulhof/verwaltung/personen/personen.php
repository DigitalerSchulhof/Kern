<?php
$SEITE = new Kern\Seite("Personen", "verwaltung.personen.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Personen"));

$tabellenid = "dshVerwaltungPersonen";
$filter = new Kern\Personenfilter("dshPersonenFilter", "ui.tabelle.sortieren(kern.schulhof.verwaltung.personen.suche, '$tabellenid')");
$spalte[] = $filter->setAnzeigen(true);

$tabelle = new UI\Tabelle($tabellenid, new UI\Icon(UI\Konstanten::SCHUELER), "Titel", "Vorname", "Nachname", "Status");
$tabelle->setAutoladen(true);
$tabelle->setSortierfunktion("kern.schulhof.verwaltung.personen.suche");

$spalte[] = $tabelle;

$knoepfe = [];
if ($DSH_BENUTZER->hatRecht("verwaltung.personen.anlegen.person")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Neue Person anlegen", "Erfolg");
  $knopf      ->addFunktion("href", "Schulhof/Verwaltung/Personen/Neue_Person");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("verwaltung.personen.importieren.[|konten,ids]")) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-file-import"), "Importieren");
  $knopf      ->addFunktion("onclick", "kern.personen.importieren.auswahl()");
  $knoepfe[]   = $knopf;
}

$vModule = array_keys($DSH_ALLEMODULE);

if ($DSH_BENUTZER->hatRecht("verwaltung.personen.kurszuordnung.[|ausDatei,ausKlasse,zuruecksetzen]") && in_array("Gruppen", $vModule)) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-chalkboard"), "Kurszuordnung");
  $knopf      ->addFunktion("onclick", "kern.personen.kurszuordnung.auswahl()");
  $knoepfe[]   = $knopf;
}
if ($DSH_BENUTZER->hatRecht("verwaltung.personen.löschen.person") && in_array("Gruppen", $vModule)) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::LOESCHEN), "Nicht zugeordnete Personen löschen", "Warnung");
  $knopf      ->addFunktion("onclick", "kern.personen.loeschen.nichtzugeornet.fragen()");
  $knoepfe[]   = $knopf;
}

if (count($knoepfe) > 0) {
  $spalte[] = new UI\Absatz(join(" ", $knoepfe));
}

$SEITE[] = new UI\Zeile($spalte);
?>
