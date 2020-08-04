<?php

Anfrage::post("mail");

if(!Check::istMail($mail)) {
  Anfrage::addFehler(9);
}
Anfrage::checkFehler();

$jetzt = time();
$sql = "SELECT * FROM (SELECT {benutzername} AS nutzer FROM kern_nutzerkonten WHERE email = [?]) AS n ORDER BY nutzer ASC;";
$anfrage = $DBS->anfrage($sql, "s", $mail);

if ($anfrage->getAnzahl() == 0) {
  Anfrage::addFehler(10, true);
}

$nutzer = [];
while ($anfrage->werte($n)) {
  $nutzer[] = $n;
}

$betreff = "Nutzerkontenauskunft";
$empfaenger = $mail;

$text  = "<p>Auf diese eMailadresse sind die folgenden Benutzerkonten registriert:<br>";
$text .= join(", ", $nutzer)."</p>";

$brief = new Kern\Mail();
$brief->senden($empfaenger, $mail, $betreff, $text);

if (!$brief) {
  Anfrage::addFehler(10, true);
}

$schulhof = new UI\Knopf("Zurück zur Anmeldung");
$schulhof->addFunktion("href", "Schulhof/Anmeldung");
$schulhof->addFunktion("onclick", "ui.laden.aus()");
$knoepfe = [$schulhof];
Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Benutzername verschickt!", "Die Benutzernamen aller Benutzer, die mit dieser Mailadresse verknüpft sind, wurden per eMail verschickt.", "Information", new UI\Icon(UI\Konstanten::VERSCHICKEN)));
Anfrage::setRueck("Knöpfe", $knoepfe);
?>
