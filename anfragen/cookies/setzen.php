<?php
Anfrage::post("aktiv", "typ");

if(!Check::istToggle($aktiv)) {
  Anfrage::addFehler(-3);
}
if($typ != "DSH" && $typ != "EXT") {
  Anfrage::addFehler(-3);
}
Anfrage::checkFehler();

if(!isset($_COOKIE["Einwilligung{$typ}"])) {
  Anfrage::addFehler(11);
}


if ($aktiv == '1') {
  $_COOKIE["Einwilligung{$typ}"] = "ja";
} else {
  $_COOKIE["Einwilligung{$typ}"] = "nein";
}

print_r($_COOKIE);

Anfrage::setTyp("Fortsetzen");
Anfrage::setRueck("Funktion", "location.reload()");
?>
