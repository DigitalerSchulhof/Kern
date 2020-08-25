<?php
Anfrage::post("id", "bezeichnung", "rechte");

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

if($DBS->existiert("kern_rollen", "bezeichnung = [?] AND id != ?", "si", $bezeichnung, $id)) {
  Anfrage::addFehler(100);
}

Anfrage::checkFehler();

include_once "$DSH_MODULE/Kern/klassen/rechtehelfer.php";

foreach($rechte as $recht) {
  if(!Kern\Rechtehelfer::istRecht($recht)) {
    Anfrage::addFehler(-3, true);
  }
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.bearbeiten")) {
  Anfrage::addFehler(-4, true);
}

$DBS->datensatzBearbeiten("kern_rollen", $id, array("bezeichnung" => "[?]"), "s", $bezeichnung);
$DBS->datensatzLoeschen("kern_rollenrechte", array("rolle" => $id));

$werte = [];
foreach($rechte as $r) {
  $werte[] = [$id, $r];
}
if(count($werte) > 0) {
  $DBS->anfrage("INSERT INTO kern_rollenrechte (rolle, recht) VALUES (?, [?])", "is", $werte);
}
?>