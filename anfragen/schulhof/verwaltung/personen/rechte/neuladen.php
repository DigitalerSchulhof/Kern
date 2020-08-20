<?php
Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.vergeben || kern.rechte.rollen.zuordnen")) {
  Anfrage::addFehler(-4, true);
}

if(($person = Kern\Nutzerkonto::vonID($id)) === null) {
  Anfrage::addFehler(-3, true);
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
