<?php
$SEITE = new Kern\Seite("Module", "kern.module.sehen");

include_once("$ROOT/yaml.php");
$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Module"));

$darflo = $DSH_BENUTZER->hatRecht("kern.module.loeschen");
$darfei = $DSH_BENUTZER->hatRecht("kern.module.einstellungen");
$darfve = $DSH_BENUTZER->hatRecht("kern.module.versionshistorie");
$aktionen = $darflo || $darfei || $darfve;

$titel = ["", "Modul", "Version", "Status", "Aktionen"];

// @TODO: modulstati prüfen
$modulstati = [];
$tabelle  = new UI\Tabelle("dshVerwaltungModule", $titel);
foreach ($DSH_ALLEMODULE as $modulpfad) {
  $modulpfadteile = explode("/", $modulpfad);
  $modul = $modulpfadteile[count($modulpfadteile)-1];
  $zeile = [];
  $zeile[""] = new UI\Icon("fas fa-puzzle-piece");
  $zeile["Modul"] = $modul;
  $version = "1.1.1";
  $zeile["Version"] = $version;
  $modulid = Kern\Check::strToCode($modul);
  $modullink = Kern\Check::strToLink($modul);
  $modulstati[] = $modulid;
  $zeile["Status"] = "<span id=\"dshVerwaltungModuleStatus$modulid\">".(new UI\IconKnopf(new UI\Icon(UI\Konstanten::LADEN), "Nach neuer Version suchen ..."))."</span>";
  $zeile["Status"] .= "<script>kern.schulhof.verwaltung.module.status('$modulid', '$version')</script>";
  $aktionen = [];
  $detailknopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::DETAILS), "Details");
  $detailknopf->addFunktion("onclick", "kern.schulhof.verwaltung.module.details('$modulid')");
  $aktionen[] = $detailknopf;
  if ($darfve) {
    $versionsknopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-code-branch"), "Versionshistorie");
    $versionsknopf->addFunktion("onclick", "kern.schulhof.verwaltung.module.version('$modulid')");
    $aktionen[] = $versionsknopf;
  }
  if ($darfei) {
    $einstellknopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-sliders-h"), "Einstellungen");
    $einstellknopf->addFunktion("href", "Schulhof/Verwaltung/$modullink/Einstellungen");
    $aktionen[] = $einstellknopf;
  }
  if ($darflo) {
    $loeschenknopf = UI\MiniIconKnopf::loeschen();
    $loeschenknopf->addFunktion("onclick", "kern.schulhof.verwaltung.module.loeschen.fragen('$modulid')");
    $aktionen[] = $loeschenknopf;
  }
  $zeile["Aktionen"] = join(" ", $aktionen);
  $tabelle->addZeile($zeile);
}

$spalte[] = $tabelle;

if ("kern.module.installieren") {
  // @TODO: Module installieren
  $knopf = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Neue Module installieren", "Erfolg");
  //$knopf->addFunktion("onclick", "alert('Diese Funktin steht noch nicht zur Verfügung.')");
  $spalte[] = new UI\Absatz($knopf);
}

$SEITE[] = new UI\Zeile($spalte);
?>
