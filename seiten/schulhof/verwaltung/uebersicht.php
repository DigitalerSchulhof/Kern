<?php
$SEITE = new Kern\Seite("Verwaltungsbereich", "kern.verwaltung");

$SEITE[] = UI\Zeile::standard((new UI\SeitenUeberschrift("Verwaltungsbereich"))->setTitel("Jetzt wird's lustig :D"));

include_once "$DIR/klassen/verwaltungselemente.php";

global $KATEGORIEN;

$KATEGORIEN = [];

foreach($DSH_ALLEMODULE as $modul) {
  if(file_exists("$modul/funktionen/verwaltung.php")) {
    include "$modul/funktionen/verwaltung.php";
  }
}

$zeile = new UI\Zeile();

foreach($KATEGORIEN as $kat) {
  if(!($kat instanceof Kern\Verwaltung\Kategorie)) {
    throw new Exception("Die Kategorie ist ungültig");
  } else {
    $spalte     = new UI\Spalte("A4");
    $spalte[]   = new UI\Ueberschrift("2", $kat->getTitel());

    $zeile[]    = $spalte;
  }
}
$SEITE[] = $zeile;
?>
