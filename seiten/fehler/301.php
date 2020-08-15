<?php
$SEITE = new Kern\Seite("Fehler 301");

$SEITE[] = UI\Zeile::standard((new UI\Meldung("Fehler 301 - Moved Permanently", "Diese Seite ist permanent umgezogen.<br>Wohin? Wissen wir nicht.<br>Drücke auf den linken Rahmen, um zur vorherigen Seite zu gelangen. Alternativ, drücke $startseite, um zur Startseite zu gelangen.", "Fehler"))->setAttribut("brleft", "window.history.back()"));

?>
