<?php
$SEITE = new Kern\Seite("Fehler 302");

$SEITE[] = UI\Zeile::standard((new UI\Meldung("Fehler 302 - Found", "Diese Seite befindet sich vorübergehend an einem besseren Ort.<br>Wo? Das wüssten wir auch gerne.<br>Drücke auf den linken Rahmen, um zur vorherigen Seite zu gelangen. Alternativ, geht's $startseite zur Startseite.", "Fehler"))->setAttribut("brleft", "core.rueck()"));

?>
