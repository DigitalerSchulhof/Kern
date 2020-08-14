<?php
Anfrage::post("aktiv", "typ");

if(!UI\Check::istToggle($aktiv)) {
  Anfrage::addFehler(-3);
}
if(!in_array($typ, ["DSH", "EXT"])) {
  Anfrage::addFehler(-3);
}
Anfrage::checkFehler();

if(!isset($_COOKIE["Einwilligung{$typ}"])) {
  Anfrage::addFehler(11, true);
}


if ($aktiv == '1') {
  $_COOKIE["Einwilligung{$typ}"] = "ja";
  setcookie("Einwilligung$typ", "ja", time()+30*24*60*60, "/");
} else {
  setcookie("Einwilligung$typ", "nein", time()+30*24*60*60, "/");
}
?>
