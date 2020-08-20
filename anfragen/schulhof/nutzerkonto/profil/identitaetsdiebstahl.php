<?php
Anfrage::post("passwortalt", "passwortneu", "passwortneu2", "hinweise");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(strlen($passwortalt) < 6) {
  Anfrage::addFehler(69);
}
if(strlen($passwortneu) < 6) {
  Anfrage::addFehler(70);
}
if($passwortneu != $passwortneu2) {
  Anfrage::addFehler(71);
}
if($passwortneu == $passwortalt) {
  Anfrage::addFehler(73);
}

Anfrage::checkFehler();

$sql = "SELECT {salt}, {email}, {titel}, {vorname}, {nachname}, {benutzername} FROM kern_nutzerkonten JOIN kern_personen ON kern_nutzerkonten.id = kern_personen.id WHERE kern_nutzerkonten.id = ?";
$anfrage = $DBS->anfrage($sql, "i", $DSH_BENUTZER->getId());
$anfrage->werte($salt, $mail, $titel, $vorname, $nachname, $benutzername);

$sql = "SELECT COUNT(*) FROM kern_nutzerkonten WHERE id = ? AND passwort = SHA1(?)";
$anfrage = $DBS->anfrage($sql, "is", $DSH_BENUTZER->getId(), $passwortalt.$salt);
$anfrage->werte($anzahl);
if ($anzahl !== 1) {
  Anfrage::addFehler(72, true);
}

$neuessalt = Kern\Nutzerkonto::generiereSalt();
$sql = "UPDATE kern_nutzerkonten SET salt = [?], passwort = SHA1(?), passworttimeout = null WHERE id = ?";
$DBS->silentanfrage($sql, "ssi", $neuessalt, $passwortneu.$neuessalt, $DSH_BENUTZER->getId());

$DBS->logZugriff("DB", "kern_nutzerkonten", "UPDATE kern_nutzerkonten SET salt = ?, passwort = SHA1(?), passworttimeout = null WHERE person = ?", "Änderung", [["*****", "*****", $DSH_BENUTZER->getId()]]);

// Benutzer ändern
$DSH_BENUTZER->setPassworttimeout(null);

// Identitätsdiebstahl eintragen
$iid = $DBS->neuerDatensatz("kern_identitaetsdiebstahl");
$sql = "UPDATE kern_identitaetsdiebstahl SET person = ?, zeit = ?, hinweise = [?] WHERE id = ?";
$DBS->anfrage($sql, "iisi", $DSH_BENUTZER->getId(), time(), $hinweise, $iid);

// Nachricht verschicken
$betreff = "Identitätsdiebstahl";
$anrede = $DSH_BENUTZER->getAnrede();
$empfaenger = $DSH_BENUTZER->getName();

$text = "<p>$anrede,</p>";
$text .= "<p>Das Passwort zu einem Zugang wurde geändert. Es wurde ein Identitätsdiebstahl gemeldet.</p>";
$text .= "<p>Wenn die Aktion bewusst ausgelöst wurde, muss auf diese Nachricht nicht reagiert werden. Andernfalls sollte umgehend ein Administrator informiert werden.</p>";

$brief = new Kern\Mail();
$brief->senden($empfaenger, $mail, $betreff, $text);
?>
