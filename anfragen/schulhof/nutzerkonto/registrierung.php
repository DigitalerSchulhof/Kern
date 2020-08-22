<?php

Anfrage::session_start();

Anfrage::post("art", "geschlecht", "titel", "vorname", "nachname", "klasse", "passwort", "passwort2", "mail", "datenschutz", "entscheidung", "korrektheit", "spamschutz", "spamid");

if(!in_array($art, Kern\Person::getArten())) {
  Anfrage::addFehler(12);
}
if(!in_array($geschlecht, Kern\Person::getGeschlechter())) {
  Anfrage::addFehler(13);
}
if(!UI\Check::istTitel($titel)) {
  Anfrage::addFehler(14);
}
if(!UI\Check::istName($vorname)) {
  Anfrage::addFehler(15);
}
if(!UI\Check::istName($nachname)) {
  Anfrage::addFehler(16);
}
if(!UI\Check::istName($klasse, 0)) {
  Anfrage::addFehler(17);
}
if(strlen($passwort) < 6) {
  Anfrage::addFehler(18);
}
if($passwort != $passwort2) {
  Anfrage::addFehler(19);
}
if(!UI\Check::istMail($mail)) {
  Anfrage::addFehler(20);
}
if($datenschutz != "1") {
  Anfrage::addFehler(21);
}
if($entscheidung != "1") {
  Anfrage::addFehler(22);
}
if($korrektheit != "1") {
  Anfrage::addFehler(23);
}

if (!isset($_SESSION["SPAMSCHUTZ_{$spamid}"])) {
  Anfrage::addFehler(24);
} else if ($_SESSION["SPAMSCHUTZ_{$spamid}"] != $spamschutz) {
  Anfrage::addFehler(25);
}
Anfrage::checkFehler();

unset($_SESSION["SPAMSCHUTZ_{$spamid}"]);

$neueid = $DBS->neuerDatensatz("kern_nutzerregistrierung", array(), "", true);
$sql = "UPDATE kern_nutzerregistrierung SET art = [?], geschlecht = [?], titel = [?], vorname = [?], nachname = [?], klasse = [?], email = [?], salt = [?], passwort = SHA1(?) WHERE id = ?";
$salt = Kern\Nutzerkonto::generiereSalt();
$DBS->anfrage($sql, "sssssssssi", $art, $geschlecht, $titel, $vorname, $nachname, $klasse, $mail, $salt, $passwort.$salt, $neueid);
?>
