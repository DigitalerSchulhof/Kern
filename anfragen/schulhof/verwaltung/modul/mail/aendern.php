<?php
Anfrage::post("mailadresse", "mailtitel", "mailuser", "mailpass", "mailhost", "mailport", "mailauth", "mailsigp", "mailsigh");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.module.einstellungen")) {
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

$sql = "UPDATE kern_einstellungen SET wert = [?] WHERE inhalt = [?]";
$werte = [];
$werte[] = [$mailadresse, "Mailadresse"];
$werte[] = [$mailtitel, "MailTitel"];
$werte[] = [$mailuser, "Mailbenutzer"];
$werte[] = [$mailpass, "Mailpasswort"];
$werte[] = [$mailhost, "MailSmtpServer"];
$werte[] = [$mailport, "MailSmtpPort"];
$werte[] = [$mailauth, "MailSmtpAuthentifizierung"];
$werte[] = [$mailsigp, "MailSignaturPlain"];
$werte[] = [$mailsigh, "MailSignaturHTML"];
$anfrage = $DBS->anfrage($sql, "ss", $werte);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen an der eMailadresse des Schulhofs wurden vorgenomen.", "Erfolg"));
?>
