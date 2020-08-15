<?php
$SEITE = new Kern\Seite("Fehler 302");

$SEITE[] = UI\Zeile::standard((new UI\Meldung("Fehler 500 - Internal Server Error", "Wenn Du diese Meldung siehst, ist etwas ordentlich schiefgelaufen... Bitte melde dies!", "Fehler"))->setAttribut("brleft", "window.history.back()"));

?>
