<?php
Anfrage::post("mailadresse", "mailtitel", "mailuser", "mailpass", "mailhost", "mailport", "mailauth", "mailsigp", "mailsigh");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.module.einstellungen")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istToggle($mailauth)) {
  Anfrage::addFehler(-3, true);
}

if(!UI\Check::istMail($mailadresse)) {
  Anfrage::addFehler(59);
}

if(!UI\Check::istText($mailtitel)) {
  Anfrage::addFehler(60);
}

if(!UI\Check::istText($mailuser)) {
  Anfrage::addFehler(61);
}

if(!UI\Check::istText($mailhost)) {
  Anfrage::addFehler(62);
}

if(!UI\Check::istZahl($mailport,0,65535)) {
  Anfrage::addFehler(63);
}

Anfrage::checkFehler();

// Nachricht verschicken
$betreff = "Neu eMail-Daten testen";
$anrede = $DSH_BENUTZER->getAnrede();
$empfaenger = $DSH_BENUTZER->getName();

$sql = "SELECT {email} FROM kern_nutzerkonten WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $DSH_BENUTZER->getId());
$anfrage->werte($mail);

$text = "<p>$anrede,</p>";
$text .= "<p>Diese eMail dient dem Test der neuen Zugansdaten zum Absender der eMails durch den Digitalen Schulhof. Wenn diese eMail ankommt, sind die neuen Zugansdaten korrekt.</p>";

$brief = new Kern\Mail();
$brief->setAttribute($mailhost, $mailport, $mailtitel, $mailauth, $mailadresse, $mailuser, $mailpass, $mailsigp, $mailsigh);
$brief->senden($empfaenger, $mail, $betreff, $text);
?>
