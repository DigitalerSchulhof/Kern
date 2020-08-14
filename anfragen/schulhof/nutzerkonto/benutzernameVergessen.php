<?php

Anfrage::post("mail");

if(!UI\Check::istMail($mail)) {
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
?>
