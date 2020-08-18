<?php

include_once("$ROOT/yaml.php");
use Async\YAML;

Anfrage::post("fehler");

$fehler = json_decode($fehler, true);
if($fehler === null || !is_array($fehler) || count($fehler) < 1) {
  Anfrage::addFehler(7, "Core");
}
Anfrage::checkFehler();

$fehlercodes = [];

foreach($fehler as $f) {
  if(count($f) !== 2 || !is_string($f[0]) || !is_numeric($f[1]) || !\Kern\Check::istModul($f[0])) {
    $fehlercodes = [["Core", -3]];
    break;
  } else {
    $fehlercodes[] = $f;
  }
}

$fehlerCode = "";
$fehlercodesyml = [];
foreach($fehlercodes as $fc) {
  $modul        = $fc[0];
  $code         = $fc[1];

  if(!isset($fehlercodesyml[$modul])) {
    if($modul === "Core") {
      $yml = "$ROOT/core/anfragen/fehlercodes.yml";
    } else {
      $yml = "$DSH_MODULE/$modul/anfragen/fehlercodes.yml";
    }
    if(is_file($yml)) {
      $fehlercodesyml[$modul] = YAML::loader(file_get_contents($yml));
    } else {
      $fehlerCode = new UI\Absatz("Unbekannter Fehlercode. <b>Bitte melden!</b> <span class=\"dshFehlercode\" title=\"Unbekannt\">XXX</span>");
      break;
    }
  }

  $beschreibung = ($fehlercodesyml[$modul][$code] ?? array("beschreibung" => "Unbekannter Fehlercode. <b>Bitte melden!</b>"))["beschreibung"];

  if($code > 0) {
    $code = strtoupper(dechex($code));
    $code = str_pad($code, 3, '0', STR_PAD_LEFT);
  }
  $fehlerCode  .= new UI\Absatz("$beschreibung <span class=\"dshFehlercode\" title=\"$modul\">$code</span>");
}

if(count($fehlercodes) > 1) {
  $titel = "Es sind folgende Fehler aufgetreten:";
} else {
  $titel = "Es ist folgender Fehler aufgetreten:";
}

Anfrage::setRueck("Meldung", (string) new UI\Meldung($titel, $fehlerCode, "Fehler"));
Anfrage::setRueck("Knoepfe", (string) UI\Knopf::ok());
?>