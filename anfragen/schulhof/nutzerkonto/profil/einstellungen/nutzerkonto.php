<?php
Anfrage::post("id", "inaktivitaetszeit", "uebersichtselemente", "wiki");

if(!Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!Check::istZahl($id,0) || !Check::istToggle($wiki)) {
  Anfrage::addFehler(-3, true);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();
if (!$DSH_BENUTZER->hatRecht("$recht.einstellungen.nutzerkonto")) {
  Anfrage::addFehler(-4, true);
}

if(!Check::istZahl($inaktivitaetszeit,5,300)) {
  Anfrage::addFehler(37);
}
if(!Check::istZahl($uebersichtselemente,1,10)) {
  Anfrage::addFehler(38);
}

Anfrage::checkFehler();

$sql = "UPDATE kern_nutzereinstellungen SET uebersichtsanzahl = [?], inaktivitaetszeit = [?], wikiknopf = [?] WHERE person = ?";
$anfrage = $DBS->anfrage($sql, "sssi", $uebersichtselemente, $inaktivitaetszeit, $wiki, $id);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Nutzerkonto-Einstellungen wurden vorgenomen.", "Erfolg"));
Anfrage::setRueck("Knöpfe", [UI\Knopf::ok()]);
?>