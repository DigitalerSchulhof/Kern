<?php
$SEITE    = new Kern\Seite("Personen", "verwaltung.rechte.rollen.anlegen");

$spalte   = new UI\Spalte("A1", new UI\SeitenUeberschrift("Neue Rolle anlegen"));

include_once __DIR__."/details.php";

$spalte[] = rollenDetails(null);

$SEITE[] = new UI\Zeile($spalte);
?>
