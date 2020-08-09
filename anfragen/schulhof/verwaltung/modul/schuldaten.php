<?php
Anfrage::post("schulname", "schulort", "strhnr", "plzort", "telefon", "fax", "mail", "domain");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.module.einstellungen")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istText($schulname)) {
  Anfrage::addFehler(48);
}

if(!UI\Check::istText($schulort)) {
  Anfrage::addFehler(49);
}

if(!UI\Check::istText($strhnr)) {
  Anfrage::addFehler(50);
}

if(!UI\Check::istText($plzort)) {
  Anfrage::addFehler(51);
}

if(!strlen($domain) > 5) {
  Anfrage::addFehler(52);
}

Anfrage::checkFehler();

$sql = "UPDATE kern_einstellungen SET wert = [?] WHERE inhalt = [?]";
$werte = [];
$werte[] = [$schulname, "Schulname"];
$werte[] = [$schulort,  "Schulort"];
$werte[] = [$strhnr,    "Schulstraße und -hausnr"];
$werte[] = [$plzort,    "SchulPLZ und -ort"];
$werte[] = [$telefon,   "Schultelefonnummer"];
$werte[] = [$fax,       "Schulfaxnummer"];
$werte[] = [$mail,      "Schulmail"];
$werte[] = [$domain,    "Schuldomain"];
$anfrage = $DBS->anfrage($sql, "ss", $werte);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Kern-Einstellungen wurden vorgenomen.", "Erfolg"));
?>
