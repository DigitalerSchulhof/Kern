<?php

Anfrage::post("benutzer", "mail");

if(!UI\Check::istText($benutzer)) {
  Anfrage::addFehler(6);
}
if(!UI\Check::istMail($mail)) {
  Anfrage::addFehler(7);
}
Anfrage::checkFehler();

$jetzt = time();
$sql = "SELECT kern_personen.id AS id, {art}, {titel}, {vorname}, {nachname}, {geschlecht}, {salt} FROM kern_personen JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.person WHERE benutzername = [?] AND email = [?];";
$anfrage = $DBS->anfrage($sql, "ss", $benutzer, $mail);

if ($anfrage->getAnzahl() == 0) {
  Anfrage::addFehler(8, true);
}

$anfrage->werte($id, $art, $titel, $vorname, $nachname, $geschlecht, $salt);
$person = new Kern\Nutzerkonto($id, $titel, $vorname, $nachname);
$person->setBenutzer($benutzer);
$person->setArt($art);
$person->setGeschlecht($geschlecht);

$passwort = $person->neuesPasswort($mail, 10, $salt);

if (!$passwort) {
  Anfrage::addFehler(8, true);
}
?>
