<?php
Anfrage::post("id", "rolle", "wert");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($id) || !UI\Check::istToggle($wert)) {
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.rechte.rollen.zuordnen")) {
  Anfrage::addFehler(-4, true);
}

$sql = "SELECT COUNT(*) FROM kern_rollen WHERE id = ?";
$sql = $DBS->anfrage($sql, "i", $rolle);
if(!$sql->werte($anz)) {
  Anfrage::addFehler(-3, true);
}

if($wert == "0") {
  // Prüfen, ob es noch einen Administrator gibt
  $sql = "SELECT COUNT(*) FROM kern_rollenzuordnung WHERE rolle = 0";
  $anfrage = $DBS->anfrage($sql);
  if ($anfrage->werte($anzahl)) {
    if ($anzahl == 0) {
      Anfrage::addFehler(88, true);
    }
  } else {
    Anfrage::addFehler(88, true);
  }

  $sql = "DELETE FROM kern_rollenzuordnung WHERE nutzer = ? AND rolle = ?";
  $anfrage = $DBS->anfrage($sql, "ii", $id, $rolle);
} else {
  $sql = "INSERT INTO kern_rollenzuordnung (nutzer, rolle) VALUES (?, ?)";
  $anfrage = $DBS->anfrage($sql, "ii", $id, $rolle);
}

?>
