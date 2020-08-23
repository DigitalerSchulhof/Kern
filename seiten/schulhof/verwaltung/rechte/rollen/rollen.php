<?php
$SEITE = new Kern\Seite("Rollen", "verwaltung.rechte.rollen.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Rollen"));

$tabelle = new UI\Tabelle("dshVerwaltungRollen", new UI\Icon("fas fa-tag"), "Rolle", "Personen", "Rechte");
$tabelle ->setAnfrageziel(43);
$tabelle ->setAutoload(true);

$spalte[] = $tabelle;
$knoepfe = [];
if ($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.anlegen")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Rolle anlegen", "Erfolg");
  $knopf      ->addFunktion("href", "Schulhof/Verwaltung/Rollen/Neue_Rolle");
  $knoepfe[]   = $knopf;
}

if ($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.zuordnen")) {
  $knopf      = new UI\IconKnopf(new UI\Icon ("fas fa-user-tag"), "Rollen zuordnen");
  $knopf      ->addFunktion("href", "Schulhof/Verwaltung/Personen");
  $knoepfe[]   = $knopf;
}

if (count($knoepfe) > 0) {
  $spalte[] = new UI\Absatz(join(" ", $knoepfe));
}

$SEITE[] = new UI\Zeile($spalte);
?>
