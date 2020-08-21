<?php
Anfrage::post("modulname");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!isset($DSH_ALLEMODULE[$modulname])) {
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("module.sehen")) {
  Anfrage::addFehler(-4, true);
}

// Modulbeschreibung laden
include_once("$ROOT/yaml.php");
use Async\YAML;

$modultitel = "<i>Unbekannt</i>";
$modulbeschreibung = "<i>Unbekannt</i>";
$modulautor = "<i>Unbekannt</i>";
$modulversion = "<i>Unbekannt</i>";

if (is_file("{$DSH_ALLEMODULE[$modulname]}/modul.yml")) {
  $modulinfo = YAML::loader(file_get_contents("{$DSH_ALLEMODULE[$modulname]}/modul.yml"));

  $modultitel         = $modulinfo["name"]          ?? "<i>unbekannt</i>";
  $modulbeschreibung  = $modulinfo["beschreibung"]  ?? "<i>unbekannt</i>";
  $modulautor         = $modulinfo["autor"]         ?? "<i>unbekannt</i>";
  $modulversion       = $modulinfo["version"]       ?? "<i>unbekannt</i>";
}

$fenstertitel = (new UI\Icon("fas fa-puzzle-piece"))." Moduldetails";

$inhalt  = new UI\Ueberschrift(3, "Moduldetails des Moduls »{$modultitel}«");
$inhalt .= new UI\Absatz($modulbeschreibung);
$inhalt .= new UI\Notiz("Version $modulversion - Autor: $modulautor");
$fensterid = "dshVerwaltungModuleModulinfo$modulname";
$code = new UI\Fenster($fensterid, $fenstertitel, $inhalt);
$code->addFensteraktion(UI\Knopf::schliessen("dshVerwaltungModuleModulinfo$modulname"));

Anfrage::setRueck("Code", (string) $code);
?>
