<?php
if(!Kern\Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

Kern\Check::verboten("kern.module.sehen");

$DSH_TITEL  = "Module";
$CODE[]     = new Kern\Aktionszeile();
$spalte     = new UI\Spalte("A1", new UI\SeitenUeberschrift("Module"));

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
    $zeile["Status"] = "<span id=\"dshVerwaltungModuleStatus$modulid\">".(new UI\Ladesymbol())."</span><scrip>kern.module</scrip>";
    $zeile["Status"] = "<span id=\"dshVerwaltungModuleStatus$modulid\">".(new UI\Ladesymbol())."</span>";
    $zeilen[] = $zeile;
  }
}
$tabelle = new UI\Tabelle("dshVerwaltungModule", $titel, $zeilen);

if ("kern.module.installieren") {
  // @TODO: Module installieren
  $knopf = new UI\IconKnopf(new UI\Icon (UI\Konstanten::NEU), "Neue Module installieren", "Erfolg");
  //$knopf->addFunktion("onclick", "alert('Diese Funktin steht noch nicht zur Verfügung.')");
  $spalte[] = new UI\Absatz($knopf);
}
$CODE[]     = new UI\Zeile($spalte);

?>
