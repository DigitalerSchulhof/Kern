<?php
Anfrage::post("bezeichnung", "rechte");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}
$rechte = json_decode($rechte, true);
if($rechte === null) {
  Anfrage::addFehler(-3, true);
}
if (!UI\Check::istText($bezeichnung)) {
  Anfrage::addFehler(99);
}

if($DBS->existiert("kern_rollen", "bezeichnung = [?]", "s", $bezeichnung)) {
  Anfrage::addFehler(100);
}

Anfrage::checkFehler();

include_once "$DSH_MODULE/Kern/klassen/rechtehelfer.php";

foreach($rechte as $recht) {
  if(!Kern\Rechtehelfer::istRecht($recht)) {
    Anfrage::addFehler(-3, true);
  }
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.anlegen")) {
  Anfrage::addFehler(-4, true);
}

$id = $DBS->neuerDatensatz("kern_rollen", array("bezeichnung" => "[?]"), "s", $bezeichnung);

$werte = [];
foreach($rechte as $r) {
  $werte[] = [$id, $r];
}
if(count($werte) > 0) {
  $DBS->anfrage("INSERT INTO kern_rollenrechte (rolle, recht) VALUES (?, [?])", "is", $werte);
}
?>