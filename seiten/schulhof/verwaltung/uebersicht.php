<?php
if(!Kern\Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL  = "Verwaltungsbereich";
$CODE[]     = new Kern\Aktionszeile();
$CODE[]     = UI\Zeile::standard((new UI\SeitenUeberschrift("Der Verwaltungsbereich"))->setTitel("Jetzt wird's lustig :D"));

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

$CODE[]         = $zeile;

?>
