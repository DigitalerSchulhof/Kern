<?php
$SEITE = new Kern\Seite("Verwaltungsbereich", null);


$SEITE[] = UI\Zeile::standard((new UI\SeitenUeberschrift("Verwaltungsbereich"))->setTitel("Jetzt wird's lustig :D"));
include_once "$DIR/klassen/verwaltungselemente.php";

use Kern\Verwaltung\Liste;
use Kern\Verwaltung\Kategorie;

new Kern\Wurmloch("funktionen/verwaltung/elemente.php");

$zeile = new UI\Zeile();

$viernulldrei = true;

foreach(Liste::getKategorien() as $kat) {
  if(!($kat instanceof \Kern\Verwaltung\Kategorie)) {
    throw new Exception("Die Kategorie ist ungültig");
  } else {
    if(count($kat->getElemente()) > 0) {
      $viernulldrei = false;
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

if($viernulldrei) {
  Seite::seiteAus("Fehler/403");
}

$SEITE[] = $zeile;
?>
