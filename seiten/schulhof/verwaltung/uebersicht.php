<?php
$SEITE = new Kern\Seite("Verwaltungsbereich", "kern.verwaltung");

$SEITE[] = UI\Zeile::standard((new UI\SeitenUeberschrift("Verwaltungsbereich"))->setTitel("Jetzt wird's lustig :D"));

include_once "$DIR/klassen/verwaltungselemente.php";

use Kern\Verwaltung\Liste;
use Kern\Verwaltung\Kategorie;

Liste::addKategorie(new Kategorie("personen", "Personen"), new Kategorie("technik", "Technik"));

foreach($DSH_ALLEMODULE as $modul) {
  if(is_file("$modul/funktionen/verwaltung/elemente.php")) {
    include  "$modul/funktionen/verwaltung/elemente.php";
  }
}

$zeile = new UI\Zeile();

foreach(Liste::getKategorien() as $kat) {
  if(!($kat instanceof \Kern\Verwaltung\Kategorie)) {
    throw new Exception("Die Kategorie ist ungültig");
  } else {
    if(count($kat->getElemente()) > 0) {
      $spalte       = new UI\Spalte("A4");
      $elemente     = new UI\Box();
      $elemente     ->addKlasse("dshVerwaltungsKategorie");
      $spalte[]     = new UI\Ueberschrift("2", $kat->getTitel());

      foreach($kat->getElemente() as $element) {
        $elemente[] = $element;
      }
      $spalte[]     = $elemente;
      $zeile[]      = $spalte;
    }
  }
}
$SEITE[] = $zeile;
?>
