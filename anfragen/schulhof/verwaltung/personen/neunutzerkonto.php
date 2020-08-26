<?php
Anfrage::post("id", "benutzername", "mail");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.personen.anlegen.nutzerkonto")) {
  Anfrage::addFehler(-4, true);
}

// Prüfen, ob es für diesen Nutzer bereits ein Nutzerkonto gibt
$sql = "SELECT COUNT(*) FROM kern_nutzerkonten WHERE person = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($nanzahl);

$sql = "SELECT COUNT(*) FROM kern_personen WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($panzahl);

if (!UI\Check::istZahl($id) || $nanzahl > 0 || $panzahl != 1) {
  Anfrage::addFehler(-3, true);
}

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

Anfrage::checkFehler();


$einstellungen = Kern\Einstellungen::ladenAlle("Kern");

// @TODO: Nutzer am LDAP anmelden
if ($einstellungen["LDAP"] === "1") {

}

$sql = "SELECT {titel}, {vorname}, {nachname}, {art}, {geschlecht} FROM kern_personen WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($titel, $vorname, $nachname, $art, $geschlecht);
$person = new Kern\Nutzerkonto($id, $titel, $vorname, $nachname);
$person->setArt($art);
$person->setGeschlecht($geschlecht);
$passwort = $person->generierePasswort();
$salt = $person->generiereSalt();
$erstellt = time();
$passworttimeout = $erstellt + 24*60*60;

$sql = "INSERT INTO kern_nutzerkonten (person, benutzername, passwort, passworttimeout, salt, email, erstellt, letztenotifikation) VALUES (?, [?], SHA1(?), ?, [?], [?], ?, ?)";
$DBS->silentanfrage($sql, "ississii", $id, $benutzername, $passwort.$salt, $passworttimeout, $salt, $mail, $erstellt, $erstellt);

$DBS->logZugriff("DB", "kern_nutzerkonten", "INSERT INTO kern_nutzerkonten (person, benutzername, passwort, passworttimeout, salt, email, erstellt, letztenotifikation) VALUES (?, ?, SHA1(?), ?, ?, ?, ?, ?)", "Änderung", [$id, $benutzername, "*****", $passworttimeout, "*****", $mail, $erstellt, $erstellt]);



// Zugansdaten verschicken
$betreff = "Neues Nutzerkonto";
$anrede = $person->getAnrede();
$empfaenger = $person->getName();

$text = "<p>$anrede,</p>";
$text .= "<p>Es wurde ein neues Passwort generiert. Hier sind die Zugangsdaten:<br>";
$text .= "Benutzername: {$benutzername}<br>";
$text .= "Passwort: {$passwort}<br>";
$text .= "eMailadresse: {$mail}</p>";
$text .= "<p><b>Achtung!</b> Dieses Passwort ist aus Sicherheitsgründen ab jetzt nur <b>24 Stunden</b> gültig. Verstreicht diese Zeit, ohne dass eine Änderung am Passwort vorgenommen wurde, muss bei der Anmeldung über <i>Passwort vergessen?</i> ein neues Passwort angefordert werden. Dazu werden die Angaben <i>Benutzername</i> und <i>eMailadresse</i> benötigt. Das neue Passwort ist dann auch nur eine Stunde gültig.</p>";
$text .= "<p><b>Kurz:</b> Das Passwort sollte sobald wie möglich geändert werden!!</p>";
$text .= "<p>Viel Spaß mit dem neuen Zugang!</p>";

$brief = new Kern\Mail();
$brief->senden($empfaenger, $mail, $betreff, $text);
?>