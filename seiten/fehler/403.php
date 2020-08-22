<?php
$SEITE = new Kern\Seite("Fehler 403");

$SEITE[] = UI\Zeile::standard((new UI\Meldung("Fehler 403 - Forbidden", "Es fehlt die Berechtigung, um diese Seite zu sehen.", "Fehler"))->setAttribut("brleft", "core.rueck()"));

?>
