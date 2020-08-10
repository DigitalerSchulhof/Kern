<?php
Anfrage::post("id", "art", "geschlecht", "titel", "vorname", "nachname", "kuerzel");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!UI\Check::istZahl($id,0) || !in_array($art, Kern\Person::getArten()) || !in_array($geschlecht, Kern\Person::getGeschlechter())) {
  Anfrage::addFehler(-3, true);
}

if(!UI\Check::istTitel($titel)) {
  Anfrage::addFehler(26);
}
if(!UI\Check::istName($vorname)) {
  Anfrage::addFehler(27);
}
if(!UI\Check::istName($nachname)) {
  Anfrage::addFehler(28);
}
if($DSH_BENUTZER->getArt() == "l" && !UI\Check::istText($kuerzel,0)) {
  Anfrage::addFehler(28);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();

// Alte Art des Benutzers ermitteln
$sql = "SELECT {art} FROM kern_personen WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($alteart);

if ($DSH_BENUTZER->hatRecht("$recht.kuerzel")) {
  // Prüfen, ob neues Kürzel schon vergeben ist
  if ($art == "l" && $alteart == "l") {
    $sql = "SELECT COUNT(*) FROM kern_lehrer WHERE kuerzel = [?] AND id != ?";
    $anfrage = $DBS->anfrage($sql, "si", $kuerzel, $id);
    $anfrage->werte($anzahl);
    if ($anzahl !== 0) {
      Anfrage::addFehler(30);
    }
  }
}

Anfrage::checkFehler();

// Falls die Art zum Lehrer wird
if ($art == "l" && $alteart != "l") {
  $sql = "INSERT INTO kern_lehrer (id, kuerzel) VALUES (?, null)";
  $DBS->anfrage($sql, "i", $id);
}
if ($art != "l" && $alteart == "l") {
  $sql = "DELETE FROM kern_lehrer WHERE id = ?";
  $DBS->anfrage($sql, "i", $id);
}

$felder = [];
$feldertypen = "";
$sql = "UPDATE kern_personen SET ";
$ausfuehren = false;
if ($DSH_BENUTZER->hatRecht("$recht.art")) {
  $ausfuehren = true;
  $sql .= "art = [?], ";
  $felder[] = $art;
  $feldertypen .= "s";
}
if ($DSH_BENUTZER->hatRecht("$recht.geschlecht")) {
  $ausfuehren = true;
  $sql .= "geschlecht = [?], ";
  $felder[] = $geschlecht;
  $feldertypen .= "s";
}
if ($DSH_BENUTZER->hatRecht("$recht.titel")) {
  $ausfuehren = true;
  $sql .= "titel = [?], ";
  $felder[] = $titel;
  $feldertypen .= "s";
}
if ($DSH_BENUTZER->hatRecht("$recht.vorname")) {
  $ausfuehren = true;
  $sql .= "vorname = [?], ";
  $felder[] = $vorname;
  $feldertypen .= "s";
}
if ($DSH_BENUTZER->hatRecht("$recht.nachname")) {
  $ausfuehren = true;
  $sql .= "nachname = [?], ";
  $felder[] = $nachname;
  $feldertypen .= "s";
}

if ($ausfuehren) {
  $sql = substr($sql, 0, -2);
  $sql .= " WHERE id = ?";
  $felder[] = $id;
  $feldertypen .= "i";
  $DBS->anfrage($sql, $feldertypen, ...$felder);
}

if ($DSH_BENUTZER->hatRecht("$recht.kuerzel")) {
  // Prüfen, ob neues Kürzel schon vergeben ist
  if ($art == "l" && $alteart == "l") {
    $sql = "UPDATE kern_lehrer SET kuerzel = [?] WHERE id = ?";
    $anfrage = $DBS->anfrage($sql, "si", $kuerzel, $id);
  }
}

// Benutzer ändern
if ($id == $DSH_BENUTZER->getId()) {
  $DSH_BENUTZER->setArt($art);
  $DSH_BENUTZER->setGeschlecht($geschlecht);
  $DSH_BENUTZER->setTitel($titel);
  $DSH_BENUTZER->setVorname($vorname);
  $DSH_BENUTZER->setNachname($nachname);
}

$knopf = new UI\Knopf("OK");
$knopf->addFunktion("onclick", "core.neuladen()");
Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der persönlichen Informationen wurden vorgenomen.", "Erfolg"));
Anfrage::setRueck("Knöpfe", [$knopf]);
?>
