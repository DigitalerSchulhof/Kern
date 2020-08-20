<?php
Anfrage::post("ldapaktiv", "ldapuser", "ldappass", "ldaphost", "ldapport");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.module.einstellungen")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istToggle($ldapaktiv)) {
  Anfrage::addFehler(-3, true);
}

if ($ldapaktiv == "1") {
  if(!UI\Check::istText($ldapuser)) {
    Anfrage::addFehler(64);
  }

  if(!UI\Check::istText($ldaphost)) {
    Anfrage::addFehler(65);
  }

  if(!UI\Check::istZahl($ldapport,0,65535)) {
    Anfrage::addFehler(66);
  }
}

Anfrage::checkFehler();

$sql = "UPDATE kern_einstellungen SET wert = [?] WHERE inhalt = [?]";
$werte = [];
$werte[] = [$ldapaktiv, "LDAP"];
$werte[] = [$ldapuser,  "LDAP-User"];
$werte[] = [$ldappass,  "LDAP-Passwort"];
$werte[] = [$ldaphost,  "LDAP-Host"];
$werte[] = [$ldapport,  "LDAP-Port"];
$anfrage = $DBS->anfrage($sql, "ss", $werte);
?>