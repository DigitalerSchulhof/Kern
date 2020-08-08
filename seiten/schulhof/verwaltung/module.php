<?php
$SEITE = new Kern\Seite("Module", "kern.module.sehen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Module"));

$pfad = "$ROOT/module";
$module = scandir($pfad);
$module = array_splice($module, 2);


$darflo = $DSH_BENUTZER->hatRecht("kern.module.loeschen");
$darfei = $DSH_BENUTZER->hatRecht("kern.module.einstellungen");
$aktionen = $darflo || $darfei;

$titel = ["", "Modul", "Version", "Status"];
if ($aktionen) {
  $titel[] = "Aktionen";
}

// @TODO: modulstati prüfen
$zeilen = [];
$modulstati = [];
foreach ($module as $modul) {
  if (is_dir("$pfad/$modul")) {
    $zeile = [];
    $zeile[""] = new UI\Icon("fas fa-puzzle-piece");
    $zeile["Modul"] = $modul;
    $version = "1.1.1";
    $zeile["Version"] = $version;
    $modulid = Kern\Check::strToCode($modul);
    $modulstati[] = $modulid;
    $zeile["Status"] = "<span id=\"dshVerwaltungModuleStatus$modulid\">".(new UI\Ladesymbol())."</span><scrip>kern.schulhof.verwaltung.module.status('$modulid', '$version')</scrip>";
    $zeilen[] = $zeile;
  }
}
$tabelle  = new UI\Tabelle("dshVerwaltungModule", $titel, $zeilen);
$spalte[] = $tabelle;
if ("kern.module.installieren") {
  // @TODO: Module installieren
  $knopf = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Neue Module installieren", "Erfolg");
  //$knopf->addFunktion("onclick", "alert('Diese Funktin steht noch nicht zur Verfügung.')");
  $spalte[] = new UI\Absatz($knopf);
}

$SEITE[] = new UI\Zeile($spalte);
?>
