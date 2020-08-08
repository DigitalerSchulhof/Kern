<?php
$SEITE = new Kern\Seite("Module", "kern.module.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Module"));

$darflo = $DSH_BENUTZER->hatRecht("kern.module.loeschen");
$darfei = $DSH_BENUTZER->hatRecht("kern.module.einstellungen");
$aktionen = $darflo || $darfei;

$titel = ["", "Modul", "Version", "Status"];
if ($aktionen) {
  $titel[] = "Aktionen";
}

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
  $zeile["Status"] = "<span id=\"dshVerwaltungModuleStatus$modulid\">".(new UI\Ladesymbol())."</apan><script>kern.schulhof.verwaltung.module.status('$modulid', '$version')</script>";
  $aktionen = [];
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
