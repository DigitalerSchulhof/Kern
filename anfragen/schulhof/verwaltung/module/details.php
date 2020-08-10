<?php
Anfrage::post("modulname");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!isset($DSH_ALLEMODULE[$modulname])) {
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.module.sehen")) {
  Anfrage::addFehler(-4, true);
}

// Modulbeschreibung laden
include_once("$ROOT/yaml.php");
use Async\YAML;

$modultitel = "<i>unbekannt</i>";
$modulbeschreibung = "<i>unbekannt</i>";
$modulautor = "<i>unbekannt</i>";
$modulversion = "<i>unbekannt</i>";

if (is_file("{$DSH_ALLEMODULE[$modulname]}/modul.yml")) {
  $modulinfo = YAML::loader(file_get_contents("{$DSH_ALLEMODULE[$modulname]}/modul.yml"));

  if (isset($modulinfo["name"])) {
    $modultitel = $modulinfo["name"];
  }
  if (isset($modulinfo["beschreibung"])) {
    $modulbeschreibung = $modulinfo["beschreibung"];
  }
  if (isset($modulinfo["autor"])) {
    $modulautor = $modulinfo["autor"];
  }
  if (isset($modulinfo["version"])) {
    $modulversion = $modulinfo["version"];
  }
}

$fenstertitel = (new UI\Icon("fas fa-puzzle-piece"))." Moduldetails";

$inhalt  = new UI\Ueberschrift(3, "Moduldetails des Moduls »{$modultitel}«");
$inhalt .= new UI\Absatz($modulbeschreibung);
$inhalt .= new UI\Notiz("Version $modulversion - Autor: $modulautor");

$code = new UI\Fenster("dshVerwaltungModuleModulinfo$modulname", $fenstertitel, $inhalt);
$code->addFensteraktion(UI\Knopf::schliessen("dshVerwaltungModuleModulinfo$modulname"));

Anfrage::setTyp("Code");
Anfrage::setRueck("Code", (string) $code);
?>
