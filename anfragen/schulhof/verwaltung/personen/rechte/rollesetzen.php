<?php
Anfrage::post("id", "rolle", "wert");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($id) || !UI\Check::istToggle($wert)) {
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.zuordnen")) {
  Anfrage::addFehler(-4, true);
}

if(($person = Kern\Nutzerkonto::vonID($id)) === null) {
  Anfrage::addFehler(-3, true);
}

$sql = "SELECT COUNT(*) FROM kern_rollen WHERE id = ?";
$sql = $DBS->anfrage($sql, "i", $rolle);
if(!$sql->werte($anz)) {
  Anfrage::addFehler(-3, true);
}

if($wert == "0") {
  // Prüfen, ob es noch einen Administrator gibt
  $sql = "SELECT COUNT(*) FROM kern_rollenzuordnung WHERE person != ? AND rolle = 0";
  $anfrage = $DBS->anfrage($sql, "i", $id);
  if ($anfrage->werte($anzahl)) {
    if ($anzahl == 0) {
      Anfrage::addFehler(88, true);
    }
  } else {
    Anfrage::addFehler(88, true);
  }

  $sql = "DELETE FROM kern_rollenzuordnung WHERE person = ? AND rolle = ?";
  $anfrage = $DBS->anfrage($sql, "ii", $id, $rolle);
} else {
  $sql = "INSERT INTO kern_rollenzuordnung (nutzer, rolle) VALUES (?, ?)";
  $anfrage = $DBS->anfrage($sql, "ii", $id, $rolle);
}

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