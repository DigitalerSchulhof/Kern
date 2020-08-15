<?php
$SEITE = new Kern\Seite("Fehlerliste");

$zeile = new UI\Zeile();

$startseite = new UI\Link("hier", "/");

$zeile[] = new UI\Spalte("A1", new UI\SeitenUeberschrift("Hallo, ich bin eine Fehlerseite"));
switch(rand(1, 5)) {
  case 1:
    $zeile[] = new UI\Spalte("A1", (new UI\Meldung("Fehler 301 - Moved Permanently", "Diese Seite ist permanent umgezogen.<br>Wohin? Wissen wir nicht.<br>Drücke auf den linken Rahmen, um zur vorherigen Seite zu gelangen. Alternativ, drücke $startseite, um zur Startseite zu gelangen.", "Fehler"))->setAttribut("brleft", "window.history.back()"));
    break;
  case 2:
    $zeile[] = new UI\Spalte("A1", (new UI\Meldung("Fehler 302 - Found", "Diese Seite befindet sich vorübergehend an einem besseren Ort.<br>Wo? Das wüssten wir auch gerne.<br>Drücke auf den linken Rahmen, um zur vorherigen Seite zu gelangen. Alternativ, geht's $startseite zur Startseite.", "Fehler"))->setAttribut("brleft", "window.history.back()"));
    break;
  case 3:
    $zeile[] = new UI\Spalte("A1", (new UI\Meldung("Fehler 403 - Forbidden", "Es fehlt die Berechtigung, um diese Seite zu sehen.", "Fehler"))->setAttribut("brleft", "window.history.back()"));
    break;
  case 4:
    $zeile[] = new UI\Spalte("A1", (new UI\Meldung("Fehler 404 - Not Found", "Die Seite konnte nicht gefunden werden!", "Fehler"))->setAttribut("brleft", "window.history.back()"));
    break;
  case 5:
    $zeile[] = new UI\Spalte("A1", (new UI\Meldung("Fehler 500 - Internal Server Error", "Wenn Du diese Meldung siehst, ist etwas ordentlich schiefgelaufen... Bitte melde dies!", "Fehler"))->setAttribut("brleft", "window.history.back()"));
    break;
}
$SEITE[] = $zeile;
?>
