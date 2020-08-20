<?php
Anfrage::post("slname", "slmail", "daname", "damail", "prname", "prmail", "wename", "wemail", "adname", "admail");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.module.einstellungen")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istText($slname)) {
  Anfrage::addFehler(53);
}

if(!UI\Check::istMail($slmail)) {
  Anfrage::addFehler(54);
}

if(!UI\Check::istText($daname)) {
  Anfrage::addFehler(55);
}

if(!UI\Check::istMail($damail)) {
  Anfrage::addFehler(56);
}

if(!UI\Check::istText($prname)) {
  Anfrage::addFehler(57);
}

if(!UI\Check::istMail($prmail)) {
  Anfrage::addFehler(58);
}

Anfrage::checkFehler();

$sql = "UPDATE kern_einstellungen SET wert = [?] WHERE inhalt = [?]";
$werte = [];
$werte[] = [$slname, "Schulleitung Name"];
$werte[] = [$slmail, "Schulleitung Mail"];
$werte[] = [$daname, "Datenschutz Name"];
$werte[] = [$damail, "Datenschutz Mail"];
$werte[] = [$prname, "Presserecht Name"];
$werte[] = [$prmail, "Presserecht Mail"];
$werte[] = [$wename, "Webmaster Name"];
$werte[] = [$wemail, "Webmaster Mail"];
$werte[] = [$adname, "Administration Name"];
$werte[] = [$admail, "Administration Mail"];
$anfrage = $DBS->anfrage($sql, "ss", $werte);
?>
