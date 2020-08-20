<?php
if(!Kern\Check::angemeldet(false)) {
  Anfrage::addFehler(-2, true);
}

// Timeout verlaengern
$verlaengern = [];
$sql = "SELECT sessiontimeout, {inaktivitaetszeit} FROM kern_nutzereinstellungen JOIN kern_nutzersessions ON kern_nutzereinstellungen.person = kern_nutzersessions.person WHERE kern_nutzersessions.person = ? AND sessionid = [?]";
$anfrage = $DBS->anfrage($sql, "is", $DSH_BENUTZER->getId(), $DSH_BENUTZER->getSessionid());
$anfrage->werte($verlaengern["Ende"], $verlaengern["Limit"]);

Anfrage::setRueck("Limit", $verlaengern["Limit"]);
Anfrage::setRueck("Ende",  $verlaengern["Ende"]);
?>
