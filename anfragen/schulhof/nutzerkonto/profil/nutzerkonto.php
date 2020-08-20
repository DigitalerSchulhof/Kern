<?php
Anfrage::post("id", "benutzer", "email");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!UI\Check::istZahl($id,0)) {
  Anfrage::addFehler(-3, true);
}

if(!UI\Check::istText($benutzer)) {
  Anfrage::addFehler(31);
}
if(!UI\Check::istMail($email)) {
  Anfrage::addFehler(32);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();

// Prüfen, ob der neue Benutzernameschon vergeben ist
if ($DSH_BENUTZER->hatRecht("$recht.benutzer")) {
  $sql = "SELECT COUNT(*) FROM kern_nutzerkonten WHERE benutzername = [?] AND person != ?";
  $anfrage = $DBS->anfrage($sql, "si", $benutzer, $id);
  $anfrage->werte($anzahl);
  if ($anzahl !== 0) {
    Anfrage::addFehler(33);
  }
}

Anfrage::checkFehler();

$sql = "SELECT {email}, {titel}, {vorname}, {nachname}, {benutzername} FROM kern_nutzerkonten JOIN kern_personen ON kern_nutzerkonten.person = kern_personen.id WHERE kern_nutzerkonten.person = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($mailalt, $titel, $vorname, $nachname, $benutzernamealt);

$felder = [];
$feldertypen = "";
$sql = "UPDATE kern_nutzerkonten SET ";
$ausfuehren = false;
if ($DSH_BENUTZER->hatRecht("$recht.benutzer")) {
  $ausfuehren = true;
  $sql .= "benutzername = [?], ";
  $felder[] = $benutzer;
  $feldertypen .= "s";
}
if ($DSH_BENUTZER->hatRecht("$recht.email")) {
  $ausfuehren = true;
  $sql .= "email = [?], ";
  $felder[] = $email;
  $feldertypen .= "s";
}

if ($ausfuehren) {
  $sql = substr($sql, 0, -2);
  $sql .= " WHERE person = ?";
  $felder[] = $id;
  $feldertypen .= "i";
  $DBS->anfrage($sql, $feldertypen, ...$felder);
}

// Benutzer ändern
if ($id == $DSH_BENUTZER->getId()) {
  $DSH_BENUTZER->setBenutzer($benutzer);
}

// Nachricht verschicken, falls die eMail-Adresse geändert wurde
$empfaenger = new Kern\Person($id, $titel, $vorname, $nachname);
$betreff = "Nutzerdaten geändert";
$anrede = $empfaenger->getAnrede();
$empfaenger = $empfaenger->getName();

$text = "<p>$anrede,</p>";
if ($mailalt != $email) {
  $text .= "<p>Die eMail-Adresse zu einem Zugang wurde geändert.</p>";
}
if ($benutzernamealt != $benutzer) {
  $text .= "<p>Der Benutzername zu einem Zugang wurde geändert.</p>";
}
$text .= "<p>Wenn die Aktion bewusst ausgelöst wurde, muss auf diese Nachricht nicht reagiert werden. Andernfalls sollte umgehend ein Administrator informiert werden.</p>";

$brief = new Kern\Mail();
$brief->senden($empfaenger, $mailalt, $betreff, $text);
?>
