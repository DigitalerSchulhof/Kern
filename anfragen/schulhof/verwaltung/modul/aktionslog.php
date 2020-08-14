<?php
Anfrage::post("aktionslog");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.module.einstellungen")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istToggle($aktionslog)) {
  Anfrage::addFehler(-3, true);
}

Anfrage::checkFehler();

$sql = "UPDATE kern_einstellungen SET wert = [?] WHERE inhalt = [?]";
$anfrage = $DBS->anfrage($sql, "ss", $aktionslog, "Aktionslog");
?>
