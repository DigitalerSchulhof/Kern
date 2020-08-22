<?php
$SEITE = new Kern\Seite("Fehler 404");

$SEITE[] = UI\Zeile::standard((new UI\Meldung("Fehler 404 - Not Found", "Die Seite konnte nicht gefunden werden!", "Fehler"))->setAttribut("brleft", "core.rueck()"));
?>
