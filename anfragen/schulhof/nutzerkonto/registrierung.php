<?php
Anfrage::post("art", "titel", "vorname", "nachname", "klasse", "passwort", "passwort2", "mail", "datenschutz", "entscheidung", "korrektheit", "spamschutz", "spamid");

if(!in_array($art, Kern\Person::getArten())) {
  Anfrage::addFehler(-3);
}
if(!in_array($art, Kern\Person::getGeschlechter())) {
  Anfrage::addFehler(-3);
}
if(!Check::istName($titel)) {
  Anfrage::addFehler(12);
}
if(!Check::istName($vorname)) {
  Anfrage::addFehler(13);
}
if(!Check::istName($nachname)) {
  Anfrage::addFehler(14);
}
if(!Check::istName($klasse, 0)) {
  Anfrage::addFehler(15);
}
if(strlen($passwort) < 6) {
  Anfrage::addFehler(16);
}
if($passwort != $passwort2) {
  Anfrage::addFehler(17);
}
if(!Check::istMail($mail)) {
  Anfrage::addFehler(18);
}
if($datenschutz != "1") {
  Anfrage::addFehler(19);
}
if($entscheidung != "1") {
  Anfrage::addFehler(20);
}
if($korrektheit != "1") {
  Anfrage::addFehler(21);
}
if (!isset($_SESSION["SPAMSCHUTZ_{$spamid}"])) {
  Anfrage::addFehler(22);
} else if ($_SESSION["SPAMSCHUTZ_{$spamid}"] != $spamschutz) {
  Anfrage::addFehler(23);
}
Anfrage::checkFehler();

$neueid = $DBS->neuerDatensatz("kern_nutzerregistrierung");
$sql = "UPDATE kern_nutzerregistrierung SET art = [?], geschlecht = [?], titel = [?], vorname = [?], nachname = [?], klasse = [?], email = [?], salt = [?], passwort = SHA1(?) WHERE id = ?";
$salt = Kern\Nutzerkonto::generiereSalt();
$DBS->anfrage($sql, "sssssssssi", $art, $geschlecht, $titel, $vorname, $nachname, $klasse, $email, $salt, $passwort.$salt, $neueid);

$website = new UI\Knopf("Zurück zur Website");
$website->addFunktion("href", "Website");
$website->addFunktion("onclick", "ui.laden.aus()");
$schulhof = new UI\Knopf("Zurück zur Anmeldung");
$schulhof->addFunktion("href", "Schulhof/Anmeldung");
$schulhof->addFunktion("onclick", "ui.laden.aus()");
$knoepfe = [$website, $schulhof];
Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Registrierung erfolgreich!", "Die Registrierung wurde durchgeführt. Ein Administrator muss noch die Verknüpfung mit einer Person des Schulhofs durchführen. Sobald das Nutzerkonto bereitsteht wird eine eMail mit dem zugehörigen Benutzernamen versendet.", "Information", new UI\Icon(UI\Konstanten::ABMELDEN)));
Anfrage::setRueck("Knöpfe", $knoepfe);
?>
