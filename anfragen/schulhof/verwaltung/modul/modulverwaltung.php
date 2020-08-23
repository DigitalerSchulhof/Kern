<?php
Anfrage::post("poolKennung", "poolToken");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("module.einstellungen")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istText($poolKennung)) {
  Anfrage::addFehler(101);
}

if(!UI\Check::istText($poolToken)) {
  Anfrage::addFehler(102);
}

Anfrage::checkFehler();

$sql = "UPDATE kern_einstellungen SET wert = [?] WHERE inhalt = [?]";
$werte = [];
$werte[] = [$poolKennung,  "PoolKennung"];
$werte[] = [$poolToken,    "PoolToken"];
$anfrage = $DBS->anfrage($sql, "ss", $werte);
?>
