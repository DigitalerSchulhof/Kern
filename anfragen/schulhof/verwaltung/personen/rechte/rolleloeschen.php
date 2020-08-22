<?php
Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

if(in_array($id, [0])) {
  // Admin löschen
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.löschen")) {
  Anfrage::addFehler(-4, true);
}

$personen = [];
// Leute mit Rolle
$anf = $DBS->anfrage("SELECT person FROM kern_rollenzuordnung WHERE rolle = ?", "i", $id);
while($anf->werte($pers)) {
  $personen[] = $pers;
}

$DBS->anfrage("DELETE FROM kern_rollen WHERE id = ?", "i", $id);

$sessid = session_id();
session_commit();

foreach($personen as $pers) {
  $sql = "SELECT {sessionid} FROM kern_nutzersessions WHERE person = ? AND sessionid IS NOT NULL AND sessiontimeout > ".time();
  $sql = $DBS->anfrage($sql, "i", $pers);
  while($sql->werte($sid)) {
    session_id($sid);
    Kern\Check::angemeldet();
    $DSH_BENUTZER->rechteLaden();
    $_SESSION["Benutzer"] = $DSH_BENUTZER;
    session_commit();
  }
}

session_id($sessid);
Kern\Check::angemeldet();

?>
