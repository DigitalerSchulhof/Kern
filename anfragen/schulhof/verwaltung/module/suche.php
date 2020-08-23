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

$tabelle  = new UI\Tabelle("dshVerwaltungModuleInstalliert", new UI\Icon(UI\Konstanten::MODUL), "Modul", "Version", "Status");
$tabelle  ->setAnfrageziel(47);

foreach($DSH_ALLEMODULE as $modul => $modulpfad) {
  $info   = YAML::loader(file_get_contents("$modulpfad/modul.yml"));
  $zeile  = new UI\Tabelle\Zeile();

  $istSystem = in_array($modul, array("Kern", "UI"));

  if($istSystem) {
    $zeile->setIcon(new UI\Icon("fas fa-microchip"));
  }

  $zeile["Modul"]   = $info["name"];
  $zeile["Version"] = $info["version"];
  $zeile["Status"]  = "<span id=\"dshVerwaltungModuleStatus$modul\">".(new UI\IconKnopf(new UI\Icon(UI\Konstanten::LADEN), "Nach neuer Version suchen..."))."</span>";
  $zeile["Status"] .= "<script>kern.schulhof.module.status('$modul', '{$info["version"]}')</script>";

  $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::DETAILS), "Details");
  $knopf ->addFunktion("onclick", "kern.schulhof.module.details('$modul')");
  $zeile ->addAktion($knopf);

  if($darfve) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-code-branch"), "Versionshistorie");
    $knopf ->addFunktion("onclick", "kern.schulhof.module.version('$modul')");
    $zeile ->addAktion($knopf);
  }

  if(($info["einstellungen"] ?? false) && $darfei) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-sliders-h"), "Einstellungen");
    $knopf ->addFunktion("href", "Schulhof/Verwaltung/Module/$modul");
    $zeile ->addAktion($knopf);
  }

  if(!$istSystem && $darflo) {
    $knopf = UI\MiniIconKnopf::loeschen();
    // @TODO: Module deinstallieren
    $knopf ->addFunktion("onclick", "kern.schulhof.module.loeschen.fragen('$modul')");
    $zeile ->addAktion($knopf);
  }

  $tabelle[] = $zeile;
}

$tabelle->sortieren();

Anfrage::setRueck("Code", (string) $tabelle);

?>