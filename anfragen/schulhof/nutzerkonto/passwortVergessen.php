<?php

Anfrage::post("benutzer", "mail");

if(!Check::istText($benutzer)) {
  Anfrage::addFehler(6);
}
if(!Check::istMail($mail)) {
  Anfrage::addFehler(7);
}
Anfrage::checkFehler();

$jetzt = time();
$sql = "SELECT kern_personen.id AS id, {art}, {titel}, {vorname}, {nachname}, {geschlecht}, {salt} FROM kern_personen JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.id WHERE benutzername = [?] AND email = [?];";
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

$schulhof = new UI\Knopf("Zurück zur Anmeldung");
$schulhof->addFunktion("href", "Schulhof/Anmeldung");
$schulhof->addFunktion("onclick", "ui.laden.aus()");
$knoepfe = [$schulhof];
Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Passwort verschickt!", "Das neue Passwort wurde per eMail verschickt. Es ist nur für kurze Zeit gültig. Eine umgehende Änderung wird empfohlen.", "Information", new UI\Icon(UI\Konstanten::VERSCHICKEN)));
Anfrage::setRueck("Knöpfe", $knoepfe);
?>
