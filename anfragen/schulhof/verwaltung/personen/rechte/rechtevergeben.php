<?php
Anfrage::post("id", "rechte");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}
$rechte = json_decode($rechte, true);
if($rechte === null) {
  Anfrage::addFehler(-3, true);
}

include_once "$DSH_MODULE/Kern/klassen/rechtehelfer.php";

foreach($rechte as $recht) {
  if(!Kern\Rechtehelfer::istRecht($recht)) {
    Anfrage::addFehler(-3, true);
  }
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.vergeben")) {
  Anfrage::addFehler(-4, true);
}

if(($person = Kern\Nutzerkonto::vonID($id)) === null) {
  Anfrage::addFehler(-3, true);
}

$DBS->anfrage("DELETE FROM kern_nutzerrechte WHERE person = ?", "i", $id);
$werte = [];
foreach($rechte as $r) {
  $werte[] = [$id, $r];
}

$DBS->anfrage("INSERT INTO kern_nutzerrechte (person, recht) VALUES (?, [?])", "is", $werte);

$sessid = session_id();

$sql = "SELECT {sessionid} FROM kern_nutzersessions WHERE person = ? AND sessionid IS NOT NULL AND sessiontimeout > ".time();
$sql = $DBS->anfrage($sql, "i", $id);
session_commit();
while($sql->werte($sid)) {
  session_id($sid);
  Kern\Check::angemeldet();
  $DSH_BENUTZER->rechteLaden();
  $_SESSION["Benutzer"] = $DSH_BENUTZER;
  session_commit();
}

session_id($sessid);
Kern\Check::angemeldet();
?>