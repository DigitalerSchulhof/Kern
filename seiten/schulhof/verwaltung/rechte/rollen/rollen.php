<?php
$SEITE = new Kern\Seite("Rollen", "verwaltung.rechte.rollen.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Rollen"));

$tabelle = new UI\Tabelle("dshVerwaltungRollen", "kern.schulhof.verwaltung.rollen.suchen", new UI\Icon(UI\Konstanten::ROLLE), "Rolle", "Personen", "Rechte");
$tabelle ->setAutoladen(true);

$spalte[] = $tabelle;
$knoepfe = [];
if ($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.anlegen")) {
  $knopf      = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Rolle anlegen", "Erfolg");
  $knopf      ->addFunktion("onclick", "kern.schulhof.verwaltung.rollen.neu.fenster()");
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
