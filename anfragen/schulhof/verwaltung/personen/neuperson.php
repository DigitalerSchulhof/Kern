<?php
Anfrage::post("art", "geschlecht", "titel", "vorname", "nachname", "kuerzel", "nutzerkonto", "benutzername", "mail");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.personen.anlegen.person")) {
  Anfrage::addFehler(-4, true);
}

if (!UI\Check::istToggle($nutzerkonto)) {
  Anfrage::addFehler(-3, true);
}

if(!in_array($art, Kern\Person::ARTEN)) {
  Anfrage::addFehler(89);
}
if(!in_array($geschlecht, Kern\Person::GESCHLECHTER)) {
  Anfrage::addFehler(90);
}
if(!UI\Check::istTitel($titel)) {
  Anfrage::addFehler(91);
}
if(!UI\Check::istName($vorname)) {
  Anfrage::addFehler(92);
}
if(!UI\Check::istName($nachname)) {
  Anfrage::addFehler(93);
}
if($art == "l" && !UI\Check::istName($kuerzel)) {
  Anfrage::addFehler(94);
}

if ($nutzerkonto == "1" && $DSH_BENUTZER->hatRecht("kern.personen.anlegen.nutzerkonto")) {
  if(!UI\Check::istText($benutzername)) {
    Anfrage::addFehler(96);
  }
  if(!UI\Check::istMail($mail)) {
    Anfrage::addFehler(97);
  }

  $sql = "SELECT COUNT(*) FROM kern_nutzerkonten WHERE benutzername = [?]";
  $anfrage = $DBS->anfrage($sql, "s", $benutzername);
  $anfrage->werte($anzahl);
  if ($anzahl !== 0) {
    Anfrage::addFehler(98);
  }
}

if ($art == "l") {
  $sql = "SELECT COUNT(*) FROM kern_lehrer WHERE kuerzel = [?]";
  $anfrage = $DBS->anfrage($sql, "s", $kuerzel);
  $anfrage->werte($anzahl);
  if ($anzahl !== 0) {
    Anfrage::addFehler(95);
  }
}

Anfrage::checkFehler();


$id = $DBS->neuerDatensatz("kern_personen");
$sql = "UPDATE kern_personen SET art = [?], titel = [?], nachname = [?], vorname = [?], geschlecht = [?] WHERE id = ?";
$DBS->anfrage($sql, "sssssi", $art, $titel, $nachname, $vorname, $geschlecht, $id);

if ($art == "l") {
  $sql = "INSERT INTO kern_lehrer (id, kuerzel) VALUES (?, [?])";
  $DBS->anfrage($sql, "is", $id, $kuerzel);
}

$sql = "INSERT INTO kern_nutzereinstellungen (person, notifikationsmail, postmail, postalletage, postpapierkorbtage, uebersichtsanzahl, oeffentlichertermin, oeffentlicherblog, oeffentlichegalerie, inaktivitaetszeit, wikiknopf, emailaktiv) VALUES (?, [?], [?], [?], [?], [?], [?], [?], [?], [?], [?], [?])";
$DBS->anfrage($sql, "iiiiiiiiiiii", $id, "1", "1", "365", "30", "5", "1", "0", "0", "30", "1", "0");

// Dateisystem des Benutzers anlegen
if (is_dir("$ROOT/dateien/Kern/personen/$id")) {
  Kern\Dateisystem::ordnerLoeschen("$ROOT/dateien/Kern/personen/$id");
}
mkdir("$ROOT/dateien/Kern/personen/$id");
mkdir("$ROOT/dateien/Kern/personen/$id/dateien");

Anfrage::setRueck("ID", $id);
?>