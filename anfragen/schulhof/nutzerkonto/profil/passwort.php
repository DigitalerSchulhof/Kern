<?php
Anfrage::post("id", "passwortalt", "passwortneu", "passwortneu2");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!UI\Check::istZahl($id,0)) {
  Anfrage::addFehler(-3, true);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();

if (!$DSH_BENUTZER->hatRecht("$recht.passwort")) {
  Anfrage::addFehler(-4);
}

if(strlen($passwortalt) < 6) {
  Anfrage::addFehler(34);
}
if(strlen($passwortneu) < 6) {
  Anfrage::addFehler(35);
}
if($passwortneu != $passwortneu2) {
  Anfrage::addFehler(36);
}

Anfrage::checkFehler();

$sql = "SELECT {salt}, {email}, {titel}, {vorname}, {nachname}, {benutzername} FROM kern_nutzerkonten JOIN kern_personen ON kern_nutzerkonten.id = kern_personen.id WHERE kern_nutzerkonten.id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($salt, $mail, $titel, $vorname, $nachname, $benutzername);

$sql = "SELECT COUNT(*) FROM kern_nutzerkonten WHERE id = ? AND passwort = SHA1(?)";
$anfrage = $DBS->anfrage($sql, "is", $id, $passwortalt.$salt);
$anfrage->werte($anzahl);
if ($anzahl !== 1) {
  Anfrage::addFehler(37, true);
}

$neuessalt = Kern\Nutzerkonto::generiereSalt();
$sql = "UPDATE kern_nutzerkonten SET salt = [?], passwort = SHA1(?), passworttimeout = null WHERE id = ?";
$DBS->anfrage($sql, "ssi", $neuessalt, $passwortneu.$neuessalt, $id);

// Benutzer ändern
$DSH_BENUTZER->setPassworttimeout(null);

// Nachricht verschicken
$empfaenger = new Kern\Person($id, $titel, $vorname, $nachname);
$betreff = "Neues Passwort vergeben";
$anrede = $empfaenger->getAnrede();
$empfaenger = $empfaenger->getName();

$text = "<p>$anrede,</p>";
$text .= "<p>Das Passwort zu einem Zugang wurde geändert.</p>";
$text .= "<p>Wenn die Aktion bewusst ausgelöst wurde, muss auf diese Nachricht nicht reagiert werden. Andernfalls sollte umgehend ein Administrator informiert werden.</p>";

$brief = new Kern\Mail();
$brief->senden($empfaenger, $mail, $betreff, $text);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Das Passwort wurde geändert. Aus Sicherheitsgründen wird eine Benachrichtigung per eMail verschickt.", "Erfolg"));
Anfrage::setRueck("Knöpfe", [UI\Knopf::ok()]);
?>
