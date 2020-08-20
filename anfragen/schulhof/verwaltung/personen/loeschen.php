<?php
Anfrage::post("id", "art");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (($art != "nutzerkonto" && $art != "person") || !UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

if ($art == "nutzerkonto") {
  if (!$DSH_BENUTZER->hatRecht("verwaltung.personen.löschen.nutzerkonto")) {
    Anfrage::addFehler(-4, true);
  }
} else {
  if (!$DSH_BENUTZER->hatRecht("verwaltung.personen.löschen.person")) {
    Anfrage::addFehler(-4, true);
  }
}

$sql = "SELECT COUNT(*) FROM kern_rollenzuordnung WHERE person != ? AND rolle = 0";
$anfrage = $DBS->anfrage($sql, "i", $id);
if ($anfrage->werte($anzahl)) {
 if ($anzahl == 0) {
   Anfrage::addFehler(-4, true);
 }
}

// Nutzerkonto immer löschen
$sql = "DELETE FROM kern_nutzerkonten WHERE id = ?";
$DBS->anfrage($sql, "i", $id);

// Person nur löschen, wenn auch im Person-Modus
if ($art == "person") {
  $sql = "DELETE FROM kern_personen WHERE id = ?";
  $DBS->anfrage($sql, "i", $id);

  // Dateien dieses Benutzers löschen
  Kern\Dateisystem::ordnerLoeschen("$ROOT/dateien/personen/$id");
  new Kern\Wurmloch("funktionen/events/personLoeschen.php", array("PERSONID" => $id));
}

?>
