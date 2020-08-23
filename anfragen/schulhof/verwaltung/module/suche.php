<?php
Anfrage::postSort();

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("module.sehen")) {
  Anfrage::addFehler(-4, true);
}

include_once("$ROOT/yaml.php");
use Async\YAML;

$darflo = $DSH_BENUTZER->hatRecht("module.löschen");
$darfei = $DSH_BENUTZER->hatRecht("module.einstellungen");
$darfve = $DSH_BENUTZER->hatRecht("module.versionshistorie");

$tabelle  = new UI\Tabelle("dshVerwaltungModuleInstalliert", new UI\Icon(UI\Konstanten::MODUL), "Modul", "Beschreibung", "Version");
$tabelle  ->setAnfrageziel(47);

$module = [];

foreach($DSH_ALLEMODULE as $modul => $modulpfad) {
  $info   = YAML::loader(file_get_contents("$modulpfad/modul.yml"));

  $istSystem = in_array($modul, array("Kern", "UI"));

  $module[$modul] = array(
    "name"    => $info["name"],
    "version" => $info["version"],
    "system"  => $istSystem,
    "einstellungen" => $info["einstellungen"],
    // "update"  => $hatUpdate
  );
}

foreach($module as $modul => $info) {
  $zeile  = new UI\Tabelle\Zeile();
  if($info["system"]) {
    $zeile->setIcon(new UI\Icon("fas fa-microchip"));
  }

  $zeile["Modul"]   = $info["name"];
  $zeile["Version"] = $info["version"];

  $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::DETAILS), "Details");
  $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.module.details('$modul')");
  $zeile ->addAktion($knopf);

  if($darfve) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-code-branch"), "Versionshistorie");
    $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.module.version('$modul')");
    $zeile ->addAktion($knopf);
  }

  if(($info["einstellungen"] ?? false) && $darfei) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-sliders-h"), "Einstellungen");
    $knopf ->addFunktion("href", "Schulhof/Verwaltung/Module/$modul");
    $zeile ->addAktion($knopf);
  }

  if(!$info["system"] && $darflo) {
    $knopf = UI\MiniIconKnopf::loeschen();
    // @TODO: Module deinstallieren
    $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.module.loeschen.fragen('$modul')");
    $zeile ->addAktion($knopf);
  }

  $tabelle[] = $zeile;
}

$tabelle->sortieren();

Anfrage::setRueck("Code", (string) $tabelle);

?>