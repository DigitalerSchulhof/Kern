<?php
$SEITE = new Kern\Seite("Module", "module.sehen");

include_once("$ROOT/yaml.php");
use Async\YAML;

$darflo = $DSH_BENUTZER->hatRecht("module.löschen");
$darfei = $DSH_BENUTZER->hatRecht("module.einstellungen");
$darfve = $DSH_BENUTZER->hatRecht("module.versionshistorie");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Module"));
$tabelle = new UI\Tabelle("dshVerwaltungModuleInstalliert", new UI\Icon(UI\Konstanten::MODUL), "Modul", "Version", "Status");

$spalte[] = new UI\Ueberschrift(2, "Installiert");

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

  if($darfei) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-sliders-h"), "Einstellungen");
    if($info["einstellungen"] ?? false) {
      $knopf ->addFunktion("href", "Schulhof/Verwaltung/Module/$modul");
    }
    $zeile ->addAktion($knopf);
  }

  if($darflo) {
    $knopf = UI\MiniIconKnopf::loeschen();
    if(!$istSystem) {
      // @TODO: Module deinstallieren
      $knopf ->addFunktion("onclick", "kern.schulhof.module.loeschen.fragen('$modul')");
    }
    $zeile ->addAktion($knopf);
  }

  $tabelle[] = $zeile;
}

$spalte[] = $tabelle;

if ($DSH_BENUTZER->hatRecht("module.installieren")) {
  // @TODO: Module installieren

  $spalte[] = new UI\Ueberschrift(2, "Verfügbar");
  $tabelle  = new UI\Tabelle("dshVerwaltungModuleVerfuegbar", new UI\Icon(UI\Konstanten::MODUL), "Modul", "Version", "Status");
  $spalte[] = $tabelle;
}

$SEITE[] = new UI\Zeile($spalte);
?>
