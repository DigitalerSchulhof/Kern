<?php
Anfrage::post("id", "postfach", "papierkorb");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!UI\Check::istZahl($id,0)) {
  Anfrage::addFehler(-3, true);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();
if (!$DSH_BENUTZER->hatRecht("$recht.einstellungen.nutzerkonto")) {
  Anfrage::addFehler(-4, true);
}

if(!UI\Check::istZahl($postfach,1,1000)) {
  Anfrage::addFehler(37);
}
if(!UI\Check::istZahl($papierkorb,1,1000)) {
  Anfrage::addFehler(38);
}

Anfrage::checkFehler();

$sql = "UPDATE kern_nutzereinstellungen SET postalletage = [?], postpapierkorbtage = [?] WHERE person = ?";
$anfrage = $DBS->anfrage($sql, "ssi", $postfach, $papierkorb, $id);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Postfach-Einstellungen wurden vorgenomen.", "Erfolg"));
Anfrage::setRueck("Knöpfe", [UI\Knopf::ok()]);
?>
