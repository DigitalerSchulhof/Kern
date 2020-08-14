<?php

Seite::checkAngemeldet();

Kern\Check::verboten("kern.module.einstellungen");

if(!Kern\Check::istModul($DSH_URL[3]) || !is_file("$DSH_MODULE/{$DSH_URL[3]}/funktionen/verwaltung/einstellungen.php")) {
  Seite::nichtGefunden();
} else {
  $SEITE = new Kern\Seite($DSH_URL[3], "kern.module.einstellungen");

  include "$DSH_MODULE/{$DSH_URL[3]}/funktionen/verwaltung/einstellungen.php";
}
?>