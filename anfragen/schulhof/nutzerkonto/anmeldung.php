<?php
Anfrage::post("benutzer", "passwort");

if(!Check::istText($benutzer)) {
  Anfrage::addFehler(1);
}
if(!Check::istText($passwort)) {
  Anfrage::addFehler(2);
}
Anfrage::hatFehler();

?>
