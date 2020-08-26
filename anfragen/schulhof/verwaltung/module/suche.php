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

$tabelle  = new UI\Tabelle("dshVerwaltungModuleInstalliert", "kern.schulhof.verwaltung.module.suchen", new UI\Icon(UI\Konstanten::MODUL), "Modul", "Beschreibung", "Autor", "Version");

$module = [];

foreach($DSH_ALLEMODULE as $modul => $modulpfad) {
  $info   = YAML::loader(file_get_contents("$modulpfad/modul.yml"));

  $istSystem = in_array($modul, array("Kern", "UI"));
  $hatEinstellungen = is_file("$DSH_MODULE/$modul/funktionen/verwaltung/einstellungen.php");

  $module[$modul] = array(
    "nam" => $info["name"],
    "ver" => $info["version"],
    "bes" => $info["beschreibung"],
    "aut" => $info["autor"],
    "ein" => $hatEinstellungen,
    "sys" => $istSystem,
    // "update"  => $hatUpdate
  );
}

foreach($module as $modul => $info) {
  $zeile  = new UI\Tabelle\Zeile();
  if($info["sys"]) {
    $zeile->setIcon(new UI\Icon("fas fa-microchip"));
  }

  $zeile["Modul"]   = $info["nam"];
  $zeile["Version"] = $info["ver"];
  $zeile["Autor"]   = $info["aut"];
  $zeile["Beschreibung"] = $info["bes"];

  if($darfve) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-code-branch"), "Versionshistorie");
    $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.module.version('$modul')");
    $zeile ->addAktion($knopf);
  }

  if($info["ein"] && $darfei) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-sliders-h"), "Einstellungen");
    $knopf ->addFunktion("href", "Schulhof/Verwaltung/Module/$modul");
    $zeile ->addAktion($knopf);
  }

  if(!$info["sys"] && $darflo) {
    $knopf = UI\MiniIconKnopf::loeschen();
    // @TODO: Module deinstallieren
    // $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.module.loeschen.fragen('$modul')");
    $knopf ->addFunktion("onclick", "alert('Diese Funktion steht demnächst zur Verfügung.')");
    $zeile ->addAktion($knopf);
  }

  $tabelle[] = $zeile;
}

$tabelle->sortieren();

Anfrage::setRueck("Code", (string) $tabelle);

?>