<?php
Anfrage::post("id", "nachrichten", "notifikationen", "blog", "termin", "galerie");

if(!Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!Check::istZahl($id,0) || !Check::istToggle($nachrichten) || !Check::istToggle($notifikationen) || !Check::istToggle($blog) || !Check::istToggle($termin) || !Check::istToggle($galerie)) {
  Anfrage::addFehler(-3, true);
}

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff();
if (!$DSH_BENUTZER->hatRecht("$recht.einstellungen.benachrichtigungen")) {
  Anfrage::addFehler(-4, true);
}

$sql = "UPDATE kern_nutzereinstellungen SET notifikationsmail = [?], postmail = [?], oeffentlichertermin = [?], oeffentlicherblog = [?], oeffentlichegalerie = [?] WHERE person = ?";
$anfrage = $DBS->anfrage($sql, "sssssi", $notifikationen, $nachrichten, $termin, $blog, $galerie, $id);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung("Änderungen erfolgreich!", "Die Änderungen der Benachrichtigungseinstellungen wurden vorgenomen.", "Erfolg"));
Anfrage::setRueck("Knöpfe", [UI\Knopf::ok()]);
?>
